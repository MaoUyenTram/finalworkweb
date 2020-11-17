<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FriendController;

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

Route::middleware(['auth:sanctum', 'verified'])->group(function (){
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('/friends-and-bans', FriendController::class)->name('index','friends-and-bans');
    Route::resource('/games', GameController::class)->name('index','games');
});

//Route::resource('/friends-and-bans', FriendController::class)->name('index','friends-and-bans');
//Route::resource(‘bans’, BanController::class);
