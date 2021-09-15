<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HasBlogController;
use App\Http\Controllers\NoBlogController;
use App\Http\Controllers\HasEccubeController;
use App\Http\Controllers\NoEccubeController;
use App\Http\Controllers\PageHtmlController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PythonController;
use App\Http\Controllers\NotActiveCustomerController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// user編集ページ
Route::resource('user', UserController::class)
    ->middleware(['auth'])
    ->only(['edit', 'update']);

// 顧客一覧表示
Route::resource('customer', CustomerController::class)
    ->middleware(['auth'])
    ->only(['index', 'show']);

// ブログあり
Route::resource('hasblog', HasBlogController::class)
    ->middleware(['auth'])
    ->only(['index', 'show']);

// ブログなし
Route::resource('noblog', NoBlogController::class)
    ->middleware(['auth'])
    ->only(['index', 'show']);

// 顧客一覧表示
Route::resource('not-active', NotActiveCustomerController::class)
    ->middleware(['auth'])
    ->only(['index', 'show']);

// Route::resource('haseccube', HasEccubeController::class)
//     ->middleware(['auth'])
//     ->only(['index', 'show']);

// Route::resource('noeccube', NoEccubeController::class)
//     ->middleware(['auth'])
//     ->only(['index', 'show']);

Route::resource('timestamp', PageHtmlController::class)
    ->middleware(['auth'])
    ->only(['index', 'show']);

Route::get('python', [PythonController::class, 'exec'])
    ->middleware(['auth'])
    ->name('python');

require __DIR__.'/auth.php';
