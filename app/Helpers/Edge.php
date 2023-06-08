<?php

namespace App\Helpers;

// Buat class untuk merepresentasikan node
class Edge
{
        public $source;
        public $target;
        public $weight;

        function __construct($source, $target, $weight)
        {
                $this->source = $source;
                $this->target = $target;
                $this->weight = $weight;
        }
}
