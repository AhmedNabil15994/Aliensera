<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonVideo extends Model{

    use \TraitsFunc;

    protected $table = 'lesson_videos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    static function getVideoPath($id, $video) {
        return \ImagesHelper::GetImagePath('lessons', $id, $video);
    }

    static function getVideoAttachment($id, $attachment) {
        return \ImagesHelper::GetImagePath('videos', $id, $attachment);
    }

    public function Lesson(){
        return $this->belongsTo('App\Models\Lesson','lesson_id','id');
    }

    public function Course(){
        return $this->belongsTo('App\Models\Course','course_id','id');
    }

    public function Comments(){
        return $this->hasMany('App\Models\VideoComment','video_id','id');
    }

    static function getOne($id){
        $source = self::NotDeleted()
            ->where('id', $id);

        if(IS_ADMIN == false){
            $source->whereHas('Course',function($courseQuery) {
                $courseQuery->where('instructor_id',USER_ID);
            });
        }
        return $source->first();
    }

    static function dataList($lesson_id=null) {
        $input = \Input::all();
        $source = self::NotDeleted()->orderBy('sort','asc');

        if (isset($lesson_id) && !empty($lesson_id) ) {
            $source->where('lesson_id', $lesson_id);
        } 

        if(IS_ADMIN == false){
            $source->whereHas('Course',function($courseQuery) {
                $courseQuery->where('instructor_id',USER_ID);
            });
        }
        return self::generateObj($source);
    }

    static function generateObj($source){
        $sourceArr = $source->get();
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }

        return (object) $list;
    }

    static function getDuration($duration){
        $result = '';
        if($duration > 3600){
            $hours = round(floor($duration / 3600));
            $minutes = round(floor(($duration % 3600) / 60));
            $result = $hours.' Hr '.$minutes.' Min';
        }elseif($duration > 60){
            $minutes = round(floor($duration / 60));
            $seconds = round($duration % 60);
            $result = $minutes.' Min '.$seconds.' Sec';
        }elseif($duration > 0 && $duration < 60){
            $result = round($duration).' Sec';
        }
        return $result;
    }

    static function getSize($size){
        $result = '';
        if($size > 1000000){
            $mbs = round($size / 1000000 , 2);
            $result = $mbs.' MB';
        }elseif($size > 1000){
            $kbs = round($size / 1000 , 2);
            $result = $kbs.' KB';
        }
        return $result;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->lesson_id = $source->lesson_id;
        $data->course_id = $source->course_id;
        $data->lesson = $source->Lesson->title;
        $data->comments = VideoComment::dataList($source->id,null,'0');
        $data->course = $source->Lesson->Course->title;
        $data->duration = self::getDuration($source->duration);
        $data->size = self::getSize($source->size);
        $data->title = $source->title;
        $data->free = $source->free == 1 ? 'Yes' : 'No';
        $data->video_id = $source->video_id; 
        $data->link = "https://player.vimeo.com/video/".$source->video_id;
        $data->video = self::getVideoPath($source->lesson_id,$source->video) != null ? self::getVideoPath($source->lesson_id,$source->video) : [];
        $data->attachment = $source->attachment != null ? self::getVideoAttachment($source->id,$source->attachment) : '';
        return $data;
    }

}
