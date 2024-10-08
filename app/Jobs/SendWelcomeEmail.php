<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\JobHistory;

class SendWelcomeEmail implements ShouldQueue
{
  use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

  protected $user;

  /**
   * Create a new job instance.
   *
   * @param User $user
   * @return void
   */
  public function __construct(User $user)
  {
    $this->user = $user;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    Mail::to($this->user->email)->send(new WelcomeMail($this->user));

    JobHistory::create([
      'job_name' => self::class,
      'payload' => json_encode($this->user),
      'completed_at' => now(),
    ]);
  }
}
