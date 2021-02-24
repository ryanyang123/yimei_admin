/**
 * Created by Administrator on 2018/7/11.
 */

var uid = "<?php echo session(XUANDB.'id');?>";
$(document).ready(function () {
    // 连接服务端
    var socket = io('http://'+document.domain+':2120');
    // 连接后登录
    socket.on('connect', function(){
        socket.emit('login', uid);
    });
    // 后端推送来消息时
    socket.on('new_msg', function(msg){
        //IE9+,Firefox,Chrome均支持<audio/>

        if(msg){
            OverAllNav();
        }

    });
    // 后端推送来在线数据时
    socket.on('update_online_count', function(online_stat){
        /*$('#online_box').html(online_stat);
         console.log(online_stat);*/
    });
});

function OverAllNav(){
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "<?php echo U('home/public/overallnav'); ?>",
        success: function (result) {
            if(result.code == 1){
                $('.overall_a .tubiao').remove();
            }
        }
    });
}

