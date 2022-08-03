<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WisataController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDataRatingController;
use App\Http\Controllers\RatingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [UsersController::class, 'landing']);

Route::get('/admin/login', [AdminController::class, 'loginIndex']);

Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);

Route::post('/admin/login/process', [AdminController::class, 'loginProcess']);

Route::get('/admin/logout', [AdminController::class, 'logout']);

Route::get('/datawisata', function () {
    return view('admin.data_wisata');
});

//Admin Data Wisata
Route::get('/datawisata',[WisataController::class, 'index']);
Route::get('/datawisata/tambah',[WisataController::class, 'tambah']);
Route::post('/datawisata/store',[WisataController::class, 'store']);
Route::get('/datawisata/edit/{id_wisata}',[WisataController::class, 'edit']);
Route::get('/datawisata/hapus/{id_wisata}',[WisataController::class, 'hapus']);
Route::get('/datawisata/search',[WisataController::class, 'search']);
Route::post('/datawisata/update',[WisataController::class, 'update']);
//Admin Data User
Route::get('/datauser',[UsersController::class, 'index']);
Route::get('/datauser/search',[UsersController::class, 'search']);
Route::get('/login', [UsersController::class, 'loginIndex']);
// Route::post('/algoritma/test', [RatingController::class, 'recArroundWisata']);
//Admin Data Rating
Route::get('/datarating',[AdminDataRatingController::class, 'index']);
Route::get('/datarating/search',[AdminDataRatingController::class, 'search']);

//USER
Route::get('/user/login', [UsersController::class, 'loginIndex']);
Route::post('/user/login/process', [UsersController::class, 'loginProcess']);
Route::get('/user/logout', [UsersController::class, 'logout']);
Route::get('/user/home', [UsersController::class, 'home']);
Route::get('/user/registrasi', [UsersController::class, 'regisIndex']);
Route::post('/user/simpanregistrasi', [UsersController::class, 'regisSave']);


Route::get('/user/rating', [UsersController::class, 'rating']);
Route::get('/user/wisata/{idWisata}', [UsersController::class, 'detailWisata']);
Route::get('/user/riwayat', [UsersController::class, 'riwayatRating']);
Route::post('/user/rating/add', [UsersController::class, 'addRating']);

Route::get('auth/{provider}', [AuthController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);