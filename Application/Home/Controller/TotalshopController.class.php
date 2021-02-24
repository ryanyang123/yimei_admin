<?php
namespace Home\Controller;
use Think\Controller;
class TotalshopController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'19'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){
        $shop = M('shop');
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
                $total_shop = 0;
                $total_sales = 0;
                $total_money = 0;
                $total_shop += $shop->where($where_month)->count();

                $total_sales += $shop->where($where_month)->sum('sales');
                $total_money += $shop->where($where_month)->sum('money');


                $where_login['lyx_level_log.create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_login['lyx_level_log.type'] = 4;
                $total_online = 0;
                $total_online += $level_log
                    ->join('lyx_shop ON lyx_level_log.user_id = lyx_shop.user_id')
                    ->where($where_login)->count();


                $user_arr[] = $total_shop;//店铺数量
                $online_arr[] = $total_online;//店铺在线人数
                $sales_arr[] = $total_sales;//总销量
                $money_arr[] = $total_money;//总销售额
            }

            $excel_list = array();
            foreach($month_arr as $key=>$item){
                $excel_list[$key]['title'] = $year.'年'.$month.'月'.$item;
                $excel_list[$key]['user'] = $user_arr[$key];
                $excel_list[$key]['online'] = $online_arr[$key];
                $excel_list[$key]['sales'] = $sales_arr[$key];
                $excel_list[$key]['money'] = $money_arr[$key];
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
                $total_shop = 0;
                $total_sales = 0;
                $total_money = 0;
                $total_shop += $shop->where($where_month)->count();

                $total_sales += $shop->where($where_month)->sum('sales');
                $total_money += $shop->where($where_month)->sum('money');


                $where_login['lyx_level_log.create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_login['lyx_level_log.type'] = 4;
                $total_online = 0;
                $total_online += $level_log
                    ->join('lyx_shop ON lyx_level_log.user_id = lyx_shop.user_id')
                    ->where($where_login)->count();


                $user_arr[] = $total_shop;//店铺数量
                $online_arr[] = $total_online;//店铺在线人数
                $sales_arr[] = $total_sales;//总销量
                $money_arr[] = $total_money;//总销售额
            }

            $excel_list = array();
            foreach($month_arr as $key=>$item){
                $excel_list[$key]['title'] = $item;
                $excel_list[$key]['user'] = $user_arr[$key];
                $excel_list[$key]['online'] = $online_arr[$key];
                $excel_list[$key]['sales'] = $sales_arr[$key];
                $excel_list[$key]['money'] = $money_arr[$key];
            }

        }else{
            $month_arr = array(
                '1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'
            );
            for($i=1;$i<=12;$i++){
                $month_start = strtotime($year.'-'.$i.'-01');
                $month_end =  strtotime("+1 month",$month_start);
                $where_month['create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $total_shop = 0;
                $total_sales = 0;
                $total_money = 0;
                $total_shop += $shop->where($where_month)->count();

                $total_sales += $shop->where($where_month)->sum('sales');
                $total_money += $shop->where($where_month)->sum('money');


                $where_login['lyx_level_log.create_time'] = array(array('EGT',$month_start),array('LT',$month_end),'AND');
                $where_login['lyx_level_log.type'] = 4;
                $total_online = 0;
                $total_online += $level_log
                    ->join('lyx_shop ON lyx_level_log.user_id = lyx_shop.user_id')
                    ->where($where_login)->count();


                $user_arr[] = $total_shop;//店铺数量
                $online_arr[] = $total_online;//店铺在线人数
                $sales_arr[] = $total_sales;//总销量
                $money_arr[] = $total_money;//总销售额
            }

            $excel_list = array();
            foreach($month_arr as $key=>$item){
                $excel_list[$key]['title'] = $year.'年'.$item;
                $excel_list[$key]['user'] = $user_arr[$key];
                $excel_list[$key]['online'] = $online_arr[$key];
                $excel_list[$key]['sales'] = $sales_arr[$key];
                $excel_list[$key]['money'] = $money_arr[$key];
            }
        }

        $zong_user = 0;
        $zong_online = 0;
        $zong_sales = 0;
        $zong_money = 0;
        foreach($excel_list as $key=>$item){
            $zong_user+= $item['user'];
            $zong_online+= $item['online'];
            $zong_sales+= $item['sales'];
            $zong_money+= $item['money'];
        }
        $zong_list['zong_user'] = $zong_user;
        $zong_list['zong_online'] = $zong_online;
        $zong_list['zong_sales'] = $zong_sales;
        $zong_list['zong_money'] = $zong_money;

        $data = array(
            'month_arr' =>json_encode($month_arr),
            'user_arr' =>json_encode($user_arr),
            'online_arr' =>json_encode($online_arr),
            'sales_arr' =>json_encode($sales_arr),
            'money_arr' =>json_encode($money_arr),
            'year_list' =>$year_list,
            'zong_list' =>$zong_list,
            'excel_list' =>$excel_list,
            'search_list' =>$search_list,
            'at' =>'totalshop',
        );
        $this->assign($data);
        $this->display();
    }




}












