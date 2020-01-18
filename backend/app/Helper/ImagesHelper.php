<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class ImagesHelper {

    static function GetImagePath($strAction, $id, $filename) {

        $default = URL::to('/assets/images/not-available.jpg');

        if($filename == '') {
            return $default;
        }

        $path = Config::get('app.IMAGE_BASE');

        $checkFile = public_path() . '/uploads';
        $checkFile = str_replace('frontend', 'engine', $checkFile);

        switch ($strAction) {
            case "users":
                $fullPath = $path . '/users/' . $id . '/' . $filename;
                $checkFile = $checkFile . '/users/' . $id . '/' . $filename;
                return is_file($checkFile) ? URL::to($fullPath) : $default;
                break;
            case "courses":
                $fullPath = $path . '/courses/' . $id . '/' . $filename;
                $checkFile = $checkFile . '/courses/' . $id . '/' . $filename;
                return is_file($checkFile) ? URL::to($fullPath) : $default;
                break;
            case "lessons":
                $fullPath = $path . '/lessons/' . $id . '/' . $filename;
                $checkFile = $checkFile . '/lessons/' . $id . '/' . $filename;
                return is_file($checkFile) ? URL::to($fullPath) : $default;
                break;
        }

        return $default;
    }

    static function uploadImage($strAction, $fieldInput, $id, $customPath = '', $inputFile = false) {

        if ($fieldInput == '') {
            return false;
        }

        if (is_object($fieldInput)) {
            $fileObj = $fieldInput;
        } else {
            if (!Input::hasFile($fieldInput)) {
                return false;
            }
            $fileObj = Input::file($fieldInput);
        }


        if ($fileObj->getClientSize() >= 2000000) {
            return false;
        }

        if ($inputFile == false) {
            $extensionExplode = explode('/', $fileObj->getMimeType()); // getting image extension
            unset($extensionExplode[0]);
            $extensionExplode = array_values($extensionExplode);
            $extension = $extensionExplode[0];
        } else {
            $extension = $fileObj->getClientOriginalExtension();
        }

        if (!in_array($extension, ['ppt','pptx','pdf','docx','doc','dotx','dot','dox','ppv','xlsx','xlsm','xml','txt','csv','xlc','jpg', 'jpeg', 'JPG', 'JPEG', 'png', 'PNG', 'gif', 'GIF'])) {
            return false;
        }

        $rand = rand() . date("YmdhisA");
        $fileName = 'aliensera' . '-' . $rand;
        $directory = '';

        $path = public_path() . '/uploads/';
        $path = str_replace('frontend', 'engine', $path);

        if ($strAction == 'users') {
            $directory = $path . 'users/' . $id;
        }

        if ($strAction == 'courses') {
            $directory = $path . 'courses/' . $id;
        }

        $fileName_full = $fileName . '.' . $extension;

        if ($directory == '') {
            return false;
        }

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        if ($fileObj->move($directory, $fileName_full)){
            return $fileName_full;
        }

        return false;
    }

    static function uploadVideo($strAction, $fieldInput, $id, $customPath = '', $inputFile = false) {

        if ($fieldInput == '') {
            return false;
        }

        if (is_object($fieldInput)) {
            $fileObj = $fieldInput;
        } else {
            if (!Input::hasFile($fieldInput)) {
                return false;
            }
            $fileObj = Input::file($fieldInput);
        }


        if ($fileObj->getClientSize() >= 200000000000) {
            return false;
        }
        $extension = $fieldInput->getClientOriginalExtension(); // getting image extension

        if (!in_array('.'.$extension, ['.3gp','.3g2','.avi','.uvh','.uvm','.uvu','.uvp','.uvs','.uaa','.fvt','.f4v','.flv','.fli','.h261','.h263','.h264','.jpgv','.m4v','.asf','.pyv','.wm','.wmx','.wmv','.wvx','.mj2','.mxu','.mpeg','.mp4','.ogv','.webm','.qt','.movie','.viv','.wav','.avi','.mkv'])) {
            return false;
        }

        $directory = '';

        $path = public_path() . '/uploads/';
        $path = str_replace('frontend', 'engine', $path);

        if ($strAction == 'lessons') {
            $directory = $path . 'lessons/' . $id;
        }

        $fileName_full = $fileObj->getClientOriginalName();
        if ($directory == '') {
            return false;
        }

        // if (!file_exists($directory)) {
        //     mkdir($directory, 0777, true);
        // }

        // if ($fileObj->move($directory, $fileName_full)){
        //     return [$fileName_full,str_replace('.'.$extension, "", $fileName_full)];
        // }
        return [$fileName_full,str_replace('.'.$extension, "", $fileName_full)];
        // return false;
    }

    static function deleteDirectory($dir) {
        system('rm -r ' . escapeshellarg($dir), $retval);
        return $retval == 0; // UNIX commands return zero on success
    }

}
