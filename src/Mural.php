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
        $comments = $content->comments()->latest()->room($room)->paginate($this->config['per_page']);

        return view("mural::index", compact('content', 'comments', 'room'))->render();
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
