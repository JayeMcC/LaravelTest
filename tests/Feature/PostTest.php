<?php

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
        // Create some posts
        Post::factory()->count(5)->create();

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'content', 'user_id', 'created_at', 'updated_at'],
                     ],
                     'links', 'meta'
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
     * Test updating a post.
     */
    public function test_update_post()
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
     * Test deleting a post.
     */
    public function test_delete_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(204);
    }
}
