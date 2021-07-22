<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class DeleteSubscribers extends BaseTestCase
{
    public function __construct() {
        $apikey = session("apikey");
        $group = session("group");
        $subscriber_id = "2537839";
        
        $response = Http::withHeaders([
            "Content-Type"=> "application/json"
        ])->post("127.0.0.1:8000/api/delete", [
            "id"=>$subscriber_id
        ]);
        echo $response;
    }
}
