<?php

Route::namespace('Api')->name('api.')->prefix('v2')->middleware('api')->group(function () {
    Route::post('register', 'Auth\RegisterController')->name('register');
    Route::post('login', 'Auth\LoginController')->name('login');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'Auth\LogoutController')->name('logout');
        Route::get('profile', 'Auth\ProfileController')->name('profile');

        /**
         * Route Market
         */
        Route::get('market', 'MarketController@index')->name('market.index');
        Route::get('market/{market}', 'MarketController@show')->name('market.show');

        // Market Admin Access role:admin
        Route::post('market', 'MarketController@store')->name('market.store');
        Route::put('market/{market}', 'MarketController@update')->name('market.update');
        Route::delete('market/{market}', 'MarketController@destroy')->name('market.destroy');

        /**
         * Route Category
         */
        Route::get('categories', 'CategoryController@index')->name('category.index');
        Route::get('category/{category}', 'CategoryController@show')->name('category.show');

        // Category Admin Access role:admin
        Route::post('category', 'CategoryController@store')->name('category.store');
        Route::put('category/{category}', 'CategoryController@update')->name('category.update');
        Route::delete('category/{category}', 'CategoryController@destroy')->name('category.destroy');

        /**
         * Route Product
         */
        Route::get('products', 'ProductController@index')->name('product.index');
        Route::get('product/{product}', 'ProductController@show')->name('product.show');

        // Product Admin Access role:admin
        Route::post('product', 'ProductController@store')->name('product.store');
        Route::put('product/{product}', 'ProductController@update')->name('product.update');
        Route::delete('product/{product}', 'ProductController@destroy')->name('product.destroy');
    });
});
