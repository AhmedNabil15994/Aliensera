<?php

class FireBase {
    protected $fcmKey;

    function __construct() {
        $this->key = "AAAApxE4wF8:APA91bEY1gQB84m1j1PCrNCODhTFB5vU9Vq2LIA_csPeg54P11B8FPZ_YSgnKF9NXI4w6VkOdD4NReIusHTG5FDIJ5_XeHtuNe-VvYEXY7JyUq3HeDPopD_zM1OW-gRx4qNjxeYi9V1r";
    }


    function send_android_notification($tokens,$data) {  
        $url = "https://fcm.googleapis.com/fcm/send";            
        $header = array("authorization: key=" . $this->key . "",
            "content-type: application/json"
        );    

        $fields = [
            'registration_ids' => $tokens,
            'priority' => 'high',
            'content_available' => true,
            'data' => $data,
        ];

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);    
        // close handle to release resources
        curl_close($ch);
        return $result;
    }

    
}
