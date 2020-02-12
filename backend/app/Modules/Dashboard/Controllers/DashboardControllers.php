<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Course;
use App\Models\LessonVideo;
use App\Models\ApiAuth;
use App\Models\StudentCourse;
use App\Models\Group;
use App\Models\StudentVideoDuration;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class DashboardControllers extends Controller {

    use \TraitsFunc;

    public function Dashboard() {
    	if(IS_ADMIN == true){
            $dataList['allStudents'] = User::getUsersByType(3)->count();
	    	$dataList['allInstructors'] = User::getUsersByType(2)->count();
	    	$dataList['allCourses'] = Course::NotDeleted()->where('status',3)->count();
	    	$dataList['allVideos'] = LessonVideo::NotDeleted()->count();

            $dataList['allStudentSessions'] = ApiAuth::count();
            $dataList['allRevenue'] = StudentCourse::getRevenue();
            $dataList['allDuration'] = StudentVideoDuration::getAllDuration();

    		$dataList['topCourses'] = StudentCourse::getTopCourses(5);
    		$dataList['topStudents'] = StudentCourse::getTopStudents(5);
    		$dataList['topInstructors'] = StudentCourse::getTopInstructors(5);
        }else{
        	$dataList['allStudents'] = StudentCourse::NotDeleted()->where('status',1)->where('instructor_id',USER_ID)->count();
            $dataList['allCourses'] = Course::NotDeleted()->where('instructor_id',USER_ID)->where('status',3)->count();
	    	$dataList['allCourses2'] = Course::NotDeleted()->where('instructor_id',USER_ID)->count();
	    	$dataList['allVideos'] = LessonVideo::NotDeleted()->count();

            $myStudents = StudentCourse::NotDeleted()->where('status',1)->where('instructor_id',USER_ID)->pluck('student_id');
            $dataList['allStudentSessions'] = ApiAuth::whereIn('user_id',$myStudents)->count();
            $dataList['allRevenue'] = StudentCourse::getRevenue();
            $dataList['allDuration'] = StudentVideoDuration::getAllDuration();

            $dataList['topCourses'] = StudentCourse::getTopCourses(5);
            $dataList['topStudents'] = StudentCourse::getTopStudents(5);
            $dataList['topSeenCourses'] = StudentVideoDuration::getTopSeenCourses(5);
        }    	
        return view('Dashboard.Views.dashboard')->with('data', (Object) $dataList);
    }

}
