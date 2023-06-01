<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Arr;
use LaravelZero\Framework\Commands\Command;
use Sigmie\Base\APIs\Cat;
use Sigmie\Sigmie;
use Symfony\Component\Console\Helper\Table;

class ListIndices extends Command
{
    use Cat;

    protected $signature = 'index:list';

    protected $description = 'List all Elasticsearch indices';

    public function handle()
    {
        /** @var  Sigmie */
        $sigmie = app(Sigmie::class);

        $this->setElasticsearchConnection($sigmie->getElasticsearchConnection());

        $indicesCat = $this->catAPICall('indices', 'GET');

        $aliasesCat = $this->catAPICall('aliases', 'GET');

        $indices = $indicesCat->json();

        $aliases = collect($aliasesCat->json())
            ->groupBy('index')
            ->mapWithKeys(fn ($aliases, $index) => [
                $index =>
                collect($aliases)->map(fn ($alias) => $alias['alias'])->implode(', ')
            ]);

        $moreKeys = ['index', 'pri', 'rep', 'docs.count', 'docs.deleted', 'store.size'];
        $names = array_map(fn ($index) => [
            $aliases->get($index['index']),
            ...Arr::only($index, $moreKeys)
        ], $indices);

        $this->output->newLine();

        $table = new Table($this->output);
        $table->setHeaderTitle('Indices');
        $table->setHeaders(['Aliases', 'Index', 'Pri', 'Rep', 'Docs Count', 'Docs Deleted', 'Store Size']);
        $table->setRows($names);

        $table->render();
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
