<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Coupon;
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
 *          	@SWG\Property(property="price", type="integer", description="Product's price"),
 *          	@SWG\Property(property="quantity", type="integer", description="Product's quantity"),
 *          	@SWG\Property(property="created_at", type="string", description="created_at", format="date-time"),
 *          	@SWG\Property(property="updated_at", type="string", description="updated_at", format="date-time"),
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
		return $this->belongsToMany('App\Models\Product')->withTimestamps();;
	}

	public $status = TRUE,
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
    		$this->status = FALSE;
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
	    	$this->recipient_name		= $input['recipient']['name'];
	    	$this->recipient_phone_no	= $input['recipient']['phone_no'];
	    	$this->recipient_email		= $input['recipient']['email'];
	    	$this->recipient_address	= $input['recipient']['address'];
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

	    	DB::commit();
    	} catch (\Exception $e) {
    		DB::rollback();
    		$this->status = FALSE;
    		$this->errorMessage = $e->getMessage();
    		return;
    	}
	}
}