<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashBoardController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\UserCatalougeController;
use App\Http\Controllers\Backend\PostCatalougeController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Middleware\AuthenticateMiddleware;
use App\Http\Middleware\LoginMiddleware;
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Ajax\DashBoardController as AjaxDashBoardController;

// Login and logout
Route::prefix('auth')->group(function () {
    Route::get('admin', [AuthController::class, 'index'])->name('auth.admin');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// Backend dashboard route
Route::middleware(LoginMiddleware::class)->group(function () {
    Route::get('/', [DashBoardController::class, 'index'])->name('dashboard.index');
});

// AJAX routes
Route::prefix('ajax')->group(function () {
    Route::get('location/getLocation', [LocationController::class, 'getLocation'])->name('ajax.location.index');
    Route::post('dashboard/changeStatus', [AjaxDashBoardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus');
    Route::post('dashboard/changeStatusAll', [AjaxDashBoardController::class, 'changeStatusAll'])->name('ajax.dashboard.changeStatusAll');
});

// Manage user
Route::prefix('user')->middleware(AuthenticateMiddleware::class)->group(function () {
    Route::get('/index', [UserController::class, 'index'])->name('user.index');
    Route::get('/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/store', [UserController::class, 'store'])->name('user.store');
    Route::get('{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('{id}/update', [UserController::class, 'update'])->name('user.update');
    Route::get('{id}/delete', [UserController::class, 'delete'])->name('user.delete');
    Route::post('{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy');
});

// Manage user catalogue
Route::prefix('user/catalouge')->middleware(AuthenticateMiddleware::class)->group(function () {
    Route::get('/index', [UserCatalougeController::class, 'index'])->name('user.catalouge.index');
    Route::get('/create', [UserCatalougeController::class, 'create'])->name('user.catalouge.create');
    Route::post('/store', [UserCatalougeController::class, 'store'])->name('user.catalouge.store');
    Route::get('{id}/edit', [UserCatalougeController::class, 'edit'])->name('user.catalouge.edit');
    Route::post('{id}/update', [UserCatalougeController::class, 'update'])->name('user.catalouge.update');
    Route::get('{id}/delete', [UserCatalougeController::class, 'delete'])->name('user.catalouge.delete');
    Route::post('{id}/destroy', [UserCatalougeController::class, 'destroy'])->name('user.catalouge.destroy');
});

// Manage post catalogue
Route::prefix('post/catalouge')->middleware(AuthenticateMiddleware::class)->group(function () {
    Route::get('/index', [PostCatalougeController::class, 'index'])->name('post.catalouge.index');
    Route::get('/create', [PostCatalougeController::class, 'create'])->name('post.catalouge.create');
    Route::post('/store', [PostCatalougeController::class, 'store'])->name('post.catalouge.store');
    Route::get('{id}/edit', [PostCatalougeController::class, 'edit'])->name('post.catalouge.edit');
    Route::post('{id}/update', [PostCatalougeController::class, 'update'])->name('post.catalouge.update');
    Route::get('{id}/delete', [PostCatalougeController::class, 'delete'])->name('post.catalouge.delete');
    Route::post('{id}/destroy', [PostCatalougeController::class, 'destroy'])->name('post.catalouge.destroy');
});

// Manage language
Route::prefix('language')->middleware(AuthenticateMiddleware::class)->group(function () {
    Route::get('/index', [LanguageController::class, 'index'])->name('language.index');
    Route::get('/create', [LanguageController::class, 'create'])->name('language.create');
    Route::post('/store', [LanguageController::class, 'store'])->name('language.store');
    Route::get('{id}/edit', [LanguageController::class, 'edit'])->name('language.edit');
    Route::post('{id}/update', [LanguageController::class, 'update'])->name('language.update');
    Route::get('{id}/delete', [LanguageController::class, 'delete'])->name('language.delete');
    Route::post('{id}/destroy', [LanguageController::class, 'destroy'])->name('language.destroy');
});
