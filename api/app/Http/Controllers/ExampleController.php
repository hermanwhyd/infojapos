<?php

namespace App\Http\Controllers;

use DB;


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
}
