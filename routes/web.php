<?php
use App\Http\Middleware\Admin;
use App\Http\Middleware\Front;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\KategoriArtikelController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelPackageController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\ArtikelController;


/////////////////////////////////////////ROUTE PUBLIC/////////////////////////////////////////////////////////
//AUTH USER
Route::post('/signup', [AuthUserController::class, 'register'])->name('register-user');
Route::post('/signin', [AuthUserController::class, 'login'])->name('login-user');

Route::get('/product/datatables', [ProductController::class, 'getdata']);
Route::get('/artikel/datatables', [ArtikelController::class, 'getdata']);

///////////////////////////////////////ROUTE UNTUK ADMIN DAN USER//////////////////////////////////////////////
Route::middleware(['front', 'api'])->group(function () {
// MASUKKAN ROUTE KESINI
});

/////////////////////////////////////// ROUTE UNTUK USER///////////////////////////////////////////////////////
Route::middleware(['front'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/penggunas/{id}', [PenggunaController::class, 'update']);
    // MASUKKAN ROUTE KESINI
});

///////////////////////////////////////ROUTE UNTUK ADMIN////////////////////////////////////////////////////////
Route::middleware(['admin'])->group(function () {

    //PENGGUNA
    Route::get('/penggunas', [PenggunaController::class, 'index'])->name('pengguna');
    Route::get('/pengguna/datatables', [PenggunaController::class, 'getdata']);
Route::get('/penggunas/{id}', [PenggunaController::class, 'show']);
Route::delete('/penggunas/{id}', [PenggunaController::class, 'destroy']);

//DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/getdata', [DashboardController::class, 'getdata']);

//PRODUCT DAN CATEGORY PRODUCT
Route::get('/product', [ProductController::class, 'index'])->name('product');
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::post('/product', [ProductController::class, 'store'])->name('product.store');
Route::post('/product/{id}', [ProductController::class, 'update'])->name('product.update');
Route::delete('/product/{id}', [ProductController::class, 'destroy']);
Route::get('/category', [CategoryController::class, 'index']);
Route::get('/category/datatables', [CategoryController::class, 'getdata']);
Route::get('/category/{id}', [CategoryController::class, 'show']);
Route::post('/category', [CategoryController::class, 'store']);
Route::put('/category/{id}', [CategoryController::class, 'update']);
Route::delete('/category/{id}', [CategoryController::class, 'destroy']);


//KATEGORI ARTIKEL
Route::get('/kategori-artikel', [KategoriArtikelController::class, 'index'])->name('kategori-artikel.index');
Route::get('/kategori-artikel/datatables', [KategoriArtikelController::class, 'getdata'])->name('kategori-artikel.datatables');
Route::get('/kategori-artikel/{id}', [KategoriArtikelController::class, 'show']);
Route::post('/kategori-artikel', [KategoriArtikelController::class, 'store'])->name('kategori-artikel.store');
Route::put('/kategori-artikel/{id}', [KategoriArtikelController::class, 'update'])->name('kategori-artikel.update');
Route::delete('/kategori-artikel/{id}', [KategoriArtikelController::class, 'destroy'])->name('kategori-artikel.destroy');

//ARTIKEL
Route::get('/artikel', [ArtikelController::class, 'index']);
Route::post('/artikel', [ArtikelController::class, 'store']);

//ADMIN
Route::get('/admin', [UserController::class, 'index']);
Route::get('/admin/datatables', [UserController::class, 'getdata']);
Route::get('/admin/{id}', [UserController::class, 'show']);
Route::post('/admin', [UserController::class, 'store']);
Route::put('/admin/{id}', [UserController::class, 'update']);
Route::delete('/admin/{id}', [UserController::class, 'destroy']);

//ORDER
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/datatables', [OrderController::class, 'getdata'])->name('orders.data');

//PAKET WISATA
Route::get('/travel-package', [TravelPackageController::class, 'index'])->name('travel-package');
Route::get('/travel-package/datatables', [TravelPackageController::class, 'getdata'])->name('travel-data');
Route::get('/travel-package/{id}', [TravelPackageController::class, 'show']);
Route::post('/travel-package', [TravelPackageController::class, 'store'])->name('travel-package.store');
Route::post('/travel-package/{id}', [TravelPackageController::class, 'update'])->name('travel-package.update');
Route::delete('/travel-package/{id}', [TravelPackageController::class, 'destroy']);

//KERANJANG
});

require __DIR__.'/auth.php';


