<?php namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Favourites;
use App\Models\StudentCourse;
use App\Models\Course;
use Illuminate\Http\Request;

class CartController extends Controller {

    use \TraitsFunc;

    public function index() {
        $input = \Input::all();
        $statusObj['data'] = Cart::favouriteList()['data'];
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function confirmPayment(){
        $courses = Cart::NotDeleted()->where('student_id',USER_ID)->get();
        foreach ($courses as $value) {
            $courseObj = Course::getOne($value->course_id);
            if($courseObj == null){
                return \TraitsFunc::ErrorMessage("This Course not found", 400);
            }
            $enrollObj = new StudentCourse;
            $enrollObj->student_id = USER_ID;
            $enrollObj->course_id = $value->course_id;
            $enrollObj->instructor_id = $courseObj->instructor_id;
            $enrollObj->status = 1;
            $enrollObj->paid = 1;
            $enrollObj->created_by = USER_ID;
            $enrollObj->created_at = DATE_TIME;
            $enrollObj->save();
        }
        Cart::where('student_id',USER_ID)->update(['deleted_by'=>USER_ID,'deleted_at'=>DATE_TIME]);
        Favourites::where('student_id',USER_ID)->update(['deleted_by'=>USER_ID,'deleted_at'=>DATE_TIME]);
        $statusObj['status'] = \TraitsFunc::SuccessResponse("Courses Added To Your My Courses Section");
        return \Response::json((object) $statusObj);
    }

    public function add(){
    	$input = \Input::all();
        $courseObj = Course::getOne($input['course_id']);
        if($courseObj == null){
            return \TraitsFunc::ErrorMessage("This Course not found", 400);
        }

        $favouriteObj = Cart::getOne($input['course_id']);
        if($favouriteObj == null){
            $favouriteObj = new Cart();
            $favouriteObj->student_id = USER_ID;
            $favouriteObj->course_id = $input['course_id'];
            $favouriteObj->save();
            $statusObj['status'] = \TraitsFunc::SuccessResponse("Course Added To Your Cart");
        }else{
            $favouriteObj->delete();
            $statusObj['status'] = \TraitsFunc::SuccessResponse("Course Removed from your Cart");
        }
    	
        return \Response::json((object) $statusObj);
    }	

}
