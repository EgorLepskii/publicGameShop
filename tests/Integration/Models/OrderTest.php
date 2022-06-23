<?php

namespace Tests\Integration\Models;

use App\Models\Category;
use App\Models\FrontCategory;
use App\Models\FrontUser;
use App\Models\Order;
use App\Models\FrontProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class  OrderTest extends TestCase
{

    /**
     * @var int
     */
    public const PRODUCTS_COUNT_FOR_TESTS = 10;

    private Category $category;

    private Product $product;

    private User $user;

    private Order $order;

    protected $faker;


    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        DB::beginTransaction();
        $this->category = FrontCategory::factory()->create() ?? new FrontCategory();

        $this->product = self::createProduct($this->category->getAttribute('id'));
        $this->product->save();

        $this->user = FrontUser::factory()->create(['isAdmin' => false]) ?? new FrontUser();

        $this->order = self::creatreOrder($this->user->getAttribute('id'), $this->product->getId());
        $this->order->save();

    }

    public function test_user_relation(): void
    {
        $this->assertEquals(
            $this->order->user()->first()->getAttribute('id'),
            $this->user->getAttribute('id')
        );
    }

    public function test_product_relation(): void
    {
        $this->assertEquals(
            $this->order->product()->first()->getAttribute('id'),
            $this->product->getAttribute('id')
        );
    }

    public function test_general_price(): void
    {
        $generalPrice = $this->product->getPrice();

        for ($i = 0; $i < self::PRODUCTS_COUNT_FOR_TESTS; ++$i)
        {
            $product = self::createProduct($this->category->getAttribute('id'));
            $product->save();
            $generalPrice += $product->getPrice();
            $order = self::creatreOrder($this->user->getAttribute('id'), $product->getId());
            $order->save();
        }

        $this->assertEquals(Order::getGeneralPrice($this->user->getAttribute('id')), $generalPrice);
    }

    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}
