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

    public function getChartData(){
        $input = \Input::all();
        $start = $input['from'];
        $end = $input['to'];

        $datediff = strtotime($end) - strtotime($start);
        $daysCount = round($datediff / (60 * 60 * 24));
        $datesArray = [];
        $datesArray[0] = $start;
        for($i=1;$i<$daysCount;$i++){
            $datesArray[$i] = date('Y-m-d',strtotime($start.'+'.$i."day") );
        }
        $datesArray[$daysCount] = $end;
        $chartData = [];
        $dataCount = count($datesArray);
        for($i=0;$i<$dataCount;$i++){
            if(IS_ADMIN == true){
                $students = StudentCourse::where('status',1)->pluck('student_id');
                $activeStudents = User::where('is_active',1)->whereIn('id',$students)->where('created_at','>=',$datesArray[$i])->where(function($whereQuery) use ($i,$datesArray){
                    if($i < 9){
                        $whereQuery->where('created_at','<',$datesArray[$i+1]);
                    }
                })->count();
            }else{
                $students = StudentCourse::where('instructor_id',USER_ID)->where('status',1)->pluck('student_id');
                $activeStudents = User::where('is_active')->whereIn('id',$students)->where('created_at','>=',$datesArray[$i])->where(function($whereQuery) use ($i,$datesArray,$dataCount){
                    if($i < $dataCount-1){
                        $whereQuery->where('created_at','<',$datesArray[$i+1]);
                    }
                })->count();

            }
            $chartData[$i] = [$datesArray[$i] , $activeStudents];
        }
        return $chartData;
    }

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
	    	$dataList['allVideos'] = LessonVideo::NotDeleted()->whereHas('Course',function($courseQuery){
                $courseQuery->where('instructor_id',USER_ID);
            })->count();

            $myStudents = StudentCourse::NotDeleted()->where('status',1)->where('instructor_id',USER_ID)->pluck('student_id');
            $dataList['allStudentSessions'] = ApiAuth::whereIn('user_id',$myStudents)->count();
            $dataList['allRevenue'] = StudentCourse::getRevenue();
            $dataList['allDuration'] = StudentVideoDuration::getAllDuration();

            $dataList['topCourses'] = StudentCourse::getTopCourses(5);
            $dataList['topStudents'] = StudentCourse::getTopStudents(5);
            $dataList['topSeenCourses'] = StudentVideoDuration::getTopSeenCourses(5);
        }    	

        $now = date('Y-m-d');
        $datesArray = [];
        $datesArray[0] = $now;
        for($i=1;$i<10;$i++){
            $datesArray[$i] = date('Y-m-d',strtotime('-'.$i."week") );
        }
        $chartData = [];
        $datesArray = array_reverse($datesArray);
        for($i=0;$i<count($datesArray);$i++){
            if(IS_ADMIN == true){
                $students = StudentCourse::where('status',1)->pluck('student_id');
                $activeStudents = User::where('is_active',1)->whereIn('id',$students)->where('created_at','>=',$datesArray[$i])->where(function($whereQuery) use ($i,$datesArray){
                    if($i < 9){
                        $whereQuery->where('created_at','<',$datesArray[$i+1]);
                    }
                })->count();
            }else{
                $students = StudentCourse::where('instructor_id',USER_ID)->where('status',1)->pluck('student_id');
                $activeStudents = User::where('is_active',1)->whereIn('id',$students)->where('created_at','>=',$datesArray[$i])->where(function($whereQuery) use ($i,$datesArray){
                    if($i < 9){
                        $whereQuery->where('created_at','<',$datesArray[$i+1]);
                    }
                })->count();
            }
            $chartData[$i] = [$datesArray[$i] , $activeStudents];
        }

        $dataList['chartData'] = $chartData;
        
        return view('Dashboard.Views.dashboard')->with('data', (Object) $dataList);
    }

}
