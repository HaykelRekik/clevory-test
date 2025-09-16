<?php

namespace App\Console\Commands;

use App\Services\FakeStoreService;
use Illuminate\Console\Command;

class SyncProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to sync products from FakeStore API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Syncing products from FakeStore API...');

        (new FakeStoreService)->syncProducts();
    }
}
