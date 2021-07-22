<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class EditSubscribers extends BaseTestCase
{
    public function __construct() {
        $apikey = session("apikey");
        $group = session("group");
        $subscriber_id = "2537839";

        $name="Test name";
        $email="test@email.com";
        $country="Nigeria";

        $response = Http::withHeaders([
            "Content-Type"=> "application/json"
        ])->post("127.0.0.1:8000/api/edit", [
            "id"=>$subscriber_id,
            "name"=>$name,
            "email"=>$email,
            "country"=>$country
        ]);
        echo $response;
    }
}
