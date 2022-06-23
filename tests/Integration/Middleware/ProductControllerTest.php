<?php

namespace Tests\Integration\Middleware;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\FrontCategory;
use App\Models\FrontProduct;
use App\Models\FrontUser;
use App\Models\Product;
use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    protected User $user;

    protected User $admin;

    protected Category $category;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        Factory::create();
        $this->withoutMiddleware(VerifyCsrfToken::class);

        DB::beginTransaction();


        $this->user = FrontUser::factory()->create(['isAdmin' => false]) ?? new FrontUser();

        $this->admin = FrontUser::factory()->create(['isAdmin' => true]) ?? new FrontUser();

        $this->category = FrontCategory::factory()->create() ?? new FrontCategory();

        $this->product = $this->createProduct($this->category->getAttribute('id'));

        $this->product->save();

    }

    public function test_edit_not_admin_access(): void
    {
        auth()->login($this->user);
        $this->get(route('product.edit', ['product' => $this->product->getId()]))->assertForbidden();
    }

    public function test_store_not_admin_access(): void
    {
        auth()->login($this->user);
        $this->post(route('product.store', ['product' => $this->product->getId()]))->assertForbidden();
    }

    public function test_update_not_admin_access(): void
    {
        auth()->login($this->user);
        $this->post(route('product.update', ['product' => $this->product->getId()]))->assertForbidden();
    }

    public function test_delete_not_admin_access(): void
    {
        auth()->login($this->user);
        $this->post(route('product.destroy', ['product' => $this->product->getId()]))->assertForbidden();
    }

    public function test_edit_admin_access(): void
    {
        auth()->login($this->admin);
        $status = $this->get(route('product.edit', ['product' => $this->product->getId()]))->status();

        $this->assertTrue($status != 403);
    }

    public function test_store_admin_access(): void
    {
        auth()->login($this->admin);
        $status = $this->post(route('product.store', ['product' => $this->product->getId()]))->status();

        $this->assertTrue($status != 403);
    }

    public function test_update_admin_access(): void
    {
        auth()->login($this->admin);
        $status = $this->post(route('product.update', ['product' => $this->product->getId()]))->status();

        $this->assertTrue($status != 403);
    }

    public function test_delete_admin_access(): void
    {
        auth()->login($this->admin);
        $status = $this->post(route('product.destroy', ['product' => $this->product->getId()]))->status();

        $this->assertTrue($status != 403);
    }

    public function test_show_authorized(): void
    {
        auth()->login($this->user);
        $status = $this->get(route('product.show', ['product' => $this->product->getId()]))->status();

        $this->assertTrue($status == 200);
    }

    public function test_show_unauthorized(): void
    {
        auth()->logout();
        $status = $this->get(route('product.show', ['product' => $this->product->getId()]))->status();

        $this->assertTrue($status == 200);
    }


    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

}