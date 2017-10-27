<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject as JWTSubject;

/**
 * @SWG\Definition(
 *      definition="User",
 *      required={"name", "email", "password", "role"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="User's name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="User's E-mail",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="role",
 *          description="User's role: admin or customer",
 *          type="string"
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

/**
 * @SWG\Definition(
 *      definition="UserLogin",
 *      required={"email", "password"},
 *      @SWG\Property(
 *          property="token",
 *          description="User's token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user",
 *          ref="#/definitions/User"
 *      )
 * )
 */

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT
     *
     * @return mixed
     */
    public function getJWTIdentifier(){
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT
     *
     * @return array
     */
    public function getJWTCustomClaims(){
        return [];
    }
}
