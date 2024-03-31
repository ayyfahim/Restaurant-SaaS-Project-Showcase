<?php

namespace App\Http;

class AdyenClient
{
    public $service;

    function __construct() {
        $client = new \Adyen\Client();
        $client->setXApiKey(config('app.api_key'));
        $client->setEnvironment(\Adyen\Environment::TEST);

        $this->service = new \Adyen\Service\Checkout($client);
    }
}
