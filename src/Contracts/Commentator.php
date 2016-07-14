<?php

namespace Laravolt\Mural\Contracts;

interface Commentator
{
    public function getCommentatorNameAttribute();

    public function getCommentatorAvatarAttribute();

    public function getCommentatorPermalinkAttribute();

    public function canModerateComment();
}
