<?php namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Profile;
use App\Models\ApiKeys;
use App\Models\ApiAuth;
use App\Models\Devices;
use Nexmo;

class AuthController extends Controller {

    use \TraitsFunc;

	public function login() {
        try{
            $_SERVER['HTTP_DEVICEKEY'];
        }catch(\Exception $e ){
            return \TraitsFunc::ErrorMessage("Please check device key", 400);
        }

        $input = \Input::all();

        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        $message = [
            'username.required' => "Sorry Username Required",
            'password.required' => "Sorry Password Required",
        ];

        $validate = \Validator::make($input, $rules, $message);

        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }

        $username = $input['username'];
        $userObj = Users::getLoginUser($username);
        
        if ($userObj == null) {
            return \TraitsFunc::ErrorMessage("Sorry this username not found, Or your username not active", 400);
        }

        $checkPassword = $input['password'] == $userObj->password ? true:false;

        if ($checkPassword == null) {
            return \TraitsFunc::ErrorMessage("Sorry Password wrong, Please try again", 400);
        }

        $dataObj = self::LoginAction($userObj);

         //Check token
        $checkAuth = ApiAuth::checkUserToken($dataObj->token);
       
        if($checkAuth == null){
            \Auth::logout();
            session()->flush();
 
            return \TraitsFunc::ErrorMessage("Error, Please contact with admin to recheck user data!", 401);
        }

        Devices::applyNewDevice($checkAuth['auth']->id);

