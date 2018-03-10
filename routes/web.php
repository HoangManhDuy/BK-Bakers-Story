<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('index', 'PageController@home')->name('home');

Route::get('product_type/{type}', 'PageController@productType')->name('product.type');

Route::get('about', 'PageController@about')->name('about');

Route::get('contact', 'PageController@contact')->name('contact');

Route::get('product_detail/{id}', 'PageController@getProductDetail')->name('product.detail');

Route::get('add-to-cart/{id}', 'PageController@addCart')->name('addCart');

Route::get('del-item-cart/{id}', 'PageController@delCart')->name('delCart');

Route::get('order', 'PageController@order')->name('order');

Route::post('order', 'PageController@postOrder')->name('postOrder');