<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class UsersPostsCommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('comments')->delete();
        \DB::table('posts')->delete();
        \DB::table('users')->delete();
        // $this->call(UserSeeder::class);
        User::factory()->count(4)->has(Post::factory()->count(3)->has(Comment::factory()->count(3))
            // function($user){
            // $user->posts()->saveMany(
            //     Post::factory()->count(4)->make()
            // )->each(function($q) {
            //     $q->comments()->saveMany(factory(Comment::class, rand(1, 8))->make());
            // }
        // );
        // }
    )->create();
    }
}
