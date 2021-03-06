<?php

namespace Tests\Integration\Controllers;

use App\Http\Controllers\SocialAuthController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\SocialNetworkType;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User;

class SocialAuthControllerTest extends \Tests\TestCase
{

    protected $faker;

    protected int $roleType = 0;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        DB::beginTransaction();

        $this->user = $this->createMock(User::class);
        $this->user->method('getId')->willReturn(mt_rand());
        $this->user->method('getName')->willReturn($this->faker->name);
        $this->user->method('getNickname')->willReturn($this->faker->name);

    }

    /**
     * If redirect contains app url, it means, that iw was error during attempt to log in with social network
     *
     * @return void
     */
    public function test_redirect()
    {
        $this->get(route('socialAuth.index', ['socialNetwork' => 'google']))->assertDontSee(env('APP_URL'));
        $this->get(route('socialAuth.index', ['socialNetwork' => 'vkontakte']))->assertDontSee(env('APP_URL'));
    }


    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

}
