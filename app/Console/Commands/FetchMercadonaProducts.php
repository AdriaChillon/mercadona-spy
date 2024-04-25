<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MercadonaService;

class FetchMercadonaProducts extends Command
{
    protected $signature = 'fetch:mercadona-products';
    protected $description = 'Fetch products from Mercadona API and store them in the database';

    public function handle(MercadonaService $service)
    {
        $service->fetchProducts();
        $this->info('Products have been fetched and stored successfully.');
    }
}
