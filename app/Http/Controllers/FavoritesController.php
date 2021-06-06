<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class FavoritesController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }

    public function store(Post $post)
    {
        $post->favorites()->attach(auth()->id());

        if(request()->expectsJson()) {
            return response()->json(null, 204);
        }

        return back();
    }

    public function destroy(Post $post) 
    {
        $post->favorites()->detach(auth()->id());

        if(request()->expectsJson()) {
            return response()->json(null, 204);
        }

        return back();
    }
}
