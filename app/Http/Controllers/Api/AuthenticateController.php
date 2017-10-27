<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Api\ApiBaseController as BaseController;

class AuthenticateController extends BaseController
{

	/**
     * Check user credentials
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     *  @SWG\Post(
     *      path="/login",
     *      summary="Check user credentials",
     *      tags={"Auth"},
     *      description="Validate the user credentials & return access-token when success",
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User credentials include email & password",
     *          required=true,
     *          @SWG\Schema(
     *          	@SWG\Property(property="email", type="string", description="e.g. admin@salestock.com / customer@mail.com"),
     *          	@SWG\Property(property="password", type="string", description="e.g. secretd")
     *          ),
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
     *                  ref="#/definitions/UserLogin"
     *              )
     *          )
     *      )
     * )
     */
    public function authenticate(Request $request) {
    	// grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
            	return $this->sendError(401, 'Invalid Credentials');
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->sendError(500, 'Could not create token');
        }

        // Get User information
        $user = \Auth::user();

        // all good so return the token
        return $this->sendResponse('Login successfully.', ['token' => 'Bearer ' . $token, 'user' => $user]);
    }

    /**
     * Logout the user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     *  @SWG\Get(
     *      path="/logout",
     *      summary="Logout user",
     *      tags={"Auth"},
     *      description="Logout User",
     *      produces={"application/json"},
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
     *                  type=null
     *              )
     *          )
     *      )
     * )
     */
	public function logout(Request $request) {
		try {
			JWTAuth::invalidate(JWTAuth::getToken());
		} catch(\Exception $e) {
			\Log::error($e->getMessage());
		}

		return $this->sendResponse('The user has been successfully logged out.');
	}
}
