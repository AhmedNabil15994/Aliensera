<?php namespace App\Http\Controllers;

use App\Models\Favourites;
use App\Models\Course;
use Illuminate\Http\Request;

class FavouritesController extends Controller {

    use \TraitsFunc;

    public function index() {
        $input = \Input::all();
        $statusObj['data'] = Favourites::favouriteList()['data'];
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function add(){
    	$input = \Input::all();
        $courseObj = Course::getOne($input['course_id']);
        if($courseObj == null){
            return \TraitsFunc::ErrorMessage("This Course not found", 400);
        }

        $favouriteObj = Favourites::getOne($input['course_id']);
        if($favouriteObj == null){
            $favouriteObj = new Favourites();
            $favouriteObj->student_id = USER_ID;
            $favouriteObj->course_id = $input['course_id'];
            $favouriteObj->save();
            $statusObj['status'] = \TraitsFunc::SuccessResponse("Course Added To Your Wishlist");
        }else{
            $favouriteObj->delete();
            $statusObj['status'] = \TraitsFunc::SuccessResponse("Course Removed from your Withlist");
        }
    	
        return \Response::json((object) $statusObj);
    }	

}
