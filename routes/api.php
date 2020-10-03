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

Route::post('/state/', 'HomeController@state');
Route::group(['prefix' => 'farmer'], function (){
    Route::post('/signup/', 'Farmers\FarmerController@store'); 
    Route::post('/signin/', 'Farmers\LoginController@login'); 
    Route::get('/', 'Farmers\FarmerController@show');
    Route::post('/createData/', 'Farmers\FarmerController@create');
    Route::get('/delete', 'Farmers\FarmerController@destroy');
    Route::get('/myinvestors', 'Farmers\FarmerController@listInvestor');
});

Route::group(['prefix' => 'investor'], function (){
    Route::post('/signup/', 'Investors\InvestorController@store'); 
    Route::post('/signin/', 'Investors\LoginController@login');
    Route::get('/', 'Investors\InvestorController@show');
    Route::post('/createData', 'Investors\InvestorController@create');
    Route::get('/delete', 'Investors\InvestorController@destroy');
    Route::get('/myfarmers', 'Investors\InvestorController@listFarmer');
});
