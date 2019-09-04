<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    protected $description = 'Run application ticks';

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
            usleep(1000); // optional, if you want to be considerate

            if (microtime(true) >= $nextTime) {
                echo 'DO JOB' . PHP_EOL; // TODO: Run one tick command
                $nextTime = microtime(true) + self::TICK_RATE;
            }
        }
    }
}
