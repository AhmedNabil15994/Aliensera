<?php namespace App\Http\Controllers;

use App\Models\CourseFeedback;
use App\Models\Course;
use Illuminate\Http\Request;

class FeedbackController extends Controller {

    use \TraitsFunc;

    public function index() {
        $input = \Input::all();
        if(!isset($input['course_id']) || empty($input['course_id'])){
            return \TraitsFunc::ErrorMessage("Please Select Course", 400);
        }
        $statusObj['data'] = CourseFeedback::dataList($input['course_id']); 
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function add(){
    	$input = \Input::all();

        $courseObj = Course::getOne($input['course_id']);
        if($courseObj == null){
            return \TraitsFunc::ErrorMessage("This Course not found", 400);
        }

        if(!isset($input['content']) || empty($input['content'])){
            return \TraitsFunc::ErrorMessage("Please Enter Feedback Content", 400);
        }

        if(!isset($input['rate']) || empty($input['rate'])){
            return \TraitsFunc::ErrorMessage("Please Enter Feedback rate", 400);
        }

        $feedbackObj = CourseFeedback::NotDeleted()->where('created_by',USER_ID)->where('course_id',$input['course_id'])->first();
        if($feedbackObj == null){
            $favouriteObj = new CourseFeedback();
            $favouriteObj->course_id = $input['course_id'];
            $favouriteObj->content = $input['content'];
            $favouriteObj->rate = $input['rate'];
            $favouriteObj->status = 1;
            $favouriteObj->created_by = USER_ID;
            $favouriteObj->created_at = date('Y-m-d H:i:s');
            $favouriteObj->save();
        }
    
        $statusObj['status'] = \TraitsFunc::SuccessResponse("Feedback Added To Course");
        return \Response::json((object) $statusObj);
    }

    public function update($id){
        $input = \Input::all();

        $feedbackObj = CourseFeedback::getOne($id);
        if($feedbackObj == null){
            return \TraitsFunc::ErrorMessage('This Feedback not found',400);
        }

        if($feedbackObj->created_by != USER_ID){
            return \TraitsFunc::ErrorMessage('You Can not update this feedback,because you are not the owner',400);
        }

        $courseObj = Course::getOne($input['course_id']);
        if($courseObj == null){
            return \TraitsFunc::ErrorMessage("This Course not found", 400);
        }

        if(!isset($input['content']) || empty($input['content'])){
            return \TraitsFunc::ErrorMessage("Please Enter Feedback Content", 400);
        }

        if(!isset($input['rate']) || empty($input['rate'])){
            return \TraitsFunc::ErrorMessage("Please Enter Feedback rate", 400);
        }

        $feedbackObj->course_id = $input['course_id'];
        $feedbackObj->content = $input['content'];
        $feedbackObj->rate = $input['rate'];
        $feedbackObj->status = 1;
        $feedbackObj->updated_by = USER_ID;
        $feedbackObj->updated_at = date('Y-m-d H:i:s');
        $feedbackObj->save();

        $statusObj['status'] = \TraitsFunc::SuccessResponse("Feedback Updated Successfully");
        return \Response::json((object) $statusObj);

    }	

    public function delete($id){
        $feedbackObj = CourseFeedback::getOne($id);
        if($feedbackObj == null){
            return \TraitsFunc::ErrorMessage('This Feedback not found',400);
        }

        if($feedbackObj->created_by != USER_ID){
            return \TraitsFunc::ErrorMessage('You Can not update this feedback,because you are not the owner',400);
        }

        $feedbackObj->deleted_by = USER_ID;
        $feedbackObj->deleted_at = date('Y-m-d H:i:s');
        $feedbackObj->save();

        $statusObj['status'] = \TraitsFunc::SuccessResponse("Feedback Deleted Successfully");
        return \Response::json((object) $statusObj);
    }

}
