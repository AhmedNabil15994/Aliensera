<?php namespace App\Http\Controllers;

use App\Models\Devices;
use App\Models\Course;
use App\Models\Field;
use App\Models\StudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class NotificationsControllers extends Controller {

    use \TraitsFunc;

    protected function validateNotification($input){
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'course_type' => 'required',
        ];

        $message = [
            'title.required' => "Sorry Title Required",
            'course_type.required' => "Sorry Course Type Required",
            'description.required' => "Sorry Body Required",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    protected function validateNotification2($input){
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

    public function index() {

        $groupsList['fields'] = Field::where('status',1)->get();
        if(!IS_ADMIN){
            $groupsList['courses'] = Course::dataList(null,null,true)['data'];
        }
        return view('Notifications.Views.add')
            ->with('data', (Object) $groupsList);
    }

    public function create(Request $request) {
        $input = \Input::all();
        
        if(IS_ADMIN){
            $validate = $this->validateNotification($input);
            if($validate->fails()){
                \Session::flash('error', $validate->messages()->first());
                return redirect()->back();
            }

            if($input['course_type'] == 1){
                if(empty($input['field_id'])){
                    \Session::flash('error', "Sorry Field Required");
                    return \Redirect::back()->withInput();
                }
            }elseif($input['course_type'] == 2){
                if(empty($input['university_id'])){
                    \Session::flash('error', "Sorry University Required");
                    return \Redirect::back()->withInput();
                }
                if(empty($input['faculty_id'])){
                    \Session::flash('error', "Sorry Faculty Required");
                    return \Redirect::back()->withInput();
                }
                if(empty($input['year'])){
                    \Session::flash('error', "Sorry Year Required");
                    return \Redirect::back()->withInput();
                }
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

            $users = StudentRequest::NotDeleted()->whereHas('Course',function($courseQuery) use ($input){
                if($input['course_type'] == 1){
                    $courseQuery->whereIn('status',[3,5])->where('field_id',$input['field_id']);
                }elseif($input['course_type'] == 2){
                    $courseQuery->whereIn('status',[3,5])->where('university_id',$input['university_id'])->where('faculty_id',$input['faculty_id'])->where('year',$input['year']);
                }        
            })->where('status',1)->pluck('student_id');
            $tokens = Devices::getDevicesBy($users);
            $tokens = reset($tokens);
            foreach ($tokens as $value) {
                $this->sendNotification($value,$input['title'],$input['description'],$notfImage);
            }    
        }else{
            $validate = $this->validateNotification2($input);
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

            $users = StudentRequest::NotDeleted()->whereHas('Course',function($courseQuery) use ($input){
                if($input['course_id'] != 0){
                    $courseQuery->where('instructor_id',USER_ID);
                }else{
                    $courseQuery->where('id',$input['course_id'])->where('instructor_id',USER_ID);
                }
            })->where('status',1)->pluck('student_id');
            $tokens = Devices::getDevicesBy($users);
            $tokens = reset($tokens);
            // $this->sendNotification2($tokens,$input['title'],$input['description'],$notfImage);
            foreach ($tokens as $value) {
                $this->sendNotification2($value,$input['title'],$input['description'],$notfImage);
            }   
        }

        \Session::flash('success', "Alert! Notification Sent Successfully");
        return redirect()->back();
    }

    public function sendNotification($tokens,$title,$msg,$image){
        $fireBase = new \FireBase();
        $metaData = ['title' => $title, 'body' => $msg,];
        $myData = ['type' => 4, 'image'=>$image,'title' => $title, 'body' => $msg,];
        $fireBase->send_android_notification($tokens,$metaData,$myData);
        return true;
    }

    public function sendNotification2($tokens,$title,$msg,$image){
        $fireBase = new \FireBase();
        $metaData = ['title' => $title, 'body' => $msg,];
        $myData = ['type' => 7, 'image'=>$image,'title' => $title, 'body' => $msg,];
        $fireBase->send_android_notification($tokens,$metaData,$myData);
        return true;
    }

}
