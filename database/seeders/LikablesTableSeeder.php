<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class LikablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('likables')->delete();
        
        $users = User::all();
        $numberOfUsers = $users->count();
        $likes = [0, 1];

        foreach (Post::all() as $post) {
            for ($i=0; $i < rand(1, $numberOfUsers); $i++) { 
                $user = $users[$i];

                $user->likePost($post, $likes[rand(0, 1)]);
            }
        }

        foreach (Comment::all() as $comment) {
            for ($i=0; $i < rand(1, $numberOfUsers); $i++) { 
                $user = $users[$i];

                $user->likeComment($comment, $likes[rand(0, 1)]);
            }
        }
    }
}
