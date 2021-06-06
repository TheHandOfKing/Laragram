<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Menu;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('keyword')) {
            $users = User::where("name", "like", "%" . $request->get('keyword') . "%")->orWhere("email", "like", "%" .$request->get('keyword') . "%")->latest()->paginate(4)->withQueryString();
        }
        
        else {
            $users = User::latest()->paginate(5);
        }

        if($request->has('admin')) {
            if($request->get('admin') == true) {
                return $users;
            }
        }
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
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $posts = auth()->user()->posts()->paginate(3)->withQueryString();

        $favorites = auth()->user()->favorites()->get();

        return view('dashboard.user')->with('posts', $posts)->with('favorites', $favorites);
    }

    public function showAdmin(User $user)
    {
        $posts = Post::with('user')->latest()->paginate(5)->withQueryString();
        $menu = new Menu();

        $users = User::paginate(5)->withQueryString();

		$favorites = auth()->user()->favorites()->get();

        return view('dashboard.admin')->with('posts', $posts)->with('users', $users)->with('favorites', $favorites);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Request $request)
    {
        $user = User::where('id', '=', $request->route('id'))->get();

        $userToPass = $user[0];

        return view('users.edit')->with('user', $userToPass);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
				$userToAdd = User::find($request->id)->firstOrFail();

				$userToAdd->update($request->only('name', 'email'));

        if($request->has('image')) 
        {
           $items = $userToAdd->getMedia('profileImages');

           if(count($items) > 0) 
           {
						foreach ($items as $mediaItem) {
							$mediaItem->delete();
						}

						$userToAdd->addMediaFromRequest('image')->toMediaCollection('profileImages');
           }

           else 
           {
							$image = $request['image'];

							$userToAdd->addMediaFromRequest('image')->toMediaCollection('profileImages');
           }
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => 'User updated successfully.'
            ], 204);
        }

        return redirect()->route('dashboardAdmin')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Request $request)
    {
        if($request->has('admin') && $request->get('admin') == true) {

            if(auth()->user()->roles == 'admin') {
                $user->delete();

                $users = User::latest()->paginate(4);

                return $users;
            } 
            else {
                return response(403);
            }
        }
    }
}
