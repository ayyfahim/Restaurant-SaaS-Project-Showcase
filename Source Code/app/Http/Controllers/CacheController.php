<?php

namespace App\Http\Controllers;

use Artisan;
use Illuminate\Http\Request;

class CacheController extends Controller
{
    //
    public function migrate()
    {
        $exitCode = Artisan::call('migrate');
        return back()->with("MSG", "Database Succssfully  Updated")->with("TYPE", "success");
    }
    public function configCache()
    {
        $exitCode = Artisan::call('config:clear');
        return back()->with("MSG", "Config cache cleared")->with("TYPE", "success");
    }
    public function clearCache()
    {
        $exitCode = Artisan::call('cache:clear');
        return back()->with("MSG", "Application cache cleared")->with("TYPE", "success");
    }
    public function viewCache()
    {
        $exitCode = Artisan::call('view:clear');
        return back()->with("MSG", "View cache cleared")->with("TYPE", "success");
    }
    public function newValue()
    {
        $exitCode = Artisan::call('db:seed --class=TwilloSeeder');
        return back()->with("MSG", "Whatsapp Notification Successfully Updated")->with("TYPE", "success");
    }
    public function insertData()
    {
        $exitCode = Artisan::call('db:seed');
        return back()->with("MSG", "Seed Successfully Updated")->with("TYPE", "success");
    }
    public function privacyNew()
    {
        $exitCode = Artisan::call('db:seed --class=PrivacyPolicySeeder');
        return back()->with("MSG", "Privacy Policy Successfully Added")->with("TYPE", "success");
    }
}
