<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AssignAvatarsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-avatars';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign random avatars to users who do not have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNull('avatar_path')->orWhere('avatar_path', '')->get();

        $count = 0;

        foreach ($users as $user) {
            // using pravatar.cc with email as seed for consistency
            $user->avatar_path = 'https://i.pravatar.cc/150?u=' . urlencode($user->email);
            $user->save();
            $count++;
        }

        $this->info("Assigned avatars to {$count} users.");
    }
}
