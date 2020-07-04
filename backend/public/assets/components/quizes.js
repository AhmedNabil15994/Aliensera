/**
 * Quizes Js
 */

$('select[name="course_id"]').on('change',function(){
    $('select[name="lesson_id"]').val('-1').trigger("change");
    $('select[name="lesson_id"]').empty();
    $('select[name="lesson_id"]').append("<option value='' disabled>Select A Lesson...</option>");
    $course_id = $(this).val();
    if($course_id){
        $.get('/courses/getLessons/'+$course_id,function(data) {
            $.each(data,function(index,item){
                $('select[name="lesson_id"]').append('<option value="'+item.id+'">'+item.title+'</option>');
            });
        })
    }
});

function clearAll(){
    $('input[name="question"]').val('');
    $('input[name="answer_a"]').val('');
    $('input[name="answer_b"]').val('');
    $('input[name="answer_c"]').val('');
    $('input[name="answer_d"]').val('');
    $('input[name="answer_e"]').val('');
    $('select[name="correct_answer"] option[value="c"],select[name="correct_answer"] option[value="d"],select[name="correct_answer"] option[value="e"]').remove();
    $('.answer_c,.answer_d,.answer_e').hide();
    $('select[name="correct_answer"]').val('').trigger('change');
    $('select[name="number_of_answers"]').val('2').trigger('change');
}

$('select[name="number_of_answers"]').on('change',function(){
    var count = $(this).val();
    $('select[name="correct_answer"] option[value="c"],select[name="correct_answer"] option[value="d"],select[name="correct_answer"] option[value="e"]').remove();
    if(count == 2){
        $('.answer_c,.answer_d,.answer_e').hide();
    }else if (count == 3) {
        $('.answer_d,.answer_e').hide();
        $('select[name="correct_answer"]').append('<option value="c">Answer C</option>');
        $('.answer_c').show();
    }else if(count == 4){
        $('.answer_e').hide();
        $('select[name="correct_answer"]').append('<option value="c">Answer C</option>');
        $('select[name="correct_answer"]').append('<option value="d">Answer D</option>');
        $('.answer_c,.answer_d').show();
    }else if (count == 5) {
        $('select[name="correct_answer"]').append('<option value="c">Answer C</option>');
        $('select[name="correct_answer"]').append('<option value="d">Answer D</option>');
        $('select[name="correct_answer"]').append('<option value="e">Answer E</option>');
        $('.answer_c,.answer_d,.answer_e').show();
    }
});
var myData = [];
$('.add-question').on('click',function(e){
	e.preventDefault();
	e.stopPropagation();
    var count = parseInt($('span.total_questions').html());
    var newCount = count + 1;
    var number_of_answers = $('select[name="number_of_answers"]').val(),
    	question = $('input[name="question"]').val(),
    	answer_a = $('input[name="answer_a"]').val(),
    	answer_b = $('input[name="answer_b"]').val(),
    	answer_c = $('input[name="answer_c"]').val(),
    	answer_d = $('input[name="answer_d"]').val(),
    	answer_e = $('input[name="answer_e"]').val(),
        correct_answer = $('select[name="correct_answer"] option:selected').val(),

     	videoString = '<div class="row results" id="questions'+newCount+'">'+
                        '<div class="col-xs-1">'+ newCount +'</div>'+
                        '<div class="col-xs-4">'+ question +'</div>'+
                        '<div class="col-xs-1">'+ answer_a + (correct_answer == 'a' ? '<br>(Correct Answer)' : '') +'</div>'+
                        '<div class="col-xs-1">'+ answer_b + (correct_answer == 'b' ? '<br>(Correct Answer)' : '') +'</div>'+
                        '<div class="col-xs-1">'+ answer_c + (correct_answer == 'c' ? '<br>(Correct Answer)' : '') +'</div>'+
                        '<div class="col-xs-1">'+ answer_d + (correct_answer == 'd' ? '<br>(Correct Answer)' : '') +'</div>'+
                        '<div class="col-xs-1">'+ answer_e + (correct_answer == 'e' ? '<br>(Correct Answer)' : '') +'</div>'+
                        '<div class="col-xs-2 text-center">'+ 
                            '<button class="btn btn-danger btn-xs" onclick="deleteQuestion('+newCount+',event)"><i class="fa fa-trash"></i></button>'+
                        '</div>'+
                      '</div>';
    $('.quiz').append(videoString);
    $('span.total_questions').html(newCount);
	clearAll();
    myData.push([number_of_answers,question,answer_a,answer_b,answer_c,answer_d,answer_e,correct_answer]);
	$('#questions').val(JSON.stringify(myData));
});

