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
                session(["apikey"=>$apikey]);
                $this->request("/groups", [], "GET", $apikey);
                $group_id = $this->api_response[0]["id"];//Get default group for Account
                session(["group"=>$group_id]);
                DB::table("server")->where("id", "1")->update(["api_key"=>$apikey, "date_created"=>time(), "date_updated"=>time(), "subscriber_group"=>$group_id]);
                //store key in session for current stream
                return json_encode(["status"=>"success", "message"=>"API key updated!"]);
            }
        }
    }
    public function create(Request $request) {
        //validate request fields
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'country' => ['required', 'string'],
            'name' => ['required', 'string'],
        ]);

        if($validator->fails()) {
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                return json_encode(["status"=>"error", "Invalid email provided!"]);
                die;
            }
            return json_encode(["status"=>"error", "No field must be left blank"]);
        } else {
            $apikey = session("apikey");
            $group = session("group");
            
            $email = $request->email;
            $name = $request->name;
            $country = $request->country;

            //Get subscribers from default group
            $this->request("/groups/$group/subscribers", [], "GET", $apikey);
            $response = $this->api_response;
            $data = ($response == null)?[]:$response;

            $check_availability = 0;
            //Check if email already exists in the group by iteration

            foreach ($data as $i) {
                if ($i["email"] == $email) {
                    $check_availability++;
                    break;
                }
            }
            if ($check_availability == 0) {
                //Not available, add to the group
                $this->request("/groups/$group/subscribers", ["name"=>$name, "email"=>$email, "fields"=>["country"=>$country]], "POST", $apikey);
                return json_encode([
                    "status"=>"success",
                    "message"=>"Subscriber created successfully!"
                ]);
            }
            else {
                //Subscriber exists. Notify user
                return json_encode([
                    "status"=>"error",
                    "message"=>"A subscriber already exists with that email address!"
                ]);
            }
        }
    }
    public function edit(Request $request) {
        //validate request fields
        $validator = Validator::make($request->all(), [
            'country' => ['required', 'string'],
            'name' => ['required', 'string'],
            'id' => ['required']
        ]);

        if($validator->fails()) {
            return json_encode(["status"=>"error", "No field must be left blank"]);
        } else {
            $apikey = session("apikey");
            $group = session("group");
            $name = $request->name;
            $country = $request->country;
            $id = $request->id;

            /*Since there is no edit endpoint:
                Get previous subscriber data
            */
            $this->request("/groups/$group/subscribers/$id", [], "GET", $apikey);
            $response = $this->api_response;
            $email = $response["email"];
            $this->request("/groups/$group/subscribers/$id", [], "DELETE", $apikey);
            //Delete previous data after retrieval

            //Recreate the subcriber!
            $this->request("/groups/$group/subscribers", ["name"=>$name, "email"=>$email, "fields"=>["country"=>$country]], "POST", $apikey);
            return json_encode([
                "status"=>"success",
                "message"=>"Subscriber #$id editted successfully!"
            ]);
            //$this->request("/subscribers", [], "GET", $apikey);
        }
    }
    public function delete(Request $request) {
        //validate request fields
        $validator = Validator::make($request->all(), [
            'id' => ['required']
        ]);

        if($validator->fails()) {
            return json_encode(["status"=>"error", "No field must be left blank"]);
        } else {
            $apikey = session("apikey");
            $group = session("group");
            $id = $request->id;
            $data = $this->request("/groups/$group/subscribers/$id", [], "DELETE", $apikey);
            //Delete subscriber endpoint!
            return json_encode([
                "status"=>"success",
                "message"=>"Subscriber deleted successfully!"
            ]);
        }
    }
    public function subscribers(Request $request) {
        $apikey = session("apikey");
        $group = session("group");

        $this->request("/groups/$group/subscribers", [], "GET", $apikey);
        //Get subscribers
        $response = $this->api_response;
        $data = ($response == null)?[]:$response;

        //Parse data to the frontend template
        return view('home')->with("subscribers", $data);
    }
    public function request($endpoint, $data=[], $method="GET", $apikey) {
        //Helper function to tokenize requests appropriately (Laravel HTTP Client)
        $payload = null;
        $method = strtoupper($method);
        //Payload (data) for request/API Call body
        if ($method == "GET") {
            //GET Method
            //Build URL parameters
            $this->api_response = json_decode(Http::withHeaders([
                "X-MailerLite-ApiKey"=>$apikey,
                "Content-Type"=> "application/json"
            ])->get(env("MAILERLITE_API_URL").$endpoint), 1);
        }
        else if ($method == "POST") {
            //POST Method
            $payload = $data;
            $this->api_response = json_decode(Http::withHeaders([
                "X-MailerLite-ApiKey"=>$apikey,
                "Content-Type"=> "application/json"
            ])->post(env("MAILERLITE_API_URL").$endpoint, $payload), 1);
        }
        else if ($method == "DELETE") {
            //DELETE Method
            $payload = $data;
            $this->api_response = json_decode(Http::withHeaders([
                "X-MailerLite-ApiKey"=>$apikey,
                "Content-Type"=> "application/json"
            ])->delete(env("MAILERLITE_API_URL").$endpoint), 1);
        }
    }
}