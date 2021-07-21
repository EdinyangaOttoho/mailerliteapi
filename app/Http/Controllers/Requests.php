<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Requests extends Controller
{
    public function auth(Request $request) {
        //validate API key
        $validator = Validator::make($request->all(), [
            'apikey' => ['required']
        ]);

        if($validator->fails()) {
            return json_encode(["status"=>"error", "You must provide the API key field!"]);
        } else {
            $apikey = $request->apikey;
            //call the /subscribers' route to test the validity of the API Key provided by the user
            $this->request("/subscribers", [], "GET", $apikey);
            if (isset($this->api_response["error"])) {
                return json_encode(["status"=>"error", "message"=>"Invalid API credentials provided! Please retry!"]);
                //not a valid key (Not available/Not Authorized)
            }
            else {
                DB::table("server")->where("id", "1")->update(["api_key"=>$apikey, "date_created"=>time(), "date_updated"=>time()]);
                session(["apikey"=>$apikey]);
                //store key in session for current stream
                return json_encode(["status"=>"success", "message"=>"API key updated!"]);
            }
        }
    }
    public function request($endpoint, $data=[], $method="GET", $apikey) {
        //Helper function to tokenize requests appropriately (Laravel HTTP Client)
        $payload = null;
        //Payload (data) for request/API Call body
        if ($method == "GET") {
            $array = [];
            foreach ($data as $k=>$v) {
                array_push($array, $k."=".urlencode($v));
            }
            $payload = implode("&", $array);
            //Build URL parameters
            $this->api_response = json_decode(Http::withHeaders([
                "X-MailerLite-ApiKey"=>$apikey,
                "Content-Type"=> "application/json"
            ])->get(env("MAILERLITE_API_URL").$endpoint.$payload), 1);
        }
        else {
            $payload = $data;
            $this->api_response = json_decode(Http::withHeaders([
                "X-MailerLite-ApiKey"=>$apikey,
                "Content-Type"=> "application/json"
            ])->post(env("MAILERLITE_API_URL").$endpoint, $payload), 1);
        }
    }
}