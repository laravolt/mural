<?php

namespace Laravolt\Mural\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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

    public function fetch(Request $request)
    {
        $comments = Mural::getComments($request->get('commentable_id'), $request->get('room'), ['beforeId' => $request->get('last_id')]);

        return view('mural::list', compact('comments', 'content'));
    }

    public function store(Store $request)
    {
        $json = ['status' => 0];
        $code = 500;

        try {
            $comment = Mural::addComment($request->get('commentable_id'), $request->get('body'), $request->get('room'));
            $json['status'] = 1;
            $json['html'] = view('mural::item', compact('comment'))->render();
            $code = 200;
        } catch(\Exception $e) {
            $json['error'] = $e->getMessage();
        }

        return response()->json($json, $code);
    }

    public function destroy(Delete $request, $id)
    {
        $json['status'] = 0;

        if(Mural::remove($id)) {
            $json['status'] = 1;
            $json['id'] = $id;
        }

        return response()->json($json);
    }
}
