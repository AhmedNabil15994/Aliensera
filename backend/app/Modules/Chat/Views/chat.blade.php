@extends('Layouts.master')
@section('title', 'Chat')
@section('otherhead')
<meta name="robots" content="noindex">
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,300' rel='stylesheet' type='text/css'>
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css'>
<link rel="stylesheet" type="text/css" href="{{ asset('/assets/build/css/chat.css') }}">
@endsection
@section('content')
<div class="">
  <div class="row" >
    <div class="col-md-12 col-sm-12 col-xs-12" >
        <div class="x_panel" >
            <div class="x_title">
                <strong>Messages</strong>
            </div>
            <div class="x_content">
              <div class="row">
                <div id="frame">
                    <div id="sidepanel">
                        <div id="profile">
                            <div class="wrap">
                                <img id="profile-img" src="{{ $data->data->image }}" class="online" alt="" />
                                <p>{{ $data->data->name }}</p>
                            </div>
                        </div>
                        <div id="contacts">
                            <h4>Recent Messages</h4>
                            <ul>
                              @foreach($data->messages as $message)
                              @if($message->sender_id == USER_ID)
                              @php 
                              $image = $message->receiver_image; 
                              $name = $message->receiver; 
                              @endphp
                              @else
                              @php 
                              $image = $message->sender_image; 
                              $name = $message->sender; 
                              @endphp
                              @endif
                              <li class="contact" data-area="{{ $message->id }}">
                                <div class="wrap">
                                  <span class="contact-status label label-xs label-danger">{{ $message->unreadCount != 0 ? $message->unreadCount : '' }}</span>
                                  <img src="{{ $image }}">
                                  <div class="meta">
                                    <p class="name">{{ $name }}</p>
                                    <p class="preview">
                                      @if(!empty($message->messages))
                                      @php 
                                      $extra = '';
                                      @endphp
                                      @if($message->messages[0]->created_by == USER_ID)
                                      @php 
                                      $extra = '<span>You: </span>';
                                      @endphp
                                      @endif
                                      {!! $extra.$message->messages[0]->message !!}
                                      @endif
                                    </p>
                                  </div>
                                </div>
                              </li>
                              @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="content">
                        <div class="chat-loader hidden">
                            <img src="{{ asset('/assets/production/images/loader.gif') }}" alt="">
                        </div>
                        <div class="main-content-msg hidden">
                            <input type="file" name="attachment" id="attachment" accept=".pdf,.png,.jpg,.jpeg">
                            <div class="contact-profile">
                                <img src="" alt="" />
                                <p></p>
                            </div>
                            <div class="messages">
                                <ul></ul>
                            </div>
                            <div class="message-input">
                                <div class="wrap">
                                    <input type="text" placeholder="Write your message..." />
                                    <i class="fa fa-paperclip attachment" aria-hidden="true"></i>
                                    <button class="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>     
              </div>
            </div>
        </div>
    </div>
  </div>
</div>        
@stop()

@section('script')
<script src="https://js.pusher.com/5.1/pusher.min.js"></script>
<script>
  var senderId = "{{ $data->data->id }}";

  var pusher = new Pusher('ca531b8bd4b4d40d5373', {
    cluster: 'eu',
    forceTLS: true
  });

  var channel = pusher.subscribe('receiver-'+senderId);
  channel.bind('my-event', function(data) {
    var checkElem = $('li.contact[data-area="'+data.msg.chat_head_id+'"]');
    if(checkElem.length == 0){
      var myElem = '<li class="contact" data-area="'+data.msg.chat_head_id+'">'+
                      '<div class="wrap">'+
                        '<span class="contact-status online"></span>'+
                        '<img src="'+data.msg.sender_image+'">'+
                        '<div class="meta">'+
                          '<p class="name">'+data.msg.sender_name+'</p>'+
                          '<p class="preview">'+data.msg.message+'</p>'+
                        '</div>'+
                      '</div>'+
                    '</li>';
      $('#contacts ul').prepend(myElem); 
    }

    if($('li.contact[data-area="'+data.msg.chat_head_id+'"]').hasClass('active')){

      if(data.msg.message_type == 0){
        $('<li class="sent"><div class="row"><img src="'+data.msg.sender_image+'" alt="" /><p>' + data.msg.message + '</p></div><span class="pull-left">'+data.msg.time+'</span></li>').appendTo($('.messages ul'));
      }else if(data.msg.message_type == 1){
        $('<li class="sent"><div class="row"><img src="'+data.msg.sender_image+'" alt="" /><p><a target="_blank" href="'+data.msg.file_url+'"><i class="fa fa-paperclip"></i> ' + data.msg.message + '</a></p></div><span class="pull-left">'+data.msg.time+'</span></li>').appendTo($('.messages ul'));
      }else if(data.msg.message_type == 2){
        $('<li class="sent"><div class="row"><img src="'+data.msg.sender_image+'" alt="" /><p><a target="_blank" href="'+data.msg.file_url+'"><img src="'+data.msg.file_url+'"> </a></p></div><span class="pull-left">'+data.msg.time+'</span></li>').appendTo($('.messages ul'));
      }

    }

    $('li.contact[data-area="'+data.msg.chat_head_id+'"] .wrap .meta .preview').html(data.msg.message);
    var unread = $('li.contact[data-area="'+data.msg.chat_head_id+'"] .wrap span.contact-status.label-danger').html();
    if(!unread){
      unread+=1; 
    }else{
      unread = parseInt(unread);
      unread = Math.round(unread+1);
    }
    $('li.contact[data-area="'+data.msg.chat_head_id+'"] .wrap span.contact-status.label-danger').html(unread);
    $(".messages").animate({ scrollTop: 20000000 }, "slow");
  });
