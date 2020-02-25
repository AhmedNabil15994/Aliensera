<?php namespace App\Http\Controllers;

use App\Models\StudentScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class QuestionControllers extends Controller {

    use \TraitsFunc;

    public function index() {
        $input = \Input::all();
        $statusObj['data'] = StudentScore::dataList();
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function getOne($id) {
        $id = (int) $id;
        $questionObj = StudentScore::getOne($id);
        if($questionObj == null) {
            return \TraitsFunc::ErrorMessage("This Question not found", 400);
        }

        $statusObj['data'] = StudentScore::getData($questionObj);
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);   
    }
}
