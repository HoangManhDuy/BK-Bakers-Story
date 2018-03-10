<?php

namespace App\Providers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\ProductType;
use App\Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('library.header', function($view)
        {
            $loai_sp = ProductType::all();

            $view->with('loai_sp', $loai_sp);
        });

        View::composer(['library.header', 'page.order'], function ($view)
        {
            if(Session('cart'))
            {
                $oldCart = Session::get('cart');
                $cart = new Cart($oldCart);

                $view->with([
                    'cart' => Session::get('cart'),
                    'product_cart' => $cart->items,
                    'totalPrice' => $cart->totalPrice,
                    'totalQty' => $cart->totalQty
                ]);
            }
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
