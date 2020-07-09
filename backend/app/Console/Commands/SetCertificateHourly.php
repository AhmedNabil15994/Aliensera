<?php

namespace App\Console\Commands;

use App\Models\Variable;
use App\Models\StudentScore;
use App\Models\LessonQuestion;
use App\Models\Certificate;
use Illuminate\Console\Command;

class SetCertificateHourly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:certificate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A Crone Job To Add Certificate For Student Automatically';

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
        $minPercent = (int) Variable::getVar('STUDENT_PERCENTAGE_TO_VIEW_NEXT_LESSON');
        $allStudents = StudentScore::whereHas('Course',function($whereQuery){
            $whereQuery->whereIn('status',[3,5]);
        })->groupBy('student_id','course_id')->get(['course_id','student_id','instructor_id']);

        foreach($allStudents as $student){
            $allQuestion = LessonQuestion::NotDeleted()->where('course_id',$student->course_id)->count();
            $studentRight = StudentScore::where('student_id',$student->student_id)->where('course_id',$student->course_id)->where('correct',1)->count();
            $studentPercent = $allQuestion > 0 ? round(($studentRight / $allQuestion) * 100,3) : 0;
            $certificateObj = Certificate::where('student_id',$student->student_id)->where('instructor_id',$student->instructor_id)->where('course_id',$student->course_id)->first();
            if($certificateObj != null){
                if($certificateObj->rate < $studentPercent){
                    $certificateObj->rate = $studentPercent;
                    $certificateObj->updated_at = $now;
                    $certificateObj->updated_by = 1;
                    $certificateObj->save();
                }
            }else{
                if($studentPercent >= $minPercent){
                    $code = rand(0,100000);
                    $codeObj = Certificate::where('code',$code)->first();
                    do {
                        $code = rand(0,100000);
                    } while ($codeObj!= null);
                    $certificateObj = new Certificate;
                    $certificateObj->code = $code;
                    $certificateObj->course_id = $student->course_id;
                    $certificateObj->instructor_id = $student->instructor_id;
                    $certificateObj->student_id = $student->student_id;
                    $certificateObj->rate = $studentPercent;
                    $certificateObj->created_at = $now;
                    $certificateObj->created_by = 1;
                    $certificateObj->save();
                }
            }
        }
    }
}
