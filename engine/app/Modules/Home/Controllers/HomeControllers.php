<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Field;
use App\Models\Course;
use App\Models\LessonVideo;
use App\Models\CourseFeedback;
use App\Models\StudentCourse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class HomeControllers extends Controller {

    use \TraitsFunc;

    public function index() {
        $dataList['myCourses'] = StudentCourse::myCourses(3);
        $dataList['popularCourses'] = StudentCourse::getTopCourses(3);
        $dataList['topRatedCourses'] = CourseFeedback::getTopRatedCourses(3);
        $dataList['categories'] = Field::dataList();
        // $dataList['allCourses2'] = Course::NotDeleted()->where('instructor_id',USER_ID)->count();
        // $dataList['allVideos'] = LessonVideo::NotDeleted()->count();

        // $dataList['topCourses'] = StudentCourse::getTopCourses(5);
        // $dataList['topStudents'] = StudentCourse::getTopStudents(5);
        $dataList['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $dataList);
        
    }

}
