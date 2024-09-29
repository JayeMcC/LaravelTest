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

  public function test_list_all_posts()
  {
    Post::factory()->count(5)->create();

    $user = User::factory()->create();

    $token = $user->createToken('Test Token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson('/api/posts');

    $response->assertStatus(200)
      ->assertJsonStructure([
        'data' => [
          '*' => ['id', 'title', 'content', 'user_id', 'created_at', 'updated_at'],
        ],
        'links'
      ]);
  }

  public function test_can_list_paginated_posts()
  {
    Post::factory(15)->create();

    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    // Test page 1
    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson('/api/posts?page=1');
    $response->assertStatus(200);
    $response->assertJsonCount(10, 'data');

    // Test page 2
    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson('/api/posts?page=2');
    $response->assertStatus(200);
    $response->assertJsonCount(5, 'data');
  }

  public function test_show_specific_post()
  {
    $post = Post::factory()->create();

    $user = User::factory()->create();

    $token = $user->createToken('Test Token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson("/api/posts/{$post->id}");

    $response->assertStatus(200)
      ->assertJson([
        'id' => $post->id,
        'title' => $post->title,
        'content' => $post->content,
      ]);
  }

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

  public function test_owner_can_update_post()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $updateData = ['title' => 'Updated Title', 'content' => 'Updated Content'];

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/posts/{$post->id}", $updateData);

    $response->assertStatus(200)
      ->assertJson([
        'title' => 'Updated Title',
        'content' => 'Updated Content'
      ]);
  }

  public function test_owner_can_update_post_title()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $updateData = ['title' => 'Updated Title'];

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/posts/{$post->id}", $updateData);

    $response->assertStatus(200)
      ->assertJson([
        'title' => 'Updated Title',
        'content' => $post->content
      ]);
  }

  public function test_owner_can_update_post_content()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $updateData = ['content' => 'Updated Content'];

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/posts/{$post->id}", $updateData);

    $response->assertStatus(200)
      ->assertJson([
        'title' => $post->title,
        'content' => 'Updated Content'
      ]);
  }

  public function test_owner_cant_update_with_no_details()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/posts/{$post->id}", []);

    $response->assertStatus(422);
  }

  public function test_non_owner_cannot_update_post()
  {
    $user = User::factory()->create();
    $nonOwner = User::factory()->create();
    $token = $nonOwner->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $updateData = ['title' => 'Updated Title by Non-Owner'];

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/posts/{$post->id}", $updateData);

    $response->assertStatus(403);
  }

  public function test_admin_can_update_any_post()
  {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();
    $token = $admin->createToken('Admin Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $updateData = ['title' => 'Updated by Admin'];

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/posts/{$post->id}", $updateData);

    $response->assertStatus(200)
      ->assertJson(['title' => 'Updated by Admin']);
  }

  public function test_owner_can_delete_post()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->deleteJson("/api/posts/{$post->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
  }

  public function test_non_owner_cannot_delete_post()
  {
    $user = User::factory()->create();
    $nonOwner = User::factory()->create();
    $token = $nonOwner->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->deleteJson("/api/posts/{$post->id}");

    $response->assertStatus(403);
  }

  public function test_admin_can_delete_any_post()
  {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();
    $token = $admin->createToken('Admin Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->deleteJson("/api/posts/{$post->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
  }
}
