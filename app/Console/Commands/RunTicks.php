<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunTicks extends Command
{
    const TICK_RATE = 1; // 1 sec

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run application logic';

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
     * @return mixed
     */
    public function handle()
    {
        $active = true;
        $nextTime   = microtime(true) + self::TICK_RATE; // Set initial delay

        while($active) {
            usleep(1000);

            if (microtime(true) >= $nextTime) {
                Artisan::call('run:tick'); // TODO: can use queue
                $nextTime = microtime(true) + self::TICK_RATE;
            }
        }
    }
}
