<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @SWG\Swagger(
 *   basePath="/api",
 * 	 schemes={"http"},
 * 	 produces={"application/json"},
 * 	 consumes={"application/json"},
 *   @SWG\Info(
 *     title="Sale Stock Assessment",
 *     version="1.0.0",
 *     description="API Documentation of Sale Stock Assessment for Praditha Hidayat",
 *     @SWG\Contact(name="Praditha Hidayat",email="pradithah.1124@gmail.com"),
 *     @SWG\License(name="Unlicense")
 *   )
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
