<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

trait Uploadable
{

    public function uploadFile($image, $path, $file_old)
    {
        $tgl = date('Ymd');
        $file = array('file' => $image);
        $rules = array('file' => 'mimes:png,jpg,jpeg,webp|max:2048');
        $validator = Validator::make($file, $rules);

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        if ($validator->fails() or $image == null) {
            $fileName = $file_old == '' ? null : $file_old;
        } else {
            $extension = strstr($image->getClientOriginalName(), '.');
            $uniq = uniqid();
            $fileName = $tgl . "_" . $uniq . $extension;
            $image->move($path, $fileName);
            $this->deleteFile($file_old, $path);
        }
        return $fileName;
    }

    public function deleteFile($image, $path)
    {
        File::delete($path . $image);
    }
}
