<?php

namespace App\OAuth;

use App\OAuth\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable;

    /**
     * [$connection description]
     * @var string
     */
    protected $connection = 'accounts';
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'timezone' => 'array'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'last_login',
        'date_activated',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'nickname',
        'email',
        'username',
        'phone',
        'avatar',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'status',
        'user_type',
        'date_activated',
        'timezone',
        'last_login',
        'password',
        // 'activation_code'

        'telegram_id',
        'gcm_token',
        'device_token',
        'onesignal_id'
    ];
}
