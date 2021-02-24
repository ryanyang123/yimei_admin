<?php
namespace Home\Controller;
use Think\Controller;
class PaylistController extends CommonController {
    public function index(){


        $pay = M('pay');
        $user = M('user');


        $search = I('get.search');
        if($search){
            $where['out_trade_no|trade_no|order_number'] =  array("like","%".$search."%");
        }


        $user_id = I('get.user_id');
        if($user_id){
            $where['user_id'] = $user_id;
        }

        $out_trade_no = I('get.out_trade_no');
        if($out_trade_no){
            $where['out_trade_no'] = $out_trade_no;
        }

        $type_id = I('get.type_id');
        if($type_id){
            $where['type'] = $type_id;
        }

        //时间搜索
        $search_date  = I('get.search_date');
        $search_date2  = I('get.search_date2');
        //$data['search_date'] = $search_date;
        //$data['search_date2'] = $search_date2;
        $newtime = strtotime($search_date);
        $newtime1 = strtotime($search_date2)+86399;
        if($search_date && $search_date2){
            $where['update_time'] = array(array('EGT',$newtime),array('ELT',$newtime1),'AND');
        }else if($search_date && !$search_date2){
            $where['update_time'] = array('EGT',$newtime);
        }else if(!$search_date && $search_date2){
            $where['update_time'] = array('ELT',$newtime1);
        }
        //时间搜索

        //$where['lyx_pay.pay_type'] = 2;
        $where['trade_status'] = array('IN','TRADE_SUCCESS,SUCCESS');
        $count = $pay->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  支付记录</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $pay
            ->where($where)->order('update_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        foreach($list as $key=>$item){
            $user_name = $user->where(array('id'=>$item['user_id']))->field('name,phone')->find();
            $list[$key]['user_name'] = $user_name['name'];
        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'search' =>$search,
            'user_id' =>$user_id,
            'type_id' =>$type_id,
            'at' =>'paylist',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $shop_classify = M('shop_classify');

        if($is_edit){
            $list = $shop_classify->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'paylist',
            'title'=>'分类',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($name,$sort,$is_edit){
        $shop_classify = M('shop_classify');

        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入分类名称');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            $save['name'] = $name;
            $save['sort'] = $sort;
            $save['update_time'] = time();
            $query = $shop_classify->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['name'] = $name;
            $add['sort'] = $sort;
            $add['create_time'] = time();
            $query = $shop_classify->add($add);
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












