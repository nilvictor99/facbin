<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;
use Orion\Http\Controllers\Controller;

class ProductController extends Controller
{
    use DisableAuthorization;
    use DisablePagination;

    protected $model = Product::class;
}
