<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *      definition="Product",
 *      required={"name", "price", "quantity"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="Product's name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="price",
 *          description="Product's price",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="quantity",
 *          description="Product's quantity",
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
class Product extends Model
{
	protected $fillable = ['name', 'price', 'quantity'];
}