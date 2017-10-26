<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;

class ApiBaseController extends BaseController
{
    const SUCCESS_RESCODE = 0;
    const FAILED_RESCODE = 1;
    const RELOGIN_RESCODE = 2;

    public function sendResponse($message, $data = null) {
        $responses = [
            'message' => $message
        ];

        $responses['data'] = $data;

        return response()->json($responses);
    }

    public function sendError($httpCode = 500, $message, $data = null) {
        $result = [
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($result);
    }
}