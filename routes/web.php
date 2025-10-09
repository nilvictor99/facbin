<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MeasureUnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UbigeoController;
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

    Route::controller(UbigeoController::class)->group(function () {
        Route::get('/search/ubigeo', 'search')->name('ubigeos.search');
    });

    Route::controller(MeasureUnitController::class)->group(function () {
        Route::get('/search/measureunit', 'search')->name('measure_units.search');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index')->name('users.index');
        Route::get('/user/list', 'list')->name('users.list');
        Route::get('/user/create', 'create')->name('users.create');
        Route::post('/user/store', 'store')->name('users.store');
        Route::get('/user/{id}/edit', 'edit')->name('users.edit');
        Route::put('/user/{id}/update', 'update')->name('users.update');
    });

    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customer', 'index')->name('customers.index');
        Route::get('/customer/list', 'list')->name('customers.list');
        Route::get('/customer/create', 'create')->name('customers.create');
        Route::post('/customer/store', 'store')->name('customers.store');
        Route::get('/customer/{id}/edit', 'edit')->name('customers.edit');
        Route::put('/customer/{id}/update', 'update')->name('customers.update');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/product', 'index')->name('products.index');
        Route::get('/product/list', 'list')->name('products.list');
        Route::get('/product/create', 'create')->name('products.create');
        Route::post('/product/store', 'store')->name('products.store');
        Route::get('/product/{id}/edit', 'edit')->name('products.edit');
        Route::put('/product/{id}/update', 'update')->name('products.update');
    });

    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoice', 'index')->name('invoices.index');
        Route::get('/invoice/list', 'list')->name('invoices.list');
    });

    Route::controller(InventoryController::class)->group(function () {
        Route::get('/inventory', 'index')->name('inventory.index');
        Route::get('/inventory/list', 'list')->name('inventory.list');
        Route::get('/inventory/create', 'create')->name('inventory.create');
        Route::post('/inventory/store', 'store')->name('inventory.store');
        Route::get('/inventory/{id}/edit', 'edit')->name('inventory.edit');
        Route::put('/inventory/{id}/update', 'update')->name('inventory.update');
    });
});
