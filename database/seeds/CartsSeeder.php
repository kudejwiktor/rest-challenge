<?php

use Dogadamycie\Domain\Common\Id;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartsSeeder extends Seeder
{
    public function run()
    {
        $cartIds = $this->insertCarts();
        $this->insertProduct($cartIds);
    }

    private function insertCarts()
    {
        $firstCardId = Id::generate();
        $secondCardId = Id::generate();
        $thirdCardId = Id::generate();
        DB::table('cart')->insert([
            [
                'id' => $firstCardId,
                'currency_iso_code' => 'USD',
            ],
            [
                'id' => $secondCardId,
                'currency_iso_code' => 'USD',
            ],
            [
                'id' => $thirdCardId,
                'currency_iso_code' => 'USD',
            ]
        ]);

        return [$firstCardId, $secondCardId, $thirdCardId];
    }

    private function insertProduct(array $cartIds)
    {
        $products = [];
        $faker = Faker\Factory::create();

        for ($i = 0; $i <= 10; $i++) {
            $products[] = [
                'id' => Id::generate(),
                'cart_id' => $cartIds[random_int(0, 2)],
                'name' => 'Product ' . $faker->colorName,
                'price' => $faker->randomFloat(2, 10, 200),
                'currency_iso_code' => 'USD',
            ];
        }

        DB::table('product')->insert($products);

        //Products which are not assigned to any cart
        DB::table('product')->insert([
            [
                'id' => Id::generate(),
                'name' => 'Product ' . $faker->colorName,
                'price' => $faker->randomFloat(2, 10, 200),
                'currency_iso_code' => 'USD',
            ],
            [
                'id' => Id::generate(),
                'name' => 'Product ' . $faker->colorName,
                'price' => $faker->randomFloat(2, 10, 200),
                'currency_iso_code' => 'USD',
            ]
        ]);
    }
}
