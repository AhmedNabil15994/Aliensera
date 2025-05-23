/**
 * Lesson Js
 */

console.log("[x] Loading Lesson js .... Done");
var elemIndex2 = 0;

$('input.datepicker').datetimepicker({
    format: 'YYYY-MM-DD',
});

$(document).on('click','.row.results a.btn.btn-warning',function(){
    var lesson_id = $(this).data('area');
    var course_id = $(this).data('plot');
    var video_id = $(this).data('tab');
    $('#move_to_another_lesson h5.modal-title span.my-title').empty();
    $('#move_to_another_lesson .btn-success').attr('data-area',0);
    $('#move_to_another_lesson .btn-success').attr('data-role',0);
    var title = $(this).parent('div.col-xs-3').siblings('div.col-xs-2.title').text();
    $('#move_to_another_lesson h5.modal-title span.my-title').html(title);

    var myURL = '/courses/view/'+course_id+'/movableLessons';

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
        type:'get',
        url: myURL,
        data:{
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'lesson_id': lesson_id,
        },
        success:function(data){
            $('#move_to_another_lesson select[name="lesson_id"]').empty();
            $('#move_to_another_lesson select[name="lesson_id"]').append('<option value="">Select A Lesson...</option>');
            $.each(data,function(index,item){
                $('#move_to_another_lesson select[name="lesson_id"]').append('<option value="'+item.id+'">'+item.title+'</option>');
            });
            $('#move_to_another_lesson .btn-success').attr('data-area',lesson_id);
            $('#move_to_another_lesson .btn-success').attr('data-role',video_id);
            $('#move_to_another_lesson .btn-success').attr('data-plot',course_id);
            $('#move_to_another_lesson select[name="lesson_id"]').select2();
            $('#move_to_another_lesson').modal('toggle');
        }
    }); 

});

$('#move_to_another_course select[name="course_id"]').on('change',function(){
    $('#move_to_another_course select[name="lesson_id"]').val('-1').trigger("change");
    $('#move_to_another_course select[name="lesson_id"]').empty();
    $('#move_to_another_course select[name="lesson_id"]').append("<option value=''>Select A Lesson...</option>");
    $course_id = $(this).val();
    if($course_id){
        $.get('/courses/getLessons/'+$course_id,function(data) {
            $.each(data,function(index,item){
                $('#move_to_another_course select[name="lesson_id"]').append('<option value="'+item.id+'">'+item.title+'</option>');
            });
        })
    }
});

$(document).on('click','.row.results a.btn.btn-dark.course',function(){
    var lesson_id = $(this).data('area');
    var video_id = $(this).data('tab');
    $('#move_to_another_course h5.modal-title span.my-title').empty();
    $('#move_to_another_course select[name="lesson_id"]').empty();
    $('#move_to_another_course select[name="lesson_id"]').append("<option value=''>Select A Lesson...</option>");
    $('#move_to_another_course .btn-success').attr('data-area',lesson_id);
    $('#move_to_another_course .btn-success').attr('data-role',video_id);
    var title = $(this).parent('div.col-xs-3').siblings('div.col-xs-2.title').text();
    $('#move_to_another_course h5.modal-title span.my-title').html(title);
    $('#move_to_another_course').modal('show');
});
    
$('#move_to_another_course .btn-success').on('click',function(e){
    var lesson_id = $(this).data('area');
    var video_id = $(this).data('role');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
        type:'post',
        url: '/lessons/moveToAnotherCourse',
        data:{
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'old_lesson_id': lesson_id,
            'video_id': video_id,
            'lesson_id': $('#move_to_another_course select[name="lesson_id"]').val(),
            'course_id': $('#move_to_another_course select[name="course_id"]').val(),
        },
        success:function(data){
            if(data == 1){
                $('#move_to_another_course').modal('hide');
                location.reload();
            }
        }
    }); 

});

$('#move_to_another_lesson .btn-success').on('click',function(e){
    var lesson_id = $(this).data('area');
    var video_id = $(this).data('role');
    var course_id = $(this).data('plot');

    var myURL = '/courses/view/'+course_id+'/moveVideo';
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
        type:'post',
        url: myURL,
        data:{
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'old_lesson_id': lesson_id,
            'video_id': video_id,
            'lesson_id': $('#move_to_another_lesson select').val(),
        },
        success:function(data){
            if(data == 1){
                $('#move_to_another_lesson').modal('hide');
                location.reload();
            }
        }
    }); 

});


