<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChatController::class, 'index'])->name('chat.index');

Route::get('/chat/{id}', [ChatController::class, 'show'])->name('chat.show');
