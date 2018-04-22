<?php

namespace App;

use App\Traits\Eloquent\NestableCommentsTrait;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
	use NestableCommentsTrait;

    public function comments()
    {
    	return $this->morphMany(Comment::class, 'commentable');
    }
}
