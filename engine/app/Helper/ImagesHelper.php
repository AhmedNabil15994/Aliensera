<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class ImagesHelper {

    static function GetImagePath($strAction, $id=null, $filename) {
        $path = Config::get('app.IMAGE_BASE');
        // $path = Config::get('app.IMAGE_BASE').'public/';

        $default = $path . 'assets/images/not-available.jpg';

        if($filename == '') {
            return $default;
        }

        $checkFile = public_path() . '/uploads';
        $checkFile = str_replace('engine', 'backend', $checkFile);

        switch ($strAction) {
            case "users":
                $fullPath = $path . 'uploads' . '/users/' . $id . '/' . $filename;
                $checkFile = $checkFile . '/users/' . $id . '/' . $filename;
                return is_file($checkFile) ? $fullPath : $default;
                break;
            case "courses":
                $fullPath = $path . 'uploads' . '/courses/' . $id . '/' . $filename;
                $checkFile = $checkFile . '/courses/' . $id . '/' . $filename;
                return is_file($checkFile) ? $fullPath : $default;
                break;
            case "lessons":
                $fullPath = $path . 'uploads' . '/lessons/' . $id . '/' . $filename;
                $checkFile = $checkFile . '/lessons/' . $id . '/' . $filename;
                return is_file($checkFile) ? $fullPath : $default;
                break;
            case "videos":
                $fullPath = $path . 'uploads' . '/videos/' . $id . '/' . $filename;
                $checkFile = $checkFile . '/videos/' . $id . '/' . $filename;
                return is_file($checkFile) ? $fullPath : $default;
                break;                
            case "chat":
                $fullPath = $path . 'uploads' . '/chat/' . $filename;
                $checkFile = $checkFile . '/chat/' . $filename;
                return is_file($checkFile) ? $fullPath : $default;
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

        $path = realpath(base_path().'/' . '../backend/public/uploads/');

        if ($strAction == 'users') {
            $directory = $path . '/users/' . $id;
        }

        if ($strAction == 'courses') {
            $directory = $path . '/courses/' . $id;
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

    static function uploadChatAttachment($strAction, $fieldInput, $customPath = '', $inputFile = false) {

        if ($fieldInput == '') {
            return false;
        }
        $fileType = '';
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

        if (!in_array('.'.$extension, ['.pdf','.png','.jpg','.jpeg'])) {
            return false;
        }

        if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' ){
            $fileType = 2;
        }else{
            $fileType = 1;
        }
        
        $rand = rand() . date("YmdhisA");
        $fileName = 'aliensera' . '-' . $rand.'.'.$extension;
        $directory = '';

        $path = realpath(base_path().'/' . '../backend/public/uploads/');

        if ($strAction == 'chat') {
            $directory = $path . '/chat/';
        }

        $fileName_full = $fileObj->getClientOriginalName();
        if ($directory == '') {
            return false;
        }

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        if ($fileObj->move($directory, $fileName)){
            return [$fileName,str_replace('.'.$extension, "", $fileName_full),$fileType];
        }

        return false;
    }



    static function deleteDirectory($dir) {
        system('rm -r ' . escapeshellarg($dir), $retval);
        return $retval == 0; // UNIX commands return zero on success
    }

}
