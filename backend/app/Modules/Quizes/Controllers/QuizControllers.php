<?php namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Course;
use App\Models\StudentRequest;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class QuizControllers extends Controller {

    use \TraitsFunc;

    protected function validateQuiz($input){
        $rules = [
            'course_id' => 'required',
            'lesson_id' => 'required',
            'questions' => 'required',
            'quiz_type' => 'required',
        ];

        $message = [
            'course_id.required' => "Sorry Course Required",
            'lesson_id.required' => "Sorry Lesson Required",
            'questions.required' => "Sorry Questions Required",
            'quiz_type.required' => 'Sorry Quiz Type Required',            
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    protected function validateQuiz2($input){
        $rules = [
            'course_id' => 'required',
            'lesson_id' => 'required',
            'quiz_type' => 'required',
        ];

        $message = [
            'course_id.required' => "Sorry Course Required",
            'lesson_id.required' => "Sorry Lesson Required",
            'quiz_type.required' => 'Sorry Quiz Type Required',            
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $dataList = Quiz::dataList();
        $dataList['courses'] = Course::latest()->get();
        $dataList['lessons'] = Lesson::latest()->get();
        return view('Quizes.Views.index')
            ->with('data', (Object) $dataList);
    }

    public function add() {
        $dataList['courses'] = Course::latest()->get();
        $dataList['lessons'] = Lesson::latest()->get();
        return view('Quizes.Views.add')->with('data', (object) $dataList);
    }

    public function create() {
        $input = \Input::all();
        
        $validate = $this->validateQuiz($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }   
        
        $quizObj = new Quiz;
        $quizObj->course_id = $input['course_id'];
        $quizObj->quiz_type = $input['quiz_type'];
        $quizObj->lesson_id = implode(",", $input['lesson_id']);
        $quizObj->created_by = USER_ID;
        $quizObj->created_at = DATE_TIME;
        $quizObj->save();

        $questions = json_decode($input['questions']);
        for ($i = 0; $i < count($questions) ; $i++) {
            $quizQuestionObj = new QuizQuestion;
            $quizQuestionObj->quiz_id = $quizObj->id;
            $quizQuestionObj->number_of_answers = $questions[$i][0];
            $quizQuestionObj->question = $questions[$i][1];
            $quizQuestionObj->answer_a = $questions[$i][2];
            $quizQuestionObj->answer_b = $questions[$i][3];
            $quizQuestionObj->answer_c = $questions[$i][4];
            $quizQuestionObj->answer_d = $questions[$i][5];
            $quizQuestionObj->answer_e = $questions[$i][6];
            $quizQuestionObj->correct_answer = $questions[$i][7];
            $quizQuestionObj->created_by = USER_ID;
            $quizQuestionObj->created_at = DATE_TIME;
            $quizQuestionObj->save();   
        }

        $msg = 'New Independent Quiz Added To Course '.$quizObj->Course->title;
        $users = StudentRequest::NotDeleted()->where('course_id',$input['course_id'])->where('status',1)->pluck('student_id');
        $tokens = Devices::getDevicesBy($users);
        $tokens = reset($tokens);
        $fireBase = new \FireBase();
        $metaData = ['title' => "New Independent Quiz", 'body' => $msg,];
        $myData = ['type' => 6 , 'id' => $quizObj->id, 'lesson_id' => $input['lesson_id'], 'course_id'=> $lessonObj->course_id];

        foreach ($tokens as $value) {
            $fireBase->send_android_notification($value,$metaData,$myData);
        }


        \Session::flash('success', "Alert! Create Successfully");
        return redirect()->to('quizes/edit/' . $quizObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $universityObj = Quiz::getOne($id);
        return \Helper::globalDelete($universityObj);
    }

    public function edit($id) {
        $id = (int) $id;
        $universityObj = Quiz::getOne($id);
        if($universityObj == null) {
            return Redirect('404');
        }

        $data['data'] = Quiz::getData($universityObj);
        $data['courses'] = Course::latest()->get();
        $data['lessons'] = Lesson::latest()->get();
        return view('Quizes.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Input::all();

        $quizObj = Quiz::getOne($id);
        if($quizObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateQuiz2($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $quizObj->course_id = $input['course_id'];
        $quizObj->quiz_type = $input['quiz_type'];
        $quizObj->lesson_id = implode(",", $input['lesson_id']);
        $quizObj->status = isset($input['status']) ? 1 : 0;
        $quizObj->updated_by = USER_ID;
        $quizObj->updated_at = DATE_TIME;
        $quizObj->save();

        \Session::flash('success', "Alert! Update Successfully");
        return \Redirect::back()->withInput();
    }

    public function addQuestion($quiz_id){
        $input = \Input::all();
        $rules = [
            'question' => 'required',
            'number_of_answers' => 'required',
            'answer_a' => 'required',
            'answer_b' => 'required',
            'correct_answer' => 'required',
        ];

        $message = [
            'question.required' => "Sorry Question Required",
            'number_of_answers.required' => "Sorry Number OF Answers Required",
            'answer_a.required' => "Sorry Answer A Required",
            'answer_b.required' => "Sorry Answer B Required",
            'correct_answer.required' => "Sorry Correct Answer Required",
        ];

        $validate = \Validator::make($input, $rules, $message);
        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }   

        $lessonObj = Quiz::getOne($quiz_id);
        if($lessonObj == null){
            return \TraitsFunc::ErrorMessage('This Quiz Not Found !!', 400);
        }

        $questionObj = new QuizQuestion;
        $questionObj->quiz_id = $quiz_id;
        $questionObj->question = $input['question'];
        $questionObj->number_of_answers = $input['number_of_answers'];
        $questionObj->answer_a = $input['answer_a'];
        $questionObj->answer_b = $input['answer_b'];
        $questionObj->answer_c = $input['answer_c'];
        $questionObj->answer_d = $input['answer_d'];
        $questionObj->answer_e = $input['answer_e'];
        $questionObj->correct_answer = $input['correct_answer'];
        $questionObj->created_by = USER_ID;
        $questionObj->created_at = date('Y-m-d H:i:s');
        $questionObj->save();

        \Session::flash('success', "Quiz Question Saved Successfully !!");
        $statusObj['status'] = \TraitsFunc::SuccessResponse('Quiz Question Saved Successfully !!');
        $statusObj['count'] = QuizQuestion::NotDeleted()->where('quiz_id',$quiz_id)->count();
        $statusObj['data'] = QuizQuestion::getData($questionObj);
        return $statusObj;
    }

    public function removeQuestion($quiz_id,$question_id){
        $questionObj = QuizQuestion::where('quiz_id',$quiz_id)->where('id',$question_id)->first();
        if($questionObj == null){
            return \TraitsFunc::ErrorMessage('This Quiz Question Not Found !!', 400);
        }
        $statusObj['status'] = \Helper::globalDelete($questionObj)->original;
        $statusObj['count'] = QuizQuestion::NotDeleted()->where('quiz_id',$questionObj->quiz_id)->count();
        return $statusObj;
    }

}
