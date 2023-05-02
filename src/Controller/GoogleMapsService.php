<?php

namespace App\Controller;

use GuzzleHttp\Client;

class GoogleMapsService
{
    private $client;
    private $apiKey = 'AIzaSyDGpvYwYwc1YEGqAFUoatv4p3fFmFZbbkY' ;
    private $baseUrl = 'https://maps.googleapis.com/maps/api';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client(['base_uri' => $this->baseUrl]);
    }

    public function getAddress($lat, $lng)
    {
        $response = $this->client->request('GET', 'geocode/json', [
            'query' => [
                'latlng' => $lat . ',' . $lng,
                'key' => $this->apiKey,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if (!isset($data['results'][0]['formatted_address'])) {
            throw new \Exception('Address not found');
        }

        return $data['results'][0]['formatted_address'];
    }
}
