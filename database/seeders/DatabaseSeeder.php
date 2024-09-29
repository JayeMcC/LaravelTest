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
    $users = User::factory(10)->create();

    $testAdmin = User::factory()->admin()->create([
      'name' => 'Test Admin',
      'email' => 'jaye.r.mcc+laravelTestAdmin@gmail.com',
      'password' => bcrypt('password123'),
    ]);

    $testUser = User::factory()->create([
      'name' => 'Test User',
      'email' => 'jaye.r.mcc+laravelTestUser@gmail.com',
      'password' => bcrypt('password123'),
    ]);

    $users->push($testUser);
    $users->push($testAdmin);

    $users->each(function (User $user) use ($users) {
      $posts = Post::factory(5)->create(['user_id' => $user->id]);

      $posts->each(function (Post $post) use ($users) {
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
