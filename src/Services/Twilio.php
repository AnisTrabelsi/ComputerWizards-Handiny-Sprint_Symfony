<?php
// src/Service/Twilio.php

namespace App\Service;

use Twilio\Rest\Client;

class Twilio
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function sendSms(string $to, string $message)
    {
        $message = $this->client->messages->create(
            $to,
            array(
                'from' => '++15673716202', // your Twilio phone number
                'body' => $message,
            )
        );

        return $message;
    }
}
