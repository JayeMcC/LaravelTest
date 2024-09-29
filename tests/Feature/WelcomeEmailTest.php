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
    $this->assertEquals(0, DB::table('jobs')->count());

    $response = $this->postJson('/api/register', [
      'name' => 'Jane Doe',
      'email' => 'jaye.r.mcc+laravelTestUser@gmail.com',
      'password' => 'password',
      'password_confirmation' => 'password',
    ]);

    $response->assertStatus(201);

    $this->assertEquals(1, DB::table('jobs')->count());

    $this->artisan('queue:work --once');

    $this->assertEquals(0, DB::table('jobs')->count());
  }
}
