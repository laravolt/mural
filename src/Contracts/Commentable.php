<?php
namespace Laravolt\Mural\Contracts;

interface Commentable
{
    public function comments();

    public function getCommentableTitleAttribute();

    public function getCommentablePermalinkAttribute();
}
