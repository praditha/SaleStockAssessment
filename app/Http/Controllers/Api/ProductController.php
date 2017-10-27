<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController as BaseController;
use App\Models\Product;

class ProductController extends BaseController
{
	/**
     * Retrieve all products
     * @return \Illuminate\Http\Response
     *
     *  @SWG\Get(
     *      path="/products",
     *      summary="Retrieve all products",
     *      tags={"Product"},
     *      description="Retrieve all products with detail information such as product name, price and available quantity",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          type="string",
     *          description="Token from `Authorization` header of login response",
     *          required=true
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Product"
     *              )
     *          )
     *      )
     * )
     */
    public function showAll() {
    	$products = Product::orderBy('name')->get();

    	return $this->sendResponse('Products successfully retrieved', $products);
    }
}
