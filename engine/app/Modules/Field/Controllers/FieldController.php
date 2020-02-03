<?php namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller {

    use \TraitsFunc;

    public function index() {
        $input = \Input::all();
        $statusObj['data'] = Field::dataList();
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function getOne($id) {
        $universityObj = Field::getOne($id);
        if ($universityObj == null) {
            return \TraitsFunc::ErrorMessage("This Field not found", 400);
        }

        $statusObj['data'] = Field::getData($universityObj);
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

}
