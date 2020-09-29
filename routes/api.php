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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//sign up route
Route::post('/farmer/signup/', 'Farmers/FarmerController@store'); 

Route::post('/investor/signup/', 'investors/InvestorController@store'); 

Route::group(['prefix' => 'farmer'], function (){
    Route::post('/signup/', 'Farmers\FarmerController@store'); 
    Route::post('/signin/', 'Farmers\LoginController@login'); 
    Route::get('/{id}', 'Farmers\FarmerController@show');
    Route::post('/mydata/', 'Farmers\FarmerController@create');
});

Route::group(['prefix' => 'investor'], function (){
    Route::post('/signup/', 'Investors\InvestorController@store'); 
    Route::post('/signin/', 'Investors\LoginController@login');
    Route::get('/{id}', 'Investors\InvestorController@show');
    Route::post('/mydata/', 'Investors\InvestorController@create');

});
