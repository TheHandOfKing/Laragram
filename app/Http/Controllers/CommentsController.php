<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth')->except('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post)
    {
        return $post->comments()->with('user')->simplePaginate(7);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Post $post, Request $request)
    {
        $request->validate([
            'body' => 'required'
        ]);

        $comment = $post->comments()->create(
            $request->validate([
                'body' => 'required'
        ]) + ['user_id' => \Auth::id()]);

        if($request->expectsJson()) {
            return response()->json([
                'message' => 'Your comment has been submitted',
                'answer' => $comment->load('user')
            ]);
        }
        return back()->with('success', 'Your comment has been submitted');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post, Comment $comment)
    {
        $this->authorize('update', $comment);

        return view('comments.edit', compact(['post', 'comment']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post, Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->update($request->validate([
            'body' => 'required',
        ]));

        if($request->expectsJson()) {
            return response()->json([
                'message' => 'Your answer has been updated',
                'body_html' => $comment->body_html
            ]);
        }

        return redirect()->route('posts.show', $post->slug)->with('success', 'Your comment has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        if(request()->expectsJson()) 
        {
            return response()->json([
                'message' => 'Your comment has been deleted'
            ]);
        }

        return back()->with('success', 'Your comment has been deleted');
    }
}
