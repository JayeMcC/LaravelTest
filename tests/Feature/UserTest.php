<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
  use RefreshDatabase;

  public function test_can_get_own_user_profile()
  {
    $user = User::factory()->create();

    $token = $user->createToken('Test Token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson("/api/users/{$user->id}");

    $response->assertStatus(200);
    $response->assertJson(['id' => $user->id, 'email' => $user->email]);
  }

  public function test_cant_get_other_user_profile()
  {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $token = $user->createToken('Test Token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson("/api/users/{$otherUser->id}");

    $response->assertStatus(403);
  }

  public function test_admin_can_access_user_list()
  {
    $admin = User::factory()->admin()->create();

    User::factory(5)->create();

    $token = $admin->createToken('Test Token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson('/api/users');

    $response->assertStatus(200);
    $response->assertJsonStructure(['data' => ['*' => ['id', 'email', 'name']]]);
  }

  public function test_admin_can_access_paginated_user_list()
  {
    $admin = User::factory()->admin()->create();
    User::factory(15)->create();
    $token = $admin->createToken('Test Token')->plainTextToken;

    // Test page 1
    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson('/api/users?page=1');
    $response->assertStatus(200);
    $response->assertJsonCount(10, 'data');

    // Test page 2
    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson('/api/users?page=2');
    $response->assertStatus(200);
    $response->assertJsonCount(6, 'data');
  }

  public function test_non_admin_cant_access_user_list()
  {
    $user = User::factory()->create();

    User::factory(5)->create();

    $token = $user->createToken('Test Token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
      ->getJson('/api/users');

    $response->assertStatus(403);
  }

  public function test_is_admin_method()
  {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->assertTrue($admin->isAdmin());

    $this->assertFalse($user->isAdmin());
  }
}
