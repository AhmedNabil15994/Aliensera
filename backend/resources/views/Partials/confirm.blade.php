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
     $.get('$url/' + $id,function(data){
     $('#{{$field}}' + {{$id}}).remove()
   })
});
