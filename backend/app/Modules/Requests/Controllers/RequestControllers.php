<?php namespace App\Http\Controllers;

use App\Models\StudentRequest;
use App\Models\Course;
use App\Models\User;
use App\Models\Cart;
use App\Models\Favourites;
use App\Models\Devices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Vimeo\Laravel\Facades\Vimeo;

class RequestControllers extends Controller {

    use \TraitsFunc;

    public function index() {
        if(IS_ADMIN){
            $dataList = StudentRequest::dataList(null,null,true);
        }else{
            $dataList = StudentRequest::dataList(USER_ID,null,true);
        }
        $dataList['courses'] = Course::NotDeleted()->orderBy('id','DESC')->get();
        $dataList['instructors'] = User::getUsersByType(2);
        $dataList['students'] = User::getUsersByType(3);
        return view('Requests.Views.index')
            ->with('data', (Object) $dataList);
    }

    public function add() {
        $dataList['courses'] = Course::NotDeleted()->whereIn('status',[3,5])->with('Instructor')->orderBy('id','DESC')->get();
        $dataList['students'] = User::getUsersByType(3);
        return view('Requests.Views.add')
            ->with('data', (Object) $dataList);
    }

    public function create() {
        $input = \Input::all();

        $studentObj = User::getOne($input['student_id']);
        if($studentObj == null) {
            return Redirect('404');
        }

        $courseObj = Course::getOne($input['course_id']);
        if($courseObj == null) {
            return Redirect('404');
        }

        $requestObj = new StudentRequest;
        $requestObj->student_id = $input['student_id'];
        $requestObj->course_id = $input['course_id'];
        $requestObj->instructor_id = $courseObj->instructor_id;
        $requestObj->status = $input['status'];
        $requestObj->created_by = USER_ID;
        $requestObj->created_at = DATE_TIME;
        $requestObj->save();

        $msg = '';
        if($input['status'] == 0){
            $msg = "Your Request For Joining ".$courseObj->title." Is Refused";
        }elseif($input['status'] == 1){
            $msg = "Your Request For Joining ".$courseObj->title." Is Accepted";
            Cart::where('student_id',$input['student_id'])->where('course_id',$input['course_id'])->update(['deleted_by'=>USER_ID,'deleted_at'=>DATE_TIME]);
            Favourites::where('student_id',$input['student_id'])->where('course_id',$input['course_id'])->update(['deleted_by'=>USER_ID,'deleted_at'=>DATE_TIME]);
        }
        $tokens = Devices::getDevicesBy($input['student_id'],true);
        if(!empty($tokens) && isset($tokens[0])){
            $this->sendNotification($tokens[0],$msg,$input['course_id']);
        }

        \Session::flash('success', "Alert! Update Successfully");
        return \Redirect::to('/users/view/'.$input['student_id']);
    }

    public function update($id,$status) {
        $id = (int) $id;

        $requestObj = StudentRequest::getOne($id);
        if($requestObj == null) {
            return Redirect('404');
        }

        if(!in_array($status, [0,1])){
            return Redirect('404');
        }

        if(!IS_ADMIN && $status == 1){
            $course_id = $requestObj->course_id;
            $instructor_id = $requestObj->Course->instructor_id;
            if($requestObj->Course->CoursePrice == null){
                \Session::flash('error', "Alert! Your Quota Exceeded The Limit, Please Contact System Adminstrator !!");
                return \Redirect::back()->withInput();
            }

            $uploadedSize = StudentRequest::where('instructor_id',$instructor_id)->where('course_id',$course_id)->where('status',1)->groupBy('student_id')->count();;
            if( !isset($requestObj->Course->CoursePrice)  || $uploadedSize >= $requestObj->Course->CoursePrice->approval_number ){
                \Session::flash('error', "Alert! Your Quota Exceeded The Limit, Please Contact System Adminstrator !!");
                return \Redirect::back()->withInput();
            }
        }

        $requestObj->status = $status;
        $requestObj->updated_by = USER_ID;
        $requestObj->updated_at = DATE_TIME;
        $requestObj->save();

        $msg = '';
        if($status == 0){
            $msg = "Your Request For Joining ".$requestObj->Course->title." Is Refused";
        }elseif($status == 1){
            $msg = "Your Request For Joining ".$requestObj->Course->title." Is Accepted";
            Cart::where('student_id',$requestObj->student_id)->where('course_id',$requestObj->course_id)->update(['deleted_by'=>USER_ID,'deleted_at'=>DATE_TIME]);
            Favourites::where('student_id',$requestObj->student_id)->where('course_id',$requestObj->course_id)->update(['deleted_by'=>USER_ID,'deleted_at'=>DATE_TIME]);
        }
        $tokens = Devices::getDevicesBy($requestObj->student_id,true);
        if(!empty($tokens) && isset($tokens[0])){
            $this->sendNotification($tokens[0],$msg,$requestObj->course_id);
        }

        \Session::flash('success', "Alert! Update Successfully");
        return \Redirect::back()->withInput();
    }

    public function sendNotification($tokens,$msg,$id){
        $fireBase = new \FireBase();
        $metaData = ['title' => "Course Join Request Reply", 'body' => $msg,];
        $myData = ['type' => 1 , 'id' => $id];
        $fireBase->send_android_notification($tokens,$metaData,$myData);
        return true;
    }

    public function delete($id) {
        $id = (int) $id;
        $requestObj = StudentRequest::getOne($id);
        return \Helper::globalDelete($requestObj);
    }

}
