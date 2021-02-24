<?php
namespace Home\Controller;
use Think\Controller;
class DsController extends Controller
{


    //设置星级会员二维码过期时间
    function setAgentQrTime(){
        $team_qr = M('team_qr');
        $where['type'] = 1;
        $where['is_use'] = 1;
        $where['end_time'] = array('LT',time());
        $list = $team_qr->where($where)->select();
        if($list){
            foreach ($list as $key=>$item){
                $team_qr->where(array('id'=>$item['id']))->save(array('is_use'=>0,'update_time'=>time()));
            }
        }
    }


    public function index(){
        $ip = $_SERVER["REMOTE_ADDR"];
        $shop_order = M('shop_order');
        $hospital_order = M('hospital_order');
        $set = M('set');
        $system = M('system');
        $task = M('task');

        //if($ip=='120.78.208.218'){
        $zuo = strtotime("-1 day");
        //删除医院订单缓存
        $where_order['create_time'] = array('ELT',$zuo);
        $where_order['status'] = 0;
        $hospital_order->where($where_order)->delete();




        //删除商城订单缓存
        $order_list = $shop_order->where($where_order)->field('id')->select();
        foreach ($order_list as $item){
            $system->where(array('status'=>array('IN','2,3'),'type_id'=>$item['id']))->delete();
            $shop_order->where(array('id'=>$item['id']))->delete();
        }


        //删除红包缓存订单
        $where_task['create_time'] = array('ELT',$zuo);
        $where_task['is_pay'] = 0;
        $task->where($where_task)->delete();
//        }

        //默认收货
        $sh_set = $set->where(array('set_type'=>'take_goods'))->find();
        $day_set = strtotime("-".$sh_set['set_value']." day");

        $where_sh['update_time'] = array('ELT',$day_set);
        $where_sh['status'] = 4;
        $shop_goods = M('shop_goods');
        $shop = M('shop');
        $shop_order_list = $shop_order->where($where_sh)->limit(50)->select();
        foreach ($shop_order_list as $item){
            //平台抽水
            $goods_info  = $shop_goods->where(array('id'=>$item['goods_id']))->field('odds')->find();
            $shop_info  = $shop->where(array('id'=>$item['shop_id']))->find();

            if($goods_info['odds']!=NULL){
                $chou_odds['set_value'] =  $goods_info['odds'];
            }else{
                if($shop_info['odds']!=NULL){
                    $chou_odds['set_value'] =  $shop_info['odds'];
                }else{
                    $chou_odds = M('set')->where(array('set_type'=>$shop_info['classify'].'_order_chou'))->find();
                }
            }
            if($chou_odds['set_value']!='0'){
                $odds = $chou_odds['set_value']/100;
                $chou = $item['total_money'] * $odds;
                $save_order['chou'] = $chou;
                $save_order['odds'] = $chou_odds['set_value'];
            }

            $save_order['status'] = 5;
            $save_order['update_time'] = time();

            $query = $shop_order->where(array('id'=>$item['id']))->save($save_order);


            //城市合伙人抽水
            cityRebateMoney(1,$item['id']);

            if($query){
                ShopOrderAgentChou($item['id']);
            }
        }

        //广告超时管理
        $ad = M('ad');
        //未开始的
        $where_ad_start['is_take'] = 0;
        $where_ad_start['type'] = 1;
        $where_ad_start['start_time'] =  array('ELT',time());
        $start_ad_count = $ad->where($where_ad_start)->count();
        if($start_ad_count>0){
             $ad->where($where_ad_start)->save(array('is_take'=>1,'update_time'=>time()));
        }
        //快结束的
        $where_ad_end['is_take'] = 1;
        $where_ad_end['type'] = 1;
        $where_ad_end['end_time'] =  array('ELT',time());
        $end_ad_count = $ad->where($where_ad_end)->count();
        if($end_ad_count>0){
            $ad->where($where_ad_end)->save(array('is_take'=>0,'update_time'=>time()));
        }
        $game = M('game');
        //游戏超时的
        $game_set = $set->where(array('set_type'=>'game_over_time'))->find();
        $day_game = strtotime("-".$game_set['set_value']." day");
        $where_game['create_time'] = array('ELT',$day_game);
        $where_game['status'] = 0;
        $game_list = M('game')->where($where_game)->limit(50)->select();
        if($game_list){
            foreach ($game_list as $item){
                if($item['status']==0){
                    $user = M('user');
                    $query = $user->where(array('id'=>$item['user_id']))->setInc('score',$item['score']);
                    if($query){
                        $game->where(array('id'=>$item['id']))->save(array('status'=>'2','update_time'=>time()));
                        //积分明细
                        $add_log['user_id'] = $item['user_id'];
                        $add_log['add_or_del'] = 1;
                        $add_log['num'] = $item['score'];
                        $add_log['type'] = 13;//10游戏扣除，11游戏获得，12游戏通关奖励
                        $add_log['order_id'] = $item['id'];
                        $add_log['create_time'] = time();
                        M('shop_score_log')->add($add_log);
                        if($item['friend_user_id']&& $item['friend_score']>0){
                            $query2 = $user->where(array('id'=>$item['friend_user_id']))->setInc('score',$item['friend_score']);
                            if($query2){
                                //积分明细
                                $add_log['user_id'] = $item['friend_user_id'];
                                $add_log['add_or_del'] = 1;
                                $add_log['num'] = $item['friend_score'];
                                $add_log['type'] = 13;//10游戏扣除，11游戏获得，12游戏通关奖励
                                $add_log['order_id'] = $item['id'];
                                $add_log['create_time'] = time();
                                M('shop_score_log')->add($add_log);
                            }
                        }
                    }
                }
            }
        }
        echo "ok";
    }
    //结算订单退款
    function getmoneytui(){
        $aliget = A('aliget');
        //$shop_order = M('shop_order');
        //$hospital_order = M('hospital_order');
        //$team_money = M('team_money');
        $set = M('set');
        $tui = M('tui');
        //$ticket_user = M('ticket_user');
        //$task = M('task');

        $set_list  = $set->where(array('type'=>'2'))->select();
        foreach ($set_list as $item){
            $new_set[$item['set_type']] = $item['set_value'];
        }

        $set_info = M('set')->where(array('set_type'=>'tix_status'))->find();
        $get_status = $set_info['set_value'];



        //商城商品退款
        $day_shop_tui = strtotime("-".$new_set['shop_tui_time']." day");
        $where_shop_tui['create_time'] = array('ELT',$day_shop_tui);
        $where_shop_tui['status'] = 0;
        $where_shop_tui['type'] = 1;
        $shop_tui_list = $tui->where($where_shop_tui)->limit(50)->select();
        foreach ($shop_tui_list as $item){
            $tui->where(array('id'=>$item['id']))->save(array('is_da'=>'1','update_time'=>time()));
            if($get_status=='1'){
                $data['money'] = $item['money'];
                $data['parent'] = $item['user_id'];
                $data['type'] = 3;
                $data['order_number'] = '';
                $data['tui_type'] = $item['type'];
                $data['tui_type_id'] = $item['type_id'];
                $data['type_id'] = $item['id'];
                $aliget->index($data);
            }
        }


        //医院订单退款至用户
        $day_shop_tui = strtotime("-".$new_set['shop_tui_time']." day");
        $where_shop_tui['create_time'] = array('ELT',$day_shop_tui);
        $where_shop_tui['status'] = 0;
        $where_shop_tui['type'] = 4;
        $shop_tui_list = $tui->where($where_shop_tui)->limit(50)->select();
        foreach ($shop_tui_list as $item){
            $tui->where(array('id'=>$item['id']))->save(array('is_da'=>'1','update_time'=>time()));
            if($get_status=='1'){
                $data['money'] = $item['money'];
                $data['parent'] = $item['user_id'];
                $data['type'] = 8;
                $data['order_number'] = '';
                $data['tui_type'] = $item['type'];
                $data['tui_type_id'] = $item['type_id'];
                $data['type_id'] = $item['id'];
                $aliget->index($data);
            }
        }


        //优惠券退款
        $day_tic_tui = strtotime("-".$new_set['ticket_tui_time']." day");
        $where_tic_tui['create_time'] = array('ELT',$day_tic_tui);
        $where_tic_tui['status'] = 0;
        $where_tic_tui['type'] = 2;
        $tic_tui_list = $tui->where($where_tic_tui)->limit(50)->select();
        foreach ($tic_tui_list as $item){
            $tui->where(array('id'=>$item['id']))->save(array('is_da'=>'1','update_time'=>time()));
            if($get_status=='1'){
                $data['money'] = $item['money'];
                $data['parent'] = $item['user_id'];
                $data['type'] = 4;
                $data['order_number'] = '';
                $data['tui_type'] = $item['type'];
                $data['tui_type_id'] = $item['type_id'];
                $data['type_id'] = $item['id'];
                $aliget->index($data);
            }
        }



        //返利给城市合伙人
        $day_city_tui = strtotime("-".$new_set['city_rebate_time']." day");
        $where_city_tui['create_time'] = array('ELT',$day_city_tui);
        $where_city_tui['status'] = 0;
        $city_tui_list = $tui->where($where_city_tui)->limit(50)->select();
        foreach ($city_tui_list as $item){
            $tui->where(array('id'=>$item['id']))->save(array('is_da'=>'1','update_time'=>time()));
            if($get_status=='1'){
                $data['money'] = $item['money'];
                $data['parent'] = $item['user_id'];
                $data['type'] = 13;
                $data['order_number'] = '';
                $data['tui_type'] = $item['type'];
                $data['tui_type_id'] = $item['type_id'];
                $data['type_id'] = $item['id'];
                $aliget->index($data);
            }
        }





        //开通团队返利给上级
        //$where_tui_agent = strtotime("-".$new_set['ticket_tui_time']." day");
        //$where_tui_agent['create_time'] = array('ELT',$day_tic_tui);
        $where_tui_agent['status'] = 0;
        //$where_tui_agent['type'] = 2;
        $tui_agent_list = M('tui_agent')->where($where_tui_agent)->limit(50)->select();
        foreach ($tui_agent_list as $item){
            M('tui_agent')->where(array('id'=>$item['id']))->save(array('is_da'=>'1','update_time'=>time()));
            if($get_status=='1'){
                $data['money'] = $item['money'];
                $data['parent'] = $item['user_id'];
                $data['type'] = 12;
                $data['order_number'] = '';
                $data['type_id'] = $item['id'];
                $aliget->index($data);
            }
        }
        echo 'ok';
    }

