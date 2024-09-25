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
        $this->actingAs($user, 'sanctum');

        $postData = [
            'title' => 'Test Post',
            'content' => 'This is a test post.',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(201)
            ->assertJson([
                'title' => 'Test Post',
                'content' => 'This is a test post.',
            ]);
    }

    /**
     * Test updating a post (owner can update).
     */
    public function test_owner_can_update_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $post = Post::factory()->create(['user_id' => $user->id]);

        $updateData = ['title' => 'Updated Title'];

        $response = $this->patchJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson(['title' => 'Updated Title']);
    }

    /**
     * Test non-owner cannot update a post.
     */
    public function test_non_owner_cannot_update_post()
    {
        $user = User::factory()->create();
        $nonOwner = User::factory()->create();
        $this->actingAs($nonOwner, 'sanctum');

        $post = Post::factory()->create(['user_id' => $user->id]);

        $updateData = ['title' => 'Updated Title by Non-Owner'];

        $response = $this->patchJson("/api/posts/{$post->id}", $updateData);

        // Expect 403 Forbidden as the non-owner shouldn't be able to update the post
        $response->assertStatus(403);
    }

    /**
     * Test admin can update any post.
     */
    public function test_admin_can_update_any_post()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $this->actingAs($admin, 'sanctum');

        $post = Post::factory()->create(['user_id' => $user->id]);

        $updateData = ['title' => 'Admin Updated Title'];

        $response = $this->patchJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson(['title' => 'Admin Updated Title']);
    }

    /**
     * Test deleting a post (owner can delete).
     */
    public function test_owner_can_delete_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(204);
    }

    /**
     * Test non-owner cannot delete a post.
     */
    public function test_non_owner_cannot_delete_post()
    {
        $user = User::factory()->create();
        $nonOwner = User::factory()->create();
        $this->actingAs($nonOwner, 'sanctum');

        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        // Expect 403 Forbidden as the non-owner shouldn't be able to delete the post
        $response->assertStatus(403);
    }

    /**
     * Test admin can delete any post.
     */
    public function test_admin_can_delete_any_post()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $this->actingAs($admin, 'sanctum');

        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(204);
    }
}
