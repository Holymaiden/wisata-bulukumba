<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $response, $title;

    public function __construct()
    {
        $this->title = 'dashboard';
    }

    public function _error($e)
    {
        $this->response = [
            'message' => $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine()
        ];
        return view('errors.message', ['message' => $this->response]);
    }

    public function index()
    {
        try {
            $title = $this->title;


            return view('admin.' . $title, compact('title'));
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }
}
