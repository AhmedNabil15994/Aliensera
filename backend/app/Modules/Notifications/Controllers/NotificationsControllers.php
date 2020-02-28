<?php namespace App\Http\Controllers;

use App\Models\Devices;
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

    public function index() {

        $groupsList['fields'] = Field::where('status',1)->get();
        return view('Notifications.Views.add')
            ->with('data', (Object) $groupsList);
    }

    public function create(Request $request) {
        $input = \Input::all();
        
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
            dd($notfImage);
            
        }
        dd($notfImage);

        $users = StudentRequest::NotDeleted()->whereHas('Course',function($courseQuery) use ($input){
            if($input['course_type'] == 1){
                $courseQuery->where('status',3)->where('field_id',$input['field_id']);
            }elseif($input['course_type'] == 2){
                $courseQuery->where('status',3)->where('university_id',$input['university_id'])->where('faculty_id',$input['faculty_id'])->where('year',$input['year']);
            }        
        })->where('status',1)->pluck('student_id');
        $tokens = Devices::getDevicesBy($users);
        $tokens = reset($tokens);
        foreach ($tokens as $value) {
            $this->sendNotification($value,$input['title'],$input['description'],$notfImage);
        }

        \Session::flash('success', "Alert! Notification Sent Successfully");
        return redirect()->back();
    }

      public function sendNotification($tokens,$title,$msg,$image){
        $fireBase = new \FireBase();
        $metaData = ['title' => $title, 'body' => $msg,];
        $myData = ['type' => 4, 'image'=>$image,];
        $fireBase->send_android_notification($tokens,$metaData,$myData);
        return true;
    }

}
