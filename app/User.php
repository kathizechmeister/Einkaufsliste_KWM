<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['firstname', 'lastname','street','streetnumber','plz','city', 'email', 'password','is_helper'
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function comments():HasMany{
        return $this->hasMany(Comment::class);
    }

    public function shoppinglists():HasMany
    {
        return $this->hasMany( Shoppinglist::class );
    }
    public function getJWTIdentifier()
    {
        return $this ->getKey();
    }

    public function getJWTCustomClaims() {
        return ['user' => ['id' => $this->id, 'is_helper' => $this->is_helper,
            'firstname' => $this->firstname, 'lastname' => $this->lastname,
            'street'=> $this->street,'streetnumber'=> $this->streetnumber,
            'plz'=> $this->plz, 'city'=> $this->city]];
    }

}
