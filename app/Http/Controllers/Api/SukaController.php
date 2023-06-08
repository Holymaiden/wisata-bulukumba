<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Suka;
use Illuminate\Http\Request;

class SukaController extends Controller
{
    protected $response;

    public function _error($e)
    {
        return response()->json([
            'message' => $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine()
        ], 500);
    }

    public function getSuka(Request $request)
    {
        try {
            // Pagination Infinite Scroll
            $limit = $request->limit;
            $offset = $request->offset;
            $search = $request->search;
            $user_id = $request->user_id;
            $data = Suka::where('user_id', $user_id)->with('wisata')->whereHas('wisata', function ($query) use ($search) {
                $query->where('nama', 'like', '%'.$search.'%');
            })->limit($limit)->offset($offset)->get();
            return response()->json($data, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }

    public function getSukaUser(Request $request)
    {
        try {
            // Pagination Infinite Scroll
            $user_id = $request->user_id;
            $id = $request->id;

            $data = Suka::where('user_id', $user_id)->where('wisata_id', $id)->get();
            return response()->json($data, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }

    public function createSuka(Request $request)
    {
        try {
            $data = new Suka();
            $data->wisata_id = $request->wisata_id;
            $data->user_id = $request->user_id;
            $data->save();
            
            return response()->json($data, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }

    public function deleteSuka(Request $request, $id)
    {
        try {
            $data = Suka::where('id', $id)->first();
            $data->delete();
            return response()->json($data, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }
}
