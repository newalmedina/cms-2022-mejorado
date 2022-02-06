<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearOldPasswordHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password-history:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Old Password History';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Getting Users...');

        \App\Models\User::chunk(100, function ($users) {
            $users->each->deletePasswordHistory();
        });

        $this->info('Old Password Cleared...');

        return 0;
    }
}
