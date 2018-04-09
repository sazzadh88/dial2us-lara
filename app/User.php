<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $dates = [
            'from_date',
        ];
        
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function bill(){
      return $this->hasMany('\App\Bill');
    }
    public function invoice(){
      return $this->hasMany('\App\Invoice');
    }

    public function payment(){
        return $this->hasMany('\App\Payment');
    }
}
