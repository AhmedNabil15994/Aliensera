/**
 * Notification Js
 */

console.log("[x] Loading Notification js .... Done");

$(function(){
    $('select[name="course_type"]').on('change',function(){
        $('select[name="university_id"],select[name="faculty_id"]').empty();
        $('select[name="university_id"]').append("<option value=''>Select An University</option>");
        $('select[name="faculty_id"]').append("<option value=''>Select A Faculty</option>");
        var course_type = $(this).val();
        if(course_type == 1){
            $('select[name="field_id"]').removeAttr('disabled');
            $('select[name="university_id"],select[name="faculty_id"],select[name="year"]').attr('disabled','disabled');
        }else if(course_type == 2){
            $('select[name="university_id"],select[name="faculty_id"],select[name="year"]').removeAttr('disabled');
            $('select[name="field_id"]').attr('disabled','disabled');
            $.get('/courses/getUniversities',function(data) {
                $.each(data,function(index,item){
                    $('select[name="university_id"]').append('<option value="'+item.id+'">'+item.title+'</option>');
                });
            })
        }
    });

    $('select[name="university_id"]').on('change',function(){
        $('select[name="faculty_id"]').empty();
        $('select[name="faculty_id"]').append("<option value=''>Select A Faculty</option>");
        $university_id = $(this).val();
        if($university_id){
            $.get('/courses/getFaculties/'+$university_id,function(data) {
                $.each(data,function(index,item){
                    $('select[name="faculty_id"]').append('<option value="'+item.id+'" data-area="'+item.number_of_years+'">'+item.title+'</option>');
                });
            })
        }
    });

    $('select[name="faculty_id"]').on('change',function(){
        $('select[name="year"]').empty();
        $('select[name="year"]').append("<option value=''>Select Year...</option>");
        $year = $('select[name="faculty_id"] option:selected').attr('data-area');
        if($year){
            for (var i = 1; i <= $year; i++) {
                $('select[name="year"]').append("<option value='"+i+"'>"+i+"</option>");
            }
        }
    })

});