$('.clear-question').on('click',function(){
    clearAll();
});

function deleteQuestion(question_id,e) {
	e.preventDefault();
	e.stopPropagation();
	myData.splice(question_id-1,1);
	$('#questions').val(JSON.stringify(myData));
    $('#questions' + question_id).remove();
    var count = $('span.total_questions').html();
    $('span.total_questions').html(count-1);
}

function deleteQuiz($id) {
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
        $.get('/quizes/delete/' + $id,function(data) {
            if (data.status.status == 1) {
                $('#tableRaw' + $id).remove();
                successNotification(data.status.message);
            } else {
                errorNotification(data.status.message);
            }
        })
    });
}

function deleteEditedQuestion(question_id) {
	var url2 = window.location.href;
    if(url2.indexOf("#") != -1){
        url2 = url2.replace('#','');
    }
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
        $.get(url2+'/removeQuestion/'+question_id,function(data) {
            if (data.status.status.status == 1) {
                if(data.count == 0){
                    $('.quiz').remove();
                    $('.x_content_questions').append('<div class="empty">No Quizes Available</div>');
                } 
                $('#questions' + question_id).remove();
                var count = $('span.total_questions').html();
                $('span.total_questions').html(count-1);
                successNotification(data.status.status.message);
            } else {
                errorNotification(data.status.status.message);
            }
        })
    });
}

$('.add-question2').on('click',function(){
    var url2 = window.location.href;
    if(url2.indexOf("#") != -1){
        url2 = url2.replace('#','');
    }
    var myURL2 = url2+'/addQuestion';
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
        type:'post',
        url: myURL2,
        data:{
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'number_of_answers': $('select[name="number_of_answers"]').val(),
            'question': $('input[name="question"]').val(),
            'answer_a': $('input[name="answer_a"]').val(),
            'answer_b': $('input[name="answer_b"]').val(),
            'answer_c': $('input[name="answer_c"]').val(),
            'answer_d': $('input[name="answer_d"]').val(),
            'answer_e': $('input[name="answer_e"]').val(),
            'correct_answer': $('select[name="correct_answer"] option:selected').val(),
        },
        success:function(data){
            if(data.status.status == 1){
                clearAll();
                var count = parseInt($('span.total_questions').html());
                var newCount = count + 1;
                var videoString = '<div class="row results" id="questions'+data.data.id+'">'+
                                    '<div class="col-xs-1">'+ newCount +'</div>'+
                                    '<div class="col-xs-4">'+ data.data.question +'</div>'+
                                    '<div class="col-xs-1">'+ data.data.answer_a +'</div>'+
                                    '<div class="col-xs-1">'+ data.data.answer_b +'</div>'+
                                    '<div class="col-xs-1">'+ data.data.answer_c +'</div>'+
                                    '<div class="col-xs-1">'+ data.data.answer_d +'</div>'+
                                    '<div class="col-xs-1">'+ data.data.answer_e +'</div>'+
                                    '<div class="col-xs-2 text-center">'+ 
                                        '<button class="btn btn-danger btn-xs" onclick="deleteQuestion('+data.data.id+')"><i class="fa fa-trash"></i></button>'+
                                    '</div>'+
                                  '</div>';
                $('.quiz').append(videoString);
                $('span.total_questions').html(newCount);                                
                successNotification(data.status.message);
            }else{
                errorNotification(data.status.message);
            }
        },
    });
});
