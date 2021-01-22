<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubredditCommentsModel extends Model
{
    use HasFactory;
    protected $table = 'reddit_comments';
    protected $guarded = [];
}
