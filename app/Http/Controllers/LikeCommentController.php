<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LikeCommentController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }

    public function __invoke(Comment $comment)
    {
        $like = (int) request()->like;

        $likesCount = auth()->user()->likeComment($comment, $like);

        $isLiked = DB::table('likables')->where('user_id', auth()->id())->where('likable_id', $comment->id)->where('likable_type', 'App\Models\Comment')->where('like', '1')->count() > 0;

        if(request()->expectsJson()) {
            return response()->json([
                'message' => 'Thanks for the feedback',
                'likesCount' => $likesCount,
                'isLiked' => $isLiked
            ]);
        }

        return back();
    }
}
