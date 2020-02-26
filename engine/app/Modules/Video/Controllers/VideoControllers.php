<?php namespace App\Http\Controllers;

use App\Models\LessonVideo;
use App\Models\VideoComment;
use App\Models\User;
use App\Models\StudentVideoDuration;
use App\Models\Devices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Vimeo\Laravel\Facades\Vimeo;

class VideoControllers extends Controller {

    use \TraitsFunc;

    public function getOne($id) {
        $id = (int) $id;
        $lessonObj = LessonVideo::getOne($id);
        if($lessonObj == null) {
            return \TraitsFunc::ErrorMessage("This Video not found", 400);
        }

        $statusObj['data'] = LessonVideo::getData($lessonObj);
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);   
    }

    public function view($id) {
        $id = (int) $id;
        $input = \Input::all();

        $rules = [
            'duration' => 'required|gt:0',
        ];

        $message = [
            'duration.required' => "Sorry Duration Required",
        ];

        $validate = \Validator::make($input, $rules, $message);
        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }   

        $lessonObj = LessonVideo::getOne($id);
        if($lessonObj == null) {
            return \TraitsFunc::ErrorMessage("This Video not found", 400);
        }

        $videoDuration = $lessonObj->duration;
        if($input['duration'] > $videoDuration){
            return \TraitsFunc::ErrorMessage("Video Duration is ".$videoDuration.' Seconds', 400);
        }

        $seeObj = StudentVideoDuration::NotDeleted()->where('student_id',USER_ID)->where('video_id',$id)->first();
        if($seeObj != null){
            $seeObj->see_duration = $input['duration'];
            $seeObj->updated_by = USER_ID;
            $seeObj->updated_at = DATE_TIME;
            $seeObj->save();
        }else{
            $seeObj = new StudentVideoDuration;
            $seeObj->student_id = USER_ID;
            $seeObj->video_id = $id;
            $seeObj->course_id = $lessonObj->course_id;
            $seeObj->lesson_id = $lessonObj->lesson_id;
            $seeObj->main_duration = $videoDuration;
            $seeObj->see_duration = $input['duration'];
            $seeObj->created_by = USER_ID;
            $seeObj->created_at = DATE_TIME;
            $seeObj->save();
        }


        $statusObj['status'] = \TraitsFunc::SuccessResponse("Duration Saved Successfully");
        return \Response::json((object) $statusObj);   
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
        return $statusObj;
    }

    public function updateComment($video_id,$comment_id){
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

        $commentObj = VideoComment::getOne($comment_id);
        if($commentObj == null){
            return \TraitsFunc::ErrorMessage('This Comment Not Found !!', 400);
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

        $commentObj->comment = $input['comment'];
        $commentObj->reply_on = $input['reply'];
        $commentObj->updated_by = USER_ID;
        $commentObj->updated_at = date('Y-m-d H:i:s');
        $commentObj->save();

        $statusObj['status'] = \TraitsFunc::SuccessResponse('Comment Updated Successfully !!');
        return $statusObj;
    }

    public function removeComment($video_id,$comment_id){

        $videoObj = LessonVideo::getOne($video_id);
        if($videoObj == null){
            return \TraitsFunc::ErrorMessage('This Lesson Video Not Found !!', 400);
        }

        $commentObj = VideoComment::getOne($comment_id);
        if($commentObj == null){
            return \TraitsFunc::ErrorMessage('This Comment Not Found !!', 400);
        }

        $commentObj->deleted_by = USER_ID;
        $commentObj->deleted_at = date('Y-m-d H:i:s');
        $commentObj->save();

        $statusObj['status'] = \TraitsFunc::SuccessResponse('Comment Deleted Successfully !!');
        return $statusObj;
    }

}
