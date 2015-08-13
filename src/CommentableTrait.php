<?php
namespace Laravolt\Mural;

trait CommentableTrait
{
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
