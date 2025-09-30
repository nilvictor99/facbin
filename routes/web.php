<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index')->name('users.index');
        Route::get('/user/list', 'list')->name('users.list');
    });

    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customer', 'index')->name('customers.index');
        Route::get('/customer/list', 'list')->name('customers.list');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/product', 'index')->name('products.index');
        Route::get('/product/list', 'list')->name('products.list');
    });

    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoice', 'index')->name('invoices.index');
        Route::get('/invoice/list', 'list')->name('invoices.list');
    });

    Route::controller(InventoryController::class)->group(function () {
        Route::get('/inventory', 'index')->name('inventory.index');
        Route::get('/inventory/list', 'list')->name('inventory.list');
    });
});
