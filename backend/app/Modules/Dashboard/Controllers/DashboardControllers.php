<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Course;
use App\Models\LessonVideo;
use App\Models\Group;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class DashboardControllers extends Controller {

    use \TraitsFunc;

    public function Dashboard() {
    	$dataList['allStudents'] = User::getUsersByType(3)->count();
    	$dataList['allInstructors'] = User::getUsersByType(2)->count();
    	$dataList['allCourses'] = Course::NotDeleted()->where('status',3)->count();
    	$dataList['allVideos'] = LessonVideo::NotDeleted()->count();
        return view('Dashboard.Views.dashboard')->with('data', (Object) $dataList);
    }

}
