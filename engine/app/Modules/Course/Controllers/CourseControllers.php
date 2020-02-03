<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\University;
use App\Models\Field;
use App\Models\Faculty;
use App\Models\CourseFeedback;
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
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);   
    }

}
