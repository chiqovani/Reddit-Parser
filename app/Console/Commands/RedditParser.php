<?php

namespace App\Console\Commands;

use App\Models\PostsModel;
use App\Models\SubredditCommentsModel;
use App\Models\SubredditPostModel;
use App\Models\SubredditRepliesModel;
use App\Models\SubredditsModel;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RedditParser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reddit:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->parseSubreddits();
        $this->parsePostUrls();

    }

    private function parsePostUrls(){
        $urls = PostsModel::where('is_processed',0)->get();
        foreach ($urls as $url){
            $link = trim($url->url,'/').'/.rss';

            $itemsFromReddit = $this->getArrayFromRedditUrl($link,'from_posturl');
            $post = $itemsFromReddit['0'];
            $this->savePost($post);
            $url->is_processed=1;
            $url->save();
        }
    }

    private function parseSubreddits(){
        $all = SubredditsModel::where('procesing',0)->get();
        foreach ($all as $subreddit){
            $this->changeState($subreddit,1);

            $url = config('reddit.reddit_url').$subreddit->subbredit.'/.rss';
            $itemsFromReddit = $this->getArrayFromRedditUrl($url,'from_subreddit');
            $itemsFromDb = $this->getSubredditPostArrayFromDB($subreddit->subbredit);
            $uniquePosts = $this->returnUniquePosts($itemsFromReddit,$itemsFromDb);
            $postToCreateId = array_rand($uniquePosts,1);
            $post = $uniquePosts[$postToCreateId];
            $this->savePost($post);
            $this->changeState($subreddit,0);
        }
    }

    protected function savePost($post){
        SubredditPostModel::insert($post);
        $comments  = $this->getComments($post['link'],$post['post_id']);
        if(count($comments)>0) {
            SubredditCommentsModel::insert($comments);
        }
        foreach ($comments as $comment) {
            $replies = $this->getReply($comment['link'],$comment['comment_id']);
            if(count($replies)>0) {
                SubredditRepliesModel::insert($replies);
            }

        }

    }

    private function changeState(SubredditsModel $subreddit,$state){
        $subreddit->procesing = $state;
        $subreddit->save();
    }

    /**
     * @param $entries
     * @return array
     */
    private function getArrayFromRedditUrl($url,$type){
        $xml = simplexml_load_file($url);
        $entriesArray = [];
        $subbredit = $xml->category['term'];
        foreach ($xml as $entry){
            if(!empty((string)$entry->id)) {
                $entriesArray[] = [
                    'post_id' => (string)$entry->id,
                    'author' => (string)$entry->author->name,
                    'title' => (string)$entry->title,
                    'content' => (string)$entry->content,
                    'update_time' => Carbon::parse((string)$entry->updated)->format('Y-m-d H:i:s'),
                    'link'=>(string) $entry->link['href'],
                    'subbreddit'=>(string)$subbredit,
                    'type'=>$type
                ];
            }
        }

        return $entriesArray;
    }

    private function getSubredditPostArrayFromDB($subreddit){
        return SubredditPostModel::where('subbreddit',$subreddit)->pluck('post_id')->toArray();
    }

    private function returnUniquePosts($xmlPostArray,$dbPostsArray){
        $posts = [];
        foreach ($xmlPostArray as $post) {
            if(!in_array($post['post_id'],$dbPostsArray)){
                $posts[] = $post;
            }
        }
        return $posts;
    }

    private function getComments($link,$postId){
        $xml = simplexml_load_file($link.'.rss');
        $count = rand(3,5);
        $comments = [];
        $counter = 1;
        foreach ($xml->entry as $entry) {
            if(!empty((string)$entry->id)) {
                $comments[] = [
                    'post_id'=>$postId,
                    'comment_id' => (string)$entry->id,
                    'author' => (string)$entry->author->name,
                    'title' => (string)$entry->title,
                    'content' => (string)$entry->content,
                    'update_time' => Carbon::parse((string)$entry->updated)->format('Y-m-d H:i:s'),
                    'link'=>(string) $entry->link['href']
                ];
            }

            if($counter>=$count){
                break;
            }
            $counter++;
        }
        return $comments;
    }


    private function getReply($link,$commentId){
        $xml = simplexml_load_file($link.'.rss');
        $count = rand(0,1);
        $replies = [];
        if($count===0){
            return $replies;
        }
        $counter = 1;
        foreach ($xml->entry as $entry) {
            if(!empty((string)$entry->id)) {
                $replies[] = [
                    'comment_id'=>$commentId,
                    'reply_id' => (string)$entry->id,
                    'author' => (string)$entry->author->name,
                    'title' => (string)$entry->title,
                    'content' => (string)$entry->content,
                    'update_time' => Carbon::parse((string)$entry->updated)->format('Y-m-d H:i:s'),
                ];
            }

            if($counter>=$count){
                break;
            }
            $counter++;
        }
        return $replies;
    }

}
