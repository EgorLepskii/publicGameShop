<?php

namespace Tests\Integration\Controllers;

use App\Models\FrontCategory;
use App\Models\FrontProduct;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CategoryStoreControllerTest extends \Tests\TestCase
{
    use WithoutMiddleware;

    protected $faker;


    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        DB::beginTransaction();
    }


    /**
     * @dataProvider storeIncorrectDataProvider
     * @param        array<string, string> $data
     * @param        string[]              $expectedErrors
     */
    public function test_store_with_incorrect_data(array $data, array $expectedErrors): void
    {
        $this->post(route('category.store', $data))->assertSessionHasErrors($expectedErrors);
    }

    /**
     * @return array{empty_fields: array{name: string}[]|string[][], name_over_length: array{name: string}[]|string[][]}
     */
    public function storeIncorrectDataProvider(): array
    {
        $faker = Factory::create();
        return [
            'empty_fields' => [
                [
                    'name' => '',
                ],
                [
                    'name'
                ]
            ],

            'name_over_length' => [
                [
                    'name' => $faker->lexify(str_repeat('?', FrontCategory::MAX_NAME_LENGTH + 1))
                ],
                [
                    'name'
                ]
            ],


        ];
    }


    /**
     * Assert, that product model will be stored in database
     * and image will be saved in public storage
     *
     * @dataProvider correctDataProvider
     * @param        array<string, int> $data
     */
    public function testStoreWithCorrectData(array $data): void
    {
        $this->post(route('category.store'), $data);

        $this->assertEquals(
            FrontCategory::query()->where('name', '=', $data['name'])->first()->getAttribute('name'),
            $data['name']
        );

    }

    /**
     * @return array{correct_data: array<int, array{name: int}>}
     */
    public function correctDataProvider(): array
    {
        return
            [
                'correct_data' => [
                    [
                        'name' => FrontCategory::MAX_NAME_LENGTH,
                    ],
                ],

            ];
    }

    public function test_if_entry_already_exists(): void
    {
        $name = $this->faker->text(self::STR_LEN_FOR_TESTS);
        (new FrontCategory(['name' => $name]))->save();
        $this->post(route('category.store'), ['name' => $name])->assertSessionHasErrors(['name']);
    }

    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

}
