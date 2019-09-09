<?php

namespace Tests\Feature;

use Dogadamycie\Domain\Common\Id;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductTest extends TestCase
{
    /**
     * @var string
     */
    private $baseUri = '/api/products';

    /**
     * @test
     */
    public function ensureProductsUriReturns200Status()
    {
        $response = $this->get($this->baseUri);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function ensureProductNotFound()
    {
        $response = $this->get($this->baseUri . '/abc');
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function ensureCanCreateProduct()
    {
        $response  = $this->postJson($this->baseUri . '/create', [
            'name' => 'product name',
            'price' => 11.11,
            'currency' => 'USD'
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     * @param $data
     * @dataProvider invalidProductDataProviderForCreate
     */
    public function ensureCanNotCreateProductIfInvalidDataGiven($data)
    {
        $response  = $this->postJson($this->baseUri . '/create', $data);

        $response->assertStatus(400);
    }

    /**
     * @test
     * @param $data
     * @dataProvider invalidProductDataProviderForUpdate
     */
    public function ensureCanNotUpdateProductIfInvalidDataGiven($data)
    {
        $response  = $this->putJson($this->baseUri . '/update', $data);

        $response->assertStatus(400);
    }

    /**
     * @test
     */
    public function ensureDeleteReturns422StatusIfInvalidIdGiven()
    {
        $response  = $this->deleteJson($this->baseUri . '/delete/abc');

        $response->assertStatus(422);
    }

    public function invalidProductDataProviderForCreate()
    {
        return [
            'empty input' => [[]],
            'invalid currency' => [['name' => 'ab', 'price' => 11.11, 'currency' => 'abc']],
            'invalid price' => [['name' => 'ab', 'price' => 'abc', 'currency' => 'USD']],
            'price out of range' => [['name' => 'ab', 'price' => 9999999999999999999999999999, 'currency' => 'USD']],
            'name too short' => [['name' => 'ab', 'price' => 11.11, 'currency' => 'USD']],
            'name too long' => [['name' => str_repeat('a', 101), 'price' => 11.11, 'currency' => 'USD']],
        ];
    }

    public function invalidProductDataProviderForUpdate()
    {
        return [
            'empty input' => [[]],
            'invalid id' => [['id'=> 'abc', 'name' => 'ab', 'price' => 11.11, 'currency' => 'abc']],
            'invalid currency' => [['id'=> Id::generate(), 'name' => 'ab', 'price' => 11.11, 'currency' => 'abc']],
            'invalid price' => [['id'=> Id::generate(), 'name' => 'ab', 'price' => 'abc', 'currency' => 'USD']],
            'price out of range' => [['id'=> Id::generate(), 'name' => 'ab', 'price' => 9999999999999999999999999999, 'currency' => 'USD']],
            'name too short' => [['id'=> Id::generate(), 'name' => 'ab', 'price' => 11.11, 'currency' => 'USD']],
            'name too long' => [['id'=> Id::generate(), 'name' => str_repeat('a', 101), 'price' => 11.11, 'currency' => 'USD']],
        ];
    }
}
