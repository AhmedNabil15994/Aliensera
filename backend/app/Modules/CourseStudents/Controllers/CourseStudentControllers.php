<?php namespace App\Http\Controllers;

use App\Models\StudentCourse;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class CourseStudentControllers extends Controller {

    use \TraitsFunc;

    public function index() {
    	$dataList['data'] = StudentCourse::dataList(null,USER_ID,null,true);
        $dataList['courses'] = Course::dataList(USER_ID,null,null)['data'];
        $dataList['students'] = User::getInstructorStudents(StudentCourse::where('instructor_id',USER_ID)->where('status',1)->pluck('student_id'));
        return view('CourseStudents.Views.index')->with('data', (Object) $dataList);
    }

}
