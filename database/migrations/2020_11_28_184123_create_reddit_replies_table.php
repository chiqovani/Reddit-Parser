<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedditRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reddit_replies', function (Blueprint $table) {
            $table->id();
            $table->string('comment_id');
            $table->string('reply_id');
            $table->string('author')->nullable();
            $table->text('title')->nullable();
            $table->text('content')->nullable();
            $table->dateTime('update_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reddit_replies');
    }
}
