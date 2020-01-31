$(function(){
	$('.profile_left .btn.btn-success').on('click',function(){
		$('input[name="image"]').click();
	});

	$('input[name="image"]').on('change',function(){
        $('.img-responsive.avatar-view').attr('src', window.URL.createObjectURL($(this)[0].files[0]));
    });
});