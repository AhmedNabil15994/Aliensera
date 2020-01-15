$(function(){

	$('a.lang-media__link').on('click',function(e){
		e.preventDefault();
		e.stopPropagation();
		var locale = $(this).attr('value');
		var _token = $('meta[name="_token"]').attr('value');

		$.ajax({

			url : '/language',
			type : 'POST',
			data:{
				"_token" : _token,
				"locale": locale,
			},
			datatype: "json",
			success:function(data){

			},
			error:function(data){

			},
			beforeSend:function(){

			},
			complete:function(data){
				window.location.reload(true);
			}

		});
	});

});
