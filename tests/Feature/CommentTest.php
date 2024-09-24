<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    // Test listing comments on a post
    public function test_can_list_comments_for_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $post = Post::factory()->create(['user_id' => $user->id]);
        Comment::factory(5)->create(['post_id' => $post->id, 'user_id' => $user->id]);

        $response = $this->getJson("/api/posts/{$post->id}/comments");

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }

    // Test getting a specific comment on a post
    public function test_can_get_specific_comment()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

        $response = $this->getJson("/api/posts/{$post->id}/comments/{$comment->id}");

        $response->assertStatus(200);
        $response->assertJson(['id' => $comment->id]);
    }

    // Test creating a comment on a post
    public function test_can_create_comment_for_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson("/api/posts/{$post->id}/comments", [
            'content' => 'This is a test comment'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('comments', ['content' => 'This is a test comment']);
    }

    // Test updating a comment on a post
    public function test_can_update_comment()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

        $response = $this->patchJson("/api/posts/{$post->id}/comments/{$comment->id}", [
            'content' => 'Updated comment'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('comments', ['content' => 'Updated comment']);
    }

    // Test deleting a comment on a post
    public function test_can_delete_comment()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

        $response = $this->deleteJson("/api/posts/{$post->id}/comments/{$comment->id}");

        $response->assertStatus(204);
        $this->assertDeleted($comment);
    }
}
