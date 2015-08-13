<?php
namespace Laravolt\Mural;

use Illuminate\Database\Eloquent\Model;

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

    public function render($content, $room = null, $options = [])
    {
        $options = collect($options);
        $content = $this->getContentObject($content);
        $comments = $this->getComments($content, $room);
        $totalComment = $content->comments()->count();

        event('mural.render', [$content]);

        return view("mural::index", compact('content', 'comments', 'room', 'totalComment', 'options'))->render();
    }

    public function addComment($content, $body, $room = null)
    {
        $author = auth()->user();
        $content = $this->getContentObject($content);
        $comment = new Comment();
        $comment->body = $body;
        $comment->room = $room;
        $comment->author()->associate($author);

        if($content->comments()->save($comment)) {
            event('mural.comment.add', [$comment, $author]);
            return $comment;
        }

        return false;
    }

    public function getComments($content, $room = null, $options = [])
    {
        $options = collect($options);
        $content = $this->getContentObject($content);
        $comments = $content->comments()->newest()->room($room);

        if($options->has('beforeId')) {
            $comments->beforeId($options->get('beforeId'));
        }

        return $comments->paginate($this->config['per_page']);
    }

    protected function getContentObject($content)
    {
        if (!$content instanceof Model) {
            $class = $this->config['default_commentable'];

            if(!$class) {
                throw new \InvalidArgumentException('Parameter only accept valid Commentable object if config votee.content_model not set');
            }

            return with(new $class)->findOrFail($content);
        }

        return $content;
    }

}
