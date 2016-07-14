<?php

namespace Laravolt\Mural;

use Illuminate\Support\Facades\Auth;
use Laravolt\Mural\Contracts\Commentable;

class Mural
{
    /**
     * Mural constructor.
     */
    public function __construct()
    {
        $this->config = config('mural');
    }

    public function render(Commentable $content, $room, $options = [])
    {
        $options = collect($options);
        $comments = $this->getComments($content, $room);
        $totalComment = $content->comments()->room($room)->count();
        $id = $content->getKey();
        $type = get_class($content);

        event('mural.render', [$content]);

        return view('mural::index', compact('content', 'id', 'type', 'comments', 'room', 'totalComment', 'options'))->render();
    }

    public function addComment(Commentable $content, $body, $room)
    {
        $author = Auth::user();
        $comment = $content->comments()->getRelated();
        $comment->body = $body;
        $comment->room = $room;
        $comment->author()->associate($author);

        if ($content->comments()->save($comment)) {
            event('mural.comment.add', [$comment, $content, $author, $room]);

            return $comment;
        }

        return false;
    }

    public function getComments(Commentable $content, $room, $options = [])
    {
        $options = collect($options);
        $comments = $content->comments()->room($room);

        $sorted = false;
        if ($options->has('sort')) {
            if ($options->get('sort') == 'liked') {
                $comments->mostVoted();
                $sorted = true;
            }
        }

        if (!$sorted) {
            $comments->latest();
        }

        return $comments->paginate($this->config['per_page']);
    }

    public function remove($id)
    {
        $comment = Comment::find($id);
        $user = Auth::user();

        if ($comment && $user->canModerateComment()) {
            $deleted = $comment->delete();

            if ($deleted) {
                event('mural.comment.remove', [$comment, $user]);

                return $comment;
            }
        }

        return false;
    }
}
