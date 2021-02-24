<?php
namespace Home\Controller;
use Think\Controller;
class ShopprizeorderController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'shopprizeorder'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){


        $user = M('user');
        $shop_prize_order = M('shop_prize_order');
        $shop_prize = M('shop_prize');

        $shop_prize_select = M('shop_prize_select');


        $user_id = I('get.user_id');
        if($user_id){
            $where['user_id'] = $user_id;
            $search_list['user_id'] = $user_id;
        }

        $order_number = I('get.order_number');
        if($order_number){
            $where['order_number'] = $order_number;
            $search_list['order_number'] = $order_number;
        }

        $status = I('get.status');
        if($status){
            $where['status'] = $status;
            $search_list['status'] = $status;
        }else{
            $where['status'] = array('NEQ','0');

        }

        $search = I('get.search');
        if($search){
            $where['order_number|addressee|mobile'] =  array("like","%".$search."%");
            $search_list['search'] = $search;
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
        //时间搜索
        $search_list['search_date'] = $search_date;
        $search_list['search_date2'] = $search_date2;

        $count = $shop_prize_order->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个订单</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $shop_prize_order->where($where)->order('status,fh_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

     /*   $courier_list = $courier->field('id,name')->select();
        foreach($courier_list as $item){
            $new_courier[$item['id']] = $item['name'];
        }*/
   /*     $expresses_list = $shop_expresses->select();
        foreach($expresses_list as $item){
            $new_expresses[$item['ex_id']] = $item['ex_name'];
        }*/

        foreach($list as $key=>$item){
            $user_name = $user->where(array('id'=>$item['user_id']))->field('name,phone')->find();
            $list[$key]['user_name'] = $user_name['name'];

          /*  if($item['ex_id']){
                $list[$key]['ex_name'] = $new_expresses[$item['ex_id']];
            }*/

            $prize_info = $shop_prize->where(array('id'=>$item['prize_id']))->field('name,cover')->find();


            $prize_info['select_val'] = $item['select_val'];

            $list[$key]['prize_info'] = $prize_info;

        }


        $data = array(
            'list' =>$list,
            'page' =>$show,
            'search_list' =>$search_list,
            'at' =>'shopprizeorder',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }

    function shouhuo($order_id){
        $shop_prize_order = M('shop_prize_order');
        $order_info = $shop_prize_order->where(array('id'=>$order_id))->field('id,status,user_id')->find();
        if($order_info['status']!='2'){
            $arr = array(
                'code'=> 0,
                'res'=>'订单状态错误'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        $save['status'] = 3;
        $save['ok_time'] = time();
        $query = $shop_prize_order->where(array('id'=>$order_id))->save($save);
        $res = '收货';
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

    function fahuo($order_id,$fh_type,$fh_courier,$fh_ex,$fh_exnumber){
        $shop_prize_order = M('shop_prize_order');
        $user = M('user');
        //$courier_user = M('courier_user');
        $order_info = $shop_prize_order->where(array('id'=>$order_id))->field('id,status,user_id')->find();

        if($order_info['status']!='1'){
            $arr = array(
                'code'=> 0,
                'res'=>'订单状态错误'
            );
            $this->ajaxReturn($arr,"JSON");
        }
        $save['status'] = 2;
        $save['fh_time'] = time();
        if($fh_type==1){
            $user_info = $user->where(array('id'=>$order_info['user_id']))->field('phone')->find();
          /*  $is_courier = $courier_user->where(array('phone'=>$user_info['phone']))->field('parent')->find();
            if(!$is_courier){
                $arr = array(
                    'code'=> 0,
                    'res'=>'该用户不属于原有配送用户，没有默认配送员，请指定配送员或填写快递单号'
                );
                $this->ajaxReturn($arr,"JSON");
            }*/

            //$save['courier_id'] = $is_courier['parent'];
        }

        if($fh_type==2){
            if(!$fh_courier){
                $arr = array(
                    'code'=> 0,
                    'res'=>'请选择配送员'
                );
                $this->ajaxReturn($arr,"JSON");
            }
            $save['courier_id'] = $fh_courier;
        }
        if($fh_type==3){
            if(!$fh_ex){
                $arr = array(
                    'code'=> 0,
                    'res'=>'请选择快递公司'
                );
                $this->ajaxReturn($arr,"JSON");
            }
            if(!$fh_exnumber){
                $arr = array(
                    'code'=> 0,
                    'res'=>'请输入快递单号'
                );
                $this->ajaxReturn($arr,"JSON");
            }
            $save['ex_id'] = $fh_ex;
            $save['ex_number'] = $fh_exnumber;
        }

        $query = $shop_prize_order->where(array('id'=>$order_id))->save($save);
        $res = '发货';
        if($query){
            $add_system['type'] = 1;
            $tis = '礼品已发货';
            //添加消息通知
            //新版
            $add_system['user_id'] = $order_info['user_id'];
            $add_system['send_user_id'] = 0;
            $add_system['content'] = $tis;
            $add_system['type_id'] = $order_info['id'];
            $add_system['status'] = 4;
            $add_system['create_time'] = time();
            M('system')->add($add_system);
            $rong_user[] = $order_info['user_id'];
            SendRongSystem($rong_user,$tis);


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












