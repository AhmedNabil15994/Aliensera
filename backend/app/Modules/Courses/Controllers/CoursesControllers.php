<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\University;
use App\Models\Field;
use App\Models\Faculty;
use App\Models\CourseFeedback;
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
        $dataList = Course::dataList();
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

        $data['data'] = Course::getData($courseObj);
        $data['fields'] = Field::where('status',1)->get();
        $data['universities'] = University::where('status',1)->get();
        $data['faculties'] = Faculty::where('status',1)->where('university_id',$courseObj->university_id)->get();
        $data['instructors'] = User::getUsersByType(2);
        return view('Courses.Views.view')->with('data', (object) $data);      
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

            $universityObj = University::getOne($input['university_id']);
            if($universityObj == null) {
                return Redirect('404');
            }

            $facultyObj = Faculty::getOneByUniversity($input['faculty_id'],$input['university_id']);
            if($facultyObj == null) {
                return Redirect('404');
            }
        }

        $courseObj->title = $input['title'];
        $courseObj->description = $input['description'];
        $courseObj->instructor_id = $input['instructor_id'];
        $courseObj->status = $input['status'];
        $courseObj->course_type = $input['course_type'];
        $courseObj->field_id = $input['field_id'];
        $courseObj->price = $input['price'];
        $courseObj->valid_until = date('Y-m-d',strtotime($input['valid_until']));
        if($input['course_type'] == 2){
            $courseObj->university_id = $input['university_id'];
            $courseObj->faculty_id = $input['faculty_id'];
        }else{
            $courseObj->university_id = null;
            $courseObj->faculty_id = null;
        }
        $courseObj->updated_by = USER_ID;
        $courseObj->updated_at = DATE_TIME;
        $courseObj->save();

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

            $universityObj = University::getOne($input['university_id']);
            if($universityObj == null) {
                return Redirect('404');
            }

            $facultyObj = Faculty::getOneByUniversity($input['faculty_id'],$input['university_id']);
            if($facultyObj == null) {
                return Redirect('404');
            }
        }

        $courseObj = new Course;
        $courseObj->title = $input['title'];
        $courseObj->description = $input['description'];
        $courseObj->instructor_id = $input['instructor_id'];
        $courseObj->field_id = $input['field_id'];
        $courseObj->status = $input['status'];
        $courseObj->course_type = $input['course_type'];
        $courseObj->price = $input['price'];
        $courseObj->valid_until = date('Y-m-d',strtotime($input['valid_until']));
        if($input['course_type'] == 2){
            $courseObj->university_id = $input['university_id'];
            $courseObj->faculty_id = $input['faculty_id'];
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

        $vimeoObj = new \Vimeos();
        $project_id = $vimeoObj->createFolder($courseObj->title);
        $courseObj->project_id = $project_id;
        $courseObj->save();

        \Session::flash('success', "Alert! Create Successfully");
        return redirect()->to('courses/edit/' . $courseObj->id);
    }

    public function getUniversities(){
        return \Response::json((object) University::where('status',1)->get());
    }

    public function getFaculties($university_id){
        return \Response::json((object) Faculty::where('status',1)->where('university_id',$university_id)->get());
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

    public function restore($id) {
        $id = (int) $id;
        $courseObj = Course::getOneD($id);
        return \Helper::globalRestore($courseObj);
    }
}
