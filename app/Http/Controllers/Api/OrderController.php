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

    	if ($order->isSuccess) {
    		return $this->sendResponse("Your order have been successfully saved.", $order->result);
    	} else {
    		return $this->sendError(422, $order->errorMessage);
    	}
    }

    /**
     * Show order detail
     * @return \Illuminate\Http\Response
     *
     *  @SWG\Get(
     *      path="/orders/{id}",
     *      summary="Get order detail",
     *      tags={"Order"},
     *      description="Get order detail",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          type="string",
     *          description="Token from `Authorization` header of login response",
     *          required=true
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="ID of Order",
     *          type="string",
     *          required=true,
     *          in="path"
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
    public function show($id) {
    	$order = Order::with('products')
    		->with('shipment')
    		->where('id', $id)
    		->first();

    	return $order;
    }

    /**
     * Confirm Payment
     * @return \Illuminate\Http\Response
     *
     *  @SWG\Post(
     *      path="/orders/{id}/confirm/payment",
     *      summary="Confirm payment",
     *      tags={"Order"},
     *      description="Confirm the payment of an order. Assumption: the confirmation payment code is VALID",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          type="string",
     *          description="Token from `Authorization` header of login response",
     *          required=true
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="ID of Order",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Confirmation payment code",
     *          required=true,
     *          @SWG\Schema(
     *          	@SWG\Property(property="confirmation_payment_code", type="string", description="Confirmation payment code from bank transfer process")
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
    public function confirmPayment(Request $request, $id) {
    	$order = new Order;
    	$order->confirmPayment($request, $id);

    	if ($order->isSuccess) {
    		return $this->sendResponse("Your confirmation payment has been saved.", $order->result);
    	} else {
    		return $this->sendError(422, $order->errorMessage);
    	}
    }

    /**
     * Ship the order
     * @return \Illuminate\Http\Response
     *
     *  @SWG\Post(
     *      path="/orders/{id}/ship",
     *      summary="Ship the order",
     *      tags={"Order"},
     *      description="Ship the order and give the order to logistic partner",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          type="string",
     *          description="Token from `Authorization` header of login response",
     *          required=true
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="ID of Order",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Confirmation payment code",
     *          required=true,
     *          @SWG\Schema(
     *          	@SWG\Property(property="logistic_partner_id", type="string", description="Logistic partner ID")
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
    public function shipOrder(Request $request, $id) {
    	$order = new Order;
    	$order->shipOrder($id, $request);

    	if ($order->isSuccess) {
    		return $this->sendResponse("The order with ID: {$id} is successfully shipped.", $order->result);
    	} else {
    		return $this->sendError(422, $order->errorMessage);
    	}
    }

    /**
     * Cancel the order
     * @return \Illuminate\Http\Response
     *
     *  @SWG\Post(
     *      path="/orders/{id}/cancel",
     *      summary="Cancel the order",
     *      tags={"Order"},
     *      description="Cancel the order",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          type="string",
     *          description="Token from `Authorization` header of login response",
     *          required=true
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="ID of Order",
     *          type="integer",
     *          required=true,
     *          in="path"
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
    public function cancelOrder($id) {
    	$order = new Order;
    	$order->cancelOrder($id, 'canceled');

    	if ($order->isSuccess) {
    		return $this->sendResponse("The order with ID: {$id} is canceled.", $order->result);
    	} else {
    		return $this->sendError(422, $order->errorMessage);
    	}
    }
}
