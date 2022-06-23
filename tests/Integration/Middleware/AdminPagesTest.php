<?php

namespace Tests\Integration\Middleware;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\FrontCategory;
use App\Models\FrontProduct;
use App\Models\FrontUser;
use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    protected User $user;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        Factory::create();
        $this->withoutMiddleware(VerifyCsrfToken::class);

        DB::beginTransaction();

        $this->user = FrontUser::factory()->create(['isAdmin' => false]) ?? new FrontUser();
    }

    public function test_not_admin_access_to_main_panel(): void
    {
        auth()->login($this->user);

        $this->get(route('admin.show'))->assertForbidden();
    }

    public function test_not_admin_access_to_category_panel(): void
    {
        auth()->login($this->user);

        $this->get(route('adminCategory.show'))->assertForbidden();
    }

    public function test_not_admin_access_to_product_panel(): void
    {
        auth()->login($this->user);

        $this->get(route('adminProduct.show'))->assertForbidden();
    }

    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

}
