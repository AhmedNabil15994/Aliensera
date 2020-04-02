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
            $dataList = StudentRequest::dataList();
        }else{
            $dataList = StudentRequest::dataList(USER_ID);
        }
        $dataList['courses'] = Course::dataList(null,null,true)['data'];
        $dataList['instructors'] = User::getUsersByType(2);
        $dataList['students'] = User::getUsersByType(3);
        return view('Requests.Views.index')
            ->with('data', (Object) $dataList);
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
        // $tokens = Devices::getDevicesBy($requestObj->student_id,true);
        // if(!empty($tokens)){
        //     $this->sendNotification($tokens[0],$msg,$requestObj->course_id);
        // }

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
