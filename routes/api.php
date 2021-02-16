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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/state/', 'HomeController@state');
Route::post('/csv/', 'HomeController@csv');
Route::apiResource('v1/farmers', 'Farmers\FarmerController');
Route::group(['prefix' => 'v1/farmers'], function (){

    Route::post('/signup/', 'Farmers\FarmerController@store'); 
    Route::post('/signin/', 'Farmers\LoginController@login');
    Route::get('/{uid}/myinvestors', 'Farmers\FarmerController@listInvestor');
    Route::delete('/{uid}/delete', 'Farmers\FarmerController@destroy');

});

Route::apiResource('v2/investors', 'Investors\InvestorController');
Route::group(['prefix' => 'v2/investors'], function (){

    Route::post('/signup/', 'Investors\InvestorController@store'); 
    Route::post('/signin/', 'Investors\LoginController@login');
    Route::get('/{uid}/myfarmers', 'Investors\InvestorController@listFarmer');
    Route::delete('/{uid}/delete', 'Investors\InvestorController@destroy');

});
