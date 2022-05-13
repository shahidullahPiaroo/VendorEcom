$(document).ready(function(){
    $("#current_password").keyup(function(){
        var current_password = $("#current_password").val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'post',
            url:'check-current-password',
            data:{current_password:current_password},
            success:function(resp){
                if(resp=="true"){
                    $("#check_password").html("<font color='green'>Current Password is Correct!</font>");
                }else if(resp=="false") {
                    $("#check_password").html("<font color='red'>Current Password is incorrect!</font>");
                }
            },error:function(){
                alert('error');
            }
        });
    });
});