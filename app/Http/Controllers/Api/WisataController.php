<?php

namespace App\Http\Controllers\Api;

ini_set('memory_limit', '256M');

use App\Helpers\DistanceMatrix;
use App\Http\Controllers\Controller;
use App\Models\Wisata;
use Illuminate\Http\Request;
use App\Helpers\Edge;
use App\Helpers\Graph;
use App\Helpers\Node;
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

            $apiKey = '';
            if ($start_lat != '' && $start_lng != '') {
                $data->map(function ($item) use ($start_lat, $start_lng, $apiKey) {
                    // $item->jarak = Helper::distance($start_lat, $start_lng, $item->lat, $item->lng, 'km');
                    // return $item;

                    $url = "https://maps.googleapis.com/maps/api/directions/json?origin={$start_lat},{$start_lng}&destination={$item->lat},{$item->lng}&alternatives=true&mode=driving&optimize:true&key={$apiKey}";

                    $response = json_decode(file_get_contents($url), true);


                    if ($response['status'] === 'OK') {
                        $routes = $response['routes'];

                        // select shortest distance
                        usort($routes, function ($a, $b) {
                            return $a['legs'][0]['distance']['value'] - $b['legs'][0]['distance']['value'];
                        });

                        $item->jarak = $routes[0]['legs'][0]['distance']['text'];
                        return $item;
                    }
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
        $apiKey = '';
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin={$startLocation['latitude']},{$startLocation['longitude']}&destination={$endLocation['latitude']},{$endLocation['longitude']}&alternatives=true&mode=driving&optimize:true&key={$apiKey}";

        $response = json_decode(file_get_contents($url), true);


        if ($response['status'] === 'OK') {
            $routes = $response['routes'];

            // select shortest distance
            $distance = [];
            usort($routes, function ($a, $b) {
                return $a['legs'][0]['distance']['value'] - $b['legs'][0]['distance']['value'];
            });

            $new_maps = [];
            for ($i = 0; $i < count($routes); $i++) {
                $new_maps[] = Helper::toArrayDistance($startLocation, $endLocation, $routes[$i]['legs'][0]['steps']);
                $distance[] = $routes[$i]['legs'][0]['distance']['text'];
            }

            $jarak = Helper::distance($startLocation['latitude'], $startLocation['longitude'], $endLocation['latitude'], $endLocation['longitude'], 'KM');


            return response()->json(['data' => ['route' => $new_maps, 'distance' => $distance, 'jarak' => $jarak]], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to find shortest route'
        ]);
    }
}
