<?php

namespace Laravolt\Mural\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Mural;

class MuralController extends Controller
{

    /**
     * ContentController constructor.
     */
    public function __construct()
    {
    }

    public function fetch(Request $request)
    {
        $content = Post::findOrFail($request->get('commentable_id'));
        $comments = Mural::getComments($content, $request->get('room'), ['beforeId' => $request->get('last_id')]);

        return view('mural::list', compact('comments', 'content'));
    }

    public function store(Request $request)
    {
        $content = Post::findOrFail($request->get('commentable_id'));
        $comment = Mural::addComment($content, $request->get('body'), $request->get('room'));

        return view('mural::item', compact('comment'));
    }
}
