<?php

namespace App\Http\Controllers\Home;

use App\Application;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
   public function index(){
       $account_info = Application::all()->first();
       return view('Home.show_store',[
           'account_info' =>$account_info,
       ]);
   }
}
