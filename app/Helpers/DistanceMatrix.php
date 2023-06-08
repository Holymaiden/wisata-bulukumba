<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class DistanceMatrix
{
        private $client;
        private $apiKey;

        public function __construct()
        {
                $this->client = new Client([
                        'base_uri' => 'https://maps.googleapis.com/maps/api/distancematrix/',
                        'timeout'  => 10.0,
                ]);

                $this->apiKey = 'AIzaSyBEMiAdMOBk5hxM-B8oY9ckRYbsqVJmOSk';
        }

        public function getDistances($origins, $destinations)
        {
                $response = $this->client->get('json', [
                        'query' => [
                                'origins' => $origins,
                                'destinations' => $destinations,
                                'mode' => 'driving',
                                'units' => 'metric',
                                'key' => $this->apiKey,
                        ],
                ]);

                if ($response->getStatusCode() == 200) {
                        $json = json_decode($response->getBody(), true);
                        if ($json['status'] == 'OK') {
                                $rows = $json['rows'];
                                $distances = [];
                                foreach ($rows as $row) {
                                        $elements = $row['elements'];
                                        $distance = null;
                                        foreach ($elements as $element) {
                                                if ($element['status'] == 'OK') {
                                                        $value = $element['distance']['value'];
                                                        if ($distance == null || $value < $distance) {
                                                                $distance = $value;
                                                        }
                                                }
                                        }
                                        $distances[] = $distance;
                                }
                                return $distances;
                        }
                }
                return null;
        }
}
