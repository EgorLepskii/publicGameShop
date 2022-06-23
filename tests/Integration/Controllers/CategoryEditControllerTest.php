<?php

namespace Tests\Integration\Controllers;

use App\Http\Controllers\CategoryController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\FrontCategory;
use App\Models\FrontProduct;
use App\Models\FrontUser;
use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;


class CategoryEditControllerTest extends \Tests\TestCase
{
    protected $faker;

    protected User $user;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->faker = Factory::create();
        DB::beginTransaction();
        $this->category = FrontCategory::factory()->create() ?? new FrontCategory();

        $this->user = FrontUser::factory()->create(['isAdmin' => true]) ?? new FrontUser();

        auth()->login($this->user);
    }

    /**
     * @return void
     */
    public function test_edit(): void
    {
        $this
            ->get(route('category.edit', ['category' => $this->category->getAttribute('id')]))
            ->assertSee(Lang::get('categories.updatePageTittle'));
    }


    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

}
