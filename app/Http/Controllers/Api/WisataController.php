<?php

namespace App\Http\Controllers\Api;

ini_set('memory_limit', '256M');

use App\Helpers\DistanceMatrix;
use App\Http\Controllers\Controller;
use App\Models\Wisata;
use Illuminate\Http\Request;
use App\Helpers\Graph;
use App\Helpers\Helper;


class WisataController extends Controller
{
    protected $response, $e;

    public function _error($e)
    {
        return response()->json([
            'message' => $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine()
        ], 500);
    }

    public function getWisata(Request $request)
    {
        try {
            // Pagination Infinite Scroll
            $limit = $request->limit;
            $offset = $request->offset;
            $search = $request->search;
            $kategori = $request->kategori;
            $start_lat = $request->lat ?? '';
            $start_lng = $request->long ?? '';
            $data = Wisata::withCount('suka')->withAvg('riwayat', 'star')->with('kategori')->whereHas('kategori', function ($query) use ($kategori) {
                $query->where('nama', 'like', '%' . $kategori . '%');
            })->when($search, function ($query) use ($search) {
                return $query->where('nama', 'like', '%' . $search . '%');
            })->limit($limit)->offset($offset)->orderBy('id', 'desc')->get();

            // get distance
            if ($start_lat != '' && $start_lng != '') {
                $data->map(function ($item) use ($start_lat, $start_lng) {
                    $item->jarak = Helper::distance($start_lat, $start_lng, $item->lat, $item->lng, 'km');
                    return $item;
                });

                // sort by distance
                $data = $data->sortBy('jarak')->values()->all();
            }

            return response()->json($data, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }

    public function getWisataPopuler(Request $request)
    {
        try {
            $data = Wisata::withCount('suka')->with('kategori')->orderBy('suka_count', 'desc')->limit(5)->get();
            return response()->json($data, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }

    public function detailWisata(Request $request, $id)
    {
        try {
            $data = Wisata::where('id', $id)->with('kategori')->first();
            return response()->json($data, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }

    public function bellmandford2(Request $request)
    {
        $startLocation = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ];
        $endLocation = [
            'latitude' => $request->latitude2,
            'longitude' => $request->longitude2
        ];
        $apiKey = 'AIzaSyBEMiAdMOBk5hxM-B8oY9ckRYbsqVJmOSk';
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin={$startLocation['latitude']},{$startLocation['longitude']}&destination={$endLocation['latitude']},{$endLocation['longitude']}&units=units&mode=driving&key={$apiKey}";

        $response = json_decode(file_get_contents($url), true);

        if ($response['status'] === 'OK') {
            // Extract the routes data from the response
            $routes = $response['routes'][0]['legs'][0]['steps'];

            // Calculate the distances between each point on the route
            $distanceMatrix = new DistanceMatrix();
            $distances = [];

            foreach ($routes as $route) {
                $start = $route['start_location'];
                $end = $route['end_location'];
                $distances[] = $distanceMatrix->getDistances($start['lat'] . ',' . $start['lng'], $end['lat'] . ',' . $end['lng']);
            }

            // Find the index of the shortest distance
            $shortestDistanceIndex = array_keys($distances, min($distances))[0];

            // Extract the shortest route
            $path = [];

            foreach ($routes as $index => $route) {
                if ($index <= $shortestDistanceIndex) {
                    $path[] = [
                        'latitude' => $route['start_location']['lat'],
                        'longitude' => $route['start_location']['lng']
                    ];
                }

                if ($index === $shortestDistanceIndex) {
                    $path[] = [
                        'latitude' => $route['end_location']['lat'],
                        'longitude' => $route['end_location']['lng']
                    ];
                }
            }

            return response()->json(['data' => $path], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to find shortest route'
        ]);
    }

    private function getLocationKey($location)
    {
        if (is_object($location)) {
            return $location->lat . ',' . $location->lng;
        } elseif (is_array($location)) {
            return $location['lat'] . ',' . $location['lng'];
        }
        throw new \Exception('Invalid location object');
    }

    private function getLocationFromKey($key)
    {
        $coords = explode(',', $key);
        return (object) ['lat' => $coords[0], 'lng' => $coords[1]];
    }

    private function bellmanFordRumus($graph, $startNode)
    {
        $nodes = array_keys($graph);
        $distances = array_fill_keys($nodes, INF);
        $distances[$startNode] = 0;
        $previous = array_fill_keys($nodes, null);

        for ($i = 0; $i < count($nodes); $i++) {
            for ($j = 0; $j < count($nodes); $j++) {
                if (!isset($graph[$j])) {
                    continue;
                }
                foreach ($graph[$j] as $edge) {
                    $u = $edge['from'];
                    $v = $edge['to'];
                    $w = $edge['distance'];
                    if ($distances[$u] !== INF && $distances[$u] + $w < $distances[$v]) {
                        $distances[$v] = $distances[$u] + $w;
                        $previous[$v] = $u;
                    }
                }
            }
        }

        // check for negative-weight cycles
        for ($j = 0; $j < count($nodes); $j++) {
            if (!isset($graph[$j])) {
                continue;
            }
            foreach ($graph[$j] as $edge) {
                $u = $edge['from'];
                $v = $edge['to'];
                $w = $edge['distance'];

                if ($distances[$u] !== INF && $distances[$u] + $w < $distances[$v]) {
                    throw new \Exception('Graph contains a negative-weight cycle');
                }
            }
        }

        return ['distances' => $distances, 'previous' => $previous];
    }

    public function bellmandford4(Request $request)
    {
        $startLocation = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ];
        $endLocation = [
            'latitude' => $request->latitude2,
            'longitude' => $request->longitude2
        ];
        $apiKey = 'AIzaSyBEMiAdMOBk5hxM-B8oY9ckRYbsqVJmOSk';
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin={$startLocation['latitude']},{$startLocation['longitude']}&destination={$endLocation['latitude']},{$endLocation['longitude']}&alternatives=true&mode=driving&optimize:true&key={$apiKey}";

        $response = json_decode(file_get_contents($url), true);

        if ($response['status'] === 'OK') {
            $routes = $response['routes'];
            $map = array();
            foreach ($routes as $route) {
                $steps = $route["legs"][0]["steps"];
                $steps = array_map(function ($step) {
                    return array(
                        "start_location" => $step["start_location"],
                        "end_location" => $step["end_location"],
                        "distance" => $step["distance"],
                        "duration" => $step["duration"]
                    );
                }, $steps);

                $map[] = array(
                    "steps" => $steps,
                    "distance" => $route["legs"][0]["distance"],
                    "duration" => $route["legs"][0]["duration"]
                );
            }

            // Bellmand Ford Algorithm
            $distance = 0;
            $path = array();
            foreach ($map as $m) {
                if ($m["distance"]["value"] > $distance) {
                    $distance = $m["distance"]["value"];
                    $path = $m["steps"];
                }
            }

            $new_path = array();
            foreach ($path as $p) {
                $new_path[] = array(
                    "latitude" => $p["start_location"]["lat"],
                    "longitude" => $p["start_location"]["lng"],
                    "distance" => $p["distance"]["value"],
                );
            }


            return response()->json(['data' => $new_path], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to find shortest route'
        ]);
    }

    public function bellmandford6(Request $request)
    {
        $startLocation = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ];
        $endLocation = [
            'latitude' => $request->latitude2,
            'longitude' => $request->longitude2
        ];
        $apiKey = 'AIzaSyBEMiAdMOBk5hxM-B8oY9ckRYbsqVJmOSk';
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin={$startLocation['latitude']},{$startLocation['longitude']}&destination={$endLocation['latitude']},{$endLocation['longitude']}&alternatives=true&mode=driving&optimize:true&provideRouteAlternatives=true&key={$apiKey}";

        $response = json_decode(file_get_contents($url), true);

        dd($response, $url);

        if ($response['status'] === 'OK') {


            $graph = array();
            $legs = $response['routes'][0]['legs'];

            foreach ($legs as $leg) {
                $start_location = $leg['start_location'];
                $end_location = $leg['end_location'];
                $distance = $leg['distance']['value'];
                $graph[$start_location['lat'] . ',' . $start_location['lng']][] = array(
                    'end_location' => $end_location['lat'] . ',' . $end_location['lng'],
                    'distance' => $distance
                );
            }

            $source = $response['routes'][0]['legs'][0]['start_location']['lat'] . ',' . $response['routes'][0]['legs'][0]['start_location']['lng'];

            $distance = array();
            $predecessor = array();
            $nodes = array();
            foreach ($graph as $vertex => $adj) {
                $distance[$vertex] = INF;
                $predecessor[$vertex] = null;
                $nodes[] = $vertex;
                foreach ($adj as $array) {
                    $end_location = $array['end_location'];
                    $distance[$end_location] = INF;
                    $predecessor[$end_location] = null;
                    $nodes[] = $end_location;
                }
            }


            $distance[$source] = 0;
            for ($i = 0; $i < count($nodes) - 1; $i++) {
                foreach ($graph as $vertex => $adj) {
                    foreach ($adj as $array) {
                        $end_location = $array['end_location'];
                        $weight = 1;
                        $alt = $distance[$vertex] + $weight;
                        if ($alt < $distance[$end_location]) {
                            $distance[$end_location] = $alt;
                            $predecessor[$end_location] = $vertex;
                        }
                    }
                }
            }

            $shortest_distance = $distance[$response['routes'][0]['legs'][0]['end_location']['lat'] . ',' . $response['routes'][0]['legs'][0]['end_location']['lng']];

            return response()->json(['data' => $shortest_distance], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to find shortest route'
        ]);
    }

    public function bellmandfordNew(Request $request)
    {
        $startLocation = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ];
        $endLocation = [
            'latitude' => $request->latitude2,
            'longitude' => $request->longitude2
        ];
        $apiKey = 'AIzaSyBEMiAdMOBk5hxM-B8oY9ckRYbsqVJmOSk';
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin={$startLocation['latitude']},{$startLocation['longitude']}&destination={$endLocation['latitude']},{$endLocation['longitude']}&alternatives=true&mode=driving&optimize:true&key={$apiKey}";

        $response = json_decode(file_get_contents($url), true);


        if ($response['status'] === 'OK') {
            $routes = $response['routes'];

            $map = array_map(function ($route) {
                $steps = $route['legs'][0]['steps'];
                $steps = array_map(function ($step) {
                    return array(
                        "start_location" => $step['start_location'],
                        "end_location" => $step['end_location'],
                        "distance" => $step['distance'],
                        "duration" => $step['duration'],
                    );
                }, $steps);
                return $steps;
            }, $routes);

            $maps = array();
            array_map(function ($route) use (&$maps) {
                array_map(function ($step) use (&$maps) {
                    $maps[] = $step;
                }, $route);
            }, $map);

            $i = 0;

            $maps = array_map(function ($step) use (&$i) {
                $i++;
                return array(
                    "id" => "{$i}",
                    "start_location" => $step["start_location"],
                    "end_location" => $step["end_location"],
                    "distance" => $step["distance"],
                    "duration" => $step["duration"],
                );
            }, $maps);

            $starts = $maps[0]['start_location']['lat'] . "," . $maps[0]['start_location']['lng'];
            $ends = $maps[count($maps) - 1]['end_location']['lat'] . "," . $maps[count($maps) - 1]['end_location']['lng'];

            // filter duplicate start_location and end_location
            $maps = array_filter($maps, function ($obj) use ($maps) {
                return array_search(
                    $obj,
                    array_filter($maps, function ($item) use ($obj) {
                        return $item['start_location']['lat'] === $obj['start_location']['lat'] &&
                            $item['start_location']['lng'] === $obj['start_location']['lng'] &&
                            $obj['end_location']['lat'] === $item['end_location']['lat'] &&
                            $obj['end_location']['lng'] === $item['end_location']['lng'];
                    })
                );
            });

            $i = 0;

            $maps = array_map(function ($step) use (&$i) {
                $i++;
                return array(
                    "name" => "step-{$i}",
                    "source" => "{$step['start_location']['lat']},{$step['start_location']['lng']}",
                    "target" => "{$step['end_location']['lat']},{$step['end_location']['lng']}",
                    "lat" => $step["start_location"]["lat"],
                    "lng" => $step["start_location"]["lng"],
                    "weight" => $step["distance"]["value"],
                );
            }, $maps);

            $graphs = new Graph();

            $source = array();
            array_map(function ($step) use (&$source) {
                $source[] = $step['source'];
                $source[] = $step['target'];
            }, $maps);

            $source = array_unique($source);


            array_map(function ($step) use ($graphs) {
                $graphs->addVertex($step);
            }, $source);

            array_map(function ($step) use ($graphs) {
                // $graphs->add_node(new Node($step['name'], $step['lat'], $step['lng']));
                // $graphs->add_edge(new Edge($step['source'], $step['target'], $step['weight']));

                $name = $step['name'];
                $source_name = $step['source'];
                $target_name = $step['target'];
                $weight = $step['weight'];
                $lat = $step['lat'];
                $lng = $step['lng'];

                $graphs->addEdge($source_name, $target_name, $weight);
            }, $maps);
            // dd($starts);
            list($distances, $prev) = $graphs->bellmanFord($starts);

            $new_path = array();

            foreach ($prev as $vertex => $prev_vertex) {
                $new_path[] = $vertex;
            }

            $new_map = array();
            array_map(function ($step) use (&$new_map) {
                $data = explode(",", $step);
                $new_map[] = array(
                    "latitude" => (float)$data[0],
                    "longitude" => (float)$data[1]
                );
            }, $new_path);

            array_unshift($new_map, array(
                "latitude" => (float)$startLocation['latitude'],
                "longitude" => (float)$startLocation['longitude']
            ));

            return response()->json(['data' => $new_map], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to find shortest route'
        ]);
    }

    public function bellmandfordNew2(Request $request)
    {
        $startLocation = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ];
        $endLocation = [
            'latitude' => $request->latitude2,
            'longitude' => $request->longitude2
        ];
        $apiKey = 'AIzaSyBEMiAdMOBk5hxM-B8oY9ckRYbsqVJmOSk';
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin={$startLocation['latitude']},{$startLocation['longitude']}&destination={$endLocation['latitude']},{$endLocation['longitude']}&alternatives=true&mode=driving&optimize:true&key={$apiKey}";

        $response = json_decode(file_get_contents($url), true);


        if ($response['status'] === 'OK') {
            $routes = $response['routes'];
            $maps = array();
            $new_maps = array();

            $maps = array_map(function ($route) {
                $steps = $route['legs'][0]['steps'];
                $steps = array_map(function ($step) {
                    return array(
                        "start_location" => $step['start_location'],
                        "end_location" => $step['end_location'],
                        "distance" => $step['distance'],
                        "duration" => $step['duration'],
                    );
                }, $steps);
                return $steps;
            }, $routes);

            if (count($maps) > 1) {
                // bandingkan jarak terpendek antara 2 rute berdasarkan jarak
                $maps1 = $maps[0];
                for ($i = 1; $i < count($maps); $i++) {
                    $maps2 = $maps[$i];
                    $path_dump = array();
                    $total_perulangan = count($maps1) > count($maps2) ? count($maps1) : count($maps2);
                    for ($j = 0; $j < $total_perulangan; $j++) {
                        $terbesar = 0;
                        if ($terbesar == 0) {
                            if (isset($maps1[$j]) && isset($maps2[$j])) {
                                if ($maps1[$j]['distance']['value'] > $maps2[$j]['distance']['value']) {
                                    $terbesar = 1;
                                } else {
                                    $terbesar = 2;
                                }
                                $path_dump[] = $maps1[$j];
                            }
                        } else if ($terbesar == 1) {
                            $path_dump[] = $maps1[$j];
                        } else if ($terbesar == 2) {
                            $path_dump[] = $maps2[$j];
                        }
                    }
                    $path = $path_dump;
                }

                // ubah format array
                $new_maps = array_map(function ($route) {
                    return array(
                        "latitude" => $route['start_location']['lat'],
                        "longitude" => $route['start_location']['lng'],
                    );
                }, $path);

                $last_index = count($path) - 1;

                // add last location
                $new_maps[] = array(
                    "latitude" => $path[$last_index]['end_location']['lat'],
                    "longitude" => $path[$last_index]['end_location']['lng'],
                );


                // tambahkan start location dan end location
                array_unshift($new_maps, array(
                    "latitude" => (float)$startLocation['latitude'],
                    "longitude" => (float)$startLocation['longitude'],
                ));

                array_push($new_maps, array(
                    "latitude" => (float)$endLocation['latitude'],
                    "longitude" => (float)$endLocation['longitude'],
                ));


                return response()->json(['data' => $new_maps], 201);
            }

            // jika hanya 1 rute
            // ubah format array
            $new_maps = array_map(function ($route) {
                return array(
                    "latitude" => $route[0]['start_location']['lat'],
                    "longitude" => $route[0]['start_location']['lng'],
                );
            }, $maps);

            $last_index = count($maps) - 1;

            // add last location
            $new_maps[] = array(
                "latitude" => $maps[0][$last_index]['end_location']['lat'],
                "longitude" => $maps[0][$last_index]['end_location']['lng'],
            );


            // tambahkan start location dan end location
            array_unshift($new_maps, array(
                "latitude" => (float)$startLocation['latitude'],
                "longitude" => (float)$startLocation['longitude'],
            ));

            array_push($new_maps, array(
                "latitude" => (float)$endLocation['latitude'],
                "longitude" => (float)$endLocation['longitude'],
            ));


            return response()->json(['data' => $new_maps], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to find shortest route'
        ]);
    }

    public function bellmandford(Request $request)
    {
        $startLocation = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ];
        $endLocation = [
            'latitude' => $request->latitude2,
            'longitude' => $request->longitude2
        ];
        $apiKey = 'AIzaSyBEMiAdMOBk5hxM-B8oY9ckRYbsqVJmOSk';
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin={$startLocation['latitude']},{$startLocation['longitude']}&destination={$endLocation['latitude']},{$endLocation['longitude']}&alternatives=true&mode=driving&optimize:true&key={$apiKey}";

        dd($url);
        $response = json_decode(file_get_contents($url), true);

        if ($response['status'] === 'OK') {
            $routes = $response['routes'];

            // select shortest distance
            $distance = 99999999999;
            $route_min = array();
            foreach ($routes as $route) {
                if ($route['legs'][0]['distance']['value'] < $distance) {
                    $distance = $route['legs'][0]['distance']['value'];
                    $route_min = $route;
                }
            }

            $iterasi = array();
            $step_iterasi = array();
            foreach ($routes as $route) {
                $iterasi[] = count($route['legs'][0]['steps']);
                $step_iterasi[] = $route['legs'][0]['steps'];
            }


            dd($iterasi, $step_iterasi);

            $maps = array();
            $new_maps = array();

            $steps = $route_min['legs'][0]['steps'];
            $maps = array_map(function ($step) {
                return array(
                    "start_location" => $step['start_location'],
                    "end_location" => $step['end_location'],
                    "distance" => $step['distance'],
                    "duration" => $step['duration'],
                );
            }, $steps);

            // jika hanya 1 rute
            // ubah format array
            $new_maps = array_map(function ($route) {
                return array(
                    "latitude" => $route['start_location']['lat'],
                    "longitude" => $route['start_location']['lng'],
                );
            }, $maps);

            $last_index = count($maps) - 1;

            // add last location
            $new_maps[] = array(
                "latitude" => $maps[$last_index]['end_location']['lat'],
                "longitude" => $maps[$last_index]['end_location']['lng'],
            );


            // tambahkan start location dan end location
            array_unshift($new_maps, array(
                "latitude" => (float)$startLocation['latitude'],
                "longitude" => (float)$startLocation['longitude'],
            ));

            array_push($new_maps, array(
                "latitude" => (float)$endLocation['latitude'],
                "longitude" => (float)$endLocation['longitude'],
            ));


            return response()->json(['data' => $new_maps], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to find shortest route'
        ]);
    }
}
