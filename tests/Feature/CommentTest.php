<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
  use RefreshDatabase;

  public function test_can_list_comments_for_post()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);
    Comment::factory(5)->create(['post_id' => $post->id, 'user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson("/api/posts/{$post->id}/comments");

    $response->assertStatus(200);
    $response->assertJsonCount(5, 'data');
  }

  public function test_can_list_paginated_comments_for_post()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;
    $post = Post::factory()->create(['user_id' => $user->id]);
    Comment::factory(15)->create(['post_id' => $post->id, 'user_id' => $user->id]);

    // Test page 1
    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson("/api/posts/{$post->id}/comments?page=1");
    $response->assertStatus(200);
    $response->assertJsonCount(10, 'data');

    // Test page 2
    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson("/api/posts/{$post->id}/comments?page=2");
    $response->assertStatus(200);
    $response->assertJsonCount(5, 'data');
  }

  public function test_can_get_specific_comment()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $comment = Comment::factory()->create();

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson("/api/comments/{$comment->id}");

    $response->assertStatus(200);
    $response->assertJson(['id' => $comment->id]);
  }

  public function test_can_create_comment_for_post()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->postJson("/api/posts/{$post->id}/comments", [
        'content' => 'This is a test comment'
      ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('comments', ['content' => 'This is a test comment']);
  }

  public function test_can_update_own_comment()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $comment = Comment::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/comments/{$comment->id}", [
        'content' => 'Updated comment'
      ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('comments', ['content' => 'Updated comment']);
  }

  public function test_non_owner_cannot_update_comment()
  {
    $user = User::factory()->create();
    $nonOwner = User::factory()->create();
    $token = $nonOwner->createToken('Test Token')->plainTextToken;

    $comment = Comment::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/comments/{$comment->id}", [
        'content' => 'Updated comment by non-owner'
      ]);

    $response->assertStatus(403);
  }

  public function test_can_delete_own_comment()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $comment = Comment::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->deleteJson("/api/comments/{$comment->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
  }

  public function test_non_owner_cannot_delete_comment()
  {
    $user = User::factory()->create();
    $nonOwner = User::factory()->create();
    $token = $nonOwner->createToken('Test Token')->plainTextToken;

    $comment = Comment::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->deleteJson("/api/comments/{$comment->id}");
    $response->assertStatus(403);
  }

  public function test_admin_can_delete_any_comment()
  {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $token = $admin->createToken('Admin Token')->plainTextToken;

    $comment = Comment::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->deleteJson("/api/comments/{$comment->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
  }
}
