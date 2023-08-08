<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => 'throttle:1000,1'], function () {
    // Global
    Route::group(['prefix' => 'global'], function () {
        Route::group(['prefix' => 'user', 'controller' => 'UserController'], function () {
            Route::group(['middleware' => 'auth.global'], function () {
                Route::post('logout', 'logout');
                Route::get('check-token', 'checkToken');
            });
            Route::post('login', 'login');
        });
    });

    // CMS
    Route::group(['prefix' => 'cms', 'middleware' => 'auth.admin'], function () {
        // User
        Route::group(['prefix' => 'user', 'controller' => 'UserController'], function () {
            Route::get('list', 'index');
            Route::post('add', 'store');
            Route::get('show/{user}', 'show');
            Route::patch('update/{user}', 'update');
            Route::delete('delete/{user}', 'destroy');
        });
    });

    // Web
    Route::group(['prefix' => 'web'], function () {
        
    });

    // Calendar Events
    Route::group(['prefix' => 'event', 'controller' => 'EventController'], function() {
        Route::get('list', 'getAllEvents');
        Route::post('list', 'saveEvent');
    });
});
