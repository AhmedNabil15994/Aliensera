<?php namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class FieldControllers extends Controller {

    use \TraitsFunc;

    protected function validateField($input){
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

        $dataList = Field::dataList();
        return view('Fields.Views.index')
            ->with('data', (Object) $dataList);
    }

    public function edit($id) {
        $id = (int) $id;
        $universityObj = Field::getOne($id);
        if($universityObj == null) {
            return Redirect('404');
        }

        $data['data'] = Field::getOne($id);
        return view('Fields.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Input::all();

        $universityObj = Field::getOne($id);
        if($universityObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateField($input);
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
        return view('Fields.Views.add');
    }

    public function create() {
        $input = \Input::all();
        
        $validate = $this->validateField($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }   
        
        $universityObj = new Field;
        $universityObj->title = $input['title'];
        $universityObj->description = $input['description'];
        $universityObj->status = isset($input['status']) ? 1 : 0;
        $universityObj->created_by = USER_ID;
        $universityObj->created_at = DATE_TIME;
        $universityObj->save();

        \Session::flash('success', "Alert! Create Successfully");
        return redirect()->to('fields/edit/' . $universityObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $universityObj = Field::getOne($id);
        return \Helper::globalDelete($universityObj);
    }
}
