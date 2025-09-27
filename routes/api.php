<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

Route::group(['as' => 'api.'], function () {
    Orion::resource('products', ProductController::class);
});
