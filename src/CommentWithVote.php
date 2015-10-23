<?php
namespace Laravolt\Mural;

use Laravolt\Votee\Traits\Voteable;

class CommentWithVote extends Comment
{
    use Voteable;

    protected $table = 'comments';

}
