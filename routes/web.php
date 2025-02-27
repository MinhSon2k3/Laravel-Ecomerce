<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashBoardController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\UserCatalougeController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\PostCatalougeController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\GenerateController;
use App\Http\Controllers\Backend\ProductCatalougeController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\AttributeCatalougeController;
use App\Http\Controllers\Backend\AttributeController;
use App\Http\Controllers\Backend\SystemController;
use App\Http\Controllers\Backend\MenuController;

use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Ajax\DashBoardController as AjaxDashBoardController;
use App\Http\Controllers\Ajax\AttributeController as AjaxAttributeController;
use App\Http\Controllers\Ajax\MenuController as AjaxMenuController;
//@@useController@@
Route::middleware('locale')->group(function () {
// Login and logout
Route::prefix('auth')->group(function () {
    Route::get('admin', [AuthController::class, 'index'])->name('auth.admin');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// Backend dashboard route
Route::middleware('login')->group(function () {
    Route::get('/', [DashBoardController::class, 'index'])->name('dashboard.index');
});

// AJAX routes
Route::prefix('ajax')->group(function () {
    Route::get('location/getLocation', [LocationController::class, 'getLocation'])->name('ajax.location.index');
    Route::post('dashboard/changeStatus', [AjaxDashBoardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus');
    Route::post('dashboard/changeStatusAll', [AjaxDashBoardController::class, 'changeStatusAll'])->name('ajax.dashboard.changeStatusAll');
    Route::get('dashboard/getMenu', [AjaxDashBoardController::class, 'getMenu'])->name('ajax.dashboard.getMenu');
    Route::get('attribute/getAttribute', [AjaxAttributeController::class, 'getAttribute'])->name('ajax.attribute.getAttribute');
    Route::get('attribute/loadAttribute', [AjaxAttributeController::class, 'loadAttribute'])->name('ajax.attribute.loadAttribute');
    Route::post('menu/createCatalouge', [AjaxMenuController::class, 'createCatalouge'])->name('ajax.menu.createCatalouge');
});

// Manage user
Route::prefix('user')->middleware('authenticate')->group(function () {
    Route::get('/index', [UserController::class, 'index'])->name('user.index');
    Route::get('/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/store', [UserController::class, 'store'])->name('user.store');
    Route::get('{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('{id}/update', [UserController::class, 'update'])->name('user.update');
    Route::get('{id}/delete', [UserController::class, 'delete'])->name('user.delete');
    Route::post('{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy');
});

// Manage user catalogue
Route::prefix('user/catalouge')->middleware('authenticate')->group(function () {
    Route::get('/index', [UserCatalougeController::class, 'index'])->name('user.catalouge.index');
    Route::get('/create', [UserCatalougeController::class, 'create'])->name('user.catalouge.create');
    Route::post('/store', [UserCatalougeController::class, 'store'])->name('user.catalouge.store');
    Route::get('{id}/edit', [UserCatalougeController::class, 'edit'])->name('user.catalouge.edit');
    Route::post('{id}/update', [UserCatalougeController::class, 'update'])->name('user.catalouge.update');
    Route::get('{id}/delete', [UserCatalougeController::class, 'delete'])->name('user.catalouge.delete');
    Route::post('{id}/destroy', [UserCatalougeController::class, 'destroy'])->name('user.catalouge.destroy');
    Route::get('/permission', [UserCatalougeController::class, 'permission'])->name('user.catalouge.permission');
    Route::post('/updatePermission', [UserCatalougeController::class, 'updatePermission'])->name('user.catalouge.updatepermission');
});

// Manage permission
Route::prefix('permission')->middleware('authenticate')->group(function () {
    Route::get('/index', [PermissionController::class, 'index'])->name('permission.index');
    Route::get('/create', [PermissionController::class, 'create'])->name('permission.create');
    Route::post('/store', [PermissionController::class, 'store'])->name('permission.store');
    Route::get('{id}/edit', [PermissionController::class, 'edit'])->name('permission.edit');
    Route::post('{id}/update', [PermissionController::class, 'update'])->name('permission.update');
    Route::get('{id}/delete', [PermissionController::class, 'delete'])->name('permission.delete');
    Route::post('{id}/destroy', [PermissionController::class, 'destroy'])->name('permission.destroy');
});

// Manage post catalogue
Route::prefix('post/catalouge')->middleware('authenticate')->group(function () {
    Route::get('/index', [PostCatalougeController::class, 'index'])->name('post.catalouge.index');
    Route::get('/create', [PostCatalougeController::class, 'create'])->name('post.catalouge.create');
    Route::post('/store', [PostCatalougeController::class, 'store'])->name('post.catalouge.store');
    Route::get('{id}/edit', [PostCatalougeController::class, 'edit'])->name('post.catalouge.edit');
    Route::post('{id}/update', [PostCatalougeController::class, 'update'])->name('post.catalouge.update');
    Route::get('{id}/delete', [PostCatalougeController::class, 'delete'])->name('post.catalouge.delete');
    Route::post('{id}/destroy', [PostCatalougeController::class, 'destroy'])->name('post.catalouge.destroy');
});

// Manage post 
Route::prefix('post')->middleware('authenticate')->group(function () {
    Route::get('/index', [PostController::class, 'index'])->name('post.index');
    Route::get('/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/store', [PostController::class, 'store'])->name('post.store');
    Route::get('{id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::post('{id}/update', [PostController::class, 'update'])->name('post.update');
    Route::get('{id}/delete', [PostController::class, 'delete'])->name('post.delete');
    Route::post('{id}/destroy', [PostController::class, 'destroy'])->name('post.destroy');
});

// Manage language
Route::prefix('language')->middleware('authenticate')->group(function () {
    Route::get('/index', [LanguageController::class, 'index'])->name('language.index');
    Route::get('/create', [LanguageController::class, 'create'])->name('language.create');
    Route::post('/store', [LanguageController::class, 'store'])->name('language.store');
    Route::get('{id}/edit', [LanguageController::class, 'edit'])->name('language.edit');
    Route::post('{id}/update', [LanguageController::class, 'update'])->name('language.update');
    Route::get('{id}/delete', [LanguageController::class, 'delete'])->name('language.delete');
    Route::post('{id}/destroy', [LanguageController::class, 'destroy'])->name('language.destroy');
    Route::post('{id}/switch', [LanguageController::class, 'switchBackendLanguage'])->name('language.switch');
});

// Manage generate
Route::prefix('generate')->middleware('authenticate')->group(function () {
    Route::get('/index', [GenerateController::class, 'index'])->name('generate.index');
    Route::get('/create', [GenerateController::class, 'create'])->name('generate.create');
    Route::post('/store', [GenerateController::class, 'store'])->name('generate.store');
    Route::get('{id}/edit', [GenerateController::class, 'edit'])->name('generate.edit');
    Route::post('{id}/update', [GenerateController::class, 'update'])->name('generate.update');
    Route::get('{id}/delete', [GenerateController::class, 'delete'])->name('generate.delete');
    Route::post('{id}/destroy', [GenerateController::class, 'destroy'])->name('generate.destroy');
});

Route::group(['prefix' => 'product/catalouge'], function () {
    Route::get('index', [ProductCatalougeController::class, 'index'])->name('product.catalouge.index');
    Route::get('create', [ProductCatalougeController::class, 'create'])->name('product.catalouge.create');
    Route::post('store', [ProductCatalougeController::class, 'store'])->name('product.catalouge.store');
    Route::get('{id}/edit', [ProductCatalougeController::class, 'edit'])->name('product.catalouge.edit');
    Route::post('{id}/update', [ProductCatalougeController::class, 'update'])->name('product.catalouge.update');
    Route::get('{id}/delete', [ProductCatalougeController::class, 'delete'])->name('product.catalouge.delete');
    Route::post('{id}/destroy', [ProductCatalougeController::class, 'destroy'])->name('product.catalouge.destroy');
});
Route::group(['prefix' => 'product'], function () {
    Route::get('index', [ProductController::class, 'index'])->name('product.index');
    Route::get('create', [ProductController::class, 'create'])->name('product.create');
    Route::post('store', [ProductController::class, 'store'])->name('product.store');
    Route::get('{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('{id}/update', [ProductController::class, 'update'])->name('product.update');
    Route::get('{id}/delete', [ProductController::class, 'delete'])->name('product.delete');
    Route::post('{id}/destroy', [ProductController::class, 'destroy'])->name('product.destroy');
});
Route::group(['prefix' => 'attribute/catalouge'], function () {
    Route::get('index', [AttributeCatalougeController::class, 'index'])->name('attribute.catalouge.index');
    Route::get('create', [AttributeCatalougeController::class, 'create'])->name('attribute.catalouge.create');
    Route::post('store', [AttributeCatalougeController::class, 'store'])->name('attribute.catalouge.store');
    Route::get('{id}/edit', [AttributeCatalougeController::class, 'edit'])->name('attribute.catalouge.edit');
    Route::post('{id}/update', [AttributeCatalougeController::class, 'update'])->name('attribute.catalouge.update');
    Route::get('{id}/delete', [AttributeCatalougeController::class, 'delete'])->name('attribute.catalouge.delete');
    Route::post('{id}/destroy', [AttributeCatalougeController::class, 'destroy'])->name('attribute.catalouge.destroy');
});
Route::group(['prefix' => 'attribute'], function () {
    Route::get('index', [AttributeController::class, 'index'])->name('attribute.index');
    Route::get('create', [AttributeController::class, 'create'])->name('attribute.create');
    Route::post('store', [AttributeController::class, 'store'])->name('attribute.store');
    Route::get('{id}/edit', [AttributeController::class, 'edit'])->name('attribute.edit');
    Route::post('{id}/update', [AttributeController::class, 'update'])->name('attribute.update');
    Route::get('{id}/delete', [AttributeController::class, 'delete'])->name('attribute.delete');
    Route::post('{id}/destroy', [AttributeController::class, 'destroy'])->name('attribute.destroy');
});

Route::prefix('system')->middleware('authenticate')->group(function () {
    Route::get('/index', [SystemController::class, 'index'])->name('system.index');
    Route::post('/store', [SystemController::class, 'store'])->name('system.store');
    
});
// Manage menu
Route::group(['prefix' => 'menu'], function () {
    Route::get('index', [MenuController::class, 'index'])->name('menu.index');
    Route::get('create', [MenuController::class, 'create'])->name('menu.create');
    Route::post('store', [MenuController::class, 'store'])->name('menu.store');
    Route::get('{id}/edit', [MenuController::class, 'edit'])->name('menu.edit');
    Route::post('{id}/update', [MenuController::class, 'update'])->name('menu.update');
    Route::get('{id}/delete', [MenuController::class, 'delete'])->name('menu.delete');
    Route::post('{id}/destroy', [MenuController::class, 'destroy'])->name('menu.destroy');
});

//@@new-module@@








});