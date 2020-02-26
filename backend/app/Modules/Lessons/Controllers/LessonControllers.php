<?php namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonVideo;
use App\Models\LessonQuestion;
use App\Models\Course;
use App\Models\StudentRequest;
use App\Models\Devices;
use App\Models\VideoComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Vimeo\Laravel\Facades\Vimeo;

class LessonControllers extends Controller {

    use \TraitsFunc;

    protected function validateLesson($input){
        $rules = [
            'title' => 'required',
            'course_id' => 'required',
            'valid_until' => 'required',
        ];

        $message = [
            'title.required' => "Sorry Title Required",
            'course_id.required' => "Sorry Course Required",
            'valid_until.required' => "Sorry Valid Until Required",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $dataList = Lesson::dataList();
        $dataList['courses'] = Course::dataList()['data'];
        return view('Lessons.Views.index')
            ->with('data', (Object) $dataList);
    }

    public function edit($id) {
        $id = (int) $id;
        $universityObj = Lesson::getOne($id);
        if($universityObj == null) {
            return Redirect('404');
        }

        $data['data'] = Lesson::getData($universityObj);
        $data['courses'] = Course::dataList()['data'];
        return view('Lessons.Views.edit')->with('data', (object) $data);      
    }

    public function sendNotification($tokens,$msg,$id){
        $fireBase = new \FireBase();
        $metaData = ['title' => "New Lesson", 'body' => $msg,];
        $myData = ['type' => 2 , 'id' => $id];
        $fireBase->send_android_notification($tokens,$metaData,$myData);
        return true;
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Input::all();

        $universityObj = Lesson::getOne($id);
        if($universityObj == null) {
            return Redirect('404');
        }

        $oldStatus = $universityObj->status;
        $validate = $this->validateLesson($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }


        $universityObj->title = $input['title'];
        $universityObj->description = isset($input['description']) && !empty($input['description']) ? $input['description'] : '';
        $universityObj->valid_until = date('Y-m-d',strtotime($input['valid_until']));
        $universityObj->status = isset($input['status']) ? 1 : 0;
        $universityObj->updated_by = USER_ID;
        $universityObj->updated_at = DATE_TIME;
        $universityObj->save();

        $newStatus = $universityObj->status;
        if($oldStatus == 0 && $newStatus == 1){
            $msg = 'New Lesson Added To Course '.$universityObj->Course->title;
            $users = StudentRequest::NotDeleted()->where('course_id',$universityObj->course_id)->where('status',1)->pluck('student_id');
            $tokens = Devices::getDevicesBy($users);
            $tokens = reset($tokens);
            foreach ($tokens as $value) {
                $this->sendNotification($value,$msg,$id);
            }
        }

        \Session::flash('success', "Alert! Update Successfully");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['courses'] = Course::dataList()['data'];
        return view('Lessons.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Input::all();
        
        $validate = $this->validateLesson($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }   
        
        $universityObj = new Lesson;
        $universityObj->title = $input['title'];
        $universityObj->description = isset($input['description']) && !empty($input['description']) ? $input['description'] : '';
        $universityObj->course_id = $input['course_id'];
        $universityObj->valid_until = date('Y-m-d',strtotime($input['valid_until']));
        $universityObj->status = IS_ADMIN ? (isset($input['status']) ? 1 : 0) : 2 ;
        $universityObj->created_by = USER_ID;
        $universityObj->created_at = DATE_TIME;
        $universityObj->save();

        if($universityObj->status == 1){
            $msg = 'New Lesson Added To Course '.$universityObj->Course->title;
            $users = StudentRequest::NotDeleted()->where('course_id',$universityObj->course_id)->where('status',1)->pluck('student_id');
            $tokens = Devices::getDevicesBy($users);
            $tokens = reset($tokens);
            foreach ($tokens as $value) {
                $this->sendNotification($value,$msg,$universityObj->id);
            }
        }

        \Session::flash('success', "Alert! Create Successfully");
        return redirect()->to('lessons/edit/' . $universityObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $universityObj = Lesson::getOne($id);
        return \Helper::globalDelete($universityObj);
    }

    public function getDuration($filePath){
        $getID3 = new \getID3;
        $file = $getID3->analyze($filePath);
        $duration = $file['playtime_seconds'];
        return [$duration,$file['filesize']];
    }

    public function uploadVideo(Request $request , $id) {
        $lessonObj = Lesson::getOne($id);
        if($lessonObj == null){
            return \TraitsFunc::ErrorMessage('This Lesson Not Found !!', 400);
        }

        if ($request->hasFile('file')) {
            $files = $request->file('file');
            $vimeoObj = new \Vimeos();

            $fileName = \ImagesHelper::UploadVideo('lessons', $files, $id);
            if($fileName == false){
                return \TraitsFunc::ErrorMessage('Upload Video Failed !!', 400);
            }
            // $video_id = $vimeoObj->upload(public_path().'/uploads/lessons/'.$id.'/'.$fileName[0],$fileName[1],$lessonObj->Course->project_id);
            $video_id = $vimeoObj->upload($_FILES['file']['tmp_name'],$fileName[1],$lessonObj->Course->project_id);
            $fileData = $this->getDuration($_FILES['file']['tmp_name']);
            $courseObj = new LessonVideo;
            $courseObj->video = $fileName[0];
            $courseObj->title = $fileName[1];
            $courseObj->lesson_id = $id;
            $courseObj->video_id = $video_id;
            $courseObj->course_id = $lessonObj->course_id;
            $courseObj->duration = $fileData[0];
            $courseObj->size = $fileData[1];
            $courseObj->created_by = USER_ID;
            $courseObj->created_at = date('Y-m-d H:i:s');
            $courseObj->save();

            $msg = 'New Video Added To Course '.$lessonObj->Course->title;
            $users = StudentRequest::NotDeleted()->where('course_id',$lessonObj->course_id)->where('status',1)->pluck('student_id');
            $tokens = Devices::getDevicesBy($users);
            $tokens = reset($tokens);
            $fireBase = new \FireBase();
            $metaData = ['title' => "New Video", 'body' => $msg,];
            $myData = ['type' => 3 , 'id' => $courseObj->id];

            foreach ($tokens as $value) {
                $fireBase->send_android_notification($value,$metaData,$myData);
            }

            \Session::flash('success', "Upload Video Success !!");
            $statusObj['status'] = \TraitsFunc::SuccessResponse('Upload Video Success !!');
            $statusObj['count'] = LessonVideo::NotDeleted()->where('lesson_id',$id)->count();
            $statusObj['data'] = LessonVideo::getData($courseObj);
            return $statusObj;
        }       
    }

    public function uploadAttachment(Request $request , $id) {
        $lessonObj = LessonVideo::getOne($id);
        if($lessonObj == null){
            return \TraitsFunc::ErrorMessage('This Lesson Video Not Found !!', 400);
        }

        if ($request->hasFile('attachment')) {
            $files = $request->file('attachment');
            $fileName = \ImagesHelper::uploadImage('videos', $files, $id);
            if($fileName == false){
                return \TraitsFunc::ErrorMessage('Upload Attachment Failed !!', 400);
            }

            $lessonObj->attachment = $fileName;
            $lessonObj->updated_by = USER_ID;
            $lessonObj->updated_at = date('Y-m-d H:i:s');
            $lessonObj->save();
            \Session::flash('success', "Upload Attachment Success !!");
            $statusObj['status'] = \TraitsFunc::SuccessResponse('Upload Attachment Success !!');
            $statusObj['data'] = LessonVideo::getData($lessonObj);
            return $statusObj;
        }       
    }

    public function removeVideo($video_id){
        $videoObj = LessonVideo::getOne($video_id);
        if($videoObj == null){
            return \TraitsFunc::ErrorMessage('This Lesson Lecture Not Found !!', 400);
        }
        $statusObj['status'] = \Helper::globalDelete($videoObj)->original;
        $statusObj['count'] = LessonVideo::NotDeleted()->where('lesson_id',$videoObj->lesson_id)->count();
        return $statusObj;
    }

    public function addQuestion($lesson_id){
        $input = \Input::all();
        $rules = [
            'question' => 'required',
            'answer_a' => 'required',
            'answer_b' => 'required',
            'answer_c' => 'required',
            'answer_d' => 'required',
            'correct_answer' => 'required',
        ];

        $message = [
            'question.required' => "Sorry Question Required",
            'answer_a.required' => "Sorry Answer A Required",
            'answer_b.required' => "Sorry Answer B Required",
            'answer_c.required' => "Sorry Answer C Required",
            'answer_d.required' => "Sorry Answer D Required",
            'correct_answer.required' => "Sorry Correct Answer Required",
        ];

        $validate = \Validator::make($input, $rules, $message);
        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }   

        $lessonObj = Lesson::getOne($lesson_id);
        if($lessonObj == null){
            return \TraitsFunc::ErrorMessage('This Lesson Not Found !!', 400);
        }

        $questionObj = new LessonQuestion;
        $questionObj->lesson_id = $lesson_id;
        $questionObj->course_id = $lessonObj->course_id;
        $questionObj->question = $input['question'];
        $questionObj->answer_a = $input['answer_a'];
        $questionObj->answer_b = $input['answer_b'];
        $questionObj->answer_c = $input['answer_c'];
        $questionObj->answer_d = $input['answer_d'];
        $questionObj->correct_answer = $input['correct_answer'];
        $questionObj->created_by = USER_ID;
        $questionObj->created_at = date('Y-m-d H:i:s');
        $questionObj->save();

        \Session::flash('success', "Lesson Question Saved Successfully !!");
        $statusObj['status'] = \TraitsFunc::SuccessResponse('Lesson Question Saved Successfully !!');
        $statusObj['count'] = LessonQuestion::NotDeleted()->where('lesson_id',$lesson_id)->count();
        $statusObj['data'] = LessonQuestion::getData($questionObj);
        return $statusObj;
    }

    public function removeQuestion($question_id){
        $questionObj = LessonQuestion::getOne($question_id);
        if($questionObj == null){
            return \TraitsFunc::ErrorMessage('This Lesson Question Not Found !!', 400);
        }
        $statusObj['status'] = \Helper::globalDelete($questionObj)->original;
        $statusObj['count'] = LessonQuestion::NotDeleted()->where('lesson_id',$questionObj->lesson_id)->count();
        return $statusObj;
    }

    public function changeStatus($video_id){
        $videoObj = LessonVideo::getOne($video_id);
        if($videoObj == null) {
            return Redirect('404');
        }

        if($videoObj->free == 1){
            $videoObj->free = 0;
            $videoObj->save();
        }else{
            $videoObj->free = 1;
            $videoObj->save(); 
        }

        \Session::flash('success', "Alert! Updated Successfully");
        return redirect()->back();
    }   

    public function comments($video_id){
        $videoObj = LessonVideo::getOne($video_id);
        if($videoObj == null) {
            return Redirect('404');
        }

        $dataList['data'] = LessonVideo::getData($videoObj);
        $dataList['count'] = VideoComment::NotDeleted()->where('status',1)->where('video_id',$video_id)->count();
        return view('Lessons.Views.comments')->with('data', (Object) $dataList);
    }    

    public function addComment($video_id){
        $input = \Input::all();
        $rules = [
            'comment' => 'required',
        ];

        $message = [
            'comment.required' => "Sorry Comment Required",
        ];

        $validate = \Validator::make($input, $rules, $message);
        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }   

        $videoObj = LessonVideo::getOne($video_id);
        if($videoObj == null){
            return \TraitsFunc::ErrorMessage('This Lesson Video Not Found !!', 400);
        }

        if($input['reply'] != 0){
            $commentObj = VideoComment::getOne($input['reply']);
            if($commentObj == null){
                return \TraitsFunc::ErrorMessage('This Comment Not Found !!', 400);
            }
            $input['reply'] = $commentObj->reply_on != 0 ? $commentObj->reply_on : $input['reply'];
            if($commentObj->reply_on == 0 ){
                if($commentObj->created_by == USER_ID){
                    return \TraitsFunc::ErrorMessage("You Can't Reply To Your Comment!!", 400);
                }
            }
        }

        $commentObj = new VideoComment;
        $commentObj->comment = $input['comment'];
        $commentObj->reply_on = $input['reply'];
        $commentObj->video_id = $video_id;
        $commentObj->course_id = $videoObj->course_id;
        $commentObj->status = 1;
        $commentObj->created_by = USER_ID;
        $commentObj->created_at = date('Y-m-d H:i:s');
        $commentObj->save();

        $replier = User::getData(User::getOne(USER_ID));
        $msg = $replier->name.' replied on your comment';
        $tokens = Devices::getDevicesBy($commentObj->created_by,true);
        $fireBase = new \FireBase();
        $metaData = ['title' => "New Comment", 'body' => $msg,];
        $myData = ['type' => 3 , 'id' => $commentObj->video_id];
        $fireBase->send_android_notification($tokens[0],$metaData,$myData);

        $statusObj['status'] = \TraitsFunc::SuccessResponse('Comment Saved Successfully !!');
        $statusObj['data'] = VideoComment::getData($commentObj);
        return $statusObj;
    }

    public function removeComment($comment_id){
        $commentObj = VideoComment::getOne($comment_id);
        if($commentObj == null){
            return \TraitsFunc::ErrorMessage('This Comment Not Found !!', 400);
        }
        return \Helper::globalDelete($commentObj);
    }

}
