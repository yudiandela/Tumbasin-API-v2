<?php

Route::namespace('Api')->name('api.')->prefix('v2')->middleware('api')->group(function () {
    Route::post('register', 'RegisterController')->name('register');
    Route::post('login', 'LoginController')->name('login');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'LogoutController')->name('logout');
        Route::get('profile', 'ProfileController')->name('profile');

        // Market
        Route::get('market', 'MarketController@index')->name('market.index');
        Route::post('market', 'MarketController@store')->name('market.store');
        Route::get('market/{market}', 'MarketController@show')->name('market.show');
        Route::put('market/{market}', 'MarketController@update')->name('market.update');
        Route::delete('market/{market}', 'MarketController@destroy')->name('market.destroy');
    });
});
