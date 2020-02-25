<?php
use App\Models\Variable;
class FireBase {
    protected $fcmKey;

    function __construct() {
        $this->key = Variable::getVar('FCM_KEY');
    }


    function send_android_notification($tokens,$data,$myData=null) {  
        $url = "https://fcm.googleapis.com/fcm/send";            
        $header = array("authorization: key=" . $this->key . "",
            "content-type: application/json"
        );    

        if($myData != null){
            $fields = [
                'to' => $tokens,
                'notification' => $data,
                'data' => $myData,
            ];
        }else{
            $fields = [
                'to' => $tokens,
                'notification' => $data,
            ];
        }

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
        dd($tokens);
        curl_close($ch);
        return $result;
    }

    
}
