<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Helper
{
    public static function title($value)
    {
        return Str::remove(' ', ucwords(Str::of($value)->replace('_', ' ')));
    }

    // get head title tabel
    public static function head($param)
    {
        return ucwords(str_replace('-', ' ', $param));
    }

    // replace spasi
    public static function replace($param)
    {
        return str_replace(' ', '', $param);
    }

    // button create
    public static function btn_create()
    {

        return '<a class="btn btn-primary" onclick="createForm()"> <i data-feather="plus-square"> </i>Add New</a>';
    }

    // get data from tabel
    public static function btn_action($edit, $delete, $id)
    {
        if ($edit) {

            $edit = '<li class="edit"> <a onclick="editForm(' . $id . ')"><i class="icon-pencil-alt"></i></a></li>';
        }
        if ($delete) {
            $delete = ' <li class="delete"><a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $id . '"
            title="Delete" class="deleteData"><i class="icon-trash"></i></a></li>';
        }
        return $edit . $delete;
    }

    // cek data menu role user
    public static function get_data($param)
    {
        $data = DB::table($param)->get();
        return isset($data) ? $data : null;
    }

    public static function arrayToString($param)
    {
        $data = null;
        foreach ($param as $v) {
            if ($data == null) {
                $data = $v;
            } else {
                $data = $data . "," . $v;
            }
        }
        return $data;
    }

    public static function toDateString($param)
    {
        #Thu, 20 Jan 2022 00:00:00 GMT
        $dates = explode(' ', $param);
        $day = str_replace(',', '', $dates[0]);
        $date = Helper::getHari($day) . ", " . $dates[1] . " " . $dates[2] . " " . $dates[3];
        return $date;
    }

    public static function toDateString2($param)
    {
        #2022-01-16 to  Senin, 16 Jan 2022
        $dates = explode('-', $param);
        $day = date('D', strtotime($param));
        $month = date('M', strtotime($param));
        $date = Helper::getHari($day) . ", " . $dates[2] . " " . $month . " " . $dates[0];
        return $date;
    }

    public static function getHari($hari)
    {
        switch ($hari) {
            case "Sun":
                $hari = "Minggu";
                break;
            case "Mon":
                $hari = "Senin";
                break;
            case "Tue":
                $hari = "Selasa";
                break;
            case "Wed":
                $hari = "Rabu";
                break;
            case "Thu":
                $hari = "Kamis";
                break;
            case "Fri":
                $hari = "Jumat";
                break;
            case "Sat":
                $hari = "Sabtu";
                break;
        }
        return isset($hari) ? $hari : null;
    }

    public static function getBulan($bulan)
    {
        switch ($bulan) {
            case "January":
                $bulan = "1";
                break;
            case "February":
                $bulan = "2";
                break;
            case "March":
                $bulan = "3";
                break;
            case "April":
                $bulan = "4";
                break;
            case "May":
                $bulan = "5";
                break;
            case "June":
                $bulan = "6";
                break;
            case "July":
                $bulan = "7";
                break;
            case "August":
                $bulan = "8";
                break;
            case "September":
                $bulan = "9";
                break;
            case "October":
                $bulan = "10";
                break;
            case "November":
                $bulan = "11";
                break;
            case "December":
                $bulan = "12";
                break;
        }
        return isset($bulan) ? $bulan : null;
    }

    public static function distance($lat1, $lon1, $lat2, $lon2, $unit = 'km')
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == 'KM') {
            return ($miles * 1.609344);
        } else if ($unit == 'MI') {
            return ($miles);
        } else {
            return ($dist);
        }
    }
}
