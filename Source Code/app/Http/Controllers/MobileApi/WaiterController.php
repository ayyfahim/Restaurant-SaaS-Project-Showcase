<?php

namespace App\Http\Controllers\MobileApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Waiter;
use App\Models\WaiterCall;
use App\Models\Setting;
use App\Models\Addon;
use App\Product;
use Arr;

class WaiterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:waiterApi');
    }

    public function getWaiterCalls()
    {
        return array_values(auth()->user()->waiter_calls()->toArray());
    }

    public function getWaiterShifts()
    {
        return json_decode(auth()->user()->waiter_shift->data);
    }

    public function createOrder()
    {
    }
}
