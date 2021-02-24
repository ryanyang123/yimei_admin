<?php
namespace Home\Controller;
use Think\Controller;
class HomeController extends CommonController {


    public function index(){

        $log = M('log');

        if (session(XUANDB.'user_type')!='1'){
            $where_log['admin_id'] = session(XUANDB.'user_id');
        }

        $count = $log->where($where_log)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $module_name = '/' . MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;//获取路径
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% <input  class="tz_input" type="text" id="tz_input"/> <a class="tz_a_all" id="tz_a_num" href="' . $module_name . '/p/">跳转</a>%HEADER%');

        $show       = $Page->show();// 分页显示输出
        $list = $log->where($where_log)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $hour_1 = strtotime('-1 hour');





        $now = date('Y-m-d');
        $start_time = date('Y-m-01', strtotime("$now -1 month"));//上一个月第一天
        $end_time = date('Y-m-d', strtotime("$start_time +1 month -1 day"));//上一个月最后一天

        $total_user = 0;
        $total_user += M('user')->where(array('is_robot'=>'0'))->count();
        $zong_list['total_user'] = $total_user;

        $total_user = 0;
        $total_user += M('hospital')->count();
        $zong_list['total_hos'] = $total_user;

        $total_user = 0;
        $total_user += M('ticket')->count();
        $zong_list['total_ticket'] = $total_user;

        $total_blogs = 0;
        $total_blogs += M('user_blogs')->where(array('id' => array('EGT', '0')))->count();
        $zong_list['total_blogs'] = $total_blogs;

        /*$total_shop = 0;
        $total_shop += M('shop')->where(array('create_time'=>array('EGT','0')))->count();
        $zong_list['total_shop'] = $total_shop;*/


       /* $total_money = 0;
        $total_money += M('order')->where(array('pay_status'=>'1','status'=>array('NEQ','3')))->sum('money');
        $zong_list['total_money'] = $total_money;*/


        $data = array(
            'zong_list' =>$zong_list,
            'list' =>$list,
            'at' =>'home',
            'page' =>$show,
            'count' =>count($list),
        );

        $this->assign($data);
        $this->display();

    }





}