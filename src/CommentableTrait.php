<?php

namespace Laravolt\Mural;

trait CommentableTrait
{
    public function comments()
    {
        $class = Comment::class;
        if (config('mural.vote')) {
            $class = CommentWithVote::class;
        }

        return $this->morphMany($class, 'commentable')->has('author');
    }
}
