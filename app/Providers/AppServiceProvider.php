<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Coupon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Custom validation for checking whether the product is available
        Validator::extend('product_available', function ($attribute, $value, $parameters, $validator) {
            return Product::where('id', $value)->where('quantity', '>', 0)->count() > 0;
        });

        // Custom validation for checking wheather the coupon is available
        Validator::extend('coupon_available', function ($attribute, $value, $parameters, $validator) {
            return Coupon::where('id', $value)
                ->where('quantity', '>', 0)
                ->where('valid_from', '<=', date('Y-m-d'))
                ->where('valid_to', '>=', date('Y-m-d'))
                ->count() > 0;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
    }
}
