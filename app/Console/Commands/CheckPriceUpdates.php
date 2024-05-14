<?php
// app/Console/Commands/CheckPriceUpdates.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MercadonaService;
use App\Services\TelegramService;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class CheckPriceUpdates extends Command
{
    protected $signature = 'price:check';
    protected $description = 'Check for price updates on products';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(MercadonaService $mercadonaService, TelegramService $telegramService)
    {
        $products = Product::all();

        foreach ($products as $product) {
            $currentPrice = $product->price;
            $newPrice = $mercadonaService->getLatestPrice($product->external_id);

            if ($newPrice === null) {
                $this->warn("No se pudo obtener el nuevo precio para el producto {$product->name} (ID: {$product->external_id})");
            } else if ($currentPrice != $newPrice) {
                $telegramService->sendMessage("{$product->name} ha cambiado de precio de {$currentPrice} a {$newPrice}");
                $product->update(['price' => $newPrice]);
            }

            // Añadir retraso para evitar ser bloqueado por la API
            usleep(200000); // 200 milisegundos
        }

        $this->info('Price check completed and notifications sent if necessary.');
    }
}
