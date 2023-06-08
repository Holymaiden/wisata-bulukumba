<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Riwayat;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    protected $response;

    public function _error($e)
    {
        return response()->json([
            'message' => $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine()
        ], 500);
    }

    public function getRiwayat(Request $request)
    {
        try {
            // Pagination Infinite Scroll
            $limit = $request->limit;
            $offset = $request->offset;
            $search = $request->search;
            $user_id = $request->user_id;
            $data = Riwayat::with('wisata')->whereHas('wisata', function ($query) use ($search) {
                $query->where('nama', 'like', '%'.$search.'%');
            })->where('user_id', $user_id)->limit($limit)->offset($offset)->get();
            return response()->json($data, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }

    public function detailRiwayat(Request $request, $id)
    {
        try {
            $data = Riwayat::where('id', $id)->first();
            return response()->json($data, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }

    public function createRiwayat(Request $request)
    {
        try {
            $data = new Riwayat();
            $data->wisata_id = $request->wisata_id;
            $data->user_id = $request->user_id;
            $data->star = 0;
            $data->save();
            return response()->json($data, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }

    public function updateRiwayat(Request $request, $id)
    {
        try {
            $data = Riwayat::where('id', $id)->first();
            $data->star = $request->star;
            $data->save();
            return response()->json($data, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }

    public function deleteRiwayat(Request $request, $id)
    {
        try {
            $data = Riwayat::where('id', $id)->first();
            $data->delete();
            return response()->json($data, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }
}