</script>
<script type="text/javascript">
  
  $(function(){
      var senderId = "{{ $data->data->id }}";

      function changeChat(key){
        $('.chat-loader.hidden').removeClass('hidden');
        setTimeout(function(){
            $('.messages ul').empty();
            $.get('/messages/' + key,function(data) {
                $.each(data.messages.messages,function(index,item){
                  if(item.created_by == senderId){
                    var image = '';
                    if(item.created_by == data.messages.sender_id){
                      image = data.messages.sender_image;
                    }else{
                      image = data.messages.receiver_image;
                    }
                    var liClass = 'replies';
                    var spanPull = 'right';

                  }else{
                    var image = '';
                    if(item.created_by == data.messages.receiver_id){
                      image = data.messages.receiver_image;
                    }else{
                      image = data.messages.sender_image;
                    }
                    var liClass = 'sent';
                    var spanPull = 'left';
                  }

                 if(item.message_type == 0){
                    $('<li class="'+liClass+'"><div class="row"><img src="'+image+'" alt="" /><p>' + item.message + '</p></div><span class="pull-'+spanPull+'">'+item.created_at+'</span></li>').appendTo($('.messages ul'));
                  }else if(item.message_type == 1){
                    $('<li class="'+liClass+'"><div class="row"><img src="'+image+'" alt="" /><p><a target="_blank" href="'+item.file_url+'"><i class="fa fa-paperclip"></i> ' + item.message + '</a></p></div><span class="pull-'+spanPull+'">'+item.created_at+'</span></li>').appendTo($('.messages ul'));
                  }else if(item.message_type == 2){
                    $('<li class="'+liClass+'"><div class="row"><img src="'+image+'" alt="" /><p><a target="_blank" href="'+item.file_url+'"><img src="'+item.file_url+'"> </a></p></div><span class="pull-'+spanPull+'">'+item.created_at+'</span></li>').appendTo($('.messages ul'));
                  }


                });
            })
            $('.chat-loader').addClass('hidden');
            $('.main-content-msg.hidden').removeClass('hidden');
            $(".messages").animate({ scrollTop: 20000000 }, "slow");
            $('li.contact.active .wrap span.contact-status.label-danger').empty();

        }, 1500);

      }

      $(document).on('click','li.contact',function(e){
          e.preventDefault();
          e.stopPropagation();
          if(!$(this).hasClass('active')){
              $(this).siblings('li.contact.active').removeClass('active');
              $(this).addClass('active');
              var imgSrc = $(this).children('div.wrap').children('img').attr('src');
              var chatName = $(this).children('div.wrap').children('div.meta').children('p.name').html();
              var myKey = $(this).attr('data-area');
              $('.contact-profile img').attr('src',imgSrc);
              $('.contact-profile p').html(chatName);
              window.key = myKey;
              changeChat(myKey);
          }
      });

      function newMessage(message,urlFile,imageH,imageW,fileType) {
            if($.trim(message) == '') {
                return false;
            }
            
            var url =  "/messages/:key/newMessage".replace(':key',window.key);
            $formData = new FormData();
            if(fileType != 0){
              $formData.append('file_url', urlFile);
            }
            $formData.append('img_height', imageH);
            $formData.append('img_width', imageW);
            $formData.append('message_type', fileType);
            $formData.append('message', message);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: url,
                type: 'POST',
                data: $formData ,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                  if(fileType == 0){
                    $('<li class="replies"><div class="row"><img src="'+data.messages.sender_image+'" alt="" /><p>' + data.messages.message + '</p></div><span class="pull-right">'+data.messages.time+'</span></li>').appendTo($('.messages ul'));
                  }else if(fileType == 1){
                    $('<li class="replies"><div class="row"><img src="'+data.messages.sender_image+'" alt="" /><p><a target="_blank" href="'+urlFile+'"><i class="fa fa-paperclip"></i> ' + message + '</a></p></div><span class="pull-right">'+data.messages.time+'</span></li>').appendTo($('.messages ul'));
                  }else if(fileType == 2){
                    $('<li class="replies"><div class="row"><img src="'+data.messages.sender_image+'" alt="" /><p><a target="_blank" href="'+urlFile+'"><img src="'+urlFile+'"> </a></p></div><span class="pull-right">'+data.messages.time+'</span></li>').appendTo($('.messages ul'));
                  }
                  $('li.contact.active .wrap span.contact-status.label-danger').empty();
                  $(".messages").animate({ scrollTop: 20000000 }, "slow");
                },        
            });

            $('.message-input input').val(null);
            $('.contact.active .preview').html('<span>You: </span>' + message);
      };

      $(document).on('click','.attachment',function(){
          $('#attachment').click();
      });

      $("#attachment").change(function (){
          var $file = document.getElementById('attachment');
          $formData = new FormData();
          if ($file.files.length > 0) {
              for (var i = 0; i < $file.files.length; i++) {
                  $formData.append('attachment', $file.files[i]);
              }
          }
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.ajax({
              url: "/messages/uploadAttachment",
              type: 'POST',
              data: $formData ,
              dataType: 'json',
              contentType: false,
              processData: false,
              success: function (data) {
                  if(data[2] == 'image'){
                      newMessage(data[0],data[1],data[3],data[4],2)
                  }else{
                      newMessage(data[0],data[1],data[3],data[4],1)
                  }
              },        
              error: function(data){
                  console.log('error');
              }
          });
      });

      $('.submit').click(function() {
          newMessage($(".message-input input").val(),'',0,0,0);
      });

      $(window).on('keydown', function(e) {
          if (e.which == 13) {
              newMessage($(".message-input input").val(),'',0,0,0);
              return false;
          }
      });
        
  });

</script>
@stop()