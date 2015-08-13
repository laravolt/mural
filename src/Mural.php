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

    public function render($content, $room = null)
    {
        $content = $this->getContentObject($content);
        $comments = $this->getComments($content, $room);

        return view("mural::index", compact('content', 'comments', 'room'))->render();
    }

    public function addComment($content, $body, $room = null)
    {
        $comment = new Comment();
        $comment->body = $body;
        $comment->room = $room;
        $comment->author()->associate(auth()->user());
        $content->comments()->save($comment);

        return $comment;
    }

    public function getComments($content, $room = null, $options = [])
    {
        $options = collect($options);
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
