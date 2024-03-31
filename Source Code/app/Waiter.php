<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use App\Models\WaiterCall;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Model;

class Waiter extends Model implements JWTSubject
{
    protected $guarded = [];
    protected $hidden = [
        'password',
    ];

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

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($waiter) {
            $waiter->waiter_shift()->create([
                'data' => '[{"id": "time_to_start_working", "name": "Time to start working", "Friday": "2:00 PM", "Monday": "2:00 PM", "Sunday": "2:00 PM", "Tuesday": "2:00 PM", "Saturday": "2:00 PM", "Thursday": "2:00 PM", "Wednesday": "2:00 PM"}, {"id": "end_time_for_work", "name": "End time for work", "Friday": "2:00 PM", "Monday": "2:00 PM", "Sunday": "2:00 PM", "Tuesday": "2:00 PM", "Saturday": "2:00 PM", "Thursday": "2:00 PM", "Wednesday": "2:00 PM"}, {"id": "start_lunch_break_time", "name": "Start lunch break time", "Friday": "2:00 PM", "Monday": "2:00 PM", "Sunday": "2:00 PM", "Tuesday": "2:00 PM", "Saturday": "2:00 PM", "Thursday": "2:00 PM", "Wednesday": "2:00 PM"}, {"id": "end_lunch_break_time", "name": "End lunch break time", "Friday": "2:00 PM", "Monday": "2:00 PM", "Sunday": "2:00 PM", "Tuesday": "2:00 PM", "Saturday": "2:00 PM", "Thursday": "2:00 PM", "Wednesday": "2:00 PM"}]',
                'store_id' => $waiter['store_id']
            ]);
            // $store->open_hours()->create([
            //     'data' => '{"monday":{"start_time":"-1","end_time":"-1"},"tuesday":{"start_time":"-1","end_time":"-1"},"wednesday":{"start_time":"-1","end_time":"-1"},"thursday":{"start_time":"-1","end_time":"-1"},"friday":{"start_time":"-1","end_time":"-1"},"saturday":{"start_time":"-1","end_time":"-1"},"sunday":{"start_time":"-1","end_time":"-1"}}'
            // ]);
        });
    }

    public function store_tables()
    {
        return $this->belongsToMany('App\Models\Table', 'waiter_table');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store', 'store_id');
    }

    public function waiter_shift()
    {
        return $this->hasOne(WaiterShift::class, 'waiter_id', 'id');
    }

    public function waiter_calls()
    {
        return WaiterCall::where([
            ['store_id', '=', $this->store->id],
            ['is_completed', '=', 0],
        ])
            ->whereIn('table_name', $this->store_tables->pluck('id'))
            ->with('order', 'order.table.unpaid_orders')
            ->get()->sortByDesc('id');
    }

    public function order_requests()
    {
        return WaiterCall::where([
            ['store_id', '=', $this->store->id],
            // ['table_name', '=', $this->store_table->id],
            ['type', '=', '1'],
        ])
            ->orWhere([
                ['store_id', '=', $this->store->id],
                // ['table_name', '=', $this->store_table->id],
                ['type', '=', '2'],
            ])
            ->orWhere([
                ['store_id', '=', $this->store->id],
                // ['table_name', '=', $this->store_table->id],
                ['type', '=', '3'],
            ])
            ->whereIn('table_name', $this->store_tables->pluck('id'))
            ->get()->sortByDesc('id');
    }

    public function waiter_orders()
    {
        return $this->hasMany('App\WaiterOrder');
    }
}
