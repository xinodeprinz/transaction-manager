<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/payment', [PaymentController::class, 'pay']);

Route::group(['prefix' => 'account'], function() {
    Route::post('/create', [RegisterController::class, 'register']);
    Route::post('/login', [RegisterController::class, 'login']);
    Route::delete('/user/delete/{id}', [DashboardController::class, 'deleteUser']);
    Route::get('/block_unblock/{id}', [DashboardController::class, 'block_unblock']);
    Route::get('/user/{username}', [DashboardController::class, 'getUserByUserName']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'account'], function() {
        Route::get('/user', [DashboardController::class, 'user']);
        Route::get('/logout', [DashboardController::class, 'logout']);
        Route::post('/pro/create', [DashboardController::class, 'proAccount']);
        Route::get('/pro/details', [DashboardController::class, 'accountDetails']);
        Route::get('/expired', [DashboardController::class, 'accountExpired']);
        Route::post('/employee/create', [DashboardController::class, 'createEmployee']);
        Route::get('/employees', [DashboardController::class, 'employees']);
        Route::delete('/employee/delete/{id}', [DashboardController::class, 'deleteEmployee']);
        Route::post('/username', [DashboardController::class, 'createUserName']);
    });

    Route::group(['prefix' => 'category'], function() {
        Route::post('/create', [DashboardController::class, 'createCategory']);
        Route::get('/all', [DashboardController::class, 'categories']);
        Route::get('/products/{id}', [DashboardController::class, 'categoryProducts']);
        Route::delete('/delete/{id}', [DashboardController::class, 'deleteCategory']);
        Route::put('/change/{id}', [ProductController::class, 'changeProductCategory']);
    });

    Route::group(['prefix' => 'product'], function() {
        Route::post('/create', [ProductController::class, 'createProduct']);
        Route::get('/id/{id}', [ProductController::class, 'searchById']);
        Route::get('/name/{name}', [ProductController::class, 'searchByName']);
        Route::get('/all', [ProductController::class, 'products']);
        Route::put('/update/quantity/{id}', [ProductController::class, 'updateQuantity']);
        Route::put('/update/unit-price/{id}', [ProductController::class, 'updateUnitPrice']);
        Route::put('/update/selling-price/{id}', [ProductController::class, 'updateSellingPrice']);
        Route::delete('/delete/{id}', [ProductController::class, 'deleteProduct']);
        Route::get('/expenses', [ProductController::class, 'expenses']);
    });

    Route::group(['prefix' => 'sale'], function() {
        Route::put('/sell/{id}', [SaleController::class, 'sell']);
        Route::get('/all', [SaleController::class, 'totalSales']);
        Route::get('/expenses', [SaleController::class, 'expenses']);
    });
});
