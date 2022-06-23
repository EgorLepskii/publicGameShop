<?php

namespace Tests\Integration\Views;

use App\Models\FrontUser;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

class AdminRecipientRoleControllerTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        DB::beginTransaction();

        $this->user = FrontUser::factory()->create(['isAdmin' => true]) ?? new FrontUser();

    }

    public function test_content(): void
    {
        auth()->login($this->user);

        $this
            ->get(route('adminRecipientRole.show'))
            ->assertSee(Lang::get('rolesMessages.storeRoleButton'))
            ->assertSee(Lang::get('rolesMessages.nameInputPlaceholder'))
            ->assertSee(Lang::get('rolesMessages.roleAdditionPageTittle'));
    }

    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}