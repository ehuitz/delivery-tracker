<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Package;
use Illuminate\Console\Command;

class SyncMeiliSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync MeiliSearch index with the latest package data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Flushing and re-importing MeiliSearch index...');
        Package::removeAllFromSearch();
        Package::makeAllSearchable();
        $this->info('MeiliSearch sync complete.');
    }
}
