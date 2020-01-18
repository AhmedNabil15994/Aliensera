/**
 * Lesson Js
 */

console.log("[x] Loading Lesson js .... Done");

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
        var videoString = '<div class="row results" id="results'+result.data.id+'">'+
                            '<div class="col-xs-1">'+ newCount +'</div>'+
                            '<div class="col-xs-5">'+ result.data.title +'</div>'+
                            '<div class="col-xs-2 text-center">'+ result.data.duration +'</div>'+
                            '<div class="col-xs-2 text-center">'+ result.data.size +'</div>'+
                            '<div class="col-xs-2 text-center">'+ 
                                '<button class="btn btn-default btn-xs" data-link="'+result.data.link+'"><i class="fa fa-play"></i></button>'+
                                '<a href="/videos/'+result.data.id+'/comments" class="btn btn-primary btn-xs"><i class="fa fa-comments"></i></a>'+
                                '<button class="btn btn-danger btn-xs" onclick="deleteLecture('+result.data.id+')"><i class="fa fa-trash"></i></button>'+
                            '</div>'+
                          '</div>';
        $('.playlist').append(videoString);
        $('span.total_videos').html(newCount);
        successNotification(result.status.message);
        // location.reload();
    }else{
        alert(result.status.message);
    }
});

$(document).on('click','.row.results .btn.btn-default.btn-xs',function(){
    var link = $(this).attr('data-link');
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

function clearAll(){
    $('input[name="question"]').val('');
    $('input[name="answer_a"]').val('');
    $('input[name="answer_b"]').val('');
    $('input[name="answer_c"]').val('');
    $('input[name="answer_d"]').val('');
    $('select[name="correct_answer"]').val('').trigger('change');
}

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
            'question': $('input[name="question"]').val(),
            'answer_a': $('input[name="answer_a"]').val(),
            'answer_b': $('input[name="answer_b"]').val(),
            'answer_c': $('input[name="answer_c"]').val(),
            'answer_d': $('input[name="answer_d"]').val(),
            'correct_answer': $('select[name="correct_answer"] option:selected').val(),
        },
        success:function(data){
            if(data.status.status == 1){
                successNotification(data.status.message);
                clearAll();
                var count = parseInt($('span.total_questions').html());
                var newCount = count + 1;
                var videoString = '<div class="row results" id="questions'+data.data.id+'">'+
                                    '<div class="col-xs-1">'+ newCount +'</div>'+
                                    '<div class="col-xs-2">'+ data.data.question +'</div>'+
                                    '<div class="col-xs-2">'+ data.data.answer_a +'</div>'+
                                    '<div class="col-xs-2">'+ data.data.answer_b +'</div>'+
                                    '<div class="col-xs-2">'+ data.data.answer_c +'</div>'+
                                    '<div class="col-xs-2">'+ data.data.answer_d +'</div>'+
                                    '<div class="col-xs-1 text-center">'+ 
                                        '<button class="btn btn-danger btn-xs" onclick="deleteQuestion('+data.data.id+')"><i class="fa fa-trash"></i></button>'+
                                    '</div>'+
                                  '</div>';
                $('.quiz').append(videoString);
                $('span.total_questions').html(newCount);
            }else{
                errorNotification(data.status.message);
            }
        },
    });
});

$('.clear-question').on('click',function(){
    clearAll();
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