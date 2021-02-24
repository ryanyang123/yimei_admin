<?php
namespace Home\Controller;
use Think\Controller;
class UpdatedataController extends Controller
{

    //清除店铺购买测试数据
    function cleartestdata(){
        $shop_order = M('shop_order');
        $shop_order_goods = M('shop_order_goods');
        $where['user_id'] = array('IN','4,5');
        $list = $shop_order->where($where)->select();
        foreach ($list as $item){
            $shop_order_goods->where(array('order_id'=>$item['id']))->delete();

        }
        $shop_order->where($where)->delete();
        dump('1');die;
    }

    function addhospitalorder(){
        $user  = M('user');
        $pay_info['order_id'] = '27';
        $pay_info['user_id'] = '6010';
        $pay_info['money'] = '29800';
        $pay_info['ticket_id'] = '';
        $pay_info['ticket_money'] = '';
        $pay_info['score'] = '';
        $pay_info['score_money'] = '';
        $order_info = M('hospital_order')->where(array('id'=>$pay_info['order_id']))->find();
        $save_order['user_id'] = $pay_info['user_id'];
        $save_order['money'] = $pay_info['money'];
        $save_order['status'] = 1;
        $save_order['update_time'] = time();
        $save_order['pay_time'] = time();
        $save_order['ticket_id'] = $pay_info['ticket_id'];
        $save_order['ticket_money'] = $pay_info['ticket_money'];
        $save_order['score'] = $pay_info['score'];
        $save_order['score_money'] = $pay_info['score_money'];


        M('hospital')->where(array('id'=>$order_info['parent']))->setInc('subscribe',1);
        M('doctor')->where(array('id'=>$order_info['doctor_id']))->setInc('subscribe',1);

        //平台抽水
        $chou_odds = M('set')->where(array('set_type'=>'hos_order_chou'))->find();
        if($chou_odds['set_value']!='0'){
            $chou_money = $order_info['total_money'] - $order_info['cost'] - $order_info['loans'];
            $odds = $chou_odds['set_value']/100;
            $chou = $chou_money * $odds;
            $save_order['chou'] = $chou;
            $save_order['odds'] = $chou_odds['set_value'];
        }
        $query = M('hospital_order')->where(array('id'=>$pay_info['order_id']))->save($save_order);
        if($query){
            $a = SendSocket($order_info['parent'],$order_info['id']);
            if($pay_info['score']>0){
                $user->where(array('id'=>$order_info['user_id']))->setDec('score',$pay_info['score']);
                //减去积分
                $add_log['user_id'] = $order_info['user_id'];
                $add_log['add_or_del'] = 2;
                $add_log['type'] = 2;
                $add_log['num'] = $pay_info['score'];
                $add_log['order_id'] = $order_info['id'];
                $add_log['create_time'] = time();
                M('shop_score_log')->add($add_log);
            }

            //代理抽水
            HosOrderAgentChou($order_info['id']);

            if  ($pay_info['ticket_id']){
                M('ticket_user')->where(array('id'=>$pay_info['ticket_id']))->save(array('status'=>'1','update_time'=>time()));
            }
        }
    }

    //更新日志的医院-换成店铺
    public function index(){
        $hospital = M('hospital');
        $shop = M('shop');
        $user_blogs = M('user_blogs');

        $where['hospital_id'] = array('NEQ','0');
        $list = $user_blogs->where($where)->field('id,hospital_id')->select();
        foreach ($list as $item){
            $is_shop = $shop->where(array('hospital_id'=>$item['hospital_id']))->field('id')->find();
            if($is_shop){
                $user_blogs->where(array('id'=>$item['id']))->save(array('shop_id'=>$is_shop['id'],'update_time'=>time()));
            }
        }
        echo "ok";
    }


    //更新因测试服务器导致的融云头像和名称错误
    function updaterong(){
        $user = M('user');

        vendor('Rong.rongcloud');
        $list = $user->field('id,name,head')->select();
        foreach ($list as $item){
            $RongCloud = new \RongCloud(C('RongCloudConf.key'),C('RongCloudConf.secret'));
            $result = $RongCloud->user()->getToken($item['id'], $item['name'],$item['head']);
            $rongres =  json_decode($result,true);
            if($result){
                $user->where(array('id'=>$item['id']))->save(array('rong_token'=>$rongres['token'],'update_time'=>time()));
            }
        }
        echo 'ok';

    }











}


























