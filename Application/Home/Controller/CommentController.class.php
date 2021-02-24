<?php
namespace Home\Controller;
use Think\Controller;
use think\helper\Time;

class CommentController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'7'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){


        $order = M('order');
        $shop_goods = M('shop_goods');
        $user = M('user');
        $shop = M('shop');



        $search = I('get.search');
        if($search){
            $where['order_number|comment'] =  array("like","%".$search."%");;
            $search_list['search'] = $search;
        }

        $user_id = I('get.user_id');
        if($user_id){
            $where['user_id'] =  $user_id;
            $search_list['user_id'] = $user_id;
        }

        $star = I('get.star');
        if($star){
            $where['star'] =  $star;
            $search_list['star'] = $star;
        }

        //时间搜索
        $search_date  = I('get.search_date');
        $search_date2  = I('get.search_date2');
        //$data['search_date'] = $search_date;
        //$data['search_date2'] = $search_date2;
        $newtime = strtotime($search_date);
        $newtime1 = strtotime($search_date2)+86399;
        if($search_date && $search_date2){
            $where['pay_time'] = array(array('EGT',$newtime),array('ELT',$newtime1),'AND');
        }else if($search_date && !$search_date2){
            $where['pay_time'] = array('EGT',$newtime);
        }else if(!$search_date && $search_date2){
            $where['pay_time'] = array('ELT',$newtime1);
        }
        $search_list['search_date'] = $search_date;
        $search_list['search_date2'] = $search_date2;
        //时间搜索


        $where['pay_status'] = 1;
        $where['status'] = 5;
        $where['comment'] = array('exp','is not null');
        $count = $order->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个评价</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $order->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $goods_info = $shop_goods->where(array('id'=>$item['goods_id']))->field('img_url,name')->find();
            $list[$key]['goods_img'] = $goods_info['img_url'];
            $list[$key]['goods_name'] = $goods_info['name'];
            $user_info = $user->where(array('id'=>$item['user_id']))->field('name')->find();
            $list[$key]['user_name'] = $user_info['name'];
            $shop_info = $shop->where(array('id'=>$item['shop_id']))->field('shop_name')->find();
            $list[$key]['shop_name'] = $shop_info['shop_name'];
        }

        $data = array(
            'list' =>$list,
            'search_list' =>$search_list,
            'page' =>$show,
            'at' =>'comment',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }

    function delete($id){
        $order = M('order');
        $order_info = $order->where(array('id'=>$id))->find();
        if ($order_info['status']!='5'){
            $arr = array('code'=> 0, 'res'=>'订单状态错误');$this->ajaxReturn($arr,"JSON");
        }
        $save['comment'] = NULL;
        $save['update_time'] = time();
        $query = $order->where(array('id'=>$id))->save($save);
        $res = '删除';
        if($query){
            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功'
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

    function save($id,$content){
        $order = M('order');
        $order_info = $order->where(array('id'=>$id))->find();
        if ($order_info['status']!='5'){
            $arr = array('code'=> 0, 'res'=>'订单状态错误');$this->ajaxReturn($arr,"JSON");
        }
        $save['comment'] = $content;
        $save['update_time'] = time();
        $query = $order->where(array('id'=>$id))->save($save);
        $res = '修改';
        if($query){
            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功'
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

    function checkcomment($id){
        $order = M('order');
        $order_info = $order->where(array('id'=>$id))->field('comment')->find();
        $arr = array(
            'code'=> 1,
            'res'=> '成功',
            'content'=> $order_info['comment']
        );
        $this->ajaxReturn($arr,"JSON");
    }


    public function add($name,$is_edit){
        $group_type = M('group_type');

        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入群分类名称');$this->ajaxReturn($arr,"JSON");}

        if ($is_edit){
            $where_name['id'] = array('NEQ',$is_edit);
        }
        $where_name['type_name'] = $name;
        $is_name = $group_type->where($where_name)->find();
        if ($is_name){
            $arr = array('code'=> 0, 'res'=>'群分类名称已存在');$this->ajaxReturn($arr,"JSON");
        }
        if($is_edit){
            $save['type_name'] = $name;
            $save['update_time'] = time();
            $query = $group_type->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['type_name'] = $name;
            $add['create_time'] = time();
            $query = $group_type->add($add);
            $res = '新增';
        }

        if($query){
            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功'
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












