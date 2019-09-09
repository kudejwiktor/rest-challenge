<?php

namespace App\Providers;

use App\Repositories\{
    CartRepositoryEloquent as CartRepositoryPrettus,
    ProductRepositoryEloquent as ProductRepositoryPrettus
};
use Dogadamycie\Application\{
    Cart\Query\CartQuery,
    Command\CommandValidator,
    Product\Query\ProductQuery
};
use Dogadamycie\Domain\{
    Cart\CartFactory,
    Cart\CartRepository,
    Product\ProductFactory,
    Product\ProductRepository
};
use Dogadamycie\Infrastructure\{Persistence\Cart\CartRepositoryEloquent,
    Persistence\Cart\CartView,
    Persistence\Product\ProductRepositoryEloquent,
    Persistence\Product\ProductView,
    Utils\Validator\CommandValidatorLaravel};
use Illuminate\Support\{Facades\DB, Facades\Validator, ServiceProvider, Facades\Schema};
use Money\Currencies\ISOCurrencies;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('currency', function ($attribute, $value, $parameters, $validator) {
            $currencies = new ISOCurrencies();
            $currency = new \Money\Currency($value);

            return $currency->isAvailableWithin($currencies);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CommandValidator::class, function () {
            return new CommandValidatorLaravel();
        });

        $this->app->bind(CartQuery::class, function () {
            return new CartView(DB::table('cart'));
        });

        $this->app->bind(CartFactory::class, function () {
            return new CartFactory(new ProductFactory());
        });

        $this->app->bind(CartRepository::class, function ($app) {
            return new CartRepositoryEloquent(new CartRepositoryPrettus($app), $app->make(CartFactory::class));
        });

        $this->app->bind(ProductQuery::class, function () {
            return new ProductView(DB::table('product'));
        });

        $this->app->bind(ProductRepository::class, function ($app) {
            return new ProductRepositoryEloquent(new ProductRepositoryPrettus($app), new ProductFactory());
        });
    }
}
