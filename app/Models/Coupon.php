<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *      definition="Coupon",
 *      required={"name", "description", "amount", "type", "valid_from", "valid_to", "quantity"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="Coupon's name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="Coupon's description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="amount",
 *          description="Coupon's amount",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="type",
 *          description="Coupon's type",
 *          type="string",
 *          enum={"percentage", "nominal"}
 *      ),
 *      @SWG\Property(
 *          property="valid_from",
 *          description="Start date of coupon period",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="valid_to",
 *          description="End date of coupon period",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="quantity",
 *          description="Coupon's quantity",
 *          type="integer"
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
class Coupon extends Model
{
	protected $fillable = ['name', 'description', 'amount', 'type', 'valid_from', 'valid_to', 'quantity'];
}