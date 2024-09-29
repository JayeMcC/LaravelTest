<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WelcomeEmailTest extends TestCase
{
  use RefreshDatabase;

  public function test_welcome_email_is_dispatched_to_real_queue()
  {
    // Assert there are no jobs in the queue initially
    $this->assertEquals(0, DB::table('jobs')->count());

    // Register a new user
    $response = $this->postJson('/api/register', [
      'name' => 'Jane Doe',
      'email' => 'jane@example.com',
      'password' => 'password',
      'password_confirmation' => 'password',
    ]);

    $response->assertStatus(201);

    // Assert the job is added to the database queue
    $this->assertEquals(1, DB::table('jobs')->count());

    // Process the queue and ensure the job is executed
    $this->artisan('queue:work --once');

    // Assert the queue is empty after processing
    $this->assertEquals(0, DB::table('jobs')->count());
  }
}
