<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class PinCommentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Comment $comment)
    {
        $this->authorize('accept', $comment);
        
        $comment->post->pinComment($comment);

        if(request()->expectsJson()) {
            return response()->json([
                'message' => 'You have pinned this comment'
            ]);
        }

        return back();
    }
}
