<?php namespace App\Http\Controllers;

use App\Models\StudentCourse;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class UpgradeControllers extends Controller {

    use \TraitsFunc;

    public function index() {
        $dataList['data'] = (object) Course::dataList(null,null,null,null,5);
        $dataList['instructors'] = User::getUsersByType(2);
        return view('Upgrade.Views.index')->with('data', (Object) $dataList);
    }

}
