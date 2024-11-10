<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::prefix('user/')->group(function () {
    Route::get('index', [UserController::class, 'index'])->name('api.user.index');
    Route::post('store', [UserController::class, 'store'])->name('api.user.store');
});

