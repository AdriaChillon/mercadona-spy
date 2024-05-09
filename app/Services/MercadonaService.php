<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

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
        $response = Http::get("https://tienda.mercadona.es/api/products/{$externalId}");
        $productData = $response->json();
        return $productData['price_instructions']['unit_price'] ?? null;
    }
    
    protected function storeProduct($productDetails)
    {
        \App\Models\Product::updateOrCreate(
            ['external_id' => $productDetails['id']], // Cambiado de 'id' a 'external_id' para claridad
            [
                'name' => $productDetails['display_name'],
                'price' => $productDetails['price_instructions']['unit_price'],
                'url' => $productDetails['share_url'],
                'thumbnail' => $productDetails['thumbnail']
            ]
        );
    }    
}
