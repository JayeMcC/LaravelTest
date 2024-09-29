<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
  use RefreshDatabase;

  public function test_user_can_log_in_and_receive_token()
  {
    $user = User::factory()->create([
      'email' => 'john.doe@example.com',
      'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
      'email' => 'john.doe@example.com',
      'password' => 'password123',
    ]);

    $response->assertStatus(200)
      ->assertJsonStructure([
        'user',
        'token',
      ]);

    $this->assertArrayHasKey('token', $response->json());
  }

  public function test_user_can_log_out_and_token_is_deleted()
  {
    $user = User::factory()->create([
      'email' => 'john.doe@example.com',
      'password' => bcrypt('password123'),
    ]);

    $loginResponse = $this->postJson('/api/login', [
      'email' => 'john.doe@example.com',
      'password' => 'password123',
    ]);

    $token = $loginResponse->json('token');
    $this->assertNotNull($token);

    $this->withHeader('Authorization', 'Bearer ' . $token);

    $logoutResponse = $this->postJson('/api/logout');
    $logoutResponse->assertStatus(200)
      ->assertJson(['message' => 'Logged out successfully']);

    $this->withHeader('Authorization', 'Bearer ' . $token);
    $protectedRouteResponse = $this->getJson('/api/posts');
    $protectedRouteResponse->assertStatus(401);
  }
}
