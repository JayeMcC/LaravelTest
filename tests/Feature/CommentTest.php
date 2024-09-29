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

  /**
   * Test listing all comments for a specific post.
   */
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

  /**
   * Test fetching a specific comment.
   */
  public function test_can_get_specific_comment()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);
    $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson("/api/posts/{$post->id}/comments/{$comment->id}");

    $response->assertStatus(200);
    $response->assertJson(['id' => $comment->id]);
  }

  /**
   * Test creating a comment on a post.
   */
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

  /**
   * Test that a comment owner can update their comment.
   */
  public function test_can_update_own_comment()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);
    $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/posts/{$post->id}/comments/{$comment->id}", [
        'content' => 'Updated comment'
      ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('comments', ['content' => 'Updated comment']);
  }

  /**
   * Test that a non-owner cannot update another user's comment.
   */
  public function test_non_owner_cannot_update_comment()
  {
    $user = User::factory()->create();
    $nonOwner = User::factory()->create();
    $token = $nonOwner->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);
    $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/posts/{$post->id}/comments/{$comment->id}", [
        'content' => 'Updated comment by non-owner'
      ]);

    $response->assertStatus(403);  // Forbidden
  }

  /**
   * Test that a comment owner can delete their comment.
   */
  public function test_can_delete_own_comment()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);
    $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->deleteJson("/api/posts/{$post->id}/comments/{$comment->id}");

    $response->assertStatus(200);
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
  }

  /**
   * Test that a non-owner cannot delete another user's comment.
   */
  public function test_non_owner_cannot_delete_comment()
  {
    $user = User::factory()->create();
    $nonOwner = User::factory()->create();
    $token = $nonOwner->createToken('Test Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);
    $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->deleteJson("/api/posts/{$post->id}/comments/{$comment->id}");

    $response->assertStatus(403);  // Forbidden
  }

  /**
   * Test that an admin can delete any comment.
   */
  public function test_admin_can_delete_any_comment()
  {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $token = $admin->createToken('Admin Token')->plainTextToken;

    $post = Post::factory()->create(['user_id' => $user->id]);
    $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->deleteJson("/api/posts/{$post->id}/comments/{$comment->id}");

    $response->assertStatus(200);
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
  }
}
