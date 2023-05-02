<?php
namespace App\Services ;

use Symfony\Component\HttpFoundation\Response;

class Servicegeo {


    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

// #[Route('/get-address/{lat}/{lng}', name: 'getadress')]
//     public function geocoding($lat, $lng)
//     {
//         $apiKey = 'pk.eyJ1IjoiZmplcmJpIiwiYSI6ImNrdWp6bXJhdTE4MGwyd215bzhpb3c0OGYifQ.jW0ZovMg20DoAaiOtGkPhg';
//         $url = sprintf('https://api.mapbox.com/geocoding/v5/mapbox.places/%s,%s.json?access_token=%s', $lng, $lat, $apiKey);
        
//         $response = file_get_contents($url);
//         $data = json_decode($response, true);
        
//         if (empty($data['features'][0]['place_name'])) {
//             throw new \Exception('Address not found');
//         }
//         console.log($data) ; 

//         return new Response($data['features'][0]['place_name']);
//     }

}