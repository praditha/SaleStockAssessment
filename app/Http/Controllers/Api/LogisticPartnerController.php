<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController as BaseController;
use App\Models\LogisticPartner;

class LogisticPartnerController extends BaseController
{
	/**
     * Retrieve all logistic partners
     * @return \Illuminate\Http\Response
     *
     *  @SWG\Get(
     *      path="/logistic-partners",
     *      summary="Retrieve all logistic partners",
     *      tags={"Product"},
     *      description="Retrieve all logitstic partners",
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
     *                  ref="#/definitions/LogisticPartner"
     *              )
     *          )
     *      )
     * )
     */
    public function showAll() {
    	$logisticPartners = LogisticPartner::orderBy('name')->get();

    	return $this->sendResponse('Logistic partners successfully retrieved', $logisticPartners);
    }
}
