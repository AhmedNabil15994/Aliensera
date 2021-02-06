<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Course;
use App\Models\CoursePrice;
use App\Models\LessonVideo;
use App\Models\ApiAuth;
use App\Models\StudentCourse;
use App\Models\Group;
use App\Models\StudentVideoDuration;
use App\Models\VideoComment;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class DashboardControllers extends Controller {

    use \TraitsFunc;

    public function getChartData(){
        $input = \Input::all();
        $start = $input['from'];
        $end = $input['to'];

        if($start == $end){
            $start = $start . ' 00:00:00';
            $end = $end . ' 23:59:59';
        }

        $datediff = strtotime($end) - strtotime($start);
        $daysCount = round($datediff / (60 * 60 * 24));
        $datesArray = [];
        $datesArray[0] = $start;

        if($daysCount > 2){
            for($i=1;$i<$daysCount;$i++){
                $datesArray[$i] = date('Y-m-d',strtotime($start.'+'.$i."day") );
            }
            $datesArray[$daysCount] = $end;  
        }else{
            // $datesArray[1] = $end;  
            for($i=1;$i<24;$i++){
                $datesArray[$i] = date('Y-m-d H:i:s',strtotime($start.'+'.$i." hour") );
            }
        }

        $chartData = [];
        $dataCount = count($datesArray);

        for($i=0;$i<$dataCount;$i++){
            if(IS_ADMIN == true){
                $students = StudentCourse::where('status',1)->pluck('student_id');
                $activeStudents = ApiAuth::where('created_at','<',date('Y-m-d H:i:s',strtotime($end.'+1 second')))->where('created_at','>=',$datesArray[$i])->where(function($whereQuery) use ($i,$datesArray,$daysCount){
                    if($daysCount > 2){
                        if($i < 6){
                            $whereQuery->where('created_at','<',$datesArray[$i+1]);
                        }
                    }else{
                        if($i < count($datesArray)-1){
                            $whereQuery->where('created_at','<',$datesArray[$i+1]);
                        }
                    }
                })->count();
            }else{
                $students = StudentCourse::where('instructor_id',USER_ID)->where('status',1)->pluck('student_id');
                $activeStudents = ApiAuth::where('created_at','<',date('Y-m-d H:i:s',strtotime($end.'+1 second')))->whereIn('user_id',$students)->where('created_at','>=',$datesArray[$i])->where(function($whereQuery) use ($i,$datesArray,$dataCount){
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
            $dataList['allStudents'] = Profile::where('group_id',3)->count();//$usersData['allStudents'];//User::getUsersByType(3)->count();
            $dataList['totalStudents'] = Profile::whereHas('User',function($whereQuery){
                $whereQuery->where('is_active',1);
            })->where('group_id',3)->count();//User::getUsersByType(3,true)->count();
            
            $dataList['allInstructors'] = Profile::where('group_id',2)->count();//User::getUsersByType(2)->count();
            $dataList['totalInstructors'] = Profile::whereHas('User',function($whereQuery){
                $whereQuery->where('is_active',1);
            })->where('group_id',2)->count();//User::getUsersByType(2,true)->count();
            
            $dataList['allCourses'] = Course::NotDeleted()->whereIn('status',[3,5])->count();
            $dataList['totalCourses'] = Course::NotDeleted()->count();
            
            $dataList['allVideos'] = LessonVideo::NotDeleted()->count();
            $dataList['freeVideos'] = LessonVideo::NotDeleted()->where('free',1)->count();

            $dataList['allStudentSessions'] = ApiAuth::count();
            $dataList['allRevenue'] = CoursePrice::whereHas('Course',function($courseQuery){
                $courseQuery->whereIn('status',[3,5]);
            })->sum(\DB::raw('upload_cost + approval_cost'));;;
            $dataList['allDuration'] = StudentVideoDuration::getAllDuration();

            $dataList['topCourses'] = Course::getTopCourses(5)['data'];
            $dataList['topStudents'] = User::getTopStudents(5);
            $dataList['topInstructors'] = User::getTopInstructors(5);
        }else{

            $dataList['allStudents'] = StudentCourse::NotDeleted()->where('instructor_id',USER_ID)->where('status',1)->groupBy('student_id')->get()->count();
            
            $dataList['totalStudents'] = StudentCourse::NotDeleted()->where('instructor_id',USER_ID)->groupBy('student_id')->get()->count();
            
            $dataList['totalCourses'] = Course::NotDeleted()->where('instructor_id',USER_ID)->count();
            $dataList['allCourses'] = Course::NotDeleted()->where('instructor_id',USER_ID)->whereIn('status',[3,5])->count();
            $dataList['expiredCourses'] = Course::NotDeleted()->where('instructor_id',USER_ID)->whereIn('status',[3,5])->count();
            
            $dataList['comments'] = VideoComment::NotDeleted()->whereHas('Course',function($courseQuery){
                $courseQuery->NotDeleted()->where('instructor_id',USER_ID);
            })->where('status',1)->count();
            
            $dataList['allVideos'] = LessonVideo::NotDeleted()->whereHas('Course',function($courseQuery){
                $courseQuery->NotDeleted()->where('instructor_id',USER_ID);
            })->count();

            $dataList['freeVideos'] = LessonVideo::NotDeleted()->where('free',1)->whereHas('Course',function($courseQuery){
                $courseQuery->NotDeleted()->where('instructor_id',USER_ID);
            })->count();

            $myStudents = StudentCourse::NotDeleted()->where('status',1)->where('instructor_id',USER_ID)->pluck('student_id');
            $dataList['allStudentSessions'] = ApiAuth::whereIn('user_id',$myStudents)->count();
            $upload = LessonVideo::whereHas('Course',function($courseQuery){
                    $courseQuery->where('instructor_id',USER_ID);
                })->sum('size');
            $dataList['allRevenue'] =  round($upload / 1000000000 ,2);
            $dataList['allDuration'] = StudentVideoDuration::getAllDuration();

            $dataList['topCourses'] = Course::getTopCourses(5)['data'];
            $dataList['topStudents'] = User::getTopStudents(5);
            $dataList['topSeenCourses'] = StudentVideoDuration::getTopSeenCourses(5);
        }       

        $now = date('Y-m-d H:i:s');
        $datesArray = [];
        $datesArray[0] = $now;
        for($i=1;$i<24;$i++){
            $datesArray[$i] = date('Y-m-d H:i:s',strtotime($now.'+'.$i." hour") );
        }
        $chartData = [];
        for($i=0;$i<count($datesArray);$i++){
            if(IS_ADMIN == true){
                $activeStudents = ApiAuth::where('created_at','<',date('Y-m-d H:i:s',strtotime($now.'+1 day')))->where('created_at','>=',$datesArray[$i])->where(function($whereQuery) use ($i,$datesArray){
                    if($i < count($datesArray)-1){
                        $whereQuery->where('created_at','<',$datesArray[$i+1]);
                    }
                })->count();
            }else{
                $students = StudentCourse::where('instructor_id',USER_ID)->where('status',1)->pluck('student_id');
                $activeStudents = ApiAuth::where('created_at','<',date('Y-m-d H:i:s',strtotime($now.'+1 day')))->whereIn('user_id',$students)->where('created_at','>=',$datesArray[$i])->where(function($whereQuery) use ($i,$datesArray){
                    if($i < count($datesArray)-1){
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
