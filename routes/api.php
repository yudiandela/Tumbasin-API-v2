<?php

Route::namespace('Api')->name('api.')->prefix('v2')->middleware('api')->group(function () {
    Route::post('register', 'RegisterController')->name('register');
    Route::post('login', 'LoginController')->name('login');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'LogoutController')->name('logout');
        Route::get('profile', 'ProfileController')->name('profile');
    });
});
