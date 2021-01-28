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

Route::group(['middleware' => ['cors']], function () {
    //Rutas a las que se permitirá acceso
    Route::get('/favorites', 'MedicineController@anyShowFavorites');
    Route::get('/testEmail');
    Route::get('/favorites/getFavorites', 'FavoritesController@getFavorites');
    Route::any('/user/contact-us', 'UserController@anyContactUs');
    Route::any('/user/user-login/{is_web}', 'UserController@anyUserLogin');
    Route::any('/user/activate-account', 'UserController@anyActivateAccount');

});
