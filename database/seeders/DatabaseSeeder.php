<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 random users
        $users = User::factory(10)->create();

        // Create a specific test user
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Add the test user to the users collection
        $users->push($testUser);

        $users->each(function (User $user) use ($users) {
            // Create 5 posts per user
            $posts = Post::factory(5)->create(['user_id' => $user->id]);

            // For each post, create random comments from random users
            $posts->each(function (Post $post) use ($users) {
                // Random users to comment on this post
                $commenters = $users->random(rand(1, 3));

                $commenters->each(function (User $commenter) use ($post) {
                    Comment::factory(rand(1, 3))->create([
                        'post_id' => $post->id,
                        'user_id' => $commenter->id,
                    ]);
                });
            });
        });
    }
}
