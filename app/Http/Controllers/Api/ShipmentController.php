<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController as BaseController;
use App\Models\Shipment;

class ShipmentController extends BaseController
{
	/**
     * Show shipment status
     * @return \Illuminate\Http\Response
     *
     *  @SWG\Get(
     *      path="/shipment/{id}/status",
     *      summary="Get shipment status",
     *      tags={"Shipment"},
     *      description="Retrieve shipment status",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          type="string",
     *          description="Token from `Authorization` header of login response",
     *          required=true
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="ID of Shipment",
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
     *                  ref="#/definitions/Shipment"
     *              )
     *          )
     *      )
     * )
     */
    public function showStatus($id) {
    	$shipment = Shipment::with('order.products')->find($id);

    	return $this->sendResponse('Shipment retrieved successfully', $shipment);
    }

    /**
     * Change shipment status
     * @return \Illuminate\Http\Response
     *
     *  @SWG\Put(
     *      path="/shipment/{id}/status/{status}",
     *      summary="Get shipment status",
     *      tags={"Shipment"},
     *      description="Retrieve shipment status",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          type="string",
     *          description="Token from `Authorization` header of login response",
     *          required=true
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="ID of Shipment",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="status",
     *          description="Shipment status",
     *          type="integer",
     *          required=true,
     *          in="path",
     *          enum={"packing", "on the way", "delivered"}
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
     *                  ref="#/definitions/Shipment"
     *              )
     *          )
     *      )
     * )
     */
    public function changeStatus($id, $status) {
    	$shipment = Shipment::find($id);

    	if (empty($shipment)) {
    		return $this->sendError(404, 'Shipment not found');
    	}

    	$shipment->status = $status;
    	$shipment->save();

    	return $this->sendResponse('Shipment status has been changed successfully', $shipment);
    }
}
