$(function(){

	$('.row.results a.btn.btn-warning').on('click',function(){
		var lesson_id = $(this).data('area');
		var video_id = $(this).data('tab');
		$('#move_to_another_lesson h5.modal-title span.my-title').empty();
		$('#move_to_another_lesson .btn-success').attr('data-area',0);
		$('#move_to_another_lesson .btn-success').attr('data-role',0);
		var title = $(this).parent('div.col-xs-3').siblings('div.col-xs-2.title').text();
    	$('#move_to_another_lesson h5.modal-title span.my-title').html(title);

		var url = window.location.href;
        if(url.indexOf("#") != -1){
            url = url.replace('#','');
        }
		var myURL = url+'/movableLessons';

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
            	$('#move_to_another_lesson select[name="lesson_id"]').select2();
				$('#move_to_another_lesson').modal('toggle');
            }
        }); 

	});

	$('#move_to_another_lesson .btn-success').on('click',function(e){
		var lesson_id = $(this).data('area');
		var video_id = $(this).data('role');
		var url = window.location.href;
        if(url.indexOf("#") != -1){
            url = url.replace('#','');
        }
		var myURL = url+'/moveVideo';
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

});