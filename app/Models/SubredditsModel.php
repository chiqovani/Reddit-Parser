<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubredditsModel extends Model
{
    use HasFactory;

    protected $table = 'subreddits';
    protected $guarded = [];
}
