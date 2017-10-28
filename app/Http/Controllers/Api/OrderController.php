<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController as BaseController;
use App\Models\Order;

class OrderController extends BaseController
{
	/**
     * Add an order
     * @return \Illuminate\Http\Response
     *
     *  @SWG\Post(
     *      path="/orders",
     *      summary="Add order",
     *      tags={"Order"},
     *      description="Select available product & available coupon and put as an order",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          type="string",
     *          description="Token from `Authorization` header of login response",
     *          required=true
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Order details",
     *          required=true,
     *          @SWG\Schema(
     *          	@SWG\Property(property="products", type="array", description="Ordered products",
     *          	@SWG\Items(
     *          		@SWG\Property(property="product_id", type="integer", description="Product ID"),
     *          		@SWG\Property(property="quantity", type="integer", description="Ordered product quantity")
     *          	)),
     *          	@SWG\Property(property="coupon_id", type="integer", description="Coupon ID"),
     *          	@SWG\Property(property="recipient", type="object", description="Recipient Detail",
     *          		@SWG\Property(property="name", type="string", description="Recipient name"),
     *          		@SWG\Property(property="phone_no", type="string", description="Recipient phone no"),
     *          		@SWG\Property(property="email", type="string", description="Recipient email"),
     *          		@SWG\Property(property="address", type="string", description="Recipient address"),
     *          	)
     *          )
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
     *                  ref="#/definitions/Order"
     *              )
     *          )
     *      )
     * )
     */
    public function addOrder(Request $request) {
    	$order = new Order;
    	$order->addOrder($request);

    	if ($order->status) {
    		return $this->sendResponse("Your order have been successfully saved.", $order->result);
    	} else {
    		return $this->sendError(422, $order->errorMessage);
    	}
    }
}
