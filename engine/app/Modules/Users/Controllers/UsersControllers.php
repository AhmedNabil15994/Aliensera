<?php namespace App\Http\Controllers;


use App\Models\Profiles;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class UsersControllers extends Controller {

    use \TraitsFunc;

    public function getUserData() {
        $userObj = Users::getOne(USER_ID);
        if ($userObj == null) {
            return \TraitsFunc::ErrorMessage("This User not found", 400);
        }
        $dataObj = new \stdClass();
        $dataObj->name = $userObj->Profile->display_name;
        $dataObj->phone = $userObj->phone;

        $statusObj['data'] = $dataObj;
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function updateUserData(){
        $input = \Input::all();
        $userObj = Users::getOne(USER_ID);
        if ($userObj == null) {
            return \TraitsFunc::ErrorMessage("This User not found", 400);
        }

        if(isset($input['name'])){
            $profileObj = Profiles::getProfile();
            $name = explode(' ', $input['name'], 2);
            $profileObj->first_name = $name[0];
            $profileObj->last_name = isset($name[1]) ? $name[1]  : '';
            $profileObj->display_name = $input['name'];
            $profileObj->save();
        }

        if(isset($input['phone'])){
            $userObj->phone = $input['phone'];
            $userObj->save();
        }

        if(isset($input['password'])){
            $userObj->password = Hash::make($input['password']);
            $userObj->save();
        }

        if (!isset($input['name']) && !isset($input['phone']) && !isset($input['password']) ) {
            return \TraitsFunc::ErrorMessage("No Data To Be Updated", 400);
        }

        $statusObj['status'] = \TraitsFunc::SuccessResponse('Data Updated Successfully');
        return \Response::json((object) $statusObj);
    }

}
