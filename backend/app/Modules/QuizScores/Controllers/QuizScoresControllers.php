<?php namespace App\Http\Controllers;

use App\Models\StudentCourse;
use App\Models\StudentScore;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class QuizScoresControllers extends Controller {

    use \TraitsFunc;

    public function index() {
    	$dataList['data'] = (object) StudentScore::dataList(null,1);
        $dataList['courses'] = Course::dataList(USER_ID)['data'];
        $dataList['students'] = User::getInstructorStudents(StudentCourse::where('instructor_id',USER_ID)->where('status',1)->pluck('student_id'));
        return view('QuizScores.Views.index')->with('data', (Object) $dataList);
    }

}
