<?php

namespace App\Helpers;

// Buat class untuk merepresentasikan node
class Graph
{
        public $vertices = array(); // array untuk menyimpan verteks-verteks pada graf
        public $edges = array(); // array untuk menyimpan edges pada graf

        // fungsi untuk menambahkan verteks baru pada graf
        public function addVertex($v)
        {
                $this->vertices[] = $v;
        }

        // fungsi untuk menambahkan edge baru pada graf
        public function addEdge($src, $dst, $weight)
        {
                $this->edges[] = array('src' => $src, 'dst' => $dst, 'weight' => $weight);
        }

        // fungsi untuk mencari rute terdekat dari sebuah titik asal ke titik tujuan
        public function bellmanFord($start)
        {
                $dist = array(); // array untuk menyimpan jarak terpendek dari titik asal ke tiap-tiap verteks pada graf
                $prev = array(); // array untuk menyimpan informasi rute terpendek dari titik asal ke tiap-tiap verteks pada graf

                // inisialisasi jarak ke setiap verteks dengan nilai tak terhingga (kecuali titik asal sendiri, yang diatur ke 0)
                foreach ($this->vertices as $v) {
                        $dist[$v] = INF;
                        $prev[$v] = null;
                }
                $dist[$start] = 0;

                // melakukan relaksasi pada edges pada graf (melakukan iterasi sebanyak N-1 kali, dengan N adalah jumlah verteks pada graf)
                for ($i = 0; $i < count($this->vertices) - 1; $i++) {
                        foreach ($this->edges as $e) {
                                if ($dist[$e['src']] + $e['weight'] < $dist[$e['dst']]) {
                                        $dist[$e['dst']] = $dist[$e['src']] + $e['weight'];
                                        $prev[$e['dst']] = $e['src'];
                                }
                        }
                }

                // memeriksa apakah terdapat negative cycle pada graf
                foreach ($this->edges as $e) {
                        if ($dist[$e['src']] + $e['weight'] < $dist[$e['dst']]) {
                                return null; // jika terdapat negative cycle, kembalikan null
                        }
                }

                return array($dist, $prev); // jika tidak terdapat negative cycle, kembalikan array yang berisi jarak terpendek dan rute terpendek
        }
}
