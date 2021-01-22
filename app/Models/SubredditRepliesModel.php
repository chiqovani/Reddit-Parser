<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubredditRepliesModel extends Model
{
    use HasFactory;
    protected $table = 'reddit_replies';
    protected $guarded = [];
}
