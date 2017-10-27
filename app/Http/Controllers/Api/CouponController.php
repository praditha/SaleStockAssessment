<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController as BaseController;
use App\Models\Coupon;

class CouponController extends BaseController
{
	/**
     * Retrieve all available coupon
     * @return \Illuminate\Http\Response
     *
     *  @SWG\Get(
     *      path="/coupons",
     *      summary="Retrieve all available coupon",
     *      tags={"Coupon"},
     *      description="Retrieve all available coupon with detail information such as coupon name, description, validity date, amount, and available quantity",
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
     *                  ref="#/definitions/Coupon"
     *              )
     *          )
     *      )
     * )
     */
    public function showAll() {
    	$coupons = Coupon::where('valid_from', '<=', date('Y-m-d'))
            ->where('valid_to', '>=', date('Y-m-d'))
            ->orderBy('valid_to')
            ->orderBy('name')
            ->get();

    	return $this->sendResponse('Coupons successfully retrieved', $coupons);
    }
}
