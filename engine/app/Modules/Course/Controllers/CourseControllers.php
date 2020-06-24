<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\CourseDiscussion;
use App\Models\University;
use App\Models\Field;
use App\Models\Faculty;
use App\Models\CourseFeedback;
use App\Models\StudentCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class CourseControllers extends Controller {

    use \TraitsFunc;

    public function index() {
        $statusObj = Course::dataList();
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function getOne($id) {
        $id = (int) $id;

        $courseObj = Course::getOne($id);
        if($courseObj == null) {
            return \TraitsFunc::ErrorMessage("This Course not found", 400);
        }

        $statusObj['data'] = Course::getData($courseObj);
        $statusObj['related'] = Course::getRalated($courseObj->field_id,3,$courseObj->id)['data'];
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);   
    }

    public function enroll($id) {
        $id = (int) $id;

        $courseObj = Course::getOne($id);
        if($courseObj == null) {
            return \TraitsFunc::ErrorMessage("This Course not found", 400);
        }

        $enrollObj = StudentCourse::where('student_id',USER_ID)->where('course_id',$id)->first();
        if($enrollObj == null){
            $enrollObj = new StudentCourse;
            $enrollObj->student_id = USER_ID;
            $enrollObj->course_id = $id;
            $enrollObj->instructor_id = $courseObj->instructor_id;
            $enrollObj->status = 2;
            $enrollObj->created_by = USER_ID;
            $enrollObj->created_at = DATE_TIME;
            $enrollObj->save();
        }else{
            if($enrollObj->status != 1){
                $enrollObj->status = 2;
                $enrollObj->save();
            }

            if($enrollObj->deleted_by != null){
                $enrollObj->deleted_by = null;
                $enrollObj->deleted_at = null;
                $enrollObj->save();
            }
        }

        $statusObj['status'] = \TraitsFunc::SuccessResponse("Your Request Had Been Sent");
        return \Response::json((object) $statusObj);   
    }

    public function addDiscussion($course_id){
        $input = \Input::all();
        $rules = [
            'comment' => 'required',
        ];

        $message = [
            'comment.required' => "Sorry Comment Required",
        ];

        $validate = \Validator::make($input, $rules, $message);
        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }   

        $videoObj = Course::getOne($course_id);
        if($videoObj == null){
            return \TraitsFunc::ErrorMessage('This Course Not Found !!', 400);
        }

        if($input['reply'] != 0){
            $commentObj = CourseDiscussion::getOne($input['reply']);
            if($commentObj == null){
                return \TraitsFunc::ErrorMessage('This Comment Not Found !!', 400);
            }
            $input['reply'] = $commentObj->reply_on != 0 ? $commentObj->reply_on : $input['reply'];
            if($commentObj->reply_on == 0 ){
                if($commentObj->created_by == USER_ID){
                    return \TraitsFunc::ErrorMessage("You Can't Reply To Your Comment!!", 400);
                }
            }

            $replier = User::getData(User::getOne(USER_ID));
            $msg = $replier->name.' replied on your comment';
            $tokens = Devices::getDevicesBy($commentObj->created_by,true);
            $fireBase = new \FireBase();
            $metaData = ['title' => "New Comment", 'body' => $msg,];
            $myData = ['type' => 3 , 'id' => $commentObj->course_id];
            $fireBase->send_android_notification($tokens[0],$metaData,$myData);
        }

        $commentObj = new CourseDiscussion;
        $commentObj->comment = $input['comment'];
        $commentObj->reply_on = $input['reply'];
        $commentObj->course_id = $course_id;
        $commentObj->status = 1;
        $commentObj->created_by = USER_ID;
        $commentObj->created_at = date('Y-m-d H:i:s');
        $commentObj->save();

        $statusObj['status'] = \TraitsFunc::SuccessResponse('Comment Saved Successfully !!');
        return $statusObj;
    }

    public function updateDiscussion($course_id,$comment_id){
        $input = \Input::all();
        $rules = [
            'comment' => 'required',
        ];

        $message = [
            'comment.required' => "Sorry Comment Required",
        ];

        $validate = \Validator::make($input, $rules, $message);
        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }   

        $videoObj = Course::getOne($course_id);
        if($videoObj == null){
            return \TraitsFunc::ErrorMessage('This Course Not Found !!', 400);
        }

        $commentObj = CourseDiscussion::getOne($comment_id);
        if($commentObj == null){
            return \TraitsFunc::ErrorMessage('This Comment Not Found !!', 400);
        }

        if($input['reply'] != 0){
            $commentObj = CourseDiscussion::getOne($input['reply']);
            if($commentObj == null){
                return \TraitsFunc::ErrorMessage('This Comment Not Found !!', 400);
            }
            $input['reply'] = $commentObj->reply_on != 0 ? $commentObj->reply_on : $input['reply'];
            if($commentObj->reply_on == 0 ){
                if($commentObj->created_by == USER_ID){
                    return \TraitsFunc::ErrorMessage("You Can't Reply To Your Comment!!", 400);
                }
            }
        }

        $commentObj->comment = $input['comment'];
        $commentObj->reply_on = $input['reply'];
        $commentObj->updated_by = USER_ID;
        $commentObj->updated_at = date('Y-m-d H:i:s');
        $commentObj->save();

        $statusObj['status'] = \TraitsFunc::SuccessResponse('Comment Updated Successfully !!');
        return $statusObj;
    }

    public function removeDiscussion($course_id,$comment_id){

        $videoObj = Course::getOne($course_id);
        if($videoObj == null){
            return \TraitsFunc::ErrorMessage('This Course Not Found !!', 400);
        }

        $commentObj = CourseDiscussion::getOne($comment_id);
        if($commentObj == null){
            return \TraitsFunc::ErrorMessage('This Comment Not Found !!', 400);
        }

        $commentObj->deleted_by = USER_ID;
        $commentObj->deleted_at = date('Y-m-d H:i:s');
        $commentObj->save();

        $statusObj['status'] = \TraitsFunc::SuccessResponse('Comment Deleted Successfully !!');
        return $statusObj;
    }

}
