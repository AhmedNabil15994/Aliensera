<?php namespace App\Http\Controllers;

use App\Models\Variable;
use Illuminate\Support\Facades\Input;

class VariablesControllers extends Controller {

    use \TraitsFunc;

    protected function validateVariable() {
        $input = Input::all();

        $rules = [
            'key' => 'required',
            'value' => 'required',
        ];

        $message = [
            'key.required' => "Sorry key Required",
            'value.required' => "Sorry value Required",

        ];

        return \Validator::make($input, $rules, $message);
    }

    public function index() {
        $variableList = Variable::variableList();
        return view('Variables.Views.index')
            ->with('data', (Object) $variableList);
    }

    public function edit($id) {
        $id = (int) $id;

        $variableObj = Variable::getOne($id);

        if($variableObj == null) {
            return Redirect('404');
        }

        $dataObj = new \stdClass();
        $dataObj->id = $variableObj->id;
        $dataObj->key = $variableObj->var_key;
        $dataObj->value = $variableObj->var_value;

        $data['data'] = $dataObj;
        return view('Variables.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $variableObj = Variable::getOne($id);

        if($variableObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateVariable();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $input = Input::all();
        $variableObj->var_key = $input['key'];
        $variableObj->var_value = $input['value'];
        $variableObj->updated_by = USER_ID;
        $variableObj->updated_at = DATE_TIME;
        $variableObj->save();

        \Session::flash('success', "Alert! Update Successfully");
        return \Redirect::back()->withInput();
    }


    public function create() {

        $validate = $this->validateVariable();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $input = Input::all();

        $variableObj = new Variable();
        $variableObj->var_key = $input['key'];
        $variableObj->var_value = $input['value'];
        $variableObj->created_by = USER_ID;
        $variableObj->created_at = DATE_TIME;
        $variableObj->save();

        \Session::flash('success', "Alert! Create Successfully");
        return redirect()->to('variables');
    }

    public function delete($id) {
        $id = (int) $id;
        $variableObj = Variable::getOne($id);
        return \Helper::globalDelete($variableObj);
    }

}
