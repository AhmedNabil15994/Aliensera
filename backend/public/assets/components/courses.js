/**
 * Course Js
 */

console.log("[x] Loading Course js .... Done");

function deleteCourse($id) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary data!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function () {
        swal("Deleted!", "Your imaginary data has been deleted.", "success");
        $.get('/courses/delete/' + $id,function(data) {
            if (data.status.status == 1) {
                // $('#tableRaw' + $id).remove();
                location.reload();
                successNotification(data.status.message);
            } else {
                errorNotification(data.status.message);
            }
        })
    });
}

function deleteReview($id) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary data!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function () {
        swal("Deleted!", "Your imaginary data has been deleted.", "success");
        $.get('/courses/deleteReview/' + $id,function(data) {
            if (data.status.status == 1) {
                $('#tableRaw' + $id).remove();
                successNotification(data.status.message);
            } else {
                errorNotification(data.status.message);
            }
        })
    });
}

function deleteRate($id) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary data!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function () {
        swal("Deleted!", "Your imaginary data has been deleted.", "success");
        $.get('/courses/deleteRate/' + $id,function(data) {
            if (data.status.status == 1) {
                $('#tableRaw' + $id).remove();
                successNotification(data.status.message);
            } else {
                errorNotification(data.status.message);
            }
        })
    });
}

function restoreCourse($id) {
    swal({
        title: "Are you sure?",
        text: "You will restore this imaginary data!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, restore it!",
        closeOnConfirm: false
    }, function () {
        swal("Restored!", "Your imaginary data has been restored.", "success");
        $.get('/courses/restore/' + $id,function(data) {
            if (data.status.status == 1) {
                // $('#tableRaw' + $id).remove();
                location.reload();
                successNotification(data.status.message);
            } else {
                errorNotification(data.status.message);
            }
        })
    });
}

function deleteImage(id){
    $.get('/courses/images/delete/' + id,function(data){
        if (data.status.status == 1){
            $('#imgRaw' + id).remove();
            successNotification(data.status.message);
        }else {
            errorNotification(data.status.message);
        }
    });
}

function deleteLecture(video_id) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary data!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function () {
        swal("Deleted!", "Your imaginary data has been deleted.", "success");
        $.get('/lessons/removeVideo/'+video_id,function(data) {
            if (data.status.status == 1) {
                $('#results' + video_id).remove();
                var count = $('span.total_videos').html();
                $('span.total_videos').html(count-1);
                successNotification(data.status.message);
            } else {
                errorNotification(data.status.message);
            }
        })
    });
}

function deleteQuestion(question_id) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary data!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function () {
        swal("Deleted!", "Your imaginary data has been deleted.", "success");
        $.get('/lessons/removeQuestion/'+question_id,function(data) {
            if (data.status.status == 1) {
                $('#questions' + question_id).remove();
                var count = $('span.total_questions').html();
                $('span.total_questions').html(count-1);
                successNotification(data.status.message);
            } else {
                errorNotification(data.status.message);
            }
        })
    });
}

// $.each(data.data,function(index,item){
//     districtSelect.append('<option value="'+item.id+'">'+item.name+'</option>')
// });
$(function(){
    $('select[name="course_type"]').on('change',function(){
        $('select[name="university_id"],select[name="faculty_id"]').empty();
        $('select[name="university_id"]').append("<option value=''>Select An University</option>");
        $('select[name="faculty_id"]').append("<option value=''>Select A Faculty</option>");
        var course_type = $(this).val();
        if(course_type == 1){
            $('select[name="university_id"],select[name="faculty_id"],input[name="year"]').attr('disabled','disabled');
        }else if(course_type == 2){
            $('select[name="university_id"],select[name="faculty_id"],input[name="year"]').removeAttr('disabled');
            $.get('/courses/getUniversities',function(data) {
                $.each(data,function(index,item){
                    $('select[name="university_id"]').append('<option value="'+item.id+'">'+item.title+'</option>');
                });
            })
        }
    })

    $('select[name="university_id"]').on('change',function(){
        $('select[name="faculty_id"]').empty();
        $('select[name="faculty_id"]').append("<option value=''>Select A Faculty</option>");
        $university_id = $(this).val();
        if($university_id){
            $.get('/courses/getFaculties/'+$university_id,function(data) {
                $.each(data,function(index,item){
                    $('select[name="faculty_id"]').append('<option value="'+item.id+'">'+item.title+'</option>');
                });
            })
        }
    })

    $('input.datepicker').datetimepicker({
        format: 'YYYY-MM-DD',
    });

    function fixDuration(elem){
        var duration = elem.val();
        if(duration % 5 != 0){
            var correctValue = Math.floor(duration / 5);
            elem.val(correctValue*5);   
        }
    }

    $('input[name="approval_numbers"]').on('change',function(){
        fixDuration($(this));
    });

    $('input[name="approval_numbers"]').on('blur',function(){
        fixDuration($(this));
    });

    $('input[name="course_duration"]').on('change',function(){
        if($('input[name="start_date"]').val()){
            var startDate = moment($('input[name="start_date"]').val()).format('YYYY-MM-DD');
            var endDate = moment(startDate).add('days',$('input[name="course_duration"]').val());
            $('input[name="end_date"]').val(moment(endDate).format('YYYY-MM-DD')); 
            $('span.upload_cost.student_approval').html($(this).val() * .5 * $('input[name="approval_number"]').val());
        }
    })

    $('input[name="start_date"]').on('dp.change', function(e){
        var startDate = moment(e.date).format('YYYY-MM-DD');
        var endDate = moment(startDate).add('days',$('input[name="course_duration"]').val());
        $('input[name="end_date"]').val(moment(endDate).format('YYYY-MM-DD')); 
    });

    $('input[name="upload_space"]').on('change',function(){
        $('span.upload_cost:not(.student_approval)').html($(this).val() * 25);
    });

    $('input[name="approval_number"]').on('change',function(){
        $('span.upload_cost.student_approval').html($(this).val() * .5 * $('input[name="course_duration"]').val());
    });


});