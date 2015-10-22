<?php
namespace Laravolt\Mural;

use Laravolt\Mural\Contracts\Commentable;

class Mural
{
    private $app;

    /**
     * Mural constructor.
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->config = config('mural');
    }

    public function render($content, $room, $options = [])
    {
        $options = collect($options);
        $content = $this->getContentObject($content);
        $comments = $this->getComments($content, $room);
        $totalComment = $content->comments()->room($room)->count();

        event('mural.render', [$content]);

        return view("mural::index", compact('content', 'comments', 'room', 'totalComment', 'options'))->render();
    }

    public function addComment($content, $body, $room)
    {
        $author = auth()->user();
        $content = $this->getContentObject($content);
        $comment = new Comment();
        $comment->body = $body;
        $comment->room = $room;
        $comment->author()->associate($author);

        if($content->comments()->save($comment)) {
            event('mural.comment.add', [$comment, $content, $author, $room]);
            return $comment;
        }

        return false;
    }

    public function getComments($content, $room, $options = [])
    {
        $options = collect($options);
        $content = $this->getContentObject($content);
        $comments = $content->comments()->newest()->room($room);

        if($options->has('beforeId')) {
            $comments->beforeId($options->get('beforeId'));
        }

        return $comments->paginate($this->config['per_page']);
    }

    public function remove($id)
    {
        $comment = Comment::find($id);
        $user = auth()->user();

        if($comment && $user->canModerateComment()) {
            $deleted = $comment->delete();

            if ($deleted) {
                event('mural.comment.remove', [$comment, $user]);
                return $comment;
            }
        }

        return false;
    }

    protected function getContentObject($content)
    {
        if (!$content instanceof Commentable) {
            $class = $this->config['default_commentable'];

            if(!$class) {
                throw new \InvalidArgumentException('Value set in config mural.default_commentable was not instance of ' . Commentable::class);
            }

            return with(new $class)->findOrFail($content);
        }

        return $content;
    }

}
