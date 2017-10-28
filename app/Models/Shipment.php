<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *      definition="Shipment",
 *      required={"name"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="order_id",
 *          description="Order ID",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="logistic_partner_id",
 *          description="Logistic Partner ID",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="recipient_name",
 *          description="Recipient name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="recipient_email",
 *          description="Recipient email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="recipient_phone_no",
 *          description="Recipient phone no",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="recipient_address",
 *          description="Recipient address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="Order's status",
 *          type="string",
 *          enum={"packing", "on the way", "delivered"}
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
class Shipment extends Model
{
	protected $fillable = ['order_id', 'logistic_partner_id', 'recipient_name', 'recipient_email', 'recipient_phone_no', 'recipient_address', 'status'];

	public function order() {
		return $this->belongsTo('App\Models\Order');
	}
}