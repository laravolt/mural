<?php

namespace Laravolt\Mural\Http\Controllers;

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
        $comments = Mural::getComments($request->get('commentable_id'), $request->get('room'), ['beforeId' => $request->get('last_id')]);

        return view('mural::list', compact('comments', 'content'));
    }

    public function store(Request $request)
    {
        $comment = Mural::addComment($request->get('commentable_id'), $request->get('body'), $request->get('room'));

        return view('mural::item', compact('comment'));
    }
}