    //结算订单返利
    function getmoneyrebate(){
        $aliget = A('aliget');
        //$shop_order = M('shop_order');
        //$hospital_order = M('hospital_order');
        $team_money = M('team_money');
        $set = M('set');
        $tui = M('tui');
        $ticket_user = M('ticket_user');
        //$task = M('task');

        $set_list  = $set->where(array('type'=>'2'))->select();
        foreach ($set_list as $item){
            $new_set[$item['set_type']] = $item['set_value'];
        }

        $set_info = M('set')->where(array('set_type'=>'tix_status'))->find();
        $get_status = $set_info['set_value'];


        //打钱给代理
        $day_team = strtotime("-".$new_set['rebate_time']." day");
        $where_team['create_time'] = array('ELT',$day_team);
        $where_team['is_get'] = 0;
        //$where_team['is_da'] = 0;
        $team_list = $team_money->where($where_team)->limit(50)->select();
        foreach ($team_list as $item){
            $team_money->where(array('id'=>$item['id']))->save(array('is_da'=>'1','update_time'=>time()));
            if($get_status=='1'){
                $data['money'] = $item['money'];
                $data['parent'] = $item['user_id'];
                $data['type'] = 11;
                $data['order_number'] = '';
                $data['type_id'] = $item['id'];

                $a=  $aliget->index($data);

            }
        }

        //优惠券返利
        $day_tic_rebate = strtotime("-".$new_set['tic_rebate_time']." day");
        $where_tic_rebate['update_time'] = array('ELT',$day_tic_rebate);
        $where_tic_rebate['status'] = 1;
        $where_tic_rebate['is_rebate'] = 0;
        $tic_rebate_list = $ticket_user->where($where_tic_rebate)->limit(50)->select();

        foreach ($tic_rebate_list as $item){
            if ($item['rebate_money']<=0){
                $ticket_user->where(array('id'=>$item['id']))->save(array('is_rebate'=>'1','rebate_time'=>time()));
            }else{
                $ticket_user->where(array('id'=>$item['id']))->save(array('is_da'=>'1','update_time'=>time()));
                if($get_status=='1'){
                    $data['money'] = $item['rebate_money'];
                    $data['parent'] = $item['user_id'];
                    $data['type'] = 5;
                    $data['order_number'] = '';
                    $data['type_id'] = $item['id'];
                    $aliget->index($data);
                }
            }
        }


        //抵消券返利给医院
        $day_dixiao_time = strtotime("-".$new_set['dixiao_time']." day");
        $where_shop_tui['create_time'] = array('ELT',$day_dixiao_time);
        $where_shop_tui['status'] = 0;
        $where_shop_tui['type'] = 3;
        $shop_tui_list = $tui->where($where_shop_tui)->limit(50)->select();

        foreach ($shop_tui_list as $item){
            $tui->where(array('id'=>$item['id']))->save(array('is_da'=>'1','update_time'=>time()));
            if($get_status=='1'){
                $data['money'] = $item['money'];
                $data['parent'] = $item['type_id'];
                $data['type'] = 7;
                $data['order_number'] = '';
                $data['tui_type'] = $item['type'];
                $data['tui_type_id'] = $item['type_id'];
                $data['type_id'] = $item['id'];
                $aliget->index($data);
            }
        }

        echo 'ok';
    }




