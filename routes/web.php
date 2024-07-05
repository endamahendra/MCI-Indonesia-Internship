<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Front;
use App\Http\Middleware\Pengguna;
use App\Http\Middleware\CorsMiddleware;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\KategoriArtikelController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TravelPackageController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\UserRewardController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\FrontController;



// Route::middleware(['cors'])->group(function () {
    Route::get('/product/datatables', [ProductController::class, 'getdata'])->name('product.data');
    Route::post('/signup', [AuthUserController::class, 'register'])->name('register-user');
    Route::post('/signin', [AuthUserController::class, 'login'])->name('login-user');
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/callback/google', [GoogleController::class, 'handleGoogleCallback'])->name('login.google.callback');
    Route::get('/', [FrontController::class, 'index'])->name('home');
    Route::get('/f/products', [FrontController::class, 'product'])->name('fproduct');
    Route::get('/f/product/category/{deskripsi}', [ProductController::class, 'fsearch'])->name('product.category');
    Route::get('/f/products/search', [ProductController::class, 'fsearch'])->name('fproduct.search');
    Route::get('/product/datatables', [ProductController::class, 'all'])->name('product.all');
    Route::get('/kategori/all', [CategoryController::class, 'all']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('products/best-selling', [OrderController::class, 'getBestSellingProducts'])->name('products.best-selling');
    Route::get('/ratings', [RatingController::class, 'index']);
    Route::get('/ratings/product/{product_id}', [RatingController::class, 'getByProduct']);
    Route::get('/f/detailproduct/{sku}', [ProductController::class, 'detail'])->name('product.detail');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/artikel/datatables', [ArtikelController::class, 'getdata'])->name('artikel.data');
    Route::get('/artikel/{id}', [ArtikelController::class, 'show']);
    Route::get('/travel-package/datatables', [TravelPackageController::class, 'getdata'])->name('travel.data');
    Route::get('/travel-package/{id}', [TravelPackageController::class, 'show']);
    Route::get('/penggunas/{id}', [PenggunaController::class, 'show']);
    Route::get('/f/about', function () {return view('front.about');})->name('about');
// });

    //sisipkan middleware pengguna cors dan front disembunyikan
    Route::middleware(['pengguna'])->group(function () {
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::post('/penggunas/{id}', [PenggunaController::class, 'update']);
        Route::get('/pengguna/{id}', [PenggunaController::class, 'showuser']);
        Route::post('/ratings', [RatingController::class, 'store']);
        Route::put('/ratings/{id}', [RatingController::class, 'update']);
        Route::delete('/ratings/{id}', [RatingController::class, 'destroy']);
        Route::post('/comments', [ArtikelController::class, 'commentStore']);
        Route::get('/diskusi', [ArtikelController::class, 'showDiskusi']);
        Route::post('/f/orders', [OrderController::class, 'fstore'])->name('forders.store');
        Route::get('/f/orders/{order}/sukses', [OrderController::class, 'sukses'])->name('orders.sukses');
        Route::get('/f/update/profil/{id}', [UserController::class, 'updateUser'])->name('update.user');
        Route::get('/f/order/datatable', [FrontController::class, 'orderUser'])->name('order.user');
        Route::get('/f/order', [FrontController::class, 'forder'])->name('forder.user');
        Route::get('/f/news/search', [FrontController::class, 'searchnews'])->name('news.search');
        Route::put('/f/update/profil/{id}', [UserController::class, 'updateUserstore'])->name('update.user.store');
        Route::post('/f/update/profil/photo/{id}', [UserController::class, 'updateUserfoto'])->name('update.user.photo');
        Route::get('/f/user/dashboard', [FrontController::class, 'userDashboard'])->name('user.dashboard');
        Route::put('/f/orders/{order}/status', [OrderController::class, 'updateStatus']);
        Route::get('/f/orders/{orderId}/rating', [OrderController::class, 'showRatingForm'])->name('orders.rating');
        Route::post('/f/orders/{orderId}/rating', [OrderController::class, 'submitRating'])->name('orders.submitRating');
        // Route::put('/f/orders/{orderId}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('/f/comments', [FrontController::class, 'fcomment'])->name('comments.store');
    });
    Route::get('/f/news/{slug?}', [FrontController::class, 'fnews'])->name('fnews');
    Route::get('/f/news/detail/{judul?}', [FrontController::class, 'fnewsdetail'])->name('fnews.detail');


    Route::middleware(['admin'])->group(function () {
        //PENGUNA
        Route::get('/penggunas', [PenggunaController::class, 'index'])->name('pengguna');
        Route::delete('/penggunas/{id}', [PenggunaController::class, 'destroy']);
        Route::get('/penggunaa/datatables', [PenggunaController::class, 'getdata'])->name('pengunaa.data');


        //DASHBOARD
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/getdata', [DashboardController::class, 'getdata']);

        //PRODUCT DAN CATEGORY PRODUCT
        Route::get('/product', [ProductController::class, 'index'])->name('product');
        Route::post('/product', [ProductController::class, 'store'])->name('product.store');
        Route::post('/product/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/product/{id}', [ProductController::class, 'destroy']);
        Route::get('/category', [CategoryController::class, 'index']);
        Route::get('/category/datatables', [CategoryController::class, 'getdata'])->name('category.data');
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
        Route::get('/artikel/{id}', [ArtikelController::class, 'show']);
        Route::post('/artikel/{id}', [ArtikelController::class, 'update']);
        Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy']);
        Route::post('/artikel', [ArtikelController::class, 'store']);

        //ADMIN
        Route::get('/admin', [UserController::class, 'index']);
        Route::get('/admin/datatables', [UserController::class, 'getdata'])->name('user.data');
        Route::get('/admin/{id}', [UserController::class, 'show']);
        Route::post('/admin', [UserController::class, 'store']);
        Route::put('/admin/{id}', [UserController::class, 'update']);
        Route::delete('/admin/{id}', [UserController::class, 'destroy']);

        //ORDER
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/datatable', [OrderController::class, 'getdata'])->name('orders.data');
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');


        //PAKET WISATA
        Route::get('/travel-package', [TravelPackageController::class, 'index'])->name('travel-package');
        Route::post('/travel-package', [TravelPackageController::class, 'store'])->name('travel-package.store');
        Route::post('/travel-package/{id}', [TravelPackageController::class, 'update'])->name('travel-package.update');
        Route::delete('/travel-package/{id}', [TravelPackageController::class, 'destroy']);


        //REWARD
        Route::get('/reward', [UserRewardController::class, 'index'])->name('reward');
        Route::get('/reward/datatables', [UserRewardController::class, 'getdata'])->name('reward.data');
        Route::get('/reward/{id}', [UserRewardController::class, 'show']);
        // Route::post('/reward/{id}', [UserRewardController::class, 'update']);
        // Route::delete('/reward/{id}', [UserRewardController::class, 'destroy']);
        // Route::post('/reward', [UserRewardController::class, 'store']);
        });

require __DIR__.'/auth.php';