        $statusObj['data'] = new \stdClass();
        $statusObj['data'] = $dataObj;
        $statusObj['status'] = \TraitsFunc::SuccessResponse("Login Success");
		return \Response::json((object) $statusObj);
	}

	static function LoginAction($userObj) {

        $dateTime = DATE_TIME;
        $apiKeyId = ApiKeys::checkApiKey()->id;

        //ApiAuth::logoutOtherSessions($userObj->id, $apiKeyId);

        $ApiAuth = new ApiAuth();
        $ApiAuth->auth_token = md5(uniqid(rand(), true));
        $ApiAuth->auth_expire = 1;
        $ApiAuth->api_id = $apiKeyId;
        $ApiAuth->user_id = $userObj->customer_id;
        $ApiAuth->created_at = $dateTime;
        $ApiAuth->save();

        // $userObj->last_login = $dateTime;
        // $userObj->save();

        $token_value = $ApiAuth->auth_token;
        $profile = $userObj->Profile;

        $dataObj = new \stdClass();
        $dataObj->email = $profile->email;
        $dataObj->phone = $profile->phone;
        $dataObj->name = $profile->name_ar;
        // $dataObj->last_login = $userObj->last_login;
        $dataObj->token = $token_value;
        $dataObj->auth_id = $ApiAuth->id;
        $dataObj->username = $userObj->username;
        return $dataObj;
    }

	public function logout() {
		$authObj = ApiAuth::checkUserToken(APP_TOKEN);
    
        if($authObj == null){
            return \TraitsFunc::ErrorMessage("Invalid Process, Please try again later", 400);
        }

        $authObj['auth']->auth_expire = 0;
        $authObj['auth']->save();

        \Auth::logout();
        session()->flush();

        $statusObj['status'] = new \stdClass();
        $statusObj['status'] = \TraitsFunc::SuccessResponse("Logout Success, You can now login again!");
		return \Response::json((object) $statusObj);
	}

    public function register() {

        if (!isset($_SERVER['HTTP_DEVICEKEY'])) {
            return \TraitsFunc::ErrorMessage("Please check device key", 400);
        }

        $dateTime = DATE_TIME;
        $input = \Input::all();

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'phone' => 'required',
            'customer_type' => 'required',
            'identity_no' => 'required',
            'credit_limit' => 'required',
            'username' => 'required',
        ];

        $message = [
            'name.required' => "Sorry Name Required",
            'email.required' => "Sorry Email Required",
            'email.email' => "Sorry Email Must Be Email Type",
            'password.required' => "Sorry Password Required",
            'phone.required' => "Sorry Phone Required",
            'customer_type.required' => "Sorry Customer Type Required",
            'identity_no.required' => "Sorry Identity NO Required",
            'credit_limit.required' => "Sorry Credit Limit Required",
            'username.required' => "Sorry Username Required",
        ];

        $validate = \Validator::make($input, $rules, $message);

        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }
            
        $checkPhone = Profile::NotDeleted()->where('phone', $input['phone'])->first();
        if($checkPhone != null) {
            return \TraitsFunc::ErrorMessage("This phone exist, Please choose another phone!", 400);
        }

        if(isset($input['email']) && !empty($input['email'])) {
            $checkEmail = Profile::NotDeleted()->where('email', $input['email'])->first();
            if($checkEmail != null) {
                return \TraitsFunc::ErrorMessage("This email exist, Please choose another email!", 400);
            }
        }

        if(isset($input['username']) && !empty($input['username'])) {
            $checkEmail = Users::NotDeleted()->where('username', $input['username'])->first();
            if($checkEmail != null) {
                return \TraitsFunc::ErrorMessage("This username exist, Please choose another username!", 400);
            }
        }
        
        $name = explode(' ', $input['name'], 2);

        $profileObj = new Profile();
        $profileObj->name_ar = $input['name'];
        $profileObj->email = $input['email'];
        $profileObj->phone = $input['phone'];
        $profileObj->customer_type = $input['customer_type'];
        $profileObj->identity_no = $input['identity_no'];
        $profileObj->credit_limit = $input['credit_limit'];
        $profileObj->created_at = $dateTime;
        $profileObj->save();

        $customer_id = $profileObj->id;

        $userObj = new Users();        
        $userObj->customer_id = $customer_id;
        $userObj->username = isset($input['username']) ? $input['username'] : null;            
        $userObj->password = isset($input['password']) ? $input['password'] : '';
        $userObj->status = 1;
        $userObj->created_at = $dateTime;
        $userObj->save();
        

        $apiKeyId = ApiKeys::checkApiKey()->id;

        ApiAuth::logoutOtherSessions($userObj->customer_id, $apiKeyId);

        $ApiAuth = new ApiAuth();
        $ApiAuth->auth_token = md5(uniqid(rand(), true));
        $ApiAuth->auth_expire = 1;
        $ApiAuth->api_id = $apiKeyId;
        $ApiAuth->user_id = $userObj->customer_id;
        $ApiAuth->created_at = $dateTime;
        $ApiAuth->save();

        $token_value = $ApiAuth->auth_token;

        //Check token
        $checkAuth = ApiAuth::checkUserToken($token_value);
    
        if($checkAuth == null){
            \Auth::logout();
            session()->flush();

            return \TraitsFunc::ErrorMessage("Error, Please contact with admin to recheck user data!", 401);
        }

        Devices::applyNewDevice($ApiAuth->id);

        $dataObj = new \stdClass();
        $dataObj->email = $profileObj->email;
        $dataObj->phone = $profileObj->phone;
        $dataObj->name = $profileObj->name_ar;
        $dataObj->token = $token_value;
        $dataObj->auth_id = $ApiAuth->id;
        $dataObj->username = $userObj->username;

        
        $statusObj['data'] = new \stdClass();
        $statusObj['data'] = $dataObj;
        $statusObj['status'] = \TraitsFunc::SuccessResponse("Register Success");
		return \Response::json((object) $statusObj);
    }

    public function getCode(){
        $input = \Input::all();
        $rules = [
            'email' => 'required|email',
        ];

        $message = [
            'email.required' => "Sorry Email Required",
            'email.email' => "Sorry Email Must Be Email Type",
        ];
        $validate = \Validator::make($input, $rules, $message);

        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }

        $userObj = Users::checkUserByEmail($input['email']);
        if ($userObj == null) {
            return \TraitsFunc::ErrorMessage("Email Not Found", 400);   
        }

        $code = rand(1000,10000);

        $userObj->code = $code;
        $userObj->code_verified = 0;
        $userObj->code_expire = date("Y-m-d H:i:s", strtotime('+24 hours'));
        $userObj->save();
        
        $emailData['firstName'] = $userObj->Profile->display_name;
        $emailData['code'] = $code;
        $emailData['subject'] = "Sayrat.com - Reset Your Password";
        $emailData['to'] = $userObj->email;
        $emailData['template'] = "emailUsers.resetPassword";
        \Helper::SendMail($emailData);

        $statusObj['status'] = \TraitsFunc::SuccessResponse("We Sent Code To Your Mobile Number");
        return \Response::json((object) $statusObj); 
    }

}
