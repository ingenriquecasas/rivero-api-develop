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

Route::prefix('v1')->group(function() {

    // Cars
    Route::get('cars', 'CarController@index');
    Route::get('cars/{carSlug}', 'CarController@show');
    Route::get('car-types', 'CarTypeController@index');
    Route::get('car-makes', 'CarMakeController@index');
    Route::get('car-years', 'CarYearController@index');

    // Blog
    Route::get('posts', 'BlogPostController@index');
    Route::get('posts/{year}/{month}/{day}/{slug}', 'BlogPostController@show');

    // Used Cars
    Route::get('used-cars', 'CarUsedController@index');
    Route::get('used-cars-mm', 'CarUsedController@seminuevosmm');
    Route::get('used-cars/{carUsed:serie}', 'CarUsedController@show');
    Route::get('used-cars-years', 'CarUsedYearController');
    Route::get('used-cars-makes', 'CarUsedMakeController');

    // Ads and promos
    Route::get('ads', 'AdController@index');
    Route::get('ads/{slug}', 'AdController@show');
    Route::get('campaigns', 'AdController@index');
    Route::get('campaigns/{slug}', 'AdController@show');

    Route::get('promos', 'PromoController@index');
    Route::get('testimonios', 'TestimoniosController@index');
    Route::get('qrs/{qr:codigo}', 'QRController@show');

    // Endpoints for "libro azul" services
    Route::prefix('bluebook')->group(function() {
        Route::get('years', 'BlueBookController@getYears');
        Route::get('years/{year}/makes', 'BlueBookController@getMakes');
        Route::get('years/{year}/makes/{make}/models', 'BlueBookController@getModels');
        Route::get('years/{year}/makes/{make}/models/{model}/versions', 'BlueBookController@getVersions');
        Route::get('quotation/{version}', 'BlueBookController@getQuotation');
    });

    // Quotations endpoints
    Route::post('quotations', 'QuotationController@store');
    Route::patch('quotations/{quotation}', 'QuotationController@update');

    // Legacy endpoints
    Route::get('autos', 'CarController@index');
    Route::get('autos/{carSlug}', 'CarController@show');
    Route::get('seminuevos', 'CarUsedController@index');
    Route::get('seminuevos/{carUsed}', 'CarUsedController@show');



});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
