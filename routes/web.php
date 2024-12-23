<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class,'index'])->name('home.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

Route::middleware(['auth'])->group(function(){
    Route::get('/account_dashboard', [UserController::class,'index'])->name('user.index');
});


Route::middleware(['auth', AuthAdmin::class])->group(function(){
    Route::get('/account_admin', [AdminController::class,'index'])->name('admin.index');
    Route::get('/admin/brands',[AdminController::class,'brands'])->name('admin.brand');

    Route::get('admin/add/brand',[AdminController::class,'brand_add'])->name('admin.brand.add');

    Route::post('admin/brand/store', [AdminController::class,'brand_store'])->name('admin.brand.store');

    Route::get('/admin/brands/edit/{id}', [AdminController::class, 'brand_edit'])->name('admin.brand.edit');

    Route::post('/admin/brands/update/{id}', [AdminController::class, 'brand_update'])->name('admin.brand.update');

    Route::get('/admin/brands/delete/{id}', [AdminController::class, 'brand_delete'])->name('admin.brand.delete');

    Route::get('/admin/categories',[AdminController::class,'categories'])->name('admin.categories');

    Route::get('admin/add/category',[AdminController::class,'category_add'])->name('admin.category.add');

    Route::post('admin/category/store', [AdminController::class,'category_store'])->name('admin.category.store');
});

require __DIR__.'/auth.php';
