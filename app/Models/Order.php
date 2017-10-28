<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Shipment;
use Validator, DB;

/**
 * @SWG\Definition(
 *      definition="Order",
 *      required={"customer_id", "products", "total_price", "status"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="customer_id",
 *          description="Customer ID who order",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="products",
 *          description="Products which ordere by customer",
 *          type="array",
 *          @SWG\Items(
 *          	@SWG\Property(property="id", type="integer", description="id"),
 *          	@SWG\Property(property="name", type="string", description="Product's name"),
 *          	@SWG\Property(property="price", type="integer", description="Product's price")
 *          )
 *      ),
 *      @SWG\Property(
 *          property="total_price",
 *          description="Total price of order",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="Order's status",
 *          type="string",
 *          enum={"ordered", "paid", "shipped"}
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Order extends Model
{
	protected $fillable = ['customer_id', 'coupon_id', 'total_price', 'status'];

	// The products that belong to order
	public function products() {
		return $this->belongsToMany('App\Models\Product')
			->select('id', 'name', 'price')
			->withPivot('quantity')
			->withTimestamps();
	}

	// The shipment that belong to order
	public function shipment() {
		return $this->hasOne('App\Models\Shipment', 'order_id');
	}

	public $isSuccess = TRUE,
		$result,
		$errorMessage;

	public function addOrder($request) {
		$user = \Auth::user();
		
		$input = $request->input();
		$input['customer_id'] = $user->id;

		// Validate the input
		$rules = [
			'customer_id'			=> 'required|exists:users,id',
			'products'				=> 'required|array',
			'products.*.product_id'	=> 'required|exists:products,id|product_available',
			'products.*.quantity'	=> 'required|integer|min:1',
			'coupon_id'				=> 'exists:coupons,id|coupon_available',
			'recipient.name'		=> 'required',
			'recipient.phone_no'	=> ['required', 'regex:/^(08|02)[0-9]{9,}$/'],
			'recipient.email'		=> 'required|email',
			'recipient.address'		=> 'required',
		];

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
    		$this->isSuccess = FALSE;
			$this->errorMessage = $validator->errors()->first();
			return;
    	}

    	try {
    		// Proceed the order
	    	DB::beginTransaction();

	    	// Save the order
	    	$this->customer_id 			= $input['customer_id'];
	    	$this->coupon_id 			= $request->has('coupon_id') ? $input['coupon_id'] : null;
	    	$this->total_price			= 0;
	    	$this->status 				= 'ordered';
	    	$this->save();

	    	$totalPrice = 0;
	    	foreach ($input['products'] as $key => $product) {
	    		$productModel = Product::find($product['product_id']);
	    		$totalPrice += ($productModel->price * $product['quantity']);

	    		// Attach product to order
	    		$this->products()->attach([
	    			$productModel->id => [
	    				'quantity' => $product['quantity']
	    			]
	    		]);

	    		// Reduce product quantity
	    		$productModel->quantity -= $product['quantity'];
	    		$productModel->save();
	    	}

	    	// Reduce coupon quantity and calculate the total price if used the coupon
	    	if ($request->has('coupon_id')) {
		    	$couponModel = Coupon::find($input['coupon_id']);
		    	$couponModel->quantity--;
		    	$couponModel->save();

		    	if ($couponModel->type == 'nominal') {
		    		$totalPrice -= $couponModel->amount;
		    	} else {
		    		$totalPrice -= ($totalPrice * $couponModel->amount / 100);
		    	}

		    	// Check if the total price is less than 0 than make it 0
		    	$totalPrice = $totalPrice < 0 ? 0 : $totalPrice;
	    	}

	    	// Update the total price
	    	$this->total_price = $totalPrice;
	    	$this->save();

	    	// Prepare the shipment
	    	$shipment = new Shipment;
	    	$shipment->id 					= time();
	    	$shipment->order_id				= $this->id;
	    	$shipment->recipient_name		= $input['recipient']['name'];
	    	$shipment->recipient_phone_no	= $input['recipient']['phone_no'];
	    	$shipment->recipient_email		= $input['recipient']['email'];
	    	$shipment->recipient_address	= $input['recipient']['address'];
	    	$shipment->save();

	    	// Get Order detail
	    	$this->result = $this->find($this->id);

	    	DB::commit();
    	} catch (\Exception $e) {
    		DB::rollback();
    		$this->isSuccess = FALSE;
    		$this->errorMessage = $e->getMessage();
    		return;
    	}
	}

	public function confirmPayment($request, $id) {
		$input = $request->input();
		$input['order_id'] = $id;

		$validator = Validator::make($input, [
			'order_id' => 'required|exists:orders,id',
			'confirmation_payment_code' => 'required',
		]);

		if ($validator->fails()) {
    		$this->isSuccess = FALSE;
			$this->errorMessage = $validator->errors()->first();
			return;
    	}

    	try {
	    	// Confirmation Payment Code is valid then save it
	    	$order = $this->find($id);

	    	if ($order->status != 'ordered') {
	    		$this->errorMessage = $this->getOrderStatus($order);
	    		$this->isSuccess = FALSE;
	    		return;
	    	}

	    	DB::beginTransaction();
			    	
	    	$order->confirmation_payment_code = $input['confirmation_payment_code'];
	    	$order->status = 'paid';
	    	$order->save();

	    	DB::commit();

	    	$this->result = $order;
	    	return;
    	} catch (\Exception $e) {
    		DB::rollback();
    		$this->isSuccess = FALSE;
    		$this->errorMessage = $e->getMessage();
    		return;
    	}
	}

	public function shipOrder($id, $request) {
		$input = $request->input();
		$input['order_id'] = $id;

		$validator = Validator::make($input, [
			'order_id' => 'required|exists:orders,id',
			'logistic_partner_id' => 'required|exists:logistic_partners,id',
		]);

		if ($validator->fails()) {
    		$this->isSuccess = FALSE;
			$this->errorMessage = $validator->errors()->first();
			return;
    	}

		try {
	    	$order = $this->find($id);

	    	if ($order->status != 'paid') {
	    		$this->errorMessage = $this->getOrderStatus($order);
	    		$this->isSuccess = FALSE;
	    		return;
	    	}

	    	DB::beginTransaction();
			    	
	    	$order->status = 'shipped';
	    	$order->save();

	    	$shipment = Shipment::where('order_id', $order->id)->first();
	    	$shipment->logistic_partner_id = $input['logistic_partner_id'];
	    	$shipment->save();

	    	DB::commit();

	    	$this->result = $order;
	    	return;
    	} catch (\Exception $e) {
    		DB::rollback();
    		$this->isSuccess = FALSE;
    		$this->errorMessage = $e->getMessage();
    		return;
    	}
	}

	public function cancelOrder($id) {
		try {
	    	$order = $this->find($id);

	    	if ($order->status != 'ordered') {
	    		$this->errorMessage = $this->getOrderStatus($order);
	    		$this->isSuccess = FALSE;
	    		return;
	    	}

	    	DB::beginTransaction();
			    	
	    	$order->status = 'canceled';
	    	$order->save();

	    	DB::commit();

	    	$this->result = $order;
	    	return;
    	} catch (\Exception $e) {
    		DB::rollback();
    		$this->isSuccess = FALSE;
    		$this->errorMessage = $e->getMessage();
    		return;
    	}
	}

	private function getOrderStatus($order) {
		switch ($order->status) {
    		case 'paid':
    			return "The order with ID: {$order->id} has been paid";
    		case 'shipped':
    			return "The order with ID: {$order->id} has been shipped";
    		case 'canceled':
    			return "The order with ID: {$order->id} has been canceled";
    		default:
    			return "The order with ID: {$order->id} has been ordered";
    	}
	}
}