    //结算订单金额至商家或医院-领取红包
    function getmoney(){
        $aliget = A('aliget');
        $shop_order = M('shop_order');
        $shop = M('shop');
        $hospital_order = M('hospital_order');
        //$team_money = M('team_money');
        $set = M('set');
        //$tui = M('tui');
        //$ticket_user = M('ticket_user');
        $task = M('task');

        $set_list  = $set->where(array('type'=>'2'))->select();
        foreach ($set_list as $item){
            $new_set[$item['set_type']] = $item['set_value'];
        }


        $set_info = M('set')->where(array('set_type'=>'tix_status'))->find();
        $get_status = $set_info['set_value'];

        $day_shop = strtotime("-".$new_set['cut_off_time']." day");
        //打钱给医院
        //$where_hos['ok_time'] = array('ELT',$day_shop);
        $where_hos['is_get'] = 0;
        $where_hos['status'] = 4;
        $where_hos['type'] = 1;


        //$where_hos['odds'] = array('NEQ',0);
        //$where_hos['chou'] = array('NEQ',0);
        $hos_list = $hospital_order->order('ok_time')->where($where_hos)->limit(50)->select();
        foreach ($hos_list as $item){
            $shop_info = $shop->where(array('id'=>$item['shop_id']))->field('id,classify')->find();
            if($shop_info['classify']){
                $day_shop_hos = strtotime("-".$new_set['cut_off_time_'.$shop_info['classify']]." day");
            }else{
                $day_shop_hos = strtotime("-".$new_set['cut_off_time_11']." day");
            }
            if($item['ok_time']<=$day_shop_hos){
                $hospital_order->where(array('id'=>$item['id']))->save(array('is_da'=>'1','update_time'=>time()));
                if($get_status=='1'){
                    $data['money'] = $item['total_money'] - $item['loans'] - $item['chou'];
                    $data['parent'] = $item['shop_id'];
                    $data['type'] = 1;
                    $data['order_number'] = $item['order_number'];
                    $data['type_id'] = $item['id'];
                    $aliget->index($data);

                }
            }
        }
        //退款订单-打钱给医院
        //$where_hos['ok_time'] = array('ELT',$day_shop);
        $where_hos['is_get'] = 0;
        $where_hos['status'] = 3;
        $where_hos['refund_status'] = 5;
        $where_hos['type'] = 1;
        //$where_hos['odds'] = array('NEQ',0);
        //$where_hos['chou'] = array('NEQ',0);
        $hos_list = $hospital_order->order('ok_time')->where($where_hos)->limit(50)->select();
        foreach ($hos_list as $item){
            $shop_info = $shop->where(array('id'=>$item['shop_id']))->field('id,classify')->find();
            if($shop_info['classify']){
                $day_shop_hos = strtotime("-".$new_set['cut_off_time_'.$shop_info['classify']]." day");
            }else{
                $day_shop_hos = strtotime("-".$new_set['cut_off_time_11']." day");
            }
            if($item['ok_time']<=$day_shop_hos){
                $hospital_order->where(array('id'=>$item['id']))->save(array('is_da'=>'1','update_time'=>time()));
                if($get_status=='1'){
                    $data['money'] = $item['money']-$item['refund_money']-$item['chou'];
                    $data['parent'] = $item['shop_id'];
                    $data['type'] = 1;
                    $data['order_number'] = $item['order_number'];
                    $data['type_id'] = $item['id'];
                    $aliget->index($data);
                }
            }
        }
        //打钱给商家
        $where_shop['lyx_shop_order.is_get'] = 0;
        $where_shop['lyx_shop_order.status'] = 5;
        //$where_shop['lyx_shop_order.odds'] = array('NEQ',0);
        //$where_shop['lyx_shop_order.chou'] = array('NEQ',0);
        $shop_list = array();
        $day_shop_11 = strtotime("-".$new_set['cut_off_time_11']." day");
        $where_shop['lyx_shop.classify'] = 11;
        $where_shop['lyx_shop_order.create_time'] = array('ELT',$day_shop_11);
        $shop_list_11 = $shop_order->join('lyx_shop ON lyx_shop_order.shop_id = lyx_shop.id')->field('lyx_shop_order.*')->where($where_shop)->limit(20)->select();
        if($shop_list_11){
            foreach ($shop_list_11 as $item){
                $shop_list[] = $item;
            }
        }

        $day_shop_12 = strtotime("-".$new_set['cut_off_time_12']." day");
        $where_shop['lyx_shop.classify'] = 12;
        $where_shop['lyx_shop_order.create_time'] = array('ELT',$day_shop_12);
        $shop_list_12 = $shop_order->join('lyx_shop ON lyx_shop_order.shop_id = lyx_shop.id')->field('lyx_shop_order.*')->where($where_shop)->limit(20)->select();
        if($shop_list_12){
            foreach ($shop_list_12 as $item){
                $shop_list[] = $item;
            }
        }


        $day_shop_13 = strtotime("-".$new_set['cut_off_time_13']." day");
        $where_shop['lyx_shop.classify'] = 13;
        $where_shop['lyx_shop_order.create_time'] = array('ELT',$day_shop_13);
        $shop_list_13 = $shop_order->join('lyx_shop ON lyx_shop_order.shop_id = lyx_shop.id')->field('lyx_shop_order.*')->where($where_shop)->limit(20)->select();
        if($shop_list_13){
            foreach ($shop_list_13 as $item){
                $shop_list[] = $item;
            }
        }

        if($shop_list){
            foreach ($shop_list as $item){
                $shop_order->where(array('id'=>$item['id']))->save(array('is_da'=>'1','update_time'=>time()));
                if($get_status=='1'){

                    $data['money'] = bcsub($item['total_money'],$item['chou']);
                    $data['parent'] = $item['shop_id'];
                    $data['type'] = 2;
                    $data['order_number'] = $item['order_number'];
                    $data['type_id'] = $item['id'];
                    if($data['money']==0){
                        $shop_order->where(array('id'=>$item['id']))->save(array('is_get'=>'1','get_time'=>time()));
                    }else{
                        $aliget->index($data);
                    }
                }
            }
        }



        //领取现金红包
        $where_task['status'] = 2;
        $where_task['is_get'] = 0;
        $where_task['is_pay'] = 1;
        $where_task['prize'] = 3;
        $task_list = $task->where($where_task)->limit(50)->select();
        foreach ($task_list as $item){
            $task->where(array('id'=>$item['id']))->save(array('is_da'=>'1','update_time'=>time()));
            if($get_status=='1'){
                $data['money'] = $item['prize_num'];
                $data['parent'] = $item['user_id'];
                $data['type'] = 6;
                $data['order_number'] = '';
                $data['type_id'] = $item['id'];
                $aliget->index($data);
            }
        }
        echo "ok";
    }

    //更新用户总返利金额
/*    function updateusermoney(){
        $team_money = M('team_money');
        $user = M('user');
        $list  = $team_money->select();
        foreach ($list as $item){
            $user->where(array('id'=>$item['user_id']))->setInc('rebate_money',$item['money']);
        }
        echo "Success";
    }*/

    //各种测试
    function test(){
        //代理抽水
       /* $a1 = ShopOrderAgentChou('159');
        dump($a1);die;*/
    /*    $a = HosOrderAgentChou('56');
        dump($a);die;*/
    }











}


























