<?php

namespace App\Models;

use App\BankDetail;
use Hash;
use Illuminate\Foundation\Auth\User as Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class Store extends Model implements JWTSubject
{
    use HasRoles, HasPermissions;

    protected $guarded = ['id'];
    protected $guard_name = 'web';

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'pay_first' => 'boolean',
    ];

    protected $table = 'stores';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($store) {
            // $store->waiter_shifts()->create([
            //     'data' => '[{"id": "time_to_start_working", "name": "Time to start working", "Friday": "2:00 PM", "Monday": "2:00 PM", "Sunday": "2:00 PM", "Tuesday": "2:00 PM", "Saturday": "2:00 PM", "Thursday": "2:00 PM", "Wednesday": "2:00 PM"}, {"id": "end_time_for_work", "name": "End time for work", "Friday": "2:00 PM", "Monday": "2:00 PM", "Sunday": "2:00 PM", "Tuesday": "2:00 PM", "Saturday": "2:00 PM", "Thursday": "2:00 PM", "Wednesday": "2:00 PM"}, {"id": "start_lunch_break_time", "name": "Start lunch break time", "Friday": "2:00 PM", "Monday": "2:00 PM", "Sunday": "2:00 PM", "Tuesday": "2:00 PM", "Saturday": "2:00 PM", "Thursday": "2:00 PM", "Wednesday": "2:00 PM"}, {"id": "end_lunch_break_time", "name": "End lunch break time", "Friday": "2:00 PM", "Monday": "2:00 PM", "Sunday": "2:00 PM", "Tuesday": "2:00 PM", "Saturday": "2:00 PM", "Thursday": "2:00 PM", "Wednesday": "2:00 PM"}]'
            // ]);

            $store->open_hours()->create([
                'data' => json_decode('{"monday":{"start_time":"00:00:00","end_time":"23:30:00"},"tuesday":{"start_time":"00:00:00","end_time":"23:30:00"},"wednesday":{"start_time":"00:00:00","end_time":"23:30:00"},"thursday":{"start_time":"00:00:00","end_time":"23:30:00"},"friday":{"start_time":"00:00:00","end_time":"23:30:00"},"saturday":{"start_time":"00:00:00","end_time":"23:30:00"},"sunday":{"start_time":"00:00:00","end_time":"23:30:00"}}'),
            ]);

            if (!$store->store_fee) {
                $store->store_fee()->create();
            }

            $kitchen_email = str_replace("@","kitchen@",trim($store->email));
            $store->kitchen_locations()->create([
                'name' => 'Main Kitchen',
                'email' => $kitchen_email,
                'password' => Hash::make('password'),
                'is_main' => 1,
            ]);
        });
    }

    public function getPercentageFeeAttribute()
    {
        if ($this->store_fee) {
            return $this->store_fee->percentage_fee;
        }
        return 3.00;
    }

    public function getAdditionalFeeAttribute()
    {
        if ($this->store_fee) {
            return $this->store_fee->additional_fee;
        }
        return 0.30;
    }

    public function getTotalFee(float $amount)
    {
        return ($amount * $this->percentage_fee  / 100) + $this->additional_fee;
    }

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

    public function tables()
    {
        return $this->hasMany('App\Models\Table')->where('is_active', 1);
    }

    public function kitchen_locations()
    {
        return $this->hasMany('App\Kitchen');
    }

    public function products(){
        return $this->hasMany('App\Product', 'store_id', 'id');
    }

    public function addons(){
        return $this->hasMany('App\Models\Addon', 'store_id', 'id');
    }

    public function addonCategories(){
        return $this->hasMany('App\Models\AddonCategory', 'store_id', 'id');
    }

    public function addonCategoryItems(){
        return $this->hasMany('App\Models\AddonCategoryItem', 'store_id', 'id');
    }

    public function categories(){
        return $this->hasMany('App\Category', 'store_id', 'id');
    }

    public function waiter_shifts()
    {
        return $this->hasMany('App\WaiterShift', 'store_id');
    }

    public function open_hours()
    {
        return $this->hasOne('App\StoreOpenHour', 'store_id');
    }

    public function bank_details()
    {
        return $this->morphMany(BankDetail::class, 'detailable');
    }

    public function time_restrictions()
    {
        return $this->hasMany('App\TimeRestriction');
    }

    public function store_translations()
    {
        return $this->belongsToMany('App\Translation', 'store_translation');
    }

    public function isFrance()
    {
        return true;
    }

    public function store_fee()
    {
        return $this->hasOne('App\StoreFee');
    }
}
