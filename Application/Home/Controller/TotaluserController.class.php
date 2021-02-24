<?php
namespace Home\Controller;
use Think\Controller;
class TotaluserController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'totaluser'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){
        $user = M('user');
        $hospital_order = M('hospital_order');
        $shop_order = M('shop_order');
        $ticket_user = M('ticket_user');
        $team_money = M('team_money');
        $level_log = M('level_log');

        $ytype = I('get.ytype');
        if (!$ytype){
            $ytype = '1';
        }
        $search_list['ytype'] = $ytype;
        for($i=2018;$i<=date('Y');$i++){
            $year_list[] = $i;
        }

        $year = I('get.year');
        if(!$year){$year=date('Y');}
        $search_list['year'] = $year;


        $month = I('get.month');
        if(!$month){$month=date('m');}
        $search_list['month'] = $month;

        $year_start = strtotime($year.'-01-01');
        $year_end =  date("Y-m-d",strtotime("+1 year",$year_start));;


        if($ytype=='2'){
            $month_start1 = strtotime($year.'-'.$month.'-01');
            $month_end1 = strtotime("+1 month",$month_start1)-1;

            $month_arr = array();
            for($i=1;$i<=date('d',$month_end1);$i++){
                $month_arr[] = $i.'号';

                $month_start = strtotime($year.'-'.$month.'-'.$i);
                $month_end =  strtotime("+1 day",$month_start);


                $where_month['create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_month['status'] = array('IN','1,2');
                $where_month['type'] = 1;
                $total_hos = 0;
                $total_hos += $hospital_order->where($where_month)->sum('chou');



                $where_login['create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_login['status'] = 5;
                $total_shop = 0;
                $total_shop += $shop_order->where($where_login)->sum('chou');



                $where_tic['lyx_ticket_user.create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_tic['lyx_ticket.type'] = array('IN','1,3');
                $where_tic['lyx_ticket_user.status'] = array('NEQ','3');
                $total_tic = 0;
                $total_tic += $ticket_user->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')->where($where_tic)->sum('buy_money');





                $where_tic2['lyx_ticket_user.create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_tic2['lyx_ticket.type'] = array('IN','1,3');
                $where_tic2['lyx_ticket_user.status'] = array('NEQ','3');
                $where_tic2['lyx_ticket_user.is_rebate'] = 0;

                $total_rebate = 0;
                $total_rebate += $ticket_user->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')->where($where_tic2)->sum('rebate_money');


                $where_fanli['create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $total_fanli = 0;
                $total_fanli += $team_money->where($where_fanli)->sum('money');

                $hos_arr[] = $total_hos;//医院抽水
                $shop_arr[] = $total_shop;//商城抽水
                $tic_arr[] = $total_tic;//优惠券购买

                $income_arr[] = $total_tic+$total_shop+$total_hos;//总收入

                $fanli_arr[] = $total_fanli;//代理返利
                $rebate_arr[] = $total_rebate;//优惠券返利


                $expend_arr[] = $total_fanli+$total_rebate;//总支出


                $profit_arr[] = ($total_tic+$total_shop+$total_hos)-($total_fanli+$total_rebate);//总利润
            }

            $excel_list = array();
            foreach($month_arr as $key=>$item){
                $excel_list[$key]['title'] = $year.'年'.$item;
                $excel_list[$key]['hos'] = $hos_arr[$key];
                $excel_list[$key]['shop'] = $shop_arr[$key];
                $excel_list[$key]['tic'] = $tic_arr[$key];
                $excel_list[$key]['fanli'] = $fanli_arr[$key];
                $excel_list[$key]['rebate'] = $rebate_arr[$key];
                $excel_list[$key]['income'] = $income_arr[$key];
                $excel_list[$key]['expend'] = $expend_arr[$key];
                $excel_list[$key]['profit'] = $profit_arr[$key];
            }

        }else if($ytype=='3'){
            $search_date  = I('get.search_date');
            if(!$search_date){$search_date = date('Y-m-d');}
            $search_list['search_date'] = $search_date;

            $newtime = strtotime("-7 day",strtotime($search_date));

            $month_arr = array();
            for($i=1;$i<=7;$i++){
                $month_arr[] = date('Y-m-d',strtotime("+".$i." day",$newtime));

                $month_start = strtotime("+".$i." day",$newtime);
                $month_end =  strtotime("+1 day",$month_start);



                $where_month['create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_month['status'] = array('IN','1,2');
                $where_month['type'] = 1;
                $total_hos = 0;
                $total_hos += $hospital_order->where($where_month)->sum('chou');



                $where_login['create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_login['status'] = 5;
                $total_shop = 0;
                $total_shop += $shop_order->where($where_login)->sum('chou');



                $where_tic['lyx_ticket_user.create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_tic['lyx_ticket.type'] = array('IN','1,3');
                $where_tic['lyx_ticket_user.status'] = array('NEQ','3');
                $total_tic = 0;
                $total_tic += $ticket_user->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')->where($where_tic)->sum('buy_money');





                $where_tic2['lyx_ticket_user.create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_tic2['lyx_ticket.type'] = array('IN','1,3');
                $where_tic2['lyx_ticket_user.status'] = array('NEQ','3');
                $where_tic2['lyx_ticket_user.is_rebate'] = 0;

                $total_rebate = 0;
                $total_rebate += $ticket_user->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')->where($where_tic2)->sum('rebate_money');


                $where_fanli['create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $total_fanli = 0;
                $total_fanli += $team_money->where($where_fanli)->sum('money');

                $hos_arr[] = $total_hos;//医院抽水
                $shop_arr[] = $total_shop;//商城抽水
                $tic_arr[] = $total_tic;//优惠券购买

                $income_arr[] = $total_tic+$total_shop+$total_hos;//总收入

                $fanli_arr[] = $total_fanli;//代理返利
                $rebate_arr[] = $total_rebate;//优惠券返利


                $expend_arr[] = $total_fanli+$total_rebate;//总支出


                $profit_arr[] = ($total_tic+$total_shop+$total_hos)-($total_fanli+$total_rebate);//总利润
            }

            $excel_list = array();
            foreach($month_arr as $key=>$item){
                $excel_list[$key]['title'] = $year.'年'.$item;
                $excel_list[$key]['hos'] = $hos_arr[$key];
                $excel_list[$key]['shop'] = $shop_arr[$key];
                $excel_list[$key]['tic'] = $tic_arr[$key];
                $excel_list[$key]['fanli'] = $fanli_arr[$key];
                $excel_list[$key]['rebate'] = $rebate_arr[$key];
                $excel_list[$key]['income'] = $income_arr[$key];
                $excel_list[$key]['expend'] = $expend_arr[$key];
                $excel_list[$key]['profit'] = $profit_arr[$key];
            }

        }else{
            $month_arr = array(
                '1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'
            );
            for($i=1;$i<=12;$i++){
                $month_start = strtotime($year.'-'.$i.'-01');
                $month_end =  strtotime("+1 month",$month_start);

                $where_month['create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_month['status'] = array('IN','1,2');
                $where_month['type'] = 1;
                $total_hos = 0;
                $total_hos += $hospital_order->where($where_month)->sum('chou');



                $where_login['create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_login['status'] = 5;
                $total_shop = 0;
                $total_shop += $shop_order->where($where_login)->sum('chou');



                $where_tic['lyx_ticket_user.create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_tic['lyx_ticket.type'] = array('IN','1,3');
                $where_tic['lyx_ticket_user.status'] = array('NEQ','3');
                $total_tic = 0;
                $total_tic += $ticket_user->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')->where($where_tic)->sum('buy_money');





                $where_tic2['lyx_ticket_user.create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_tic2['lyx_ticket.type'] = array('IN','1,3');
                $where_tic2['lyx_ticket_user.status'] = array('NEQ','3');
                $where_tic2['lyx_ticket_user.is_rebate'] = 0;

                $total_rebate = 0;
                $total_rebate += $ticket_user->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')->where($where_tic2)->sum('rebate_money');


                $where_fanli['create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $total_fanli = 0;
                $total_fanli += $team_money->where($where_fanli)->sum('money');

                $hos_arr[] = $total_hos;//医院抽水
                $shop_arr[] = $total_shop;//商城抽水
                $tic_arr[] = $total_tic;//优惠券购买

                $income_arr[] = $total_tic+$total_shop+$total_hos;//总收入

                $fanli_arr[] = $total_fanli;//代理返利
                $rebate_arr[] = $total_rebate;//优惠券返利


                $expend_arr[] = $total_fanli+$total_rebate;//总支出


                $profit_arr[] = ($total_tic+$total_shop+$total_hos)-($total_fanli+$total_rebate);//总利润
            }

            $excel_list = array();
            foreach($month_arr as $key=>$item){
                $excel_list[$key]['title'] = $year.'年'.$item;
                $excel_list[$key]['hos'] = $hos_arr[$key];
                $excel_list[$key]['shop'] = $shop_arr[$key];
                $excel_list[$key]['tic'] = $tic_arr[$key];
                $excel_list[$key]['fanli'] = $fanli_arr[$key];
                $excel_list[$key]['rebate'] = $rebate_arr[$key];
                $excel_list[$key]['income'] = $income_arr[$key];
                $excel_list[$key]['expend'] = $expend_arr[$key];
                $excel_list[$key]['profit'] = $profit_arr[$key];
            }
        }

        $zong_hos = 0;
        $zong_shop = 0;
        $zong_tic = 0;
        $zong_fanli = 0;
        $zong_rebate = 0;
        $zong_income = 0;
        $zong_expend = 0;
        $zong_profit = 0;
        foreach($excel_list as $key=>$item){
            $zong_hos+= $item['hos'];
            $zong_shop+= $item['shop'];
            $zong_tic+= $item['tic'];
            $zong_fanli+= $item['fanli'];
            $zong_rebate+= $item['rebate'];
            $zong_income+= $item['income'];
            $zong_expend+= $item['expend'];
            $zong_profit+= $item['profit'];
        }
        $zong_list['zong_hos'] = $zong_hos;
        $zong_list['zong_shop'] = $zong_shop;
        $zong_list['zong_tic'] = $zong_tic;
        $zong_list['zong_fanli'] = $zong_fanli;
        $zong_list['zong_rebate'] = $zong_rebate;
        $zong_list['zong_income'] = $zong_income;
        $zong_list['zong_expend'] = $zong_expend;
        $zong_list['zong_profit'] = $zong_profit;


        $show_list['month_arr'] = json_encode($month_arr);
        $show_list['hos_arr'] = json_encode($hos_arr);
        $show_list['shop_arr'] = json_encode($shop_arr);
        $show_list['tic_arr'] = json_encode($tic_arr);
        $show_list['fanli_arr'] = json_encode($fanli_arr);
        $show_list['rebate_arr'] = json_encode($rebate_arr);
        $show_list['income_arr'] = json_encode($income_arr);
        $show_list['expend_arr'] = json_encode($expend_arr);
        $show_list['profit_arr'] = json_encode($profit_arr);

        $data = array(
            'show_list' =>$show_list,
            'year_list' =>$year_list,
            'zong_list' =>$zong_list,
            'excel_list' =>$excel_list,
            'search_list' =>$search_list,
            'at' =>'totaluser',
        );
        $this->assign($data);
        $this->display();
    }




}












