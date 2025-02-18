<?php

use App\Http\Controllers\CandidatsController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\HomeConroller;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeConroller::class, 'index'])->name('home');
Route::get('/about', [HomeConroller::class, 'about'])->name('about');
Route::get('/candidat/{id}', [HomeConroller::class, 'show'])->name('candidat');
Route::post('/vote/{id}', [HomeConroller::class, 'vote'])->name('vote');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('events', EventsController::class);
Route::resource('candidats', CandidatsController::class);
Route::get('/votes',[CandidatsController::class , 'votes'])->name('votes');
Route::get('/payment/callback', [HomeConroller::class, 'paymentCallback'])->name('payment.callback');




require __DIR__ . '/auth.php';
