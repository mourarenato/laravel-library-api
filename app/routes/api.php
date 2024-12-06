<?php

use App\Interfaces\Controllers\UserController;
use App\Interfaces\Controllers\AuthorController;
use App\Interfaces\Controllers\BookController;
use App\Interfaces\Controllers\LoanController;
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

Route::group(['middleware' => ['exception.handleUser']], function () {
    Route::post('signin', [UserController::class, 'signin']);
    Route::post('signup', [UserController::class, 'signup']);
});

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::group(['middleware' => ['exception.handleUser']], function () {
        Route::post('signout', [UserController::class, 'signout']);
    });

    Route::group(['middleware' => ['exception.handleLibrary']], function () {
        //Author
        Route::post('createAuthor', [AuthorController::class, 'create']);
        Route::get('getAuthors', [AuthorController::class, 'get']);
        Route::put('updateAuthor', [AuthorController::class, 'update']);
        Route::delete('deleteAuthor', [AuthorController::class, 'delete']);
        //Book
        Route::post('createBook', [BookController::class, 'create']);
        Route::get('getBooks', [BookController::class, 'get']);
        Route::put('updateBook', [BookController::class, 'update']);
        Route::delete('deleteBook', [BookController::class, 'delete']);
        //Book
        Route::post('createLoan', [LoanController::class, 'create']);
        Route::get('getLoans', [LoanController::class, 'get']);
    });
});
