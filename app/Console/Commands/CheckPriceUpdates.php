<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MercadonaService;
use App\Services\TelegramService;
use App\Models\Product;

class CheckPriceUpdates extends Command
{
    protected $signature = 'price:check';
    protected $description = 'Check for price updates on products';

    public function handle(MercadonaService $mercadonaService, TelegramService $telegramService)
    {
        $mercadonaService->fetchProducts(); // Asumimos que este método actualiza los precios actuales

        $products = Product::all();

        foreach ($products as $product) {
            $currentPrice = $product->price;
            $newPrice = $mercadonaService->getLatestPrice($product->external_id); // Método ficticio, necesitas implementarlo

            if ($currentPrice != $newPrice) {
                $telegramService->sendMessage('chat_id', "{$product->name} ha cambiado de precio de {$currentPrice} a {$newPrice}");
                $product->update(['price' => $newPrice]);
            }
        }
    }
}

