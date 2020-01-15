<?php namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class UniversityControllers extends Controller {

    use \TraitsFunc;

    protected function validateUniversity($input){
        $rules = [
            'title' => 'required',
        ];

        $message = [
            'title.required' => "Sorry Title Required",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {

        $dataList = University::dataList();
        return view('University.Views.index')
            ->with('data', (Object) $dataList);
    }

    public function edit($id) {
        $id = (int) $id;
        $universityObj = University::getOne($id);
        if($universityObj == null) {
            return Redirect('404');
        }

        $data['data'] = University::getOne($id);
        return view('University.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Input::all();

        $universityObj = University::getOne($id);
        if($universityObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateUniversity($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }


        $universityObj->title = $input['title'];
        $universityObj->description = $input['description'];
        $universityObj->status = isset($input['status']) ? 1 : 0;
        $universityObj->updated_by = USER_ID;
        $universityObj->updated_at = DATE_TIME;
        $universityObj->save();

        \Session::flash('success', "Alert! Update Successfully");
        return \Redirect::back()->withInput();
    }

    public function add() {
        return view('University.Views.add');
    }

    public function create() {
        $input = \Input::all();
        
        $validate = $this->validateUniversity($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }   
        
        $universityObj = new University;
        $universityObj->title = $input['title'];
        $universityObj->description = $input['description'];
        $universityObj->status = isset($input['status']) ? 1 : 0;
        $universityObj->created_by = USER_ID;
        $universityObj->created_at = DATE_TIME;
        $universityObj->save();

        \Session::flash('success', "Alert! Create Successfully");
        return redirect()->to('universities/edit/' . $universityObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $universityObj = University::getOne($id);
        return \Helper::globalDelete($universityObj);
    }
}
