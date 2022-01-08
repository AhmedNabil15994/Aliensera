<?php namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class AccountControllers extends Controller {

    use \TraitsFunc;

    protected function validateField($input){
        $rules = [
            'name' => 'required',
            'app_id' => 'required',
            'client_id' => 'required',
            'client_secret' => 'required',
            'access_token' => 'required',
        ];

        $message = [
            'name.required' => "Sorry Title Required",
            'app_id.required' => "Sorry App ID Required",
            'client_id.required' => "Sorry Client ID Required",
            'client_secret.required' => "Sorry Client Secret Required",
            'access_token.required' => "Sorry Access Token Required",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {

        $dataList = Account::dataList();
        return view('Account.Views.index')
            ->with('data', (Object) $dataList);
    }

    public function edit($id) {
        $id = (int) $id;
        $universityObj = Account::getOne($id);
        if($universityObj == null) {
            return Redirect('404');
        }

        $data['data'] = Account::getOne($id);
        return view('Account.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Input::all();

        $universityObj = Account::getOne($id);
        if($universityObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateField($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }


        $universityObj->name = $input['name'];
        $universityObj->app_id = $input['app_id'];
        $universityObj->client_secret = $input['client_secret'];
        $universityObj->client_id = $input['client_id'];
        $universityObj->access_token = $input['access_token'];
        $universityObj->updated_by = USER_ID;
        $universityObj->updated_at = date('Y-m-d H:i:s');
        $universityObj->save();

        \Session::flash('success', "Alert! Update Successfully");
        return \Redirect::back()->withInput();
    }

    public function add() {
        return view('Account.Views.add');
    }

    public function create() {
        $input = \Input::all();
        
        $validate = $this->validateField($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }   
        
        $universityObj = new Account;
        $universityObj->name = $input['name'];
        $universityObj->app_id = $input['app_id'];
        $universityObj->client_secret = $input['client_secret'];
        $universityObj->client_id = $input['client_id'];
        $universityObj->access_token = $input['access_token'];
        $universityObj->created_by = USER_ID;
        $universityObj->created_at = date('Y-m-d H:i:s');
        $universityObj->save();

        \Session::flash('success', "Alert! Create Successfully");
        return redirect()->to('accounts/edit/' . $universityObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $universityObj = Account::getOne($id);
        return \Helper::globalDelete($universityObj);
    }
}
