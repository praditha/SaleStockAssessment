<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController as BaseController;
use App\Models\Product;

class ProductController extends BaseController
{
    public function showAll() {
    	$products = Product::orderBy('name')->get();

    	return $this->sendResponse('Products successfully retrieved', $products);
    }
}
