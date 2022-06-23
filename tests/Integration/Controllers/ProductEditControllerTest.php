<?php

namespace Tests\Integration\Controllers;

use App\Http\Controllers\ProductController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\FrontCategory;
use App\Models\FrontProduct;
use App\Models\FrontUser;
use App\Models\Product;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use OpenApi\Examples\UsingRefs\Model;


class ProductEditControllerTest extends \Tests\TestCase
{
    protected $faker;

    protected Product $product;

    protected FrontUser $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        DB::beginTransaction();

        $this->product = FrontProduct::factory()->create() ?? new FrontProduct();
        $this->product->save();
        $this->user = FrontUser::factory()->create(['isAdmin' => true]) ?? new FrontUser();
        auth()->login($this->user);

    }

    /**
     * Assert, that there will be data with product and all categories in edit product page
     */
    public function test_edit(): void
    {
        $response = $this->get(route('product.edit', ['product' => $this->product->getAttribute('id')]));
        $response->assertSee($this->product->getName());

        foreach (FrontCategory::all() as $category) {
            $response->assertSee($category->getName());
        }

    }

    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

}