function initEditable(){
    $('a.editable').editable({
        mode: 'inline',
        success: function(response,newValue){
            var url = "/videos/"+$(this).attr('data-area')+"/updateName";
            if(url.indexOf("#") != -1){
                url = url.replace('#','');
            }
            $.ajax({
                url: url,
                type: 'POST',
                data:{
                    'name': newValue,
                } ,
                success: function (data) {
                    successNotification(data.status.message);
                }
            });
        }
    });
}


function deleteLesson($id) {
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
        $.get('/lessons/delete/' + $id,function(data) {
            if (data.status.status == 1) {
                $('#tableRaw' + $id).remove();
                successNotification(data.status.message);
            } else {
                errorNotification(data.status.message);
            }
        })
    });
}

var previewNode = document.querySelector("#template");
if(previewNode){
    previewNode.id = "";
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);

    var url = window.location.href;
    if(url.indexOf("#") != -1){
        url = url.replace('#','');
    }
    var myURL = url+'/uploadVideo';

    var myDropzone = new Dropzone(document.body, {
      url: myURL,
      method: 'post',
      thumbnailWidth: 80,
      thumbnailHeight: 80,
      parallelUploads: 20,
      previewTemplate: previewTemplate,
      maxFilesize: 2048,
      timeout:10800000,
      acceptedFiles: '.3gp,.3g2,.avi,.uvh,.uvm,.uvu,.uvp,.uvs,.uaa,.fvt,.f4v,.flv,.fli,.h261,.h263,.h264,.jpgv,.m4v,.asf,.pyv,.wm,.wmx,.wmv,.wvx,.mj2,.mxu,.mpeg,.mp4,.ogv,.webm,.qt,.movie,.viv,.wav,.avi,.mkv',
      autoQueue: false,
      previewsContainer: "#previews",
      clickable: ".fileinput-button"
    });

    myDropzone.on("addedfile", function(file) {
      file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
    });

    myDropzone.on("totaluploadprogress", function(progress) {
      document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
    });

    myDropzone.on("sending", function(file) {
      document.querySelector("#total-progress").style.opacity = "1";
      file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
    });

    myDropzone.on("queuecomplete", function(progress) {
      document.querySelector("#total-progress").style.opacity = "0";
    });

    myDropzone.on("complete", function(file) {
        myDropzone.removeFile(file);
    });

    document.querySelector("#actions .start").onclick = function() {
      myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
    };
    document.querySelector("#actions .cancel").onclick = function() {
      myDropzone.removeAllFiles(true);
    };

    myDropzone.on("success", function( file, result ) {
        if(result.status.status == 1){
            var count = parseInt($('span.total_videos').html());
            var newCount = count + 1;
            var videoString = '<div class="row results" id="results'+result.data.id+'" data-tab="'+result.data.id+'" data-area="'+result.data.lesson_id+'" data-plot="'+result.data.course_id+'">'+
                                '<div class="col-xs-1"><i class="fa fa-arrows-alt second"></i> '+ newCount +'</div>'+
                                '<div class="col-xs-2 title"><a href="#" class="editable" data-area="'+result.data.id+'">'+ result.data.title +'</a></div>'+
                                '<div class="col-xs-2 text-center">'+ result.data.duration +'</div>'+
                                '<div class="col-xs-2 text-center">'+ result.data.size +'</div>'+
                                '<div class="col-xs-2 text-center">'+ result.data.free +'</div>'+
                                '<div class="col-xs-3 text-center">'+ 
                                    '<button class="btn btn-default btn-xs" data-link="'+result.data.link+'"><i class="fa fa-play"></i> Play Video</button>'+
                                    '<button class="btn btn-danger btn-xs" onclick="deleteLecture('+result.data.id+')"><i class="fa fa-trash"></i> Delete</button>'+
                                    '<a href="/videos/'+result.data.id+'/comments" class="btn btn-primary btn-xs"><i class="fa fa-comments"></i> Comments</a>'+
                                    '<a href="/videos/'+result.data.id+'/changeStatus" class="btn btn-success btn-xs"><i class="fa fa-video-camera"></i> Toggle Free</a>'+
                                    '<a class="btn btn-warning btn-xs" target="_blank" data-tab="'+result.data.id+'" data-area="'+result.data.lesson_id+'" data-plot="'+result.data.course_id+'"><i class="fa fa-share"></i> Move To Another Lesson</a>'+
                                    '<input id="fileUpload" class="hidden" name="attachment" type="file">'+
                                    '<button class="btn btn-info btn-xs" onclick="uploadAttachment('+result.data.id+')"><i class="fa fa-file"></i> Upload PDF</button>'+
                                '</div>'+
                              '</div>';

            $('.playlist').append(videoString);
            $('span.total_videos').html(newCount);                
            initEditable();
            successNotification(result.status.message);
        }else{
            alert(result.status.message);
        }
    });
}



