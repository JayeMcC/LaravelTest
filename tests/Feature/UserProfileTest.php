<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test fetching a user's profile.
   */
  public function test_can_get_own_user_profile()
  {
    $user = User::factory()->create();

    $token = $user->createToken('Test Token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson("/api/users/{$user->id}");

    $response->assertStatus(200);
    $response->assertJson(['user' => ['id' => $user->id, 'email' => $user->email]]);
  }

  /**
   * Test updating the user's profile.
   */
  public function test_can_update_own_user_profile()
  {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/users/{$user->id}", ['name' => 'Updated Name', 'email' => $user->email]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
  }

  /**
   * Test normal user cannot update another user's profile.
   */
  public function test_normal_user_cannot_update_other_user()
  {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $token = $user->createToken('Test Token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/users/{$otherUser->id}", ['name' => 'Hacker Name']);

    $response->assertStatus(403); // Forbidden
  }

  /**
   * Test user list access.
   */
  public function test_admin_can_access_user_list()
  {
    $user = User::factory()->create();

    User::factory(5)->create();

    $token = $user->createToken('Test Token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson('/api/users');

    $response->assertStatus(200);
    $response->assertJsonStructure(['data' => ['*' => ['id', 'email', 'name']]]);
  }

  /**
   * Test admin can update other users.
   */
  public function test_admin_can_update_other_user()
  {
    $admin = User::factory()->admin()->create();
    $otherUser = User::factory()->create();

    $token = $admin->createToken('Admin Token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->patchJson("/api/users/{$otherUser->id}", ['name' => 'Updated by Admin']);

    $response->assertStatus(200);
    $this->assertDatabaseHas('users', ['id' => $otherUser->id, 'name' => 'Updated by Admin']);
  }

  /**
   * Test admin can delete other users.
   */
  public function test_admin_can_delete_other_user()
  {
    $admin = User::factory()->admin()->create();
    $otherUser = User::factory()->create();

    $token = $admin->createToken('Admin Token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->deleteJson("/api/users/{$otherUser->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('users', ['id' => $otherUser->id]);
  }

  /**
   * Test that the isAdmin method works properly.
   */
  public function test_is_admin_method()
  {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->assertTrue($admin->isAdmin());

    $this->assertFalse($user->isAdmin());
  }
}
