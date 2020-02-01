<?php namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller {

    use \TraitsFunc;

    public function index() {
        $input = \Input::all();
        $statusObj['data'] = University::dataList();
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function getOne($id) {
        $universityObj = University::getOne($id);
        if ($universityObj == null) {
            return \TraitsFunc::ErrorMessage("This University not found", 400);
        }

        $statusObj['data'] = University::getData($universityObj);
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

}
