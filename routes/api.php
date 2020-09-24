<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v01'], function () {
    Route::get('ping',function(){
        return "PONG";
    });
        
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');
    });

    Route::group(['middleware' => ['auth:api', 'verified']], function() {
 
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');

        Route::group(['prefix' => 'listing'], function () { 
    
            Route::get('', 'ListingsController@listings'); 
            Route::get('info/{id}', 'ListingsController@info');
            Route::get('user/{id}', 'ListingsController@user');

            // You can add middleware here that only broker type of users can do CRUD operations

            Route::get('own', 'ListingsController@own');
            Route::post('create', 'ListingsController@create');
            // Route::get('edit', 'ListingsController@edit/{id}');
            Route::put('update/{id}', 'ListingsController@update');
            Route::put('delete/{id}', 'ListingsController@delete');
            Route::put('approve/{id}', 'ListingsController@approve');

        });
        
         Route::group(['prefix' => 'user'], function () { 
            

            Route::post('create', 'UserController@create');
            // Route::get('edit', 'ListingsController@edit/{id}');
            Route::put('update/{id}', 'UserController@update');
            Route::put('delete/{id}', 'UserController@delete');
            Route::put('approve/{id}', 'UserController@approve');
            Route::put('info/{id}', 'UserController@info');

        });

    });


Route::get('email/verify/{id}', 'VerificationApiController@verify')->name('verificationapi.verify');
Route::get('email/resend', 'VerificationApiController@resend')->name('verificationapi.resend');

});

//Auth::routes(['verify' => true]);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
