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

// Buat class untuk merepresentasikan edge
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

// Buat class untuk merepresentasikan graph
class Graph
{
        public $nodes = array();
        public $edges = array();

        function add_node($node)
        {
                array_push($this->nodes, $node);
        }

        function add_edge($edge)
        {
                array_push($this->edges, $edge);
        }

        function bellman_ford($source)
        {
                $distances = array();
                $predecessors = array();
                foreach ($this->nodes as $node) {
                        $distances[$node->name] = INF;
                        $predecessors[$node->name] = null;
                }
                $distances[$source->name] = 0;

                // Relax edges repeatedly
                for ($i = 1; $i <= count($this->nodes) - 1; $i++) {
                        foreach ($this->edges as $edge) {
                                $u = $edge->source;
                                $v = $edge->target;
                                $w = $edge->weight;
                                if ($distances[$u->name] + $w < $distances[$v->name]) {
                                        $distances[$v->name] = $distances[$u->name] + $w;
                                        $predecessors[$v->name] = $u->name;
                                }
                        }
                }

                // Check for negative-weight cycles
                foreach ($this->edges as $edge) {
                        $u = $edge->source;
                        $v = $edge->target;
                        $w = $edge->weight;
                        if ($distances[$u->name] + $w < $distances[$v->name]) {
                                throw new \Exception('Graph contains a negative-weight cycle');
                        }
                }

                return array($distances, $predecessors);
        }

        function shortest_path($source_name, $target_name)
        {
                $source = null;
                $target = null;
                foreach ($this->nodes as $node) {
                        if ($node->name === $source_name) {
                                $source = $node;
                        }
                        if ($node->name === $target_name) {
                                $target = $node;
                        }
                }
                if ($source === null || $target === null) {
                        throw new \Exception('Node not found');
                }

                list($distances, $predecessors) = $this->bellman_ford($source);

                $path = array();
                $node = $target->name;
                while ($node !== $source->name) {
                        array_unshift($path, $node);
                        $node = $predecessors[$node];
                        if ($node === null) {
                                throw new \Exception('No path found');
                        }
                }
                array_unshift($path, $source->name);

                return array($path, $distances[$target->name]);
        }
}
