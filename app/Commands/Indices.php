<?php

namespace App\Commands;

use FFI\CData;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Sigmie\Sigmie;

class Indices extends Command
{
    protected $signature = 'index:create {name}';

    protected $description = 'Create an Elasticsearch index';

    public function handle()
    {
        $this->create($this->argument('name'));

        $this->list('*');
    }

    public function list(string $pattern)
    {
        /** @var Sigmie $sigmie */
        $sigmie = app(Sigmie::class);

        $indices = $sigmie->indices();

        $names = array_map(fn ($index) => [$index->name], $indices);

        $this->output->table(['Indices'], $names);
    }

    public function create(string $name)
    {
        /** @var Sigmie $sigmie */
        $sigmie = app(Sigmie::class);

        $sigmie->newIndex($name)->create();
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
