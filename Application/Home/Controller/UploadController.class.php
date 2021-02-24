<?php
namespace Home\Controller;
use Think\Controller;
class UploadController extends CommonController {
    public function index(){
        dump(1);die;
    }

    /*上传视频*/
    public function article_video(){
        $file = $_FILES['photoimage'];
        if ($_FILES['photoimage']['error']== 0) {
            //这里在同目录下需要有pictures文件夹
            $fillname = $_FILES['photoimage']['name']; // 得到文件全名
            $dotArray = explode('.', $fillname); // 以.分割字符串，得到数组
            $type = end($dotArray); // 得到最后一个元素：文件后缀
            if($type!='mp4' && $type!='MP4'){

                $this->ajaxReturn('',"JSON");
            }
        }


        if ($_FILES['photoimage']['error'] == 0) {
            //这里在同目录下需要有pictures文件夹
            $fillname = $_FILES['photoimage']['name']; // 得到文件全名
            $dotArray = explode('.', $fillname); // 以.分割字符串，得到数组
            $type = end($dotArray); // 得到最后一个元素：文件后缀

            $mk = date('Y-m-d');
            $path="./Public/Uploads/video/".$mk."/";
            //判断目录存在否，存在给出提示，不存在则创建目录
            if (is_dir($path)){
                //echo "对不起！目录 " . $path . " 已经存在！";
            }else{
                //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
                $res=mkdir(iconv("UTF-8", "GBK", $path),0777,true);
                if ($res){
                    //echo "目录 $path 创建成功";
                }else{
                    //echo "目录 $path 创建失败";
                }
            }
            $num = date('YmdHis',time()).rand(1000,9999);
            if(move_uploaded_file($_FILES['photoimage']['tmp_name'], './Public/Uploads/video/'.$mk.'/'.$num.'.'.$type)) {


                chmod( './Public/Uploads/video/'.$mk.'/'.$num.'.'.$type, 0755);
                $thumb_url = './Public/Uploads/video/'.$mk.'/'.$num.'.jpg';


                $wh = getVideoCover( './Public/Uploads/video/'.$mk.'/'.$num.'.'.$type, '0.001',$thumb_url);

                $image = new \Think\Image();
                $image->open($thumb_url);
                // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
                $image->thumb(150,150)->save('./Public/Uploads/video/'.$mk.'/'.$num.'_thumb.jpg');

                $add['url'] = XUANIMG.'/Public/Uploads/video/'.$mk.'/'.$num.'.'.$type;
                $add['first'] = XUANIMG.'/Public/Uploads/video/'.$mk.'/'.$num.'.jpg';
                $add['thumb'] = XUANIMG.'/Public/Uploads/video/'.$mk.'/'.$num.'_thumb.jpg';
                $add['type'] = 2;
                $add['width'] = $wh[0];
                $add['height'] = $wh[1];
                $add['create_time'] = time();
                M('cache')->add($add);
                $this->ajaxReturn($add['url'],"JSON");
            }
        }

    }




    public function thumb(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize  = 5145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->saveName = date('YmdHis').rand(10000,99999);
        $upload->savePath =  'Uploads/';// 设置附件上传目录
        /*if(!$upload->upload()) {// 上传错误提示错误信息
            $msg['success'] =false;
            echo json_encode($msg);
        }else{// 上传成功
            $info =  $upload->getUploadFileInfo();
            $msg['success'] =true;
            $msg['file_path'] ='/'.$info[0]['savepath'].$info[0]['savename'];
            echo json_encode($msg);
        }*/
        $info   =   $upload->uploadOne($_FILES['photoimage']);

        if(!$info) {// 上传错误提示错误信息
            $msg  =false;
            $this->ajaxReturn($msg,'JSON');
        }else{// 上传成功 获取上传文件信息
            //$msg['success'] =true;
            $msg= XUANIMG.'/Public/'.$info['savepath'].$info['savename'];

            $this->ajaxReturn($msg,'JSON');
        }
    }


