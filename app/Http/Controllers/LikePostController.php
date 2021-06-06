<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LikePostController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }

    public function __invoke(Post $post)
    {
        $like = (int) request()->like;

        $likesCount = auth()->user()->likePost($post, $like);

        $isLiked = DB::table('likables')->where('user_id', auth()->id())
        ->where('likable_id', $post->id)->where('likable_type', 'App\Models\Post')->where('like', '1')->count() > 0;

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
