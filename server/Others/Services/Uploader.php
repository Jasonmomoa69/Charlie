<?php

namespace Server\Others\Services;

use Server\Models\Setting;

class Uploader
{

    public static function upload($key, $fullname = "")
    {



        $settings = Setting::where('id', 1)->first();


        if (!isset($_FILES[$key])) {
            return [
                'fullname' => '',
                'errors' => ['file is required'],
            ];
        }


        if ($settings->file_uploads == "disabled") {
            return [
                'fullname' => '',
                'errors' => ['file uploads disabled'],
            ];
        }


        $errors = [];


        if (strlen($fullname) == 0) {
            $fullname = time() . ".jpg";
        }


        try {
            move_uploaded_file($_FILES[$key]['tmp_name'], IMAGE_DIR . $fullname);
        } catch (\Exception $e) {
            $errors = [$e->getMessage()];
        }


        return [
            'errors' => $errors,
            'fullname' => $fullname,
        ];
    }
}
