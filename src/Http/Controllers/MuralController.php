<?php

namespace Laravolt\Mural\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravolt\Mural\Factory;
use Laravolt\Mural\Http\Requests\Delete;
use Laravolt\Mural\Http\Requests\Store;
use Mural;

class MuralController extends Controller
{

    /**
     * ContentController constructor.
     */
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $content = Factory::create($request->get('id'), $request->get('type'));
        $comments = Mural::getComments($content, $request->get('room'), ['sort' => $request->get('sort')]);

        return view('mural::list', compact('comments', 'content'));

    }

    public function store(Store $request)
    {
        $json = ['status' => 0];
        $code = 500;

        $room = $request->get('room');

        try {
            $comment = Mural::addComment(Factory::create($request->get('commentable_id'), $request->get('commentable_type')), $request->get('body'), $room);
            $json['status'] = 1;
            $json['html'] = view('mural::item', compact('comment'))->render();
            $json['title'] = trans('mural::mural.title_with_count', ['count' => $comment->siblingsAndSelf()->has('author')->count()]);
            $code = 200;
        } catch (\Exception $e) {
            $json['error'] = $e->getMessage();
        }

        return response()->json($json, $code);
    }

    public function destroy(Delete $request, $id)
    {
        $json['status'] = 0;

        if ($comment = Mural::remove($id)) {
            $json['status'] = 1;
            $json['id'] = $id;
            $json['title'] = trans('mural::mural.title_with_count', ['count' => $comment->siblingsAndSelf()->has('author')->count()]);
        }

        return response()->json($json);
    }
}
