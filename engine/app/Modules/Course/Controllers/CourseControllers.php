<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
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
        }

        $statusObj['status'] = \TraitsFunc::SuccessResponse("Your Request Had Been Sent");
        return \Response::json((object) $statusObj);   
    }

}
