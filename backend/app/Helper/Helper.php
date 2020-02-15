<?php

class Helper
{

    static function formatDate($date, $format = "Y-m-d h:i:s", $unix = false){
        if ($date == null) {
            return '';
        }
        $date = str_replace("," , '' , $date);
        $FinalDate = $unix != false ? gmdate($format, $date) : date($format, strtotime($date));
        return $FinalDate != '1970-01-01 12:00:00' ? $FinalDate : null;
    }

    static function formatDateForDisplay($date, $withTime = false){
        if($date == null || $date == "0000-00-00 00:00:00" || $date == "0000-00-00"){
            return '';
        }

        $time = $withTime == true ? " H:i" : '';
        $format = "d M, Y" . $time;
        return date($format, strtotime($date));
//        return self::setClientTimeZone($date, $format);
    }

    static function formatDateCustom($date, $format = "Y-m-d H:i:s", $custom = false) {
        if($date == null || $date == "0000-00-00 00:00:00" || $date == "0000-00-00" || $date == ""){
            return '';
        }

        $date = str_replace("," , '' , $date);

        $FinalDate = date($format, strtotime($date));

        if ($format == '24') {
            $FinalDate = date('Y-m-d', strtotime($date)) . ' 24:00:00';
        }

        if ($custom != false) {
            $FinalDate = date($format, strtotime($custom, strtotime($date)));
        }

        return $FinalDate != '1970-01-01 12:00:00' ? $FinalDate : null;
    }

    static function setClientTimeZone($date, $format = "Y-m-d H:i:s") {
        $ip = "156.223.146.199";  //$_SERVER['REMOTE_ADDR']
//        $ip = \Request::ip();  //$_SERVER['REMOTE_ADDR']

        $ipInfo = file_get_contents('https://ipapi.co/' . $ip . '/timezone/');
//        $ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
//        $ipInfo = json_decode($ipInfo);

        if ($ipInfo != "Undefined") {
            date_default_timezone_set($ipInfo);
        }

//        if ($ipInfo->status == "success") {
//            $timezone = $ipInfo->timezone;
//            date_default_timezone_set($timezone);
//        }

        return date($format, strtotime($date));
    }

    static function getCountDays($from, $to){
        $from = new DateTime($from);
        $to = new DateTime($to);
        $result = $to->diff($from)->format("%a");
        return (int) $result;
    }

    static function dateRanges($from, $to, $step = '+1 day', $output_format = 'Y-m-d') {
        $dates = array();
        $current = strtotime($from);
        $from = strtotime($to);

        while( $current <= $from ) {
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

    static function fixPaginate($url, $key) {
        if(strpos($key , $url) == false){
            $url = preg_replace('/(.*)(?)' . $key . '=[^&]+?(?)[0-9]{0,4}(.*)|[^&]+&(&)(.*)/i', '$1$2$3$4$5$6$7$8$9$10$11$12$13$14$15$16$17$18$19$20', $url . '&');
            $url = substr($url, 0, -1);
            return $url ;
        }else{
            return $url;
        }
    }

    Static function GeneratePagination($source){
        $uri = \Input::getUri();
        $count = count($source);
        $total = $source->total();
        $lastPage = $source->lastPage();
        $currentPage = $source->currentPage();

        $data = new \stdClass();
        $data->count = $count;
        $data->total_count = $total;
        $data->current_page = $currentPage;
        $data->last_page = $lastPage;
        $next = $currentPage + 1;
        $prev = $currentPage - 1;

        $newUrl = self::fixPaginate($uri, "page");

        if(preg_match('/(&)/' , $newUrl) != 0 || strpos($newUrl , '?') != false ){
            $separator = '&';
        }else{
            $separator = '?';
        }

        if($currentPage !=  $lastPage ){
            $link = str_replace('&&' , '&' , $newUrl . $separator. "page=". $next);
            $link = str_replace('?&' , '?' , $link);
            $data->next = $link;
            if($currentPage == 1){
                $data->prev = "";
            }else{
                $link = str_replace('&&' , '&' , $newUrl . $separator. "page=". $prev);
                $link = str_replace('?&' , '?' , $link);
                $data->prev = $link ;
            }
        }else{
            $data->next = "";
            if($currentPage == 1){
                $data->prev = "";
            }else{
                $link = str_replace('&&' , '&' , $newUrl . $separator. "page=". $prev);
                $link = str_replace('?&' , '?' , $link);
                $data->prev = $link ;
            }
        }

        return $data;
    }

    static function checkRules($rule){
        if(IS_ADMIN == 1){
            return true;
        }

        $explodeRule = explode(',', $rule);

        /** Sections Rule (array) && User Permission (array) */
        $containsSearch = count(array_intersect($explodeRule, (array) PERMISSIONS)) > 0;
        if($containsSearch == true){
            return true;
        }

        return false;
    }

    static function globalDelete($dataObj) {
        if ($dataObj == null) {
            return response()->json(\TraitsFunc::ErrorMessage('Not Found'));
        }

        $dataObj->deleted_by = USER_ID;
        $dataObj->deleted_at = DATE_TIME;
        $dataObj->save();

        $data['status'] = \TraitsFunc::SuccessResponse("Deleted Successfully");
        return response()->json($data);
    }

    static function globalRestore($dataObj) {
        if ($dataObj == null) {
            return response()->json(\TraitsFunc::ErrorMessage('Not Found'));
        }

        $dataObj->deleted_by = null;
        $dataObj->deleted_at = null;
        $dataObj->save();

        $data['status'] = \TraitsFunc::SuccessResponse("Restored Successfully");
        return response()->json($data);
    }
}
