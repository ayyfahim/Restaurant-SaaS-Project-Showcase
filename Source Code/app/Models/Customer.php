<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Model implements JWTSubject
{
    use Notifiable;
    
    protected $guarded = [];
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'table_joined_at' => 'datetime',
    ];

    protected $appends = ['full_name'];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function cards()
    {
        return $this->hasMany('App\Card')->orderBy('last_used_at', 'desc');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function waiter_calls()
    {
        return $this->hasMany('App\Models\WaiterCall', 'user_id');
    }

    public function allergens()
    {
        return $this->belongsToMany('App\Allergen');
    }

    public function coupons()
    {
        return $this->belongsToMany('App\Coupon', 'customer_coupon');
    }

    public function table()
    {
        return $this->belongsTo('App\Models\Table', 'table_id');
    }
}
