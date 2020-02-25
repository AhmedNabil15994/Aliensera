<?php namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonQuestion;
use App\Models\Course;
use App\Models\StudentScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LessonControllers extends Controller {

    use \TraitsFunc;

    public function getOne($id) {
        $id = (int) $id;
        $lessonObj = Lesson::getOne($id);
        if($lessonObj == null) {
            return \TraitsFunc::ErrorMessage("This Lesson not found", 400);
        }

        $statusObj['data'] = Lesson::getData($lessonObj);
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);   
    }

    public function answerQuestion($id){
        $id = (int) $id;
        $input = \Input::all();

        $rules = [
            'question_id' => 'required',
            'answer' => 'required',
        ];

        $message = [
            'question_id.required' => "Sorry Question Required",
            'answer.required' => "Sorry Answer Required",
        ];

        $validate = \Validator::make($input, $rules, $message);
        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }   

        $lessonObj = Lesson::getOne($id);
        if($lessonObj == null) {
            return \TraitsFunc::ErrorMessage("This Lesson not found", 400);
        }

        $courseObj = Course::getOne($lessonObj->course_id);
        if($courseObj == null) {
            return \TraitsFunc::ErrorMessage("This Course not found", 400);
        }

        $questionObj = LessonQuestion::getOne($input['question_id']);
        if($questionObj == null) {
            return \TraitsFunc::ErrorMessage("This Lesson Question not found", 400);
        }

        if(!in_array($input['answer'], ['a','b','c','d'])){
            return \TraitsFunc::ErrorMessage("Please Select Answer", 400);
        }

        $scoreObj = StudentScore::where('student_id',USER_ID)->where('lesson_id',$id)->where('question_id',$input['question_id'])->first();
        if($scoreObj == null){
            $scoreObj = new StudentScore;
            $scoreObj->student_id = USER_ID;
            $scoreObj->lesson_id = $id;
            $scoreObj->course_id = $lessonObj->course_id;
            $scoreObj->instructor_id = $courseObj->instructor_id;
            $scoreObj->question_id = $input['question_id'];
            $scoreObj->answer = $input['answer'];
            $scoreObj->correct = $input['answer'] == $questionObj->correct_answer ? 1 : 0;
            $scoreObj->created_by = USER_ID;
            $scoreObj->created_at = DATE_TIME;
            $scoreObj->save();
        }


        $statusObj['status'] = \TraitsFunc::SuccessResponse("Answer Added Successfully");
        return \Response::json((object) $statusObj);   
    }

}
