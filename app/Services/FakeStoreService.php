<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

final class FakeStoreService
{
    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function getAllProducts(): array
    {
        return Http::get(config('services.fake-store.base_url').'products')
            ->throw()
            ->json();
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function syncProducts(): void
    {
        collect($this->getAllProducts())
            ->each(function (array $product) {
                Product::query()
                    ->updateOrCreate(attributes: [
                        'external_id' => $product['id'],
                    ], values: [
                        'title' => $product['title'],
                        'price' => (float) $product['price'],
                        'description' => $product['description'],
                        'category' => $product['category'],
                        'image' => $product['image'],
                    ]);
            });
    }
}
