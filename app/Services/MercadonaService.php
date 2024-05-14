<?php
// app/Services/MercadonaService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadonaService
{
    public function fetchProducts()
    {
        $response = Http::get('https://tienda.mercadona.es/api/categories/');
        $categories = $response->json()['results'];

        foreach ($categories as $category) {
            foreach ($category['categories'] as $subCategory) {
                $this->fetchAndStoreProducts($subCategory['id']);
            }
        }
    }

    protected function fetchAndStoreProducts($categoryId)
    {
        $response = Http::get("https://tienda.mercadona.es/api/categories/$categoryId");
        $products = $response->json()['categories'] ?? [];

        foreach ($products as $product) {
            foreach ($product['products'] as $productDetails) {
                $this->storeProduct($productDetails);
            }
        }
    }

    public function getLatestPrice($externalId)
    {
        try {
            $response = Http::get("https://tienda.mercadona.es/api/products/{$externalId}");
            Log::info("Response for product ID: {$externalId}. Response: " . $response->body());
            if ($response->successful()) {
                $productData = $response->json();
                return $productData['price_instructions']['unit_price'] ?? null;
            } else {
                Log::error("Error fetching product data for ID: {$externalId}. Response: " . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Exception fetching product data for ID: {$externalId}. Error: " . $e->getMessage());
            return null;
        }
    }

    protected function storeProduct($productDetails)
    {
        \App\Models\Product::updateOrCreate(
            ['external_id' => $productDetails['id']],
            [
                'name' => $productDetails['display_name'],
                'price' => $productDetails['price_instructions']['unit_price'] ?? 0,
                'url' => $productDetails['share_url'],
                'thumbnail' => $productDetails['thumbnail']
            ]
        );
    }
}
