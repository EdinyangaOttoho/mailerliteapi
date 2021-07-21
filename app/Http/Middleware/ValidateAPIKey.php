<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ValidateAPIKey
{
    //Middleware to authorize API Key access for routes
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->exists("apikey")) {
            return $next($request);//A valid session is already on!
        }
        else {
            $api = DB::table("server")->where("id", "1")->first(); //Check table first
            if ($api->api_key == "0") {
                return redirect("/auth");//No present key!
            }
            else {
                session(["apikey"=>$api->api_key]);//set session to database API key
                return $next($request);
            }
        }
    }
}
