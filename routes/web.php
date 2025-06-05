<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PusherController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', [PusherController::class, 'index'])->name('broadcast');
    Route::post('/broadcast', [PusherController::class, 'broadcast'])->name('broadcast');
    Route::post('/receive', [PusherController::class, 'receive']);
    Route::get('/chat', [PusherController::class, 'chatRoom'])->name('chat.room');
    Route::post('/chat/store', [PusherController::class, 'store'])->name('chat.store');
    Route::get('/chat/{userId}', [PusherController::class, 'chatWithUser']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
