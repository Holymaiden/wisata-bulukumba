<?php

namespace App\Helpers;

// Buat class untuk merepresentasikan node
class Node
{
        public $name;
        public $lat;
        public $lng;

        function __construct($name, $lat, $lng)
        {
                $this->name = $name;
                $this->lat = $lat;
                $this->lng = $lng;
        }
}
