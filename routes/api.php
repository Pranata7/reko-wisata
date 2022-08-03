<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingController;

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
Route::post('/wisata-in-city', [RatingController::class, 'wisataInCity']);
Route::post('/own-rated-wisata', [RatingController::class, 'ownRatedWisata']);
Route::post('/showother', [RatingController::class, 'showOther']);
Route::post('/wisata-rec', [RatingController::class, 'newAlgorithm']);
Route::post('/search-wisata', [RatingController::class, 'searchWisata']);
Route::post('/search-tag-wisata', [RatingController::class, 'searchTagWisata']);
Route::get('/rec-wisata', [RatingController::class, 'recArroundWisata']);
