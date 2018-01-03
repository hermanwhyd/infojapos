<?php

namespace App\Http\Controllers;

use DB;
use \Datetime;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // test
    public function testConnection() {
        $message = "Success";
        try {
            DB::connection()->getPdo();
            if(DB::connection()->getDatabaseName()) {
                $message = "Yes! Successfully connected to the DB: " . DB::connection()->getDatabaseName();
            }
        } catch (\Exception $e) {
            $message = "Could not connect to the database.  Please check your configuration.";
        }
        
        return ["Status" => "Success", "Message" => $message];
    }

    public function getSQLTime() {
        $message = "Success";
        try {
            DB::connection()->getPdo();
            if(DB::connection()->getDatabaseName()) {
                $message = DB::select("select now()");
            }
        } catch (\Exception $e) {
            $message = "Could not connect to the database.  Please check your configuration.";
        }
        
        return ["Status" => "Success", "Message" => $message];
    }

    public function getPHPTime(Request $request) {
        $message = "Success";
        $date = DateTime::createFromFormat('d-m-Y', $request->input('timestamp'));
        $message = $date;
        
        return ["Status" => "Success", "InputTimestamp" => $request->input('timestamp'), "Message" => $message];
    }
}
