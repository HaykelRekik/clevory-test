<?php

namespace Feature\API\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()->create();
    }

    public function test_products_are_not_accessible_without_authentication(): void
    {
        $response = $this->getJson(route('products.index'));
        $response->assertUnauthorized();
    }

    public function test_products_are_accessible_with_authentication(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->getJson(route('products.index'), [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertOk();

        $this->assertDatabaseCount('products', 1);

    }
}
