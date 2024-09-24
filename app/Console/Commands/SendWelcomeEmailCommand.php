<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Console\Command;

class SendWelcomeEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-welcome {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a welcome email to a specific user by ID';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);

        if (!$user) {
            $this->error('User not found.');
            return 1;
        }

        // Dispatch the job to send the welcome email
        SendWelcomeEmail::dispatch($user);

        $this->info("Welcome email sent to user: {$user->email}");

        return 0;
    }
}
