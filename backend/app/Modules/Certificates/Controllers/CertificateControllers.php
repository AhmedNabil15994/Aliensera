<?php namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use PDF;

class CertificateControllers extends Controller {

    use \TraitsFunc;

    public function index() {
        $dataList['data'] = (object) Certificate::dataList();
        $dataList['instructors'] = User::getUsersByType(2);
        $dataList['students'] = User::getUsersByType(3);
        $dataList['courses'] = Course::latest()->get();
        return view('Certificates.Views.index')->with('data', (Object) $dataList);
    }

    public function download($id){

    	$certificateObj = Certificate::getOne($id);
        if($certificateObj == null) {
        	\Session::flash('error', 'This Certificate not found');
            return redirect()->back()->withInput();
        }
        $instructorObj = User::getData($certificateObj->Instructor);
        $data['course'] = $certificateObj->Course->title;
        $data['instructor'] = $instructorObj->name;
        $data['student'] = $certificateObj->Student->name;
        $data['date'] = date('Y-m-d',strtotime($certificateObj->created_at));
        $data['code'] = $certificateObj->code;
        $data['logo'] = $instructorObj->logo;
        $pdf = PDF::loadView('Certificates.Views.certification', $data)->setPaper('a4', 'landscape');
        return $pdf->download('Certification.pdf');
    }

}
