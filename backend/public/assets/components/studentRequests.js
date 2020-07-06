/**
 * Field Js
 */

console.log("[x] Loading StudentRequest js .... Done");

function deleteRequest($id) {
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
        $.get('/requests/delete/' + $id,function(data) {
            if (data.status.status == 1) {
                $('#tableRaw' + $id).remove();
                successNotification(data.status.message);
            } else {
                errorNotification(data.status.message);
            }
        })
    });
}

$('select[name="course_id"]').on('change',function(){
    $('select[name="lesson_id"]').val('-1]').trigger("change");
    $('select[name="lesson_id"]').empty();
    $('select[name="lesson_id"]').append("<option value=''>Select A Lesson...</option>");
    $course_id = $(this).val();
    if($course_id){
        $.get('/courses/getLessons/'+$course_id,function(data) {
            $.each(data,function(index,item){
                $('select[name="lesson_id"]').append('<option value="'+item.id+'">'+item.title+'</option>');
            });
        })
    }
});
