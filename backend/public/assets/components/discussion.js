/**
 * Discussion Js
 */

console.log("[x] Loading Discussion js .... Done");

function deleteComment($id) {
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
        $.post('/courses/removeDiscussion/' + $id,function(data) {
            if (data.status.status == 1) {
                $('#tableRaw' + $id).remove();
                successNotification(data.status.message);
            } else {
                errorNotification(data.status.message);
            }
        })
    });
}

function sendComment(reply){
    var url = window.location.href;
    url = window.location.href.split('?')[0];
    if(url.indexOf("#") != -1){
        url = url.replace('#','');
    }
    var myURL = url+'/addComment';
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
        type:'post',
        url: myURL,
        data:{
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'comment': $('textarea.comment').val(),
            'reply': reply,
        },
        success:function(data){
            if(data.status.status == 1){
                successNotification(data.status.message);
                $('textarea.comment').val('');
                $('.row.comment').slideToggle(250);
                var commentString = '<li id="tableRaw'+data.data.id+'">'+
                                        '<img src="'+data.data.image+'" class="avatar" alt="Avatar">'+
                                        '<div class="message_wrapper">'+
                                            '<h4 class="heading">'+data.data.creator+'</h4>'+
                                            '<span class="time">'+data.data.created_at+'</span>'+
                                            '<p class="message">'+ data.data.comment +'</p>'+
                                            '<p class="url">'+ 
                                                '<a href="#" class="reply" data-area="'+data.data.id+'">'+
                                                    '<i class="fa fa-reply"></i> Reply'+
                                                '</a> &nbsp;'+
                                                '<a href="#" onclick="deleteComment('+data.data.id+')">'+
                                                    '<i class="fa fa-trash"></i> Delete'+
                                                '</a>'+
                                            '</p>'+
                                            '<div class="clearfix"></div>'+
                                        '</div>'+
                                  '</li>';
                if(reply == 0){
                    $('ul.messages.messages1').prepend(commentString);
                }else{
                    if($('#tableRaw'+data.data.reply_on+' ul.messages2').length > 0){
                        $('#tableRaw'+data.data.reply_on+' ul.messages2').prepend(commentString);
                    }else{
                        $('#tableRaw'+data.data.reply_on).append('<ul class="messages messages2"></ul>');
                        $('#tableRaw'+data.data.reply_on+' ul.messages2').prepend(commentString);
                    }
                }
                $('html, body').animate({
                    scrollTop: $(commentString).offset().top
                }, 350);
            }else{
                errorNotification(data.status.message);
            }
        },
    });
}

$(document).on('click','a.reply',function(e){
    e.preventDefault();
    e.stopPropagation();
    var comment_id = $(this).attr('data-area');
    $('.row.comment').slideDown(250);
    $('html, body').animate({
        scrollTop: $(".row.comment").offset().top
    }, 350);
    $('.row.comment button.btn-success').attr('data-area',comment_id);
});

$('li.btn-default').on('click',function(e){
    e.preventDefault();
    e.stopPropagation();
    $('.row.comment').slideDown(250);
    $('html, body').animate({
        scrollTop: $(".row.comment").offset().top
    }, 350);
    $('.row.comment button.btn-success').attr('data-area',0);
});

$('.row.comment button.btn-success').on('click',function(e){
    e.preventDefault();
    e.stopPropagation();
    sendComment($(this).attr('data-area'));
});
