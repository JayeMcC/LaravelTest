<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test listing all posts.
   */
  public function test_list_all_posts()
  {
    Post::factory()->count(5)->create();

    $response = $this->getJson('/api/posts');

    $response->assertStatus(200)
      ->assertJsonStructure([
        'data' => [
          '*' => ['id', 'title', 'content', 'user_id', 'created_at', 'updated_at'],
        ],
        'links',
        'meta'
      ]);
  }

  /**
   * Test showing a specific post.
   */
  public function test_show_specific_post()
  {
    $post = Post::factory()->create();

    $response = $this->getJson("/api/posts/{$post->id}");

    $response->assertStatus(200)
      ->assertJson([
        'id' => $post->id,
        'title' => $post->title,
        'content' => $post->content,
      ]);
  }

  /**
   * Test creating a new post.
   */
  public function test_create_new_post()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $postData = [
      'title' => 'Test Post',
      'content' => 'This is a test post.',
    ];

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->postJson('/api/posts', $postData);

    $response->assertStatus(201)
      ->assertJson([
        'title' => 'Test Post',
        'content' => 'This is a test post.',
      ]);
  }

  /**
   * Test updating a post by the owner.
   */
  public function test_owner_can_update_post()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $updateData = ['title' => 'Updated Title'];

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/posts/{$post->id}", $updateData);

    $response->assertStatus(200)
      ->assertJson(['title' => 'Updated Title']);
  }

  /**
   * Test that a non-owner cannot update a post.
   */
  public function test_non_owner_cannot_update_post()
  {
    $user = User::factory()->create();
    $nonOwner = User::factory()->create();
    $token = $nonOwner->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $updateData = ['title' => 'Updated Title by Non-Owner'];

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/posts/{$post->id}", $updateData);

    $response->assertStatus(403);  // Forbidden
  }

  /**
   * Test that an admin can update any post.
   */
  public function test_admin_can_update_any_post()
  {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $token = $admin->createToken('Admin Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $updateData = ['title' => 'Admin Updated Title'];

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/posts/{$post->id}", $updateData);

    $response->assertStatus(200)
      ->assertJson(['title' => 'Admin Updated Title']);
  }

  /**
   * Test that the owner can delete their own post.
   */
  public function test_owner_can_delete_post()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->deleteJson("/api/posts/{$post->id}");

    $response->assertStatus(204);
  }

  /**
   * Test that a non-owner cannot delete a post.
   */
  public function test_non_owner_cannot_delete_post()
  {
    $user = User::factory()->create();
    $nonOwner = User::factory()->create();
    $token = $nonOwner->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->deleteJson("/api/posts/{$post->id}");

    $response->assertStatus(403);  // Forbidden
  }

  /**
   * Test that an admin can delete any post.
   */
  public function test_admin_can_delete_any_post()
  {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $token = $admin->createToken('Admin Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->deleteJson("/api/posts/{$post->id}");

    $response->assertStatus(204);
  }
}
