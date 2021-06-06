<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('favorites')->delete();

        $users = User::pluck('id')->all();
        $numberOfUsers = count($users);

        foreach (Post::all() as $post) 
        {
            for ($i = 0; $i < rand(0, $numberOfUsers); $i++) {
                $user = $users[$i];

                $post->favorites()->attach($user);
            }
        }
    }
}
