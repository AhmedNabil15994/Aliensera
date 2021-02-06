<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\University;
use App\Models\Field;
use App\Models\Faculty;
use App\Models\CoursePrice;
use App\Models\Lesson;
use App\Models\LessonVideo;
use App\Models\Devices;
use App\Models\InstructorRate;
use App\Models\CourseFeedback;
use App\Models\CourseDiscussion;
use App\Models\StudentScore;
use App\Models\StudentVideoDuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class CoursesControllers extends Controller {

    use \TraitsFunc;

    protected function validateCourse($input){
        $rules = [
            'title' => 'required',
            'instructor_id' => 'required',
            'status' => 'required',
            'course_type' => 'required',
            'field_id' => 'required',
        ];

        $message = [
            'title.required' => "Sorry Title Required",
            'instructor_id.required' => "Sorry Instructor Required",
            'course_type.required' => "Sorry Course Type Required",
            'field_id.required' => "Sorry Course Field Required",
            'status.required' => "Sorry Status Required",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $dataList = Course::dataList(null,null,null,null,null,'courses');
        $dataList['fields'] = Field::where('status',1)->get();
        $dataList['instructors'] = User::getUsersByType(2);
        return view('Courses.Views.index')
            ->with('data', (Object) $dataList);
    }

    public function edit($id) {
        $id = (int) $id;

        $courseObj = Course::getOne($id);
        if($courseObj == null) {
            return Redirect('404');
        }

        $data['data'] = Course::getData($courseObj);
        $data['fields'] = Field::where('status',1)->get();
        $data['universities'] = University::where('status',1)->get();
        $data['faculties'] = Faculty::where('status',1)->where('university_id',$courseObj->university_id)->get();
        $data['instructors'] = User::getUsersByType(2);
        return view('Courses.Views.edit')->with('data', (object) $data);      
    }

    public function view($id) {
        $id = (int) $id;

        $courseObj = Course::getOne($id);
        if($courseObj == null) {
            return Redirect('404');
        }

        $data['data'] = Course::getData($courseObj,'course');
        $data['fields'] = Field::where('status',1)->get();
        $data['universities'] = University::where('status',1)->get();
        $data['faculties'] = Faculty::where('status',1)->where('university_id',$courseObj->university_id)->get();
        $data['instructors'] = User::getUsersByType(2);
        return view('Courses.Views.view')->with('data', (object) $data);      
    }

    public function discussion($id) {
        $id = (int) $id;

        $courseObj = Course::getOne($id);
        if($courseObj == null) {
            return Redirect('404');
        }

        $data = CourseDiscussion::dataList($id,true);
        $data['course'] = Course::getData($courseObj,true);
        return view('Courses.Views.discussion')->with('data', (object) $data);      
    }

    public function addDiscussion($id){
        $input = \Input::all();
        $rules = [
            'comment' => 'required',
        ];

        $message = [
            'comment.required' => "Sorry Comment Required",
        ];

        $validate = \Validator::make($input, $rules, $message);
        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }   

        $courseObj = Course::getOne($id);
        if($courseObj == null){
            return \TraitsFunc::ErrorMessage('This Course Not Found !!', 400);
        }

        if($input['reply'] != 0){
            $commentObj = CourseDiscussion::getOne($input['reply']);
            if($commentObj == null){
                return \TraitsFunc::ErrorMessage('This Comment Not Found !!', 400);
            }
            $input['reply'] = $commentObj->reply_on != 0 ? $commentObj->reply_on : $input['reply'];
            if($commentObj->reply_on == 0 ){
                if($commentObj->created_by == USER_ID){
                    return \TraitsFunc::ErrorMessage("You Can't Reply To Your Comment!!", 400);
                }
            }
            $replier = User::getData(User::getOne(USER_ID));
            $msg = $replier->name.' replied on your comment';
            $tokens = Devices::getDevicesBy($commentObj->created_by,true);
            $fireBase = new \FireBase();
            $metaData = ['title' => "New Comment", 'body' => $msg,];
            $myData = ['type' => 4 , 'id' => $id];
            $fireBase->send_android_notification($tokens[0],$metaData,$myData);
        }

        $commentObj = new CourseDiscussion;
        $commentObj->comment = $input['comment'];
        $commentObj->reply_on = $input['reply'];
        $commentObj->course_id = $id;
        $commentObj->status = 1;
        $commentObj->created_by = USER_ID;
        $commentObj->created_at = date('Y-m-d H:i:s');
        $commentObj->save();

        $statusObj['status'] = \TraitsFunc::SuccessResponse('Comment Saved Successfully !!');
        $statusObj['data'] = CourseDiscussion::getData($commentObj);
        return $statusObj;
    }

    public function removeDiscussion($comment_id){
        $commentObj = CourseDiscussion::getOne($comment_id);
        if($commentObj == null){
            return \TraitsFunc::ErrorMessage('This Comment Not Found !!', 400);
        }
        return \Helper::globalDelete($commentObj);
    }


    public function movableLessons($id){
        $id = (int) $id;

        $courseObj = Course::getOne($id);
        if($courseObj == null) {
            return Redirect('404');
        }
        $input = \Input::all();
        $lessonObj = Lesson::getOne($input['lesson_id']);
        if($lessonObj == null) {
            return Redirect('404');
        }
        $data = Lesson::NotDeleted()->where('status',1)->where('course_id',$id)->where('id','!=',$input['lesson_id'])->get();
        return \Response::json((object) $data );
    }

    public function moveVideo($id){
        $id = (int) $id;

        $courseObj = Course::getOne($id);
        if($courseObj == null) {
            return Redirect('404');
        }
        $input = \Input::all();
        $oldLessonObj = Lesson::getOne($input['old_lesson_id']);
        if($oldLessonObj == null) {
            return Redirect('404');
        }
        $lessonObj = Lesson::getOne($input['lesson_id']);
        if($lessonObj == null) {
            return Redirect('404');
        }
        $videoObj = LessonVideo::getOne($input['video_id']);
        if($videoObj == null) {
            return Redirect('404');
        }

        LessonVideo::where('id',$input['video_id'])->where('lesson_id',$input['old_lesson_id'])->update(['lesson_id'=>$input['lesson_id']]);
        StudentVideoDuration::where('video_id',$input['video_id'])->where('lesson_id',$input['old_lesson_id'])->update(['lesson_id'=>$input['lesson_id']]);

        \Session::flash('success', "Moving Video Success !!");
        return 1;
    }

    public function sortLesson($id) {
        $id = (int) $id;

        $courseObj = Course::getOne($id);
        if($courseObj == null) {
            return Redirect('404');
        }

        $input = \Input::all();
        $ids = json_decode($input['ids']);
        $sorts = json_decode($input['sorts']);

        for ($i = 0; $i < count($ids) ; $i++) {
            Lesson::where('id',$ids[$i])->update(['sort'=>$sorts[$i]]);
        }
        return 'sorted';
    }

    public function sortVideo($id) {
        $id = (int) $id;

        $courseObj = Course::getOne($id);
        if($courseObj == null) {
            return Redirect('404');
        }

        $input = \Input::all();
        $ids = json_decode($input['ids']);
        $sorts = json_decode($input['sorts']);

        for ($i = 0; $i < count($ids) ; $i++) {
            LessonVideo::where('lesson_id',$input['lesson_id'])->where('id',$ids[$i])->update(['sort'=>$sorts[$i]]);
        }
        return 'sorted';
    }

    public function update($id,Request $request) {
        $id = (int) $id;
        $input = \Input::all();
        if(IS_ADMIN == false){
            $input['instructor_id'] = USER_ID;
            $input['status'] = 1;
        }
        $courseObj = Course::getOne($id);
        if($courseObj == null) {
            return Redirect('404');
        }

        $old_title = $courseObj->title;
        $coursePriceObj = $courseObj->CoursePrice;

        $validate = $this->validateCourse($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        if($input['course_type'] == 2){

            if(empty($input['university_id']) || !isset($input['university_id'])){
                \Session::flash('error', 'Sorry University Required');
                return redirect()->back()->withInput();
            }

            if(empty($input['faculty_id']) || !isset($input['faculty_id'])){
                \Session::flash('error', 'Sorry Faculty Required');
                return redirect()->back()->withInput();
            }

            if(empty($input['year']) || !isset($input['year'])){
                \Session::flash('error', 'Sorry Level Required');
                return redirect()->back()->withInput();
            }

            $universityObj = University::getOne($input['university_id']);
            if($universityObj == null) {
                return Redirect('404');
            }

            $facultyObj = Faculty::getOneByUniversity($input['faculty_id'],$input['university_id']);
            if($facultyObj == null) {
                return Redirect('404');
            }

            if(isset($input['year']) && !empty($input['year']) ){
                if($input['year'] < 1){
                    \Session::flash('error', 'Year Must Be Greater than or equal to 1');
                    return redirect()->back()->withInput();
                }
                if ($input['year'] > 0 && $input['year'] > $facultyObj->number_of_years) {
                    \Session::flash('error', "Year Must Be Less than or equal to ".$facultyObj->number_of_years);
                    return redirect()->back()->withInput();
                }
            }    

        }

        $courseObj->title = $input['title'];
        $courseObj->description = $input['description'];
        $courseObj->instructor_id = $input['instructor_id'];
        if(IS_ADMIN){
            $courseObj->status = $input['status'];
        }
        $courseObj->course_type = $input['course_type'];
        $courseObj->field_id = $input['field_id'];
        $courseObj->price = isset($input['price']) ? $input['price'] : 0;
        $courseObj->what_learn = $input['what_learn'];
        $courseObj->requirements = $input['requirements'];
        if($input['status'] ==3){
            $courseObj->valid_until = !empty($input['end_date']) ? date('Y-m-d',strtotime($input['end_date'])) : null;
        }
        if($input['course_type'] == 2){
            $courseObj->university_id = $input['university_id'];
            $courseObj->faculty_id = $input['faculty_id'];
            $courseObj->year = $input['year'];
        }else{
            $courseObj->university_id = null;
            $courseObj->faculty_id = null;
            $courseObj->year = null;
        }
        $courseObj->updated_by = USER_ID;
        $courseObj->updated_at = DATE_TIME;
        $courseObj->save();
        if($courseObj->status == 3 && !IS_ADMIN){
            if(( isset($coursePriceObj) && $coursePriceObj->upload_space != $input['upload_space'] ) || (isset($coursePriceObj) && $coursePriceObj->approval_number != $input['approval_number']) || ( $input['course_duration'] != \Carbon\Carbon::parse($courseObj->valid_until)->diffInDays(\Carbon\Carbon::parse($courseObj->created_at2)) ) ){

                if(isset($coursePriceObj)){
                    $coursePriceObj->updated_upload_space = $input['upload_space'];
                    $coursePriceObj->updated_upload_cost = $input['upload_space'] * 25;
                    $coursePriceObj->updated_course_duration = $input['course_duration'];
                    $coursePriceObj->updated_start_date = $input['start_date'];
                    $coursePriceObj->updated_end_date = $input['end_date'];
                    $coursePriceObj->updated_approval_number = $input['approval_number'];
                    $coursePriceObj->updated_approval_cost = round((2/3) * $input['course_duration'] * $input['approval_number']);
                    $coursePriceObj->updated_by = USER_ID;
                    $coursePriceObj->updated_at = DATE_TIME;
                }else{
                    $coursePriceObj = new CoursePrice;
                    $coursePriceObj->upload_space = $input['upload_space'];
                    $coursePriceObj->upload_cost = $input['upload_space'] * 25;
                    $coursePriceObj->course_duration = $input['course_duration'];
                    $coursePriceObj->start_date = $input['start_date'];
                    $coursePriceObj->end_date = $input['end_date'];
                    $coursePriceObj->approval_number = $input['approval_number'];
                    $coursePriceObj->approval_cost = round((2/3) * $input['course_duration'] * $input['approval_number']);
                    $coursePriceObj->created_by = USER_ID;
                    $coursePriceObj->created_at = DATE_TIME;
                }

                $coursePriceObj->instructor_id = $courseObj->instructor_id;
                $coursePriceObj->course_id = $courseObj->id;               
                $coursePriceObj->save();

                $courseObj->status = 5;
                $courseObj->save();
            }

        }elseif($courseObj->status == 1 && !IS_ADMIN){
            $coursePriceObj = isset($courseObj->CoursePrice) ? $courseObj->CoursePrice : new CoursePrice; 
            $coursePriceObj->start_date = $input['start_date'];
            $coursePriceObj->end_date = $input['end_date'];
            $coursePriceObj->course_duration = $input['course_duration'];
            $coursePriceObj->instructor_id = $courseObj->instructor_id;
            $coursePriceObj->course_id = $courseObj->id;
            $coursePriceObj->upload_space = $input['upload_space'];
            $coursePriceObj->upload_cost = $input['upload_space'] * 25;
            $coursePriceObj->approval_number = $input['approval_number'];
            $coursePriceObj->approval_cost = round((2/3) * $input['course_duration'] * $input['approval_number']);
            $coursePriceObj->save();
        }

        if ($request->hasFile('image')) {
            $files = $request->file('image');
            $images = self::addImage($files, $courseObj->id);
            if ($images == false) {
                \Session::flash('error', "Upload images Failed");
                return \Redirect::back()->withInput();
            }
        }

        if($old_title != $input['title']){
            $vimeoObj = new \Vimeos();
            $project_id = $vimeoObj->renameFolder($input['title'],$courseObj->project_id);
        }

        \Session::flash('success', "Alert! Update Successfully");
        return \Redirect::back()->withInput();
    }

    public function upgrade($id,$status){
        $id = (int) $id;
        $status = (int) $status;
        $courseObj = Course::getOne($id);
        if($courseObj == null) {
            return Redirect('404');
        }

        if(!in_array($status, [1,2])){
            return \Redirect::back()->withInput();
        }

        $coursePriceObj = $courseObj->CoursePrice;

        $courseObj->status = 3;
        $courseObj->save();
    
        if($status == 1){
            $courseObj->valid_until = $coursePriceObj->updated_end_date;
            $courseObj->save();
            if(!empty($coursePriceObj->updated_start_date)){
                $coursePriceObj->start_date = $coursePriceObj->updated_start_date;
            }
            if(!empty($coursePriceObj->updated_end_date)){
                $coursePriceObj->end_date = $coursePriceObj->updated_end_date;
            }
            if(!empty($coursePriceObj->updated_course_duration)){
                $coursePriceObj->course_duration = $coursePriceObj->updated_course_duration;
            }
            if(!empty($coursePriceObj->updated_upload_space) && !empty($coursePriceObj->updated_upload_cost)){
                $coursePriceObj->upload_space = $coursePriceObj->updated_upload_space;
                $coursePriceObj->upload_cost = $coursePriceObj->updated_upload_cost;
            }
            if(!empty($coursePriceObj->updated_approval_number) && !empty($coursePriceObj->updated_approval_cost)){
                $coursePriceObj->approval_number = $coursePriceObj->updated_approval_number;
                $coursePriceObj->approval_cost = $coursePriceObj->updated_approval_cost;
            }
        }

        $coursePriceObj->updated_start_date = null;
        $coursePriceObj->updated_end_date = null;
        $coursePriceObj->updated_course_duration = null;
        $coursePriceObj->updated_upload_space = null;
        $coursePriceObj->updated_upload_cost = null;
        $coursePriceObj->updated_approval_number = null;
        $coursePriceObj->updated_approval_cost = null;
        $coursePriceObj->updated_at = null;
        $coursePriceObj->updated_by = null;
        $coursePriceObj->save();

        \Session::flash('success', "Alert! Update Successfully");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $dataList['instructors'] = User::getUsersByType(2);
        $dataList['fields'] = Field::where('status',1)->get();
        return view('Courses.Views.add')->with('data', (Object) $dataList);
    }

    public function create(Request $request) {
        $input = \Input::all();
        if(IS_ADMIN == false){
            $input['instructor_id'] = USER_ID;
            $input['status'] = 1;
            $input['approval_number'] = $input['approval_number'] % 5 != 0 ? floor($input['approval_number'] / 5) * 5 : $input['approval_number'];

        }
        $validate = $this->validateCourse($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }   
        

        if($input['course_type'] == 2){

            if(empty($input['university_id']) || !isset($input['university_id'])){
                \Session::flash('error', 'Sorry University Required');
                return redirect()->back()->withInput();
            }

            if(empty($input['faculty_id']) || !isset($input['faculty_id'])){
                \Session::flash('error', 'Sorry Faculty Required');
                return redirect()->back()->withInput();
            }

            if(empty($input['year']) || !isset($input['year'])){
                \Session::flash('error', 'Sorry Level Required');
                return redirect()->back()->withInput();
            }

            $universityObj = University::getOne($input['university_id']);
            if($universityObj == null) {
                return Redirect('404');
            }

            $facultyObj = Faculty::getOneByUniversity($input['faculty_id'],$input['university_id']);
            if($facultyObj == null) {
                return Redirect('404');
            }

            if(isset($input['year']) && !empty($input['year']) ){
                if($input['year'] < 1){
                    \Session::flash('error', 'Year Must Be Greater than or equal to 1');
                    return redirect()->back()->withInput();
                }
                if ($input['year'] > 0 && $input['year'] > $facultyObj->number_of_years) {
                    \Session::flash('error', "Year Must Be Less than or equal to ".$facultyObj->number_of_years);
                    return redirect()->back()->withInput();
                }
            }    
        }

        $courseObj = new Course;
        $courseObj->title = $input['title'];
        $courseObj->description = $input['description'];
        $courseObj->instructor_id = $input['instructor_id'];
        $courseObj->field_id = $input['field_id'];
        $courseObj->status = $input['status'];
        $courseObj->course_type = $input['course_type'];
        $courseObj->price = isset($input['price']) ? $input['price'] : 0;
        $courseObj->what_learn = $input['what_learn'];
        $courseObj->requirements = $input['requirements'];
        $courseObj->valid_until = !empty($input['valid_until']) ? date('Y-m-d',strtotime($input['valid_until'])) : $input['end_date'];
        if($input['course_type'] == 2){
            $courseObj->university_id = $input['university_id'];
            $courseObj->faculty_id = $input['faculty_id'];
            $courseObj->year = $input['year'];
        }else{
            $courseObj->university_id = null;
            $courseObj->faculty_id = null;
            $courseObj->year = null;
        }
        $courseObj->created_by = USER_ID;
        $courseObj->created_at = DATE_TIME;
        $courseObj->save();

        if ($request->hasFile('image')) {
            $files = $request->file('image');
            $images = self::addImage($files, $courseObj->id);
            if ($images == false) {
                \Session::flash('error', "Upload images Failed");
                return \Redirect::back()->withInput();
            }
        }

        if(IS_ADMIN){
            $vimeoObj = new \Vimeos();
            $project_id = $vimeoObj->createFolder($courseObj->title);
            $courseObj->project_id = $project_id;
            $courseObj->save();
        }else{
            $coursePriceObj = new CoursePrice;
            $coursePriceObj->course_id = $courseObj->id;
            $coursePriceObj->instructor_id = $courseObj->instructor_id;
            $coursePriceObj->start_date = $input['start_date'];
            $coursePriceObj->end_date = $input['end_date'];
            $coursePriceObj->course_duration = $input['course_duration'];
            $coursePriceObj->upload_space = $input['upload_space'];
            $coursePriceObj->upload_cost = $input['upload_space'] * 25;
            $coursePriceObj->approval_number = $input['approval_number'];
            $coursePriceObj->approval_cost = round((2/3) * $input['course_duration'] * $input['approval_number']);
            $coursePriceObj->created_by = USER_ID;
            $coursePriceObj->created_at = DATE_TIME;
            $coursePriceObj->save();
        }

        \Session::flash('success', "Alert! Create Successfully");
        return redirect()->to('courses/edit/' . $courseObj->id);
    }

    public function getUniversities(){
        return \Response::json((object) University::NotDeleted()->where('status',1)->get());
    }

    public function getFaculties($university_id){
        return \Response::json((object) Faculty::NotDeleted()->where('status',1)->where('university_id',$university_id)->get());
    }

    public function getLessons($course_id){
        return \Response::json((object) Lesson::NotDeleted()->where('status',1)->where('course_id',$course_id)->get());
    }

    public function addImage($images, $id) {
        $fileName = \ImagesHelper::UploadImage('courses', $images, $id);
        if($fileName == false){
            return false;
        }
        $courseObj = Course::find($id);
        $courseObj->image = $fileName;
        $courseObj->save();
       
        return true;
    }

    public function imageDelete($id) {
        $id = (int) $id;
        $courseObj = Course::find($id);
        $courseObj->image = '';
        $courseObj->save();

        $data['status'] = \TraitsFunc::SuccessResponse("Deleted Successfully");
        return response()->json($data);
    }

    public function delete($id) {
        $id = (int) $id;
        $courseObj = Course::getOne($id);
        return \Helper::globalDelete($courseObj);
    }

    public function deleteReview($id) {
        $id = (int) $id;
        $courseObj = CourseFeedback::getOne($id);
        return \Helper::globalDelete($courseObj);
    }

    public function deleteRate($id) {
        $id = (int) $id;
        $courseObj = InstructorRate::getOne($id);
        return \Helper::globalDelete($courseObj);
    }

    public function restore($id) {
        $id = (int) $id;
        $courseObj = Course::getOneD($id);
        return \Helper::globalRestore($courseObj);
    }
}
