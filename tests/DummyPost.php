<?php

namespace Laravolt\Mural\Test;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Mural\CommentableTrait;
use Laravolt\Mural\Contracts\Commentable;

class DummyPost extends Model implements Commentable
{
    use CommentableTrait;

    protected $fillable = ['content'];

    public function getCommentableTitleAttribute()
    {
        // TODO: Implement getCommentableTitleAttribute() method.
    }

    public function getCommentablePermalinkAttribute()
    {
        // TODO: Implement getCommentablePermalinkAttribute() method.
    }
}
