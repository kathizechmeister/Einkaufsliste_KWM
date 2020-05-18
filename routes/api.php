<?php

use Illuminate\Http\Request;
use App\Shoppinglist;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('shoppinglists', 'ShoppinglistController@index');
Route::get('shoppinglist/{id}', 'ShoppinglistController@findByShoppinglistId');


/**
 * Anmelden bzw. Registrieren
 */
Route:: group (['middleware' => [ 'api' , 'cors' ]], function () {
    Route:: post ( 'auth/login' , 'Auth\ApiAuthController@login' );
    Route::get('auth/user/{id}', 'Auth\ApiAuthController@findByUserId');
    Route:: post ( 'auth/register' , 'Auth\ApiRegisterController@create' );

});

/**
 * Angemeldet
 */
Route::group ([ 'middleware' => [ 'api' , 'cors' , 'jwt.auth'] ], function (){
    Route::post ( 'shoppinglist' , 'ShoppinglistController@save' );
    Route::post ( 'auth/logout' , 'Auth\ApiAuthController@logout' );
    Route::delete ( 'shoppinglist/{id}' , 'ShoppinglistController@delete' );

    //Route::delete ( 'shoppinglist/{id}/comment{comment_id)' , 'ShoppinglistController@deleteComment' );


    Route::get('user/{id}','ShoppinglistController@getUserById');
    Route::put('shoppinglist/{id}','ShoppinglistController@update');
    Route::post('shoppinglist/{id}/helper/{helper_id}','ShoppinglistController@accept');
    Route::post('shoppinglist/{id}/comment/','ShoppinglistController@addComment');

});
