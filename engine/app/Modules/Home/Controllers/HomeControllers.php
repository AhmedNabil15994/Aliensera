<?php namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Adverts;
use App\Models\ApiAuth;
use Illuminate\Support\Facades\Hash;

class HomeControllers extends Controller {

    use \TraitsFunc;

    public function __construct(){
        $this->countryID = \Helper::getCountryID();
    }

    public function index(){
        $input = \Input::all();
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $apiAuthToken = $_SERVER['HTTP_AUTHORIZATION'];
            $checkAuth = ApiAuth::checkUserToken($apiAuthToken);
            if($checkAuth == null){
                return \TraitsFunc::ErrorMessage("Session Expired, Please Login Again!", 401);
            }
            $dataObj = ApiAuth::checkUserToken($apiAuthToken);
            $user_id = $dataObj['user_id'];

            $statusObj['adverts'] = Adverts::advertsList($this->countryID,$user_id);
        }else{
            $statusObj['adverts'] = Adverts::advertsList($this->countryID,null);
        }
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function getAdvert($id) {
        $adverObj = Adverts::getOne($id);

        if ($adverObj == null) {
            return \TraitsFunc::ErrorMessage("This Advertisment not found", 400);
        }

        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $apiAuthToken = $_SERVER['HTTP_AUTHORIZATION'];
            $checkAuth = ApiAuth::checkUserToken($apiAuthToken);
            if($checkAuth == null){
                return \TraitsFunc::ErrorMessage("Session Expired, Please Login Again!", 401);
            }
            $dataObj = ApiAuth::checkUserToken($apiAuthToken);
            $user_id = $dataObj['user_id'];
            
            $statusObj['data'] = Adverts::getData($adverObj,$user_id);
        }else{
            $statusObj['data'] = Adverts::getData($adverObj);
        }

        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

}
