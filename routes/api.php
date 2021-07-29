<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserController;
  
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
  
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
     //Route::get('user/index-paging', 'UserController@indexPaging');
	 //Route::get('user/index-filtering', 'UserController@indexFiltering');
	 //Route::post('user', [UserController::class, 'fileUpload']);


Route::middleware('auth:api')->group( function () {
    Route::resource('user', UserController::class);
});