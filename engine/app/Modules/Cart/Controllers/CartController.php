<?php namespace App\Http\Controllers;

use App\Models\Cart;
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
