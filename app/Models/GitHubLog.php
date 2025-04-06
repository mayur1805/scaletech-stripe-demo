<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GitHubLog extends Model
{
    protected $fillable = ["commit_id", "message", "author", "payload"];
}
