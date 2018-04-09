<?php

use Illuminate\Http\Request;

Route::post('register', 'UserController@register');



Route::group([

    'middleware' => 'api',

], function () {

    Route::any('login', 'UserController@login');
    Route::post('logout', 'UserController@logout');
    Route::post('refresh', 'UserController@refresh');
    Route::any('me', 'UserController@me');
    Route::post('verify', 'UserController@checkToken');
    Route::post('/bills', 'UserController@bills');
    Route::post('/billdetails', 'UserController@billDetails');
});