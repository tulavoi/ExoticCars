<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\client\HomeController;
use App\Http\Controllers\client\NewsController;
use App\Http\Controllers\client\ClientBrandController;
use App\Http\Controllers\client\ClientCarController;
use App\Http\Controllers\client\LoginController;

use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\CarController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\PostsController;

// Client routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');


// News
Route::prefix('/news')->group(function(){
    Route::get('/', [NewsController::class, 'index']);

    // Lấy chi tiết 1 posts
    Route::get('/posts/{slug}', [NewsController::class, 'getPosts']);
});


// Brands
Route::prefix('/all-brands')->group(function(){
    Route::get('/', [ClientBrandController::class, 'index'])->name('all-brands');

    // Lấy chi tiết 1 brand
    Route::get('/{slug}', [ClientBrandController::class, 'getBrand'])->name('brand.detail');
});


// Car
Route::prefix('/car')->group(function(){
    // Lấy chi tiết 1 car
    Route::get('/{slug}', [ClientCarController::class, 'getCar']);
});


// Login Logout
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Admin routes
Route::middleware('auth.admin')->prefix('/admin')->group(function(){
    Route::get('/', [DashboardController::class, 'index'])->name('admin');
});

// Car
Route::middleware('auth.admin')->prefix('cars')->group(function(){
    Route::get('/', [CarController::class, 'index'])->name('cars.index');

    Route::get('/add', [CarController::class, 'add'])->name('cars.add');
    Route::post('/store', [CarController::class, 'store'])->name('cars.store');

    Route::get('/edit/{id}', [CarController::class, 'edit'])->name('cars.edit');
    Route::post('/update/{id}', [CarController::class, 'update'])->name('cars.update');

    Route::get('/delete/{id}', [CarController::class, 'delete'])->name('cars.delete');
});

// Category
Route::middleware('auth.admin')->prefix('categories')->group(function(){
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');

    Route::get('/add', [CategoryController::class, 'add'])->name('categories.add');
    Route::post('/store', [CategoryController::class, 'store'])->name('categories.store');

    Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('/update/{id}', [CategoryController::class, 'update'])->name('categories.update');

    Route::get('/delete/{id}', [CategoryController::class, 'delete'])->name('categories.delete');
});

Route::get('/add', [BrandController::class, 'add'])->name('brands.add');


// Brand
Route::middleware('auth.admin')->prefix('/brands')->group(function(){
    Route::get('/', [BrandController::class, 'index'])->name('brands.index');

    Route::get('/add', [BrandController::class, 'add'])->name('brands.add');
    Route::post('/store', [BrandController::class, 'store'])->name('brands.store');

    Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('brands.edit');
    Route::post('/update/{id}', [BrandController::class, 'update'])->name('brands.update');

    Route::get('/delete/{id}', [BrandController::class, 'delete'])->name('brands.delete');
});

// Posts
Route::middleware('auth.admin')->prefix('/posts')->group(function(){
    Route::get('/', [PostsController::class, 'index'])->name('posts.index');

    Route::get('/add', [PostsController::class, 'add'])->name('posts.add');
    Route::post('/store', [PostsController::class, 'store'])->name('posts.store');

    Route::get('/edit/{id}', [PostsController::class, 'edit'])->name('posts.edit');
    Route::post('/update/{id}', [PostsController::class, 'update'])->name('posts.update');

    Route::get('/delete/{id}', [PostsController::class, 'delete'])->name('posts.delete');
});
