<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an admin user can restore a deleted comment.
     */
    public function test_admin_can_restore_comment()
    {
        // Create an admin user
        $admin = User::factory()->create(['is_admin' => true]);

        // Create a comment and delete it
        $comment = Comment::factory()->create();
        $comment->delete();

        // Acting as admin, attempt to restore the comment
        $response = $this->actingAs($admin)->postJson('/api/comments/' . $comment->id . '/restore');

        // Assert the response status is 200 OK (or whatever you set it to in the restore method)
        $response->assertStatus(200);

        // Assert that the comment is restored
        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'deleted_at' => null]);
    }

    /**
     * Test that a regular user cannot restore a deleted comment.
     */
    public function test_non_admin_cannot_restore_comment()
    {
        // Create a non-admin user
        $user = User::factory()->create(['is_admin' => false]);

        // Create a comment and delete it
        $comment = Comment::factory()->create();
        $comment->delete();

        // Acting as non-admin, attempt to restore the comment
        $response = $this->actingAs($user)->postJson('/api/comments/' . $comment->id . '/restore');

        // Assert the response status is 403 Forbidden
        $response->assertStatus(403);
    }

    /**
     * Test that an admin user can permanently delete a comment.
     */
    public function test_admin_can_force_delete_comment()
    {
        // Create an admin user
        $admin = User::factory()->create(['is_admin' => true]);

        // Create a comment and delete it
        $comment = Comment::factory()->create();
        $comment->delete();

        // Acting as admin, attempt to permanently delete the comment
        $response = $this->actingAs($admin)->deleteJson('/api/comments/' . $comment->id . '/force-delete');

        // Assert the response status is 204 No Content (or whatever you return)
        $response->assertStatus(204);

        // Assert that the comment is permanently deleted from the database
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    /**
     * Test that a non-admin user cannot permanently delete a comment.
     */
    public function test_non_admin_cannot_force_delete_comment()
    {
        // Create a non-admin user
        $user = User::factory()->create(['is_admin' => false]);

        // Create a comment and delete it
        $comment = Comment::factory()->create();
        $comment->delete();

        // Acting as non-admin, attempt to permanently delete the comment
        $response = $this->actingAs($user)->deleteJson('/api/comments/' . $comment->id . '/force-delete');

        // Assert the response status is 403 Forbidden
        $response->assertStatus(403);
    }

    /**
     * Test that the isAdmin method works properly.
     */
    public function test_is_admin_method()
    {
        // Create an admin user
        $admin = User::factory()->create(['is_admin' => true]);

        // Create a non-admin user
        $user = User::factory()->create(['is_admin' => false]);

        // Assert that the admin is recognized as an admin
        $this->assertTrue($admin->isAdmin());

        // Assert that the non-admin user is not an admin
        $this->assertFalse($user->isAdmin());
    }
}
