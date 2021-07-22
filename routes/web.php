<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
$router->get('/', function() {
	return view('home'); //Home route
});
$router->get('/auth', function () {
    return view('auth'); //Authorization (Access) page
});
$router->get('/terminate', function (Request $request) {
    $request->session()->flush();
    DB::table("server")->where("id", "1")->update(["api_key"=>"0", "date_created"=>"0", "date_updated"=>"0", "subscriber_group"=>"0"]);
    //Logout user and reset the server table::
    return redirect("/auth");
});
$router->group(['middleware' => 'auth.api'], function() use($router) {
	$router->get('/home', [Requests::class, "subscribers"]); //Get subscribers (Home route)
});
$router->group(["prefix"=>"api"], function() use ($router) {
    $router->post('/auth', [Requests::class, "auth"]); //Authorize/Acquire API token
    $router->post('/create', [Requests::class, "create"]); //Create subscriber endpoint
    $router->post('/delete', [Requests::class, "delete"]); //Delete subscriber endpoint
    $router->post('/edit', [Requests::class, "edit"]); //Edit subscriber endpoint
});