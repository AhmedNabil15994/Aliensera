<?php namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class PagesControllers extends Controller {

    use \TraitsFunc;

    public function index($title) {
        $title = (string) $title;
        $lessonObj = Page::getOneByTitle($title);
        if($lessonObj == null) {
            return \TraitsFunc::ErrorMessage("This Page not found", 400);
        }

        $statusObj['data'] = Page::getData($lessonObj);
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);   
    }
    
}
