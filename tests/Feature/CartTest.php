<?php

namespace Tests\Feature;

use Dogadamycie\Domain\Common\Id;
use Tests\TestCase;

class CartTest extends TestCase
{
    /**
     * @var string
     */
    private $baseUri = '/api/carts';

    /**
     * @test
     */
    public function ensureCartsUriReturns200Status()
    {
        $response = $this->get($this->baseUri);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function ensureCartNotFound()
    {
        $response = $this->get($this->baseUri . '/abc');
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function ensureCanCreateCart()
    {
        $response  = $this->postJson($this->baseUri . '/create', [
            'currency' => 'USD'
        ]);

        $response->assertStatus(201);
    }

    /**
     * @test
     * @param $data
     * @dataProvider invalidCartDataProvider
     */
    public function ensureCanNotCreateCartIfInvalidDataGiven($data)
    {
        $response  = $this->postJson($this->baseUri . '/create', $data);

        $response->assertStatus(400);
    }

    /**
     * @test
     * @param $data
     * @dataProvider invalidCartDataProvider
     */
    public function ensureCanNotUpdateCartIfInvalidDataGiven($data)
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

    /**
     * @test
     * @param $data
     * @param $expected
     * @dataProvider invalidProductDataProviderForAdd
     */
    public function ensureInvalidProductCanNotBeAddedToCart($data, $expected)
    {
        $response  = $this->postJson($this->baseUri . '/addProduct', $data);

        $response->assertStatus($expected);
    }

    /**
     * @test
     * @param $data
     * @param $expected
     * @dataProvider invalidProductDataProviderForRemove
     */
    public function ensureInvalidProductCanNotBeRemovedToCart($data, $expected)
    {
        $response  = $this->postJson($this->baseUri . '/removeProduct', $data);

        $response->assertStatus($expected);
    }

    public function invalidCartDataProvider()
    {
        return [
            'empty input' => [[]],
            'invalid currency' => [['currency' => 'abc']],
        ];
    }

    public function invalidProductDataProviderForAdd()
    {
        return [
            'empty input' => [[], 400],
            'invalid id' => [['cart_id' => 'abc', 'product_id' => 'abc'], 404],
            'non existing product' => [['cart_id' => Id::generate()->getId(), 'product_id' => Id::generate()->getId()], 404]
        ];
    }

    public function invalidProductDataProviderForRemove()
    {
        return [
            'empty input' => [[], 400],
            'invalid id' => [['id' => 'abc'], 422],
            'non existing product' => [['id' => Id::generate()->getId()], 422]
        ];
    }
}