<?php

namespace App\Console\Commands;

use App\Models\Lesson;
use App\Models\Devices;
use App\Models\StudentRequest;
use Illuminate\Console\Command;

class ActiveLessonHourly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'active:lesson';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A Crone Job To Active Lessons Based On Their Attribue Active At';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = date('Y-m-d H:i:s');
        Lesson::where([
            ['valid_until','>=',$now],
            ['status','=',0],
            ['active_at','!=',null],
            ['active_at',$now],
        ])->whereHas('Course',function($courseQuery){
            $courseQuery->whereIn('status',[3,5]);
        })->update(['status'=>1,'updated_at'=> $now]);

        $lessonControllerObj = new \App\Http\Controllers\LessonControllers;
        $lessons = Lesson::where('updated_at',$now)->get();
        foreach ($lessons as $lesson) {
            $msg = 'New Lesson Added To Course '.$lesson->Course->title;
            $users = StudentRequest::NotDeleted()->where('course_id',$lesson->course_id)->where('status',1)->pluck('student_id');
            $tokens = Devices::getDevicesBy($users);
            $tokens = reset($tokens);
            // foreach ($tokens as $value) {
            $lessonControllerObj->sendNotification($tokens,$msg,$lesson->id);
            // }   
        }
    }
}
