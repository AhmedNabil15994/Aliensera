<?php namespace App\Http\Controllers;

use App\Models\VideoComment;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\StudentCourse;
use App\Models\User;
use Illuminate\Http\Request;

class CommentControllers extends Controller {

    use \TraitsFunc;

    public function index() {
        $dataList = VideoComment::dataList(null,null,null,null,true);
        $dataList['courses'] = Course::dataList(null,null,true)['data'];
        $dataList['lessons'] = Lesson::dataList(null,true)['data'];
        $dataList['instructors'] = User::getUsersByType(2);
        if(IS_ADMIN){
        	$dataList['students'] = User::getUsersByType(3);
        }else{
            $dataList['students'] = User::getInstructorStudents(StudentCourse::where('instructor_id',USER_ID)->where('status',1)->pluck('student_id'));
        }
        return view('Comments.Views.index')
            ->with('data', (Object) $dataList);
    }

}
