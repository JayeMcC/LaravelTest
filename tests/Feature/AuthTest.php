<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class AuthTest extends TestCase
{
  use RefreshDatabase;

  public function test_user_can_log_in_and_receive_token()
  {
    $user = User::factory()->create([
      'email' => 'jaye.r.mcc+laravelTestUser@gmail.com',
      'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
      'email' => 'jaye.r.mcc+laravelTestUser@gmail.com',
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
    $this->assertDatabaseCount('personal_access_tokens', 0);

    $user = User::factory()->create([
      'email' => 'jaye.r.mcc+laravelTestUser@gmail.com',
      'password' => bcrypt('password123'),
    ]);

    $preLoginProtectedRouteResponse = $this->getJson('/api/posts');
    $preLoginProtectedRouteResponse->assertStatus(401);

    $loginResponse = $this->postJson('/api/login', [
      'email' => 'jaye.r.mcc+laravelTestUser@gmail.com',
      'password' => 'password123',
    ]);

    $token = $loginResponse->json('token');
    $this->assertNotNull($token);
    $this->assertDatabaseCount('personal_access_tokens', 1);

    $this->withHeader('Authorization', 'Bearer ' . $token);
    $postLoginProtectedRouteResponse = $this->getJson('/api/posts');
    $postLoginProtectedRouteResponse->assertStatus(200);

    $this->withHeader('Authorization', 'Bearer ' . $token);
    $logoutResponse = $this->postJson('/api/logout');
    $logoutResponse->assertStatus(200)
      ->assertJson(['message' => 'Logged out successfully']);

    $this->assertDatabaseCount('personal_access_tokens', 0);

    // There's some cursed caching going in Sanctum
    // https://stackoverflow.com/questions/78755494/how-to-revoke-token-in-laravel-sanctum
    // https://laravel.io/forum/revoking-sanctum-api-token-doesnt-appear-to-prevent-use

    // "Have been informed by a Laravel developer that due to the internal architecture of Laravel's test framework, multiple requests per test are not supported; the framework only boots once per test, so a second request during a test veers into undefined behavior."

    $this->withHeader('Authorization', 'Bearer ' . $token);
    $postLogOutProtectedRouteResponse = $this->getJson('/api/posts');
    $postLogOutProtectedRouteResponse->assertStatus(401);
  }

  public function test_welcome_email_is_dispatched_on_registration()
  {
    Queue::fake();

    $response = $this->postJson('/api/register', [
      'name' => 'John Doe',
      'email' => 'jaye.r.mcc+laravelTestUser@gmail.com',
      'password' => 'password',
      'password_confirmation' => 'password',
    ]);

    $response->assertStatus(201);

    Queue::assertPushed(SendWelcomeEmail::class);
  }
}
