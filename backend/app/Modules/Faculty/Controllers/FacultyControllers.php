<?php namespace App\Http\Controllers;

use App\Models\University;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class FacultyControllers extends Controller {

    use \TraitsFunc;

    protected function validateFaculty($input){
        $rules = [
            'title' => 'required',
            'university_id' => 'required',
        ];

        $message = [
            'title.required' => "Sorry Title Required",
            'university_id.required' => "Sorry University Required",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {

        $dataList = Faculty::dataList();
        $dataList['universities'] = University::where('status',1)->get();
        return view('Faculty.Views.index')
            ->with('data', (Object) $dataList);
    }

    public function edit($id) {
        $id = (int) $id;
        $facultyObj = Faculty::getOne($id);
        if($facultyObj == null) {
            return Redirect('404');
        }

        $data['data'] = Faculty::getData($facultyObj);
        $data['universities'] = University::where('status',1)->get();
        return view('Faculty.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Input::all();

        $facultyObj = Faculty::getOne($id);
        if($facultyObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateFaculty($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }


        $facultyObj->title = $input['title'];
        $facultyObj->description = $input['description'];
        $facultyObj->university_id = $input['university_id'];
        $facultyObj->status = isset($input['status']) ? 1 : 0;
        $facultyObj->updated_by = USER_ID;
        $facultyObj->updated_at = DATE_TIME;
        $facultyObj->save();

        \Session::flash('success', "Alert! Update Successfully");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['universities'] = University::get();
        return view('Faculty.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Input::all();
        
        $validate = $this->validateFaculty($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }   
        
        $facultyObj = new Faculty;
        $facultyObj->title = $input['title'];
        $facultyObj->description = $input['description'];
        $facultyObj->university_id = $input['university_id'];
        $facultyObj->status = isset($input['status']) ? 1 : 0;
        $facultyObj->created_by = USER_ID;
        $facultyObj->created_at = DATE_TIME;
        $facultyObj->save();

        \Session::flash('success', "Alert! Create Successfully");
        return redirect()->to('faculties/edit/' . $facultyObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $facultyObj = Faculty::getOne($id);
        return \Helper::globalDelete($facultyObj);
    }
}
