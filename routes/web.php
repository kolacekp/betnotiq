<?php

use App\Http\Controllers\BetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Livewire\LiveBet;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/', [BetController::class, 'index'])->name('bets.index');
    Route::get('/bets/new', [BetController::class, 'new'])->name('bets.new');
    Route::get('/bets/newCashout', [BetController::class, 'newCashout'])->name('bets.newCashout');
    Route::get('/bets/{id}', [BetController::class, 'edit'])->name('bets.edit');
    Route::post('/bets', [BetController::class, 'create'])->name('bets.create');
    Route::post('/bets/cashout', [BetController::class, 'createCashout'])->name('bets.createCashout');
    Route::patch('/bets', [BetController::class, 'update'])->name('bets.update');
    Route::delete('/bets', [BetController::class, 'destroy'])->name('bets.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/user/new', [UserController::class, 'new'])->name('users.new');
    Route::get('/user/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/user', [UserController::class, 'create'])->name('users.create');
    Route::patch('/user', [UserController::class, 'update'])->name('users.update');
    Route::delete('/user', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// publicly accessbile "bets" live page
Route::get('/4342c774-da28-466c-8178-2e2d94f8b451', LiveBet::class);

require __DIR__.'/auth.php';
