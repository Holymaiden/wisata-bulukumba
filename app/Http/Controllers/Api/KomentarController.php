<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Komentar;
use Illuminate\Http\Request;

class KomentarController extends Controller
{
    protected $response, $title;

    public function _error($e)
    {
        $this->response = [
            'message' => $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine()
        ];
        return response()->json($this->response, 500);
    }

    public function getByWU(Request $request)
    {
        try {
            $data = Komentar::where('user_id', $request->user_id)->where('wisata_id', $request->wisata_id)->get();


            return response()->json([
                'message' => 'Successfully created user!',
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }

    public function create(Request $request)
    {
        try {
            $komentar = new Komentar();
            $komentar->user_id = $request->user_id;
            $komentar->wisata_id = $request->wisata_id;
            $komentar->komentar = $request->komentar;
            $komentar->save();

            return response()->json([
                'message' => 'Successfully created user!',
                'data' => $komentar,
            ], 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }
}
