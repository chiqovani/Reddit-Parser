<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\SubredditRequest;
use App\Models\PostsModel;
use App\Models\SubredditPostModel;
use App\Models\SubredditsModel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $subreddit = SubredditsModel::all();
        $posts = PostsModel::all();
        $subredditPosts = SubredditPostModel::orderBy('id','desc')->get();

        return view('home',['subreddits'=>$subreddit,'posts'=>$posts,'subredditPosts'=>$subredditPosts]);
    }

    public function saveSubreddit(SubredditRequest $request){
        $result = SubredditsModel::create(['subbredit'=>$request->subreddit]);
        return redirect('/');
    }

    public function deleteSubreddit(SubredditsModel $subreddit){
        $subreddit->delete();
        return redirect('/');
    }

    public  function savePost(PostRequest $request){
        PostsModel::create(['url'=>$request->url]);
        return redirect('/');
    }
    public function deletePostUrl(PostsModel $post){
        $post->delete();
        return redirect('/');
    }
}
