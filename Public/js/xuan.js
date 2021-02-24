/**
 * Created by YXL on 2017/11/23.
 */



function xuan_login(u,p,code){
    $('#sub_btn').attr('disabled',true).addClass('black');
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: submit_url,
        data:{username:u,password:p,verify:code},
        success: function (result) {
            if(result.code == 1){
                location.href = to_url;
            }else if(result.code == 0){
                $('#tis').html(result.res);
                $('.alert-error', $('.login-form')).show();
                $('#sub_btn').attr('disabled',false).removeClass('black');
            }else{

                $('#sub_btn').attr('disabled',false).removeClass('black');
            }
        }
    });
}