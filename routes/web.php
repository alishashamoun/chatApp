<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PusherController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::get('/', [PusherController::class, 'index'])->name('dashboard');
    Route::post('/broadcast', [PusherController::class, 'broadcast'])->name('broadcast');
    Route::post('/receive', [PusherController::class, 'receive']);
    Route::get('/messages', [PusherController::class, 'getMessages'])->name('messages.get');
    Route::get('/chat', [PusherController::class, 'chatRoom'])->name('chat.room');
    Route::post('/chat/store', [PusherController::class, 'store'])->name('chat.store');
    Route::get('/chat/{userId}', [PusherController::class, 'chatWithUser']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
