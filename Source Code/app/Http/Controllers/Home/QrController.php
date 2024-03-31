<?php

namespace App\Http\Controllers\Home;

use App\Application;
use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class QrController extends Controller
{
    public function print($view_id)
    {
        if (Store::all()->where('view_id', '=', $view_id)->count()==0) {
            abort('404');
        }
        $account_info = Application::all()->first();

        $data = Store::all()->where('view_id', '=', $view_id)->first();
        return view('Home.print_qr', [
            'data'=>$data,
            'account_info'=>$account_info
        ]);
    }

    public function tblprint($view_id, $table_number)
    {
        $data = Store::all()->where('view_id', '=', $view_id)->first();

        if (!$data) {
            abort('404');
        }

        $account_info = Application::all()->first();

        $table = $data->tables()->where('table_number', $table_number)->first();
        // $data['table_no']=$table_no;
        // $data['table_id']=$table->id;

        return view('Home.print_tblqr', [
            'data'=> $data,
            'table'=> $table,
            'account_info'=> $account_info
        ]);
    }
}
