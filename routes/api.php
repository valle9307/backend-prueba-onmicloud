<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\Author\AuthorController;
use App\Http\Controllers\Api\Book\BookController;
use App\Http\Controllers\Api\Editorial\EditorialController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

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

//AuthControllers

Route::group(['prefix' => 'auth'], function () {
    Route::get('sanctum/csrf-cookie', [CsrfCookieController::class,'show']);
    Route::post('login', [LoginController::class,'login'])->name('login');
    Route::post('logout', [LoginController::class,'logout'])->name('logout');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    //Authors
    Route::get(
        'authors/list',
        [AuthorController::class,'list']
    );
    Route::apiResource('authors', AuthorController::class);

    //Books
    Route::apiResource('books', BookController::class);

    //Editorials
    Route::get(
        'editorials/list',
        [EditorialController::class,'list']
    );
    Route::apiResource('editorials', EditorialController::class);
});
