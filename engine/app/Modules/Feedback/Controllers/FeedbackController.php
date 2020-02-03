<?php namespace App\Http\Controllers;

use App\Models\CourseFeedback;
use App\Models\InstructorRate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class FeedbackController extends Controller {

    use \TraitsFunc;

    public function index() {
        $input = \Input::all();
        $statusObj['data'] = CourseFeedback::dataList($input['course_id']);
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function add(){
    	$input = \Input::all();

        if($input['type'] == 1){
            if(!isset($input['item_id']) || empty($input['item_id'])){
                return \TraitsFunc::ErrorMessage("Please Select Course", 400);
            }

            $courseObj = Course::getOne($input['item_id']);
            if($courseObj == null){
                return \TraitsFunc::ErrorMessage("This Course not found", 400);
            }

            if(!isset($input['content']) || empty($input['content'])){
                return \TraitsFunc::ErrorMessage("Please Enter Feedback Content", 400);
            }

            if(!isset($input['rate']) || empty($input['rate'])){
                return \TraitsFunc::ErrorMessage("Please Enter Feedback rate", 400);
            }

            $favouriteObj = new CourseFeedback();
            $favouriteObj->course_id = $input['item_id'];
            $favouriteObj->content = $input['content'];
            $favouriteObj->rate = $input['rate'];
            $favouriteObj->status = 1;
            $favouriteObj->created_by = USER_ID;
            $favouriteObj->created_at = date('Y-m-d H:i:s');
            $favouriteObj->save();
        
            $statusObj['status'] = \TraitsFunc::SuccessResponse("Feedback Added To Course");
            return \Response::json((object) $statusObj);
        }elseif ($input['type'] == 2) {
            if(!isset($input['item_id']) || empty($input['item_id'])){
                return \TraitsFunc::ErrorMessage("Please Select Instructor", 400);
            }

            $courseObj = User::getOneByType(2,$input['item_id']);
            if($courseObj == null){
                return \TraitsFunc::ErrorMessage("This Instructor not found", 400);
            }

            if(!isset($input['rate']) || empty($input['rate'])){
                return \TraitsFunc::ErrorMessage("Please Enter Rate", 400);
            }

            $favouriteObj = new InstructorRate();
            $favouriteObj->instructor_id = $input['item_id'];
            $favouriteObj->rate = $input['rate'];
            $favouriteObj->status = 1;
            $favouriteObj->created_by = USER_ID;
            $favouriteObj->created_at = date('Y-m-d H:i:s');
            $favouriteObj->save();
        
            $statusObj['status'] = \TraitsFunc::SuccessResponse("Rate Added To Instructor");
            return \Response::json((object) $statusObj);
        }
    }	

}
