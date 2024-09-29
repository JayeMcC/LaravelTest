<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnAuthTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test unauthenticated access to the posts index (list all posts).
   */
  public function test_unauthenticated_access_to_posts_index()
  {
    $response = $this->getJson('/api/posts');

    $response->assertStatus(401)
      ->assertJson(['error' => 'Unauthenticated.']);
  }

  /**
   * Test unauthenticated access to view a specific post.
   */
  public function test_unauthenticated_access_to_view_specific_post()
  {
    $response = $this->getJson('/api/posts/1'); // Assuming post ID is 1

    $response->assertStatus(401)
      ->assertJson(['error' => 'Unauthenticated.']);
  }

  /**
   * Test unauthenticated attempt to create a post.
   */
  public function test_unauthenticated_attempt_to_create_post()
  {
    $postData = [
      'title' => 'Unauthorized Post',
      'content' => 'This is an unauthorized post.',
    ];

    $response = $this->postJson('/api/posts', $postData);

    $response->assertStatus(401)
      ->assertJson(['error' => 'Unauthenticated.']);
  }

  /**
   * Test unauthenticated attempt to update a post.
   */
  public function test_unauthenticated_attempt_to_update_post()
  {
    $updateData = [
      'title' => 'Updated Title',
      'content' => 'Updated content.',
    ];

    $response = $this->patchJson('/api/posts/1', $updateData); // Assuming post ID is 1

    $response->assertStatus(401)
      ->assertJson(['error' => 'Unauthenticated.']);
  }

  /**
   * Test unauthenticated attempt to delete a post.
   */
  public function test_unauthenticated_attempt_to_delete_post()
  {
    $response = $this->deleteJson('/api/posts/1'); // Assuming post ID is 1

    $response->assertStatus(401)
      ->assertJson(['error' => 'Unauthenticated.']);
  }

  /**
   * Test unauthenticated access to comments index (list all comments for a post).
   */
  public function test_unauthenticated_access_to_comments_index()
  {
    $response = $this->getJson('/api/posts/1/comments'); // Assuming post ID is 1

    $response->assertStatus(401)
      ->assertJson(['error' => 'Unauthenticated.']);
  }

  /**
   * Test unauthenticated attempt to create a comment.
   */
  public function test_unauthenticated_attempt_to_create_comment()
  {
    $commentData = [
      'content' => 'This is an unauthorized comment.',
    ];

    $response = $this->postJson('/api/posts/1/comments', $commentData); // Assuming post ID is 1

    $response->assertStatus(401)
      ->assertJson(['error' => 'Unauthenticated.']);
  }

  /**
   * Test unauthenticated access to view a specific comment.
   */
  public function test_unauthenticated_access_to_view_specific_comment()
  {
    $response = $this->getJson('/api/posts/1/comments/1'); // Assuming post ID is 1 and comment ID is 1

    $response->assertStatus(401)
      ->assertJson(['error' => 'Unauthenticated.']);
  }

  /**
   * Test unauthenticated attempt to update a comment.
   */
  public function test_unauthenticated_attempt_to_update_comment()
  {
    $updateData = [
      'content' => 'Updated unauthorized comment content.',
    ];

    $response = $this->patchJson('/api/posts/1/comments/1', $updateData); // Assuming post ID is 1 and comment ID is 1

    $response->assertStatus(401)
      ->assertJson(['error' => 'Unauthenticated.']);
  }

  /**
   * Test unauthenticated attempt to delete a comment.
   */
  public function test_unauthenticated_attempt_to_delete_comment()
  {
    $response = $this->deleteJson('/api/posts/1/comments/1'); // Assuming post ID is 1 and comment ID is 1

    $response->assertStatus(401)
      ->assertJson(['error' => 'Unauthenticated.']);
  }

  /**
   * Test unauthenticated access to users index (list all users).
   */
  public function test_unauthenticated_access_to_users_index()
  {
    $response = $this->getJson('/api/users');

    $response->assertStatus(401)
      ->assertJson(['error' => 'Unauthenticated.']);
  }

  /**
   * Test unauthenticated access to view a specific user.
   */
  public function test_unauthenticated_access_to_view_specific_user()
  {
    $response = $this->getJson('/api/users/1'); // Assuming user ID is 1

    $response->assertStatus(401)
      ->assertJson(['error' => 'Unauthenticated.']);
  }

  /**
   * Test unauthenticated attempt to log out.
   */
  public function test_unauthenticated_attempt_to_logout()
  {
    $response = $this->postJson('/api/logout');

    $response->assertStatus(401)
      ->assertJson(['error' => 'Unauthenticated.']);
  }
}
