<?php
namespace Home\Controller;
use Think\Controller;
class RongwebController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'diaryclassify'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){
        $user = M('user');
        $rong = M('rong');
        $ul_list = $rong->where(array('user_id'=>'1'))->limit(10)->order('create_time desc')->select();
        $now_id = 0;
        if($ul_list){
            foreach ($ul_list as $key=>$item){
                if($key==0){
                    $now_id = $item['send_user_id'];
                }
                $user_info2 = $user->where(array('id'=>$item['send_user_id']))->field('id,name,head')->find();
                $ul_list[$key]['name'] = $user_info2['name'];
                $ul_list[$key]['head'] = $user_info2['head'];
            }
        }
        $user_info = $user->where(array('id'=>'1'))->field('id,head,name')->find();
        $data['user_info'] = $user_info;
        $data['ul_list'] = $ul_list;
        $data['ul_list_json'] = json_encode($ul_list);
        $data['now_id'] = $now_id;
        $data['at'] = 'rongweb';
        $this->assign($data);
        $this->display();
    }







    public function thumb(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize  = 5145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->saveName = date('YmdHis').rand(10000,99999);
        $upload->savePath =  'Uploads/rong/';// 设置附件上传目录
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


            $image = new \Think\Image();
            $image->open('./Public/'.$info['savepath'].$info['savename']);
            // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
            $image->thumb(150,150)->save('./Public/'.$info['savepath'].time().'_thumb.jpg');
            $img_file = './Public/'.$info['savepath'].time().'_thumb.jpg';

            $base64_image = '';
            $image_info = getimagesize($img_file);
            $image_data = fread(fopen($img_file, 'r'), filesize($img_file));
            $base64_image =  chunk_split(base64_encode($image_data));
            $list['url'] = $msg;
            $list['base64'] = $base64_image;
            $this->ajaxReturn(json_encode($list),'JSON');
        }
    }

    function getuserinfo($id){
        $user = M('user');
        $rong = M('rong');
        $user_info = $user->where(array('id'=>$id))->field('id,name,head')->find();



        $res = '查询';
        if($user_info){
            $add['user_id'] = 1;
            $add['send_user_id'] = $user_info['id'];
            $is_add = $rong->where($add)->find();
            $add['create_time'] = time();
            if($is_add){
                $rong->where(array('id'=>$is_add['id']))->save($add);
            }else{
                $rong->add($add);
            }


            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功',
                'list'=> $user_info
            );
            $this->ajaxReturn($arr,"JSON");
        }else{
            $arr = array(
                'code'=> 0,
                'res'=> $res.'失败'
            );
            $this->ajaxReturn($arr,"JSON");
        }
    }



}












