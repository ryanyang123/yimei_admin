<?php
namespace Home\Controller;
use Think\Controller;

header ( 'Content-type:text/html;charset=utf-8' );
vendor('Union.acp_service');
class UnionpayController extends Controller {

    public function create(){

        //vendor('Union.SDKConfig');
        //$x=new \SDKConfig();


        $user = M('user');
        $order_db = M('order');

        $token = decrypt1(trim(I('post.token')));
        $order_id = decrypt1(trim(I('post.order_id')));
        $type = decrypt1(trim(I('post.type')));//1.充值，2.支付订单，3.购买会员（仅限安卓）
        $cz_money = decrypt1(trim(I('post.cz_money')));
        $vip_type = decrypt1(trim(I('post.vip_type')));//会员类型：1月，2季度，3半年，4一年

        if ($type=='3'){
            if (!$vip_type){
                $arr = array('code' => 0, 'res' => '请传入要购买的会员等级');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");
            }
            if ($vip_type=='1'){
                $money = "10";
            }
            if ($vip_type=='2'){
                $money = "28";
            }
            if ($vip_type=='3'){
                $money = "54";
            }
            if ($vip_type=='4'){
                $money = "96";
            }
        }


        //$token = '66dc8eb82532bfe292dc66ad1e8dae0f';
        //$order_id = '14';

        /* $token ='dcb54848991670ee524758cbac370228';
         $type ='1';
         $money ='1';*/
        if (!$type){
            $arr = array('code' => 0, 'res' => '请传入支付类型');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");
        }
        if ($type=='2'){
            if (!$order_id) {
                $arr = array('code' => 0, 'res' => '请传入支付类型');
                $data = json_encode($arr);
                $return['encryption'] = encrypt1($data);
                $this->ajaxReturn($return, "JSON");
            }
        }




        $user_info = $user->where(array('token' => $token))->find();
        if (!$user_info) {$arr = array('code' => 2001, 'res' => 'token错误');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");}
        if ($user_info['is_freeze'] == '0') {$arr = array('code' => 0, 'res' => '您的帐号已被禁止登陆，如有疑问，请联系工作人员');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");}

        if ($type=='2'){
            $order_info = $order_db->where(array('id'=>$order_id))->find();
            if ($order_info['money_type']=='2' && $order_info['pay_status']=='1'){
                $arr = array('code' => 0, 'res' => '订单已支付');
                $data = json_encode($arr);
                $return['encryption'] = encrypt1($data);
                $this->ajaxReturn($return, "JSON");
            }
            if ($order_info['status']=='1'){
                $money = $order_info['price'] - $order_info['money'];
            }else{
                $money = $order_info['money'];
            }


        }

        if ($type=='1'){
            if (!is_numeric($cz_money) || $cz_money<0){
                $arr = array(
                    'code'=> 0,
                    'res'=>'充值金额错误',
                );
                $data = json_encode($arr);
                $return['encryption'] =  encrypt1($data);
                $this->ajaxReturn($return,"JSON");
            }
            $money = $cz_money;
        }

        if($type =='1'){
            $body = "小时光支付(".$money.")元-银联支付";
        }else{
            $body = "小时光支付(".$money.")元-银联支付";
        }


        $orderid = time().rand(10000,99999).$user_info['id'];

        $zuo = time()-7200;

        $pay= M('pay');

        $pay->where(array('create_time'=>array('LT',$zuo),'trade_status'=>'0'))->delete();

        $add['pay_type'] = $type;//1充值，2支付订单
        if($type=='2'){
            $add['order_id'] = $order_id;
        }
        if($type=='3'){
            $add['vip_type'] = $vip_type;
        }
        $add['type'] = 3;
        $add['out_trade_no'] = $orderid;
        $add['order_name'] = $body;
        $add['money_type'] = $type;
        $add['money'] = $money;
        $add['user_id'] = $user_info['id'];
        $add['create_time'] = time();


        //$acp=new \AcpService();
        //$orderid=I('orderid');
        //$money='0.01';
        $params = array(
            //以下信息非特殊情况不需要改动
            'version' => \com\unionpay\acp\sdk\SDKConfig::getSDKConfig()->version,                 //版本号
            'encoding' => 'utf-8',				  //编码方式
            'txnType' => '01',				      //交易类型
            'txnSubType' => '01',				  //交易子类
            'bizType' => '000201',				  //业务类型
            'frontUrl' =>  \com\unionpay\acp\sdk\SDKConfig::getSDKConfig()->frontUrl,  //前台通知地址
            'backUrl' => \com\unionpay\acp\sdk\SDKConfig::getSDKConfig()->backUrl,	  //后台通知地址
            'signMethod' => \com\unionpay\acp\sdk\SDKConfig::getSDKConfig()->signMethod,	              //签名方法
            'channelType' => '08',	              //渠道类型，07-PC，08-手机
            'accessType' => '0',		          //接入类型
            'currencyCode' => '156',	          //交易币种，境内商户固定156
            //TODO 以下信息需要填写
            'merId' => "826440153110056",
            //商户代码，请改自己的测试商户号
            'orderId' =>$orderid,
            //商户订单号，8-32位数字字母，不能含“-”或“_”
            'txnTime' => date('YmdHis'),
            //订单发送时间，格式为YYYYMMDDhhmmss，取北京时间
            'txnAmt' =>$money*100, //交易金额，单位分，
        );
        /*$cert_path = '/SERVICE01/certs/littletime.pfx';
        $cert_pwd = '315451';*/

        \com\unionpay\acp\sdk\AcpService::sign ( $params ); // 签名

        $url = \com\unionpay\acp\sdk\SDKConfig::getSDKConfig()->appTransUrl;

        $result_arr = \com\unionpay\acp\sdk\AcpService::post ($params,$url);

        if(count($result_arr)<=0) { //没收到200应答的情况
            printResult ($url, $params, "" );
            return;
        }

        if (!\com\unionpay\acp\sdk\AcpService::validate ($result_arr) ){

            return;
        }


        if ($result_arr["respCode"] == "00"){
            $pay->add($add);
            //成功
            /*$return['status']=1;
            $return['msg']="success";
            $data['tn']=$result_arr["tn"];
            $return['data']['tn']= $data['tn'];
            $this->ajaxReturn($return, 'JSON');*/
            //后续请将此tn传给手机开发，他们用此tn调起控件后完成支付;

            $arr = array(
                'code' => 1,
                'res' => '成功',
                'orderInfo' => $result_arr["tn"],

            );

        } else {
            $arr = array(
                'code' => 0,
                'res' => '参数错误',

            );
        }
        $data = json_encode($arr);
        $return['encryption'] =  encrypt1($data);
        $this->ajaxReturn($return,"JSON");
    }

    function huidiao(){
       /* $data = $_REQUEST;
        $add['content'] = json_encode($data);
        $add['time'] = date('Y-m-d H:i:s');
        M('test')->add($add);*/

        if (isset ( $_POST['signature'] )) {

            if (\com\unionpay\acp\sdk\AcpService::validate ( $_POST )){

                $orderId = $_POST ['orderId']; //其他字段也可用类似方式获取
                $respCode = $_POST ['respCode'];
                $queryId = $_POST ['queryId'];
                $traceNo = $_POST ['traceNo'];
                $txnTime = $_POST ['txnTime'];
                $txnAmt = $_POST ['txnAmt'];
                //判断respCode=00、A6后，对涉及资金类的交易，请再发起查询接口查询，确定交易成功后更新数据库。
                if ($respCode=='00'){

                    $save['trade_no']      = $queryId;
                    $save['notify_id']        = $traceNo;
                    $save['notify_time']        = $txnTime;
                    $save['total_amount']     = $txnAmt/100;
                    $save['buyer_pay_amount']     = $txnAmt/100;
                    $save['trade_status']  = 'SUCCESS';
                    $save['update_time']        = time();
                    $query = M('pay')->where(array('out_trade_no'=>$orderId))->save($save);

                    $pay_info = M('pay')->where(array('out_trade_no'=>$orderId))->find();

                    //充值
                    if($pay_info['pay_type']=='2'){
                        $order_info = M('order')->where(array('id'=>$pay_info['order_id']))->find();

                        $save_order['pay_status'] = 1;
                        $save_order['pay_type'] = 3;
                        if ($order_info['pay_status']=='1' && $order_info['money_type']=='1'){
                            $save_order['status'] = 2;
                            $save_order['money_type'] = 2;
                            $save_order['money'] = $order_info['price'];
                        }else{
                            if ($order_info['pay_status']=='0' && $order_info['money_type']=='2'){
                                $save_order['status'] = 2;
                                $save_order['money_type'] = 2;
                                $save_order['money'] = $order_info['price'];
                            }else{
                                $save_order['status'] = 1;
                                $save_order['money_type'] = 1;
                            }

                        }
                        $save_order['pay_time'] = time();
                        $is_save = M('order')->where(array('id'=>$order_info['id']))->save($save_order);
                        if ($is_save){
                            $user_info = M('user')->where(array('id'=>$order_info['user_id']))->find();
                            //金额交易记录
                            $add_money['user_id'] = $user_info['id'];
                            $add_money['money'] = $order_info['money'];

                            //店铺通知
                            $shop_info  = M('shop')->where(array('id'=>$order_info['shop_id']))->find();
                            $other_user = M('user')->where(array('id'=>$shop_info['user_id']))->field('phone')->find();
                            $goods_info  = M('shop_goods')->where(array('id'=>$order_info['goods_id']))->field('name')->find();
                            if ($order_info['money_type']=='1'){
                                //OrderSendSms($user_info['phone'],'1','1',$order_info['order_number'],$goods_info['name']);
                                OrderSendSms($other_user['phone'],'2','1',$order_info['order_number'],$goods_info['name']);


                                $add_money['type'] = 3;
                            }else{
                                //OrderSendSms($user_info['phone'],'1','2',$order_info['order_number'],$goods_info['name']);
                                OrderSendSms($other_user['phone'],'2','2',$order_info['order_number'],$goods_info['name']);
                                $add_money['type'] = 7;
                            }
                            $add_money['add_or_del'] = 2;//1增加，2减去
                            $add_money['pay_type'] = 1;//1微信，2支付宝，3银联，4余额
                            $add_money['create_time'] = time();
                            $add_money['order_id'] = $order_info['id'];
                            $add_money['create_type'] = 2;//1管理员操作，2系统操作，3用户操作，4业务员操作
                            M('money_log')->add($add_money);
                            //金额交易记录

                            M('shop')->where(array('id'=>$order_info['shop_id']))->setInc('sales',1);
                            M('shop')->where(array('id'=>$order_info['shop_id']))->setInc('money',$order_info['money']);
                            M('shop_goods')->where(array('id'=>$order_info['goods_id']))->setInc('sales',1);
                            //添加消息通知
                            $tis = '您有新的订单';
                            //新版
                            $add_system['user_id'] = $shop_info['user_id'];
                            $add_system['send_user_id'] = $user_info['id'];
                            $add_system['content'] = $tis;
                            $add_system['type'] = 1;//1新的订单，2订单交易成功，3待处理订单
                            $add_system['type_id'] = $order_info['id'];
                            $add_system['status'] = 3;
                            $add_system['create_time'] = time();
                            M('system')->add($add_system);

                            $rong_user[] = $shop_info['user_id'];

                            SendRongSystem($rong_user,$tis);

                            //交易提醒
                            if ($save_order['money_type']=='1'){

                                $tis = '预约成功';
                                //新版
                                $add_system['user_id'] = $user_info['id'];
                                $add_system['send_user_id'] = $shop_info['user_id'];
                                $add_system['content'] = $tis;
                                $add_system['type'] = 1;//1预约成功，2交易成功，3退款成功，4退款失败
                                $add_system['type_id'] = $order_info['id'];
                                $add_system['status'] = 4;
                                $add_system['create_time'] = time();
                                M('system')->add($add_system);

                                $rong_user[] = $user_info['id'];

                                SendRongSystem($rong_user,$tis);
                            }else{
                                $tis = '交易成功';
                                //新版
                                $add_system['user_id'] = $user_info['id'];
                                $add_system['send_user_id'] = $shop_info['user_id'];
                                $add_system['content'] = $tis;
                                $add_system['type'] = 2;//1预约成功，2交易成功，3退款成功，4退款失败
                                $add_system['type_id'] = $order_info['id'];
                                $add_system['status'] = 4;
                                $add_system['create_time'] = time();
                                M('system')->add($add_system);

                                $rong_user[] = $user_info['id'];

                                SendRongSystem($rong_user,$tis);
                            }


                        }else{
                            echo 'error';
                        }
                    }


                    if ($pay_info['pay_type']=='1'){
                        $query = M('user')->where(array('id'=>$pay_info['user_id']))->setInc('money',$pay_info['money']);
                        if ($query){
                            $add_money['type'] = 1;
                            $add_money['user_id'] = $pay_info['user_id'];
                            $add_money['money'] = $pay_info['money'];
                            $add_money['add_or_del'] = 1;//1增加，2减去
                            $add_money['pay_type'] = 3;//1微信，2支付宝，3银联，4余额
                            $add_money['create_time'] = time();
                            $add_money['out_trade_no'] = $pay_info['out_trade_no'];//1管理员操作，2系统操作，3用户操作，4业务员操作
                            $add_money['create_type'] = 3;//1管理员操作，2系统操作，3用户操作，4业务员操作
                            M('money_log')->add($add_money);
                        }else{
                            echo 'error';
                        }
                    }



                    if($pay_info['pay_type']=='3'){
                        $is_buy = M('user_vip')->where(array('user_id'=>$pay_info['user_id']))->find();
                        if ($is_buy){
                            $save_user['vip'] = 1;
                            if ($is_buy['is_end']=='1'){
                                if ($pay_info['vip_type']=='4'){
                                    $save_user['vip'] = 2;
                                }
                                $end_time = time();
                            }else{
                                if ($is_buy['type']=='4'){
                                    $save_user['vip'] = 2;
                                }else{
                                    if ($pay_info['vip_type']=='4'){
                                        $save_user['vip'] = 2;
                                    }
                                }
                                $end_time = $is_buy['end_time'];
                            }

                            $save_user['update_time'] = time();
                            $query  = M('user')->where(array('id'=>$pay_info['user_id']))->save($save_user);

                            if ($query){
                                $save_vip['user_id'] = $pay_info['user_id'];
                                $save_vip['pay_type'] = 3;
                                $save_vip['phone_type'] = 2;
                                $save_vip['pay_status'] = 1;
                                $save_vip['is_end'] = 0;
                                $save_vip['money'] = $pay_info['money'];
                                $save_vip['type'] = $pay_info['vip_type'];
                                $save_vip['update_time'] = time();
                                //$save_vip['start_time'] = time();
                                if ($pay_info['vip_type']=='1'){
                                    $save_vip['end_time'] = strtotime("+1 month -1 day",$end_time);
                                }
                                if ($pay_info['vip_type']=='2'){
                                    $save_vip['end_time'] = strtotime("+3 month -1 day",$end_time);
                                }
                                if ($pay_info['vip_type']=='3'){
                                    $save_vip['end_time'] = strtotime("+6 month -1 day",$end_time);
                                }
                                if ($pay_info['vip_type']=='4'){
                                    $save_vip['end_time'] = strtotime("+1 year -1 day",$end_time);
                                }
                                M('user_vip')->where(array('id'=>$is_buy['id']))->save($save_vip);
                            }else{
                                echo 'error';
                            }



                        }else{
                            if ($pay_info['vip_type']=='4'){
                                $save_user['vip'] = 2;
                            }else{
                                $save_user['vip'] = 1;
                            }
                            $save_user['update_time'] = time();
                            $query  = M('user')->where(array('id'=>$pay_info['user_id']))->save($save_user);


                            if ($query){
                                $add_vip['user_id'] = $pay_info['user_id'];
                                $add_vip['pay_type'] = 1;
                                $add_vip['phone_type'] = 2;
                                $add_vip['pay_status'] = 1;
                                $add_vip['is_end'] = 0;
                                $add_vip['money'] = $pay_info['money'];
                                $add_vip['type'] = $pay_info['vip_type'];
                                $add_vip['create_time'] = time();
                                $add_vip['start_time'] = time();
                                if ($pay_info['vip_type']=='1'){
                                    $add_vip['end_time'] = strtotime("+1 month -1 day");
                                }
                                if ($pay_info['vip_type']=='2'){
                                    $add_vip['end_time'] = strtotime("+3 month -1 day");
                                }
                                if ($pay_info['vip_type']=='3'){
                                    $add_vip['end_time'] = strtotime("+6 month -1 day");
                                }
                                if ($pay_info['vip_type']=='4'){
                                    $add_vip['end_time'] = strtotime("+1 year -1 day");
                                }
                                M('user_vip')->add($add_vip);
                            }else{
                                echo 'error';
                            }



                        }



                    }




                    echo 'success';
                }else{
                    echo '支付失败';
                }

            }else{
                echo '签名错误';
            }



        } else {
            echo '签名为空';
        }

    }

}
?>