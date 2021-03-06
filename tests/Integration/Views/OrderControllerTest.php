<?php

namespace Tests\Integration\Views;

use App\Models\FrontUser;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        DB::beginTransaction();

        $this->user = FrontUser::factory()->create(['isAdmin' => true]) ?? new FrontUser();
    }

    public function test_main_content(): void
    {
        auth()->login($this->user);

        $this
            ->get(route('order.index'))
            ->assertSeeText(Lang::get('mainPage.myOrdersText'));
    }

    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}
