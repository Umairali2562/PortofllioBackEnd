<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartReactServer extends Command
{
    protected $signature = 'react:start';

    protected $description = 'Start the React server';

    public function handle()
    {
        // Execute npm start command in the background
        exec('npm start > /dev/null 2>&1 &');

        $this->info('React server started successfully.');
    }
}
