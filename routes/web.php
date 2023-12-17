<?php

use Illuminate\Support\Facades\Route;

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

/* Auth */
Route::post('/register', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\UserController::class, 'logout'])->middleware('auth');
Route::get('/user', function () {
    return view('welcome');
})->middleware('auth');
Route::get('/no-user', function () {
    return response()->json(['success' => false, 'errors' => 'You must be logged in'], 422);
})->name('home');

/* User Info */
Route::get('/user/', [\App\Http\Controllers\SubscriptionController::class, 'memberships'])->middleware('auth')->name('user.memberships');

/* Subscription */
Route::post('/user/subscription', [\App\Http\Controllers\SubscriptionController::class, 'subscription'])->middleware('auth')->name('user.subscription');
Route::put('/user/subscription/{id}', [\App\Http\Controllers\SubscriptionController::class, 'update'])->middleware('auth')->name('user.subscription_update');
Route::delete('/user/subscription', [\App\Http\Controllers\SubscriptionController::class, 'delete'])->middleware('auth')->name('user.subscription_delete');

/* Transaction */
Route::post('/user/transaction', [\App\Http\Controllers\SubscriptionController::class, 'transaction'])->middleware('auth')->name('user.transaction');