$(document).on('click','.row.results .btn.btn-default.btn-xs',function(){
    $('#myModal h5.modal-title span.my-title').empty();
    var link = $(this).attr('data-link');
    var title = $(this).parent('div.col-xs-3').siblings('div.col-xs-2.title').text();
    $('#myModal h5.modal-title span.my-title').html(title);
    $('#myModal iframe').attr('src',link);
    $('#myModal').modal('toggle');
});

$('#myModal').on('hidden.bs.modal', function () {
    $('#myModal iframe').attr('src','');
});

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
            if (data.status.status.status == 1) {
                if(data.count == 0){
                    $('.playlist').remove();
                    $('.x_content_playlist').append('<div class="empty">No Videos Available</div>');
                } 
                $('#results' + video_id).remove();
                var count = $('span.total_videos').html();
                $('span.total_videos').html(count-1);
                successNotification(data.status.status.message);
            } else {
                errorNotification(data.status.status.message);
            }
        })
    });
}

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

$('.add-question').on('click',function(){
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
                if(data.count > 1){
                    $('.quiz').append(videoString);
                    $('span.total_questions').html(newCount);
                }else if(data.count == 1){
                    location.reload();
                }                                   
                successNotification(data.status.message);
            }else{
                errorNotification(data.status.message);
            }
        },
    });
});

$('.clear-question').on('click',function(){
    clearAll();
});

$('input[name="valid_until"]','input[name="start_date"]').datetimepicker({
    format: 'YYYY-MM-DD',
});

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

function uploadAttachment(video_id) {
    $('#fileUpload').click();
    var url = "/videos/"+video_id+"/uploadAttachment";
    if(url.indexOf("#") != -1){
        url = url.replace('#','');
    }
    window.videoURL = url;
}

$('#fileUpload').on('change',function(){
    var formData = new FormData();
    var $file = document.getElementById('fileUpload');
    if ($file.files.length > 0) {
       for (var i = 0; i < $file.files.length; i++) {
            formData.append('attachment', $file.files[i]);
       }
    }
    $.ajax({
        type:'POST',
        url: window.videoURL,
        data:formData,
        cache:false,
        contentType: false,
        processData: false,
        success:function(data){
            location.reload();
        },
        error: function(data){
            errorNotification(data.status.message);
            location.reload();
        }
    });
});

$('input[name="active_at"]').datetimepicker({
    format: "YYYY-MM-DD HH:00:00"
})

initEditable();

$(function(){
    $(".playlist").sortable({
        group: 'no-drop',
        containerSelector: '.playlist',
        itemSelector: '.row.results',
        handle: 'i.fa.fa-arrows-alt.second',
        // set $item relative to cursor position
        onDragStart: function ($item, container, _super,event) {
            elemIndex2 = $item.index();
            event.stopPropagation();
            event.preventDefault();
            _super($item, container);
        },
        onDrop: function  ($item, container, _super,event) {
            event.stopPropagation();
            event.preventDefault();
            if(!container.options.drop)
              $item.clone().insertAfter($item);
            _super($item, container);
            if($item.index() != 0 && $item.index() != elemIndex2){

                var ids2 = [];
                var indexes2 = [];
                var course_id = $item.data('plot');
                var lesson_id = $item.data('area');
                $item.parent('.playlist').children('.row.results').each(function(index,elem){
                    ids2.push($(elem).data('tab'));
                    indexes2.push(index);
                });
                var myURL = '/courses/view/'+course_id+'/sortVideo';

                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    type:'post',
                    url: myURL,
                    data:{
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'lesson_id': lesson_id,
                        'ids': JSON.stringify(ids2),
                        'sorts': JSON.stringify(indexes2),
                    },
                });     
            }
        },
    });
})