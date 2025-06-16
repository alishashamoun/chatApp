<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PusherController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::get('/broadcast', [PusherController::class, 'index'])->name('dashboard');
    Route::post('/broadcast', [PusherController::class, 'broadcast'])->name('broadcast');
    Route::post('/receive', [PusherController::class, 'receive']);
    Route::get('/messages', [PusherController::class, 'getMessages'])->name('messages.get');
    Route::get('/chat', [PusherController::class, 'chatRoom'])->name('chat.room');
    Route::post('/chat/store', [PusherController::class, 'store'])->name('chat.store');
    Route::get('/chat/{userId}', [PusherController::class, 'chatWithUser']);

    Route::get('/chats', [PusherController::class,'showChats'])->name('chats.index');
    Route::get('/chats/search', [PusherController::class,'search'])->name('chats.search');
    Route::get('/chats/{id}', [PusherController::class, 'show'])->name('chats.show');
    Route::post('/chats/{chat}/messages',[PusherController::class, 'storeMessage'])->name('chats.messages.store');
    Route::get('/client/{id}/chat', [PusherController::class,'initiateChat'])->name('chats.create');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
