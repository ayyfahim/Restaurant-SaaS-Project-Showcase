<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WaiterShift extends Model
{
    protected $guarded = [];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'data' => '[{"id": "time_to_start_working", "name": "Time to start working", "Friday": "2:00 PM", "Monday": "2:00 PM", "Sunday": "2:00 PM", "Tuesday": "2:00 PM", "Saturday": "2:00 PM", "Thursday": "2:00 PM", "Wednesday": "2:00 PM"}, {"id": "end_time_for_work", "name": "End time for work", "Friday": "2:00 PM", "Monday": "2:00 PM", "Sunday": "2:00 PM", "Tuesday": "2:00 PM", "Saturday": "2:00 PM", "Thursday": "2:00 PM", "Wednesday": "2:00 PM"}, {"id": "start_lunch_break_time", "name": "Start lunch break time", "Friday": "2:00 PM", "Monday": "2:00 PM", "Sunday": "2:00 PM", "Tuesday": "2:00 PM", "Saturday": "2:00 PM", "Thursday": "2:00 PM", "Wednesday": "2:00 PM"}, {"id": "end_lunch_break_time", "name": "End lunch break time", "Friday": "2:00 PM", "Monday": "2:00 PM", "Sunday": "2:00 PM", "Tuesday": "2:00 PM", "Saturday": "2:00 PM", "Thursday": "2:00 PM", "Wednesday": "2:00 PM"}]'
    ];

    public function waiter()
    {
        return $this->belongsTo('App\Waiter', 'waiter_id');
    }
}
