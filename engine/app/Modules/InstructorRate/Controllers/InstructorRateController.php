<?php namespace App\Http\Controllers;

use App\Models\InstructorRate;
use App\Models\User;
use Illuminate\Http\Request;

class InstructorRateController extends Controller {

    use \TraitsFunc;

    public function add(){
    	$input = \Input::all();

        if(!isset($input['instructor_id']) || empty($input['instructor_id'])){
            return \TraitsFunc::ErrorMessage("Please Select Instructor", 400);
        }

        $courseObj = User::getOneByType(2,$input['instructor_id']);
        if($courseObj == null){
            return \TraitsFunc::ErrorMessage("This Instructor not found", 400);
        }

        if(!isset($input['rate']) || empty($input['rate'])){
            return \TraitsFunc::ErrorMessage("Please Enter Rate", 400);
        }

        $rateObj = InstructorRate::NotDeleted()->where('created_by',USER_ID)->where('instructor_id',$input['instructor_id'])->first();
        if($rateObj == null){
            $favouriteObj = new InstructorRate();
            $favouriteObj->instructor_id = $input['instructor_id'];
            $favouriteObj->rate = $input['rate'];
            $favouriteObj->status = 1;
            $favouriteObj->created_by = USER_ID;
            $favouriteObj->created_at = date('Y-m-d H:i:s');
            $favouriteObj->save();
        }
    
        $statusObj['status'] = \TraitsFunc::SuccessResponse("Rate Added To Instructor");
        return \Response::json((object) $statusObj);
    }

    public function update($id){
        $input = \Input::all();

        $feedbackObj = InstructorRate::getOne($id);
        if($feedbackObj == null){
            return \TraitsFunc::ErrorMessage('This Rate not found',400);
        }

        if($feedbackObj->created_by != USER_ID){
            return \TraitsFunc::ErrorMessage('You Can not update this feedback,because you are not the owner',400);
        }

        $courseObj = User::getOneByType(2,$input['instructor_id']);
        if($courseObj == null){
            return \TraitsFunc::ErrorMessage("This Instructor not found", 400);
        }

        if(!isset($input['rate']) || empty($input['rate'])){
            return \TraitsFunc::ErrorMessage("Please Enter rate", 400);
        }

        $feedbackObj->instructor_id = $input['instructor_id'];
        $feedbackObj->rate = $input['rate'];
        $feedbackObj->status = 1;
        $feedbackObj->updated_by = USER_ID;
        $feedbackObj->updated_at = date('Y-m-d H:i:s');
        $feedbackObj->save();

        $statusObj['status'] = \TraitsFunc::SuccessResponse("Rate Updated Successfully");
        return \Response::json((object) $statusObj);

    }	

    public function delete($id){
        $feedbackObj = InstructorRate::getOne($id);
        if($feedbackObj == null){
            return \TraitsFunc::ErrorMessage('This Rate not found',400);
        }

        if($feedbackObj->created_by != USER_ID){
            return \TraitsFunc::ErrorMessage('You Can not update this feedback,because you are not the owner',400);
        }

        $feedbackObj->deleted_by = USER_ID;
        $feedbackObj->deleted_at = date('Y-m-d H:i:s');
        $feedbackObj->save();

        $statusObj['status'] = \TraitsFunc::SuccessResponse("Rate Deleted Successfully");
        return \Response::json((object) $statusObj);
    }

}
