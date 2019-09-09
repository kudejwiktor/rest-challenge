<?php

/**
 * Cart Routes
 */
Route::get('/carts/{id}', 'Cart\Cart');
Route::get('/carts', 'Cart\Carts');
Route::post('/carts/create', 'Cart\Create');
Route::post('/carts/addProduct', 'Cart\AddProductToCart');
Route::post('/carts/removeProduct', 'Cart\RemoveProductFromCart');
Route::put('/carts/update', 'Cart\Update');
Route::delete('/carts/delete/{id}', 'Cart\Delete');

/**
 * Product Routes
 */
Route::get('/products/{id}', 'Product\Product');
Route::get('/products', 'Product\Products');
Route::post('/products/create', 'Product\Create');
Route::put('/products/update', 'Product\Update');
Route::delete('/products/delete/{id}', 'Product\Delete');
