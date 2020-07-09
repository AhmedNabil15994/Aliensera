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
        $statusObj = Certificate::dataList();
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }


    public function getCertificate($id) {
        $id = (int) $id;

        $courseObj = Certificate::getOne($id);
        if($courseObj == null) {
            return \TraitsFunc::ErrorMessage("This Certificate not found", 400);
        }
        $statusObj['data'] = Certificate::getData($courseObj);
        $statusObj['data']->link = \URL::to('/certificates/'.encrypt($id).'/downloadCertificate');
        $statusObj['status'] = \TraitsFunc::SuccessResponse("Your Certification Is Ready");
        return \Response::json((object) $statusObj);
    }

    public function download($id){
        $id = decrypt($id);
        $certificateObj = Certificate::where('code',$id)->first();
        if($certificateObj == null) {
            return \TraitsFunc::ErrorMessage("This Certificate not found", 400);
        }
        $instructorObj = User::getData($certificateObj->Instructor);
        $data['course'] = $certificateObj->Course->title;
        $data['instructor'] = $instructorObj->name;
        $data['student'] = $certificateObj->Student->name;
        $data['date'] = date('Y-m-d',strtotime($certificateObj->created_at));
        $data['code'] = $certificateObj->code;
        $data['logo'] = $instructorObj->certificate_logo;
        $pdf = PDF::loadView('certification', $data)->setPaper('a4', 'landscape');
        return $pdf->download('Certification.pdf');
    }

}
