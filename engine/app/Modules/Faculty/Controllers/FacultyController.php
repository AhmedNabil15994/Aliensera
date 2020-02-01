<?php namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller {

    use \TraitsFunc;

    public function index() {
        $input = \Input::all();
        $statusObj['data'] = Faculty::dataList();
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function getOne($id) {
        $facultyObj = Faculty::getOne($id);

        if ($facultyObj == null) {
            return \TraitsFunc::ErrorMessage("This Faculty not found", 400);
        }

        $statusObj['data'] = Faculty::getData($facultyObj);
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }
}
