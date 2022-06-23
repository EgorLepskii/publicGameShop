<?php

namespace Tests\Integration\Controllers;

use App\Models\FrontCategory;
use App\Models\OrderRecipientRole;
use App\Models\FrontProduct;
use App\Models\Recipient;
use App\Models\Role;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RecipientStoreControllerTest extends \Tests\TestCase
{
    use WithoutMiddleware;

    protected $faker;

    protected Role $role;


    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        DB::beginTransaction();

        $this->role = new Role
        (
            ['name' => $this->faker->text(self::STR_LEN_FOR_TESTS), 'type' => OrderRecipientRole::getType()]
        );
        $this->role->save();
    }


    public function test_correct_store(): void
    {
        $email = $this->faker->email;
        $roleId = $this->role->getAttribute('id');


        $this->post(route('recipient.store'), ['email' => $email, 'roleId' => $roleId]);

        $this->assertNotEmpty(Recipient::query()->where('email', '=', $email)->first());
    }

    public function test_if_exists(): void
    {
        $email = $this->faker->email;
        (new Recipient(['email' => $email, 'roleId' => $this->role->getAttribute('id')]))->save();

        $this->post(route('recipient.store'), ['email' => $email])->assertSessionHasErrors('email');
    }

    public function test_if_role_does_not_exists(): void
    {
        $this->post
        (
            route('recipient.store',), ['email' => $this->faker->email, 'roleId' => -1]
        )->assertSessionHasErrors(['roleId']);
    }

    /**
     * @dataProvider incorrectDataProvider
     * @param array<string, string> $data
     * @param string[] $expectedErrors
     */
    public function test_incorrect_data(array $data, array $expectedErrors): void
    {
        $data['roleId'] = $this->role->getAttribute('id');

        $this->post(route('recipient.store'), $data)->assertSessionHasErrors($expectedErrors);
    }

    /**
     * @return array{email_over_length: array{email: string}[]|string[][], empty_email: array{email: string}[]|string[][], incorrect_email: array{email: string}[]|string[][]}
     */
    public function incorrectDataProvider(): array
    {
        $faker = Factory::create();

        return [
            'email_over_length' => [
                [
                    'email' => $faker->lexify(str_repeat('?', Recipient::MAX_EMAIL_LENGTH + 1)) . '@mail.ru'
                ],
                [
                    'email'
                ]
            ],
            'empty_email' => [
                [
                    'email' => ''
                ],
                [
                    'email'
                ]
            ],
            'incorrect_email' => [
                [
                    'email' => $faker->lexify('??????')
                ],
                [
                    'email'
                ]
            ]
        ];
    }


    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

}