    public function thumb_a(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize  = 5145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->saveName = date('YmdHis').rand(10000,99999);
        $upload->savePath =  'Uploads/';// 设置附件上传目录
        /*if(!$upload->upload()) {// 上传错误提示错误信息
            $msg['success'] =false;
            echo json_encode($msg);
        }else{// 上传成功
            $info =  $upload->getUploadFileInfo();
            $msg['success'] =true;
            $msg['file_path'] ='/'.$info[0]['savepath'].$info[0]['savename'];
            echo json_encode($msg);
        }*/
        $info   =   $upload->uploadOne($_FILES['photoimage']);
        $size = $_FILES['photoimage']['size'];
        if($size>32768){
            $msg  =false;
            $this->ajaxReturn($msg,'JSON');
        }else{
            if(!$info) {// 上传错误提示错误信息
                $msg  =false;
                $this->ajaxReturn($msg,'JSON');
            }else{// 上传成功 获取上传文件信息
                //$msg['success'] =true;
                $msg= XUANIMG.'/Public/'.$info['savepath'].$info['savename'];

                $this->ajaxReturn($msg,'JSON');
            }
        }

    }
    public function video(){
        $list = $_FILES;

        $tm = date('Y-m-d H:i:s',time());
        $list = json_encode($list,true);
        $rew = $tm." / 输入 ：". $list;
        //file_put_contents('D:/WWW/rainbow/taxi/Public/txt/log_shift_add.php',$rew.PHP_EOL,FILE_APPEND);

        if ($_FILES["file"]["error"] > 0) {
            echo $_FILES["file"]["error"]; // 错误代码
        } else {
            $fillname = $_FILES['file']['name']; // 得到文件全名
            $dotArray = explode('.', $fillname); // 以.分割字符串，得到数组
            $type = end($dotArray); // 得到最后一个元素：文件后缀
            $file_name = date('YmdHis').rand(10000,99999);
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize  = 8485760;// 设置附件上传大小
            $upload->allowExts  = array('mp4', 'rmvb', 'png', 'jpeg','jpg','JPG');// 设置附件上传类型
            $upload->saveName = $file_name;
            $upload->savePath =  'Uploads/video/';// 设置附件上传目录

            $info   =   $upload->uploadOne($_FILES['photoimage']);
            if(!$info) {// 上传错误提示错误信息
                $msg  =false;
                $this->ajaxReturn($msg,'JSON');
            }else{// 上传成功 获取上传文件信息
                //$msg['success'] =true;
                $msg =XUANIMG.'/Public/'.$info['savepath'].$info['savename'];

                $video_url = '/www/wwwroot/default/yimei/Public/'.$info['savepath'].$info['savename'];


                chmod($video_url, 0755);
                $thumb_url = '/www/wwwroot/default/yimei/Public/'.$info['savepath'].$file_name.'.jpg';

                $msg_thumb =XUANIMG.'/Public/'.$info['savepath'].$file_name.'.jpg';
                $wh = getVideoCover($video_url, '0.001',$thumb_url);


                $image = new \Think\Image();
                $image->open( './Public/'.$info['savepath'].$file_name.'.jpg');
                // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
                $image->thumb(150,150)->save('./Public/'.$info['savepath'].$file_name.'_thumb.jpg');

                $add['url'] = XUANIMG.'/Public/'.$info['savepath'].$info['savename'];
                $add['first'] = XUANIMG.'/Public/'.$info['savepath'].$file_name.'.jpg';
                $add['thumb'] = XUANIMG.'/Public/'.$info['savepath'].$file_name.'_thumb.jpg';
                $add['type'] = 2;
                $add['width'] = $wh[0];
                $add['height'] = $wh[1];
                $add['create_time'] = time();
                M('cache')->add($add);
                $this->ajaxReturn($add['url'],'JSON');
            }

        }
    }

    public function thumb2(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize  = 5145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->saveName = date('YmdHis').rand(10000,99999);
        $upload->savePath =  'Uploads/';// 设置附件上传目录
        /*if(!$upload->upload()) {// 上传错误提示错误信息
            $msg['success'] =false;
            echo json_encode($msg);
        }else{// 上传成功
            $info =  $upload->getUploadFileInfo();
            $msg['success'] =true;
            $msg['file_path'] ='/'.$info[0]['savepath'].$info[0]['savename'];
            echo json_encode($msg);
        }*/
        $info   =   $upload->uploadOne($_FILES['photoimage2']);

        if(!$info) {// 上传错误提示错误信息
            $msg  =false;
            $this->ajaxReturn($msg,'JSON');
        }else{// 上传成功 获取上传文件信息
            //$msg['success'] =true;
            $msg= XUANIMG.'/Public/'.$info['savepath'].$info['savename'];

            $this->ajaxReturn($msg,'JSON');
        }
    }




    /*上传图片*/
    public function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize  = 5145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->saveName = date('YmdHis').rand(10000,99999);
        $upload->savePath =  'Uploads/goods/';// 设置附件上传目录
        /*if(!$upload->upload()) {// 上传错误提示错误信息
            $msg['success'] =false;
            echo json_encode($msg);
        }else{// 上传成功
            $info =  $upload->getUploadFileInfo();
            $msg['success'] =true;
            $msg['file_path'] ='/'.$info[0]['savepath'].$info[0]['savename'];
            echo json_encode($msg);
        }*/

        $info   =   $upload->uploadOne($_FILES['fileData']);
        if(!$info) {// 上传错误提示错误信息
            $msg['success'] =false;
            echo json_encode($msg);
        }else{// 上传成功 获取上传文件信息

            $msg[] =XUANIMG.'/Public/'.$info['savepath'].$info['savename'];
            $arr  = array(
                'errno'=>0,
                'data'=>$msg
            );

            $this->ajaxReturn($arr,"JSON");
        }


    }

    /*上传图片*/
    public function upload_duo(){
        if ($_FILES["file"]["error"] > 0) {
            echo $_FILES["file"]["error"]; // 错误代码
        } else {
            $fillname = $_FILES['file']['name']; // 得到文件全名
            $dotArray = explode('.', $fillname); // 以.分割字符串，得到数组
            $type = end($dotArray); // 得到最后一个元素：文件后缀

            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize  = 5145728000 ;// 设置附件上传大小
            $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->saveName = date('YmdHis').rand(10000,99999);
            $upload->savePath =  'Uploads/duo/';// 设置附件上传目录

            $info   =   $upload->upload();
            if(!$info) {// 上传错误提示错误信息
                $msg  =false;
                $arr = array(
                    'code'=> 0,
                    'res'=>'文件类型不正确'
                );
                $this->ajaxReturn($arr,"JSON");
            }else{// 上传成功 获取上传文件信息
                //$msg['success'] =true;
                $msg =XUANIP.__ROOT__.'/Public/'.$info['file']['savepath'].$info['file']['savename'];

                $arr = array(
                    'code'=> 1,
                    'res'=>$msg
                );
                $this->ajaxReturn($arr,"JSON");
            }
        }
    }




}