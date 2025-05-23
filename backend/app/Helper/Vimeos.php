<?php
use App\Models\Variable;
use Vimeo\Vimeo;
use Vimeo\Exceptions\VimeoUploadException;

class Vimeos {
    protected $client_id;
    protected $client_secret;
    protected $access_token;

    function __construct($accountObj) {
        $this->client_id = $accountObj->client_id;
        $this->client_secret = $accountObj->client_secret;
        $this->access_token = $accountObj->access_token;
    }

    public function createFolder($name) {  
        $url = "https://api.vimeo.com/me/projects";            
        $header = array(
            'Content-Type: application/json',                                                                                
            "Authorization: Bearer $this->access_token"
        );    

        $fields = [
            'name' => $name,
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
        $data = json_decode($result);
        $uri = $data->uri;
        $arr = explode("/", $uri, 5);
        $project_id = $arr[4];
        return $project_id;
    }

    public function renameFolder($name,$project_id) {  
        $url = "https://api.vimeo.com/me/projects/".$project_id;            
        $header = array(
            'Content-Type: application/json',                                                                                
            "Authorization: Bearer $this->access_token"
        );    

        $fields = [
            'name' => $name,
        ];

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);    
        // close handle to release resources
        curl_close($ch);
        return $result;
    }

    public function moveToFolder($video_id,$project_id) {  
        $url = "https://api.vimeo.com/me/projects/".$project_id.'/videos/'.$video_id;            
        $header = array(
            'Content-Type: application/json',                                                                                
            "Authorization: Bearer $this->access_token"
        );    


        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);    
        // close handle to release resources
        curl_close($ch);
        return $result;
    }

    public function upload($file,$title,$project_id){
        // dd($file);
        if (empty($this->access_token)) {
            throw new Exception(
                'You can not upload a file without an access token. You can find this token on your app page, or generate ' .
                'one using `auth.php`.'
            );
        }

        $lib = new Vimeo($this->client_id, $this->client_secret, $this->access_token);

        $file_name = $title;//'/home/ahmednabil94/Desktop/CR7.mp4';

        try {
            // Upload the file and include the video title and description.
            $uri = $lib->upload($file, array(
                'name' => $title,
                'description' => $title
            ));
            // Get the metadata response from the upload and log out the Vimeo.com url
            $old_video_data = $lib->request($uri . '?fields=link');
            // Make an API call to edit the title and description of the video.
            $lib->request($uri, array(
                'name' => $title,
                'description' => $title
            ), 'POST');
            // Make an API call to see if the video is finished transcoding.
            $video_data = $lib->request($uri . '?fields=transcode.status');
            $arr = explode("/", $old_video_data['body']['link']);
            
            $video_id = $arr[3];
            $queryStringId = isset($arr[4]) ? $arr[4] : '';
            $this->moveToFolder($video_id , $project_id);
            return [$video_id,$queryStringId];
        } catch (VimeoUploadException $e) {
            // We may have had an error. We can't resolve it here necessarily, so report it to the user.
            echo 'Error uploading ' . $title . "\n";
            echo 'Server reported: ' . $e->getMessage() . "\n";
        } catch (VimeoRequestException $e) {
            echo 'There was an error making the request.' . "\n";
            echo 'Server reported: ' . $e->getMessage() . "\n";
        }
    }

    public function getVideo($video_id) {  
        $lib = new Vimeo($this->client_id, $this->client_secret, $this->access_token);
        $url = "/videos/".$video_id;
        $videoUrl = '';  
        $old_video_data = $lib->request($url);
        if($old_video_data && isset($old_video_data['body']) && isset($old_video_data['body']['download'])){
            foreach($old_video_data['body']['download'] as $link){
                if($link['quality'] == 'source'){
                    return $link['link'];
                }
            }
        }
        return $videoUrl;
    }

}
