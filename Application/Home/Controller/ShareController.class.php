<?php
namespace Home\Controller;
use Think\Controller;
class ShareController extends Controller {
    public function index(){

        $type = I('get.type');
        if (!$type){$type='1';}
        $aid = I('get.aid');
        //$aid = 233;
        //$aid = 999;
        if(!$aid){
            echo "参数错误";
        }
        if ($type=='1'){
            $list =  M('user_blogs')
                ->where(array('lyx_user_blogs.id'=>$aid))
                ->join('lyx_user ON lyx_user_blogs.user_id=lyx_user.id')
                ->field('lyx_user_blogs.type,lyx_user.head,lyx_user.name,lyx_user_blogs.content,lyx_user_blogs.video_url,lyx_user_blogs.video_thumb,lyx_user_blogs.video_width,lyx_user_blogs.video_height')
                ->find();
        }else{

            $list = M('shop_blogs')
                ->where(array('lyx_shop_blogs.id'=>$aid))
                ->join('lyx_shop ON lyx_shop_blogs.shop_id=lyx_shop.id')
                ->field('lyx_shop_blogs.type,lyx_shop.logo as head,lyx_shop.shop_name as name,lyx_shop_blogs.content,lyx_shop_blogs.video_url,lyx_shop_blogs.video_thumb,lyx_shop_blogs.video_width,lyx_shop_blogs.video_height')
                ->find();


        }




        if($list){
            if($list['type']=='1'){
                if ($type=='1'){
                    $list['img_list'] = M('user_blogs_photo')->where(array('blogs_id'=>$aid))->select();
                }else{
                    $list['img_list'] = M('shop_blogs_photo')->where(array('shop_blogs_id'=>$aid))->select();
                }

            }
            $data['is_have'] = 1;
        }else{
            $data['is_have'] = 0;
        }




        $data['list'] =$list;



        $this->assign($data);
        $this->display();
    }



}