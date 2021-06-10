<?php

use App\Events\MyEvent;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameSessionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\pileController;
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




Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');}
        )->name('dashboard');
    Route::resource('/friends-and-bans', FriendController::class)->name('index', 'friends-and-bans');
    Route::resource('/games', GameController::class)->name('index', 'games');
    Route::post('/piles/createpiles', [PileController::class, 'createpiles'])->name('createpiles');
    Route::post('/piles/uploadboard', [PileController::class, 'uploadboard'])->name('uploadboard');
    Route::post('/piles/setndice', [PileController::class,'setndice' ])->name('setndice');
    Route::post('/piles/setcdice', [PileController::class, 'setcdice'])->name('setcdice');
    Route::post('/piles/destroydice', [PileController::class, 'destroydice'])->name('destroydice');
    Route::post('/piles/addowner', [PileController::class, 'addowner'])->name('addowner');
    Route::get('/piles/settings', [PileController::class, 'settings'])->name('settings');
    Route::resource('/piles', PileController::class)->name('index', 'piles');
    Route::resource('/items', ItemController::class)->name('index', 'items');

    Route::post('/gamesession/setowners', [GameSessionController::class, 'setowners'])->name('setowners');
    Route::post('/gamesession/changes', [GameSessionController::class, 'changes'])->name('changes');
    Route::post('/gamesession/places', [GameSessionController::class, 'places'])->name('places');
    Route::post('/gamesession/message', [GameSessionController::class, 'message'])->name('messages');
    Route::post('/gamesession/getimages', [GameSessionController::class, 'getimages'])->name('getimages');
    Route::post('/gamesession/join', [GameSessionController::class, 'join'])->name('join');
    Route::post('/gamesession/{id}', [GameSessionController::class, 'start'])->name('start');

});

//Route::resource('/friends-and-bans', FriendController::class)->name('index','friends-and-bans');
//Route::resource(‘bans’, BanController::class);
