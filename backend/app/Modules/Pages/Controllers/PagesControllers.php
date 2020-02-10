<?php namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class PagesControllers extends Controller {

    use \TraitsFunc;

    protected function validateGroup($input){
        $rules = [
            'title' => 'required',
            'content' => 'required',
        ];

        $message = [
            'title.required' => "Sorry Title Required",
            'content.required' => "Sorry Content Required",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {

        $groupsList = Page::groupsList();
        return view('Pages.Views.index')
            ->with('data', (Object) $groupsList);
    }

    public function edit($id) {
        $id = (int) $id;

        $groupObj = Page::find($id);

        if($groupObj == null) {
            return Redirect('404');
        }

        
        $dataObj = new \stdClass();
        $dataObj->id = $groupObj->id;
        $dataObj->title = $groupObj->title;
        $dataObj->content = $groupObj->content;

        $data['data'] = $dataObj;
        return view('Pages.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Input::all();

        $groupObj = Page::find($id);

        if($groupObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateGroup($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }


        $groupObj->title = $input['title'];
        $groupObj->content = $input['content'];

        $groupObj->save();

        \Session::flash('success', "Alert! Update Successfully");
        return \Redirect::back()->withInput();
    }

    public function add() {
        return view('Pages.Views.add');
    }

    public function create() {
        $input = \Input::all();
        
        $validate = $this->validateGroup($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $groupObj = new Page();
        $groupObj->title = $input['title'];
        $groupObj->content = $input['content'];
        $groupObj->save();

        \Session::flash('success', "Alert! Create Successfully");
        return redirect()->to('pages/edit/' . $groupObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $groupObj = Page::getOne($id);
        return \Helper::globalDelete($groupObj);
    }
}
