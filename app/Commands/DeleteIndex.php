<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Sigmie\Index\AliasedIndex;
use Sigmie\Sigmie;

class DeleteIndex extends Command
{
    protected $signature = 'index:delete {index}';

    protected $description = 'Delete an index in the cluster';

    public function handle()
    {
        /** @var Sigmie $sigmie */
        $sigmie = app(\Sigmie\Sigmie::class);

        $name = $this->argument('index');

        $index = $sigmie->index($name);

        if ($index instanceof AliasedIndex) {

            $sigmie->delete($index->name);

            $this->info("Index {$index->name} deleted.");

            return;
        }

        $this->info("Index '{$name}' does not exist.");
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
