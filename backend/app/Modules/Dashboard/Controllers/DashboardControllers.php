<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Course;
use App\Models\CoursePrice;
use App\Models\LessonVideo;
use App\Models\ApiAuth;
use App\Models\StudentCourse;
use App\Models\Group;
use App\Models\Faculty;
use App\Models\University;
use App\Models\Devices;
use App\Models\StudentVideoDuration;
use App\Models\VideoComment;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Excel;
use App\Exports\UserExport;

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

    public function stats() {   
        $input = \Input::all();
        $data = [];
        $studentCourseObj = StudentCourse::NotDeleted()->whereHas('Student',function($studentQuery){
            $studentQuery->where('is_active',1);
        })->whereHas('Instructor',function($instructorQuery){
            $instructorQuery->where('is_active',1);
        })->whereHas('Course',function($courseQuery) use ($input){
            $courseQuery->where('course_type',2);
            if(isset($input['university_id']) && !empty($input['university_id'])){
                $courseQuery->where('university_id',$input['university_id']);
            }
            if(isset($input['faculty_id']) && !empty($input['faculty_id'])){
                $courseQuery->where('faculty_id',$input['faculty_id']);
            }
            if(isset($input['year']) && !empty($input['year'])){
                $courseQuery->where('year',$input['year']);
            }
            if(isset($input['course_id']) && !empty($input['course_id'])){
                $courseQuery->where('id',$input['course_id']);
            }
        })->where('status',1)->groupBy('course_id')->selectRaw(\DB::raw('count(*) as counts, course_id'))->orderBy('counts','DESC')->get();

        foreach ($studentCourseObj as $value) {
            $courseObj = Course::getOne($value->course_id);
            if($courseObj){
                $data[] =[
                    'university' => $courseObj->University->title,
                    'faculty' => $courseObj->Faculty->title,
                    'year' => $courseObj->year,
                    'course' => $courseObj->title,
                    'university_id' => $courseObj->university_id,
                    'faculty_id' => $courseObj->faculty_id,
                    'course_id' => $courseObj->id,
                    'studentCount' => $value->counts,
                ];
            }
        }

        $dataList['data'] = $data;
        $dataList['courses'] = Course::latest()->get();
        $dataList['universities'] = University::NotDeleted()->get();
        $dataList['faculties'] = Faculty::NotDeleted()->get();
        return view('Dashboard.Views.stats')->with('data', (Object) $dataList);
    }

    public function downloadStats($course){
        $course = (int) $course;

        $courseObj = Course::getOne($course);
        if($courseObj == null){
            \Session::flash('error','This Course Is Not Found ');
            return redirect()->back();
        }

        $queryData = StudentCourse::NotDeleted()->where('course_id',$course)->where('status',1)->groupBy('student_id')->pluck('student_id');

        $student_id = reset($queryData);
        
        return Excel::download(new UserExport($student_id), 'statistics.xlsx');
    }

    protected function validateNotification($input){
        $rules = [
            'title' => 'required',
            'description' => 'required',
        ];

        $message = [
            'title.required' => "Sorry Title Required",
            'description.required' => "Sorry Body Required",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function sendNotification(){
        return view('Dashboard.Views.sendNotification');
    }

    public function postSendNotification($course,Request $request) {
        $input = \Input::all();

        $validate = $this->validateNotification($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $notfImage = '';
        if ($request->hasFile('image')) {
            $files = $request->file('image');
            $fileName = \ImagesHelper::UploadImage('notifications', $files, 0);
            if ($fileName == false) {
                \Session::flash('error', "Upload images Failed");
                return \Redirect::back()->withInput();
            }
            $notfImage = \ImagesHelper::GetImagePath('notifications',0,$fileName);            
        }

        $queryData = StudentCourse::NotDeleted()->where('course_id',$course)->where('status',1)->groupBy('student_id')->pluck('student_id');

        $users = reset($queryData);

        $tokens = Devices::getDevicesBy($users);
        $tokens = reset($tokens);
        foreach ($tokens as $value) {
            $this->sendNots($value,$input['title'],$input['description'],$notfImage);
        }   
        \Session::flash('success', "Alert! Notification Sent Successfully");
        return redirect()->back();
    }

    public function sendNots($tokens,$title,$msg,$image){
        $fireBase = new \FireBase();
        $metaData = ['title' => $title, 'body' => $msg,];
        $myData = ['type' => 4, 'image'=>$image,'title' => $title, 'body' => $msg,];
        $fireBase->send_android_notification($tokens,$metaData,$myData);
        return true;
    }
}
