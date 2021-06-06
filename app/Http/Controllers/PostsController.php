<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePostRequest;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->has('keyword')) 
        {
            if($request->has('admin')) 
            {
                if($request->get('admin') == true) {
                    $posts = Post::with('user')->where("name", "like", "%" . $request->get('keyword') . "%")->orWhere('location', 'like', '%' . $request->get('keyword') . '%')->latest()->paginate(5)->withQueryString();
                    return $posts;
                }
            }

            if($request->has('user')) {
                if($request->get('user') == true) {
                    $posts = Post::where('user_id', "=", $request->get('user_id'))->where("name", "like", "%" . $request->get('keyword') . "%")->latest()->paginate(3)->withQueryString();
                    return $posts;
                }
            }
        }
        else 
        {
            $posts = Post::with('user')->latest()->paginate(5);
        }

        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $post = new Post();

        return view('posts.create')->with('post', $post);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        $post = $request->user()->posts()->create($request->only('name', 'description', 'location'));

        $images = $request['images'];
        
        if (isset($images)) {
            foreach ($images as $image) {
                $post->addMedia($image)->toMediaCollection();
            }
        }

        return redirect()->route('posts.index')->with('success', 'Post Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post->increment('views');

        $mediaItems = $post->getMedia();

        $images = array();

        if(count($mediaItems) > 0) {
            foreach ($mediaItems as $mediaItem) {
                array_push($images, $mediaItem->getUrl());
            }
            $post->image_url = $images;
        }

        else $post->image_url = [env('APP_URL') . '/public/storage/notset.jpg'];

        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $post->update($request->only('name', 'location', 'description'));

        if($request->has('images')) 
        {
            $images = $request['images'];
            $items = $post->getMedia();

            if(count($items) > 0) 
            {
                foreach ($items as $mediaItem) {
                    $mediaItem->delete();
                }
                
                foreach ($images as $image) {
                    $post->addMedia($image)->toMediaCollection();
                }
            }

            else 
            {
                foreach ($images as $image) {
                    $post->addMedia($image)->toMediaCollection();
                }
            }
        }
        

        return redirect()->route('posts.index')->with('success', 'Your post has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, Request $request)
    {
        if($request->has('admin') && $request->get('admin') == true) {
            $post->delete();

            $posts = Post::with('user')->latest()->paginate(5);

            return $posts;
        }
        
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post successfuly deleted');
    }
}
