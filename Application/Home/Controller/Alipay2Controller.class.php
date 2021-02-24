<?php
namespace Home\Controller;
use Think\Controller;
class Alipay2Controller extends Controller
{
    public function _initialize() {
        vendor('appalipay.AopSdk');// 加载类库
    }

    public function create(){

        $user = M('user');
        $shop_order = M('shop_order');
        $hospital_order = M('hospital_order');
        $ticket_user = M('ticket_user');
        $ticket_hospital = M('ticket_hospital');
        $ticket = M('ticket');
        $team = M('team');
        $set = M('set');

        $token = decrypt1(trim(I('post.token')));
        $type = decrypt1(trim(I('post.type')));//支付类型：1支付医院，2支付商城订单，3优惠券，4团队费用,5支付红包,6支付城市合伙人费用
        $type_id = decrypt1(trim(I('post.type_id')));
        $have_id = decrypt1(trim(I('post.have_id')));
        $score = decrypt1(trim(I('post.score')));

        $remark = decrypt1(trim(I('post.remark')));
        $pay_type = decrypt1(trim(I('post.pay_type')));//支付类型：1支付全款，2支付预定金,3支付剩余金额

        $token = '1';

        $type = 2;
        $type_id = 4085;
        $pay_type = 1;
        $have_id = 299;



        //构造业务请求参数的集合(订单信息)

        $body = "伊美小天鹅支付订单-".$money.'元';
        $title = "伊美小天鹅支付订单(".$money.")元-支付宝支付";
        $price = $money;

        if($money==0){
            if($type==2){
                $is_pay = Pay0Money('1',$type_id,$user_info['id'],'2');
                if($is_pay){
                    $arr = array(
                        'code' => 0,
                        'res' => '支付成功'
                    );
                    $data = json_encode($arr);
                    $return['encryption'] =  encrypt1($data);
                    $this->ajaxReturn($return,"JSON");
                }
            }
            if($type==3){
                $is_pay = Pay0Money('2',$type_id,$user_info['id'],'2');
                if($is_pay){
                    $arr = array(
                        'code' => 0,
                        'res' => '支付成功'
                    );
                    $data = json_encode($arr);
                    $return['encryption'] =  encrypt1($data);
                    $this->ajaxReturn($return,"JSON");
                }
            }
            $arr = array(
                'code' => 0,
                'res' => '当前价格无法支付！'
            );
            $data = json_encode($arr);
            $return['encryption'] =  encrypt1($data);
            $this->ajaxReturn($return,"JSON");
        }else{
            //$price = 0.01;
            if ($user_info['id']==4 || $user_info['id']==5 || $user_info['id']==1){
                $price = 0.01;
            }
            $order = time().rand(10000,99999).$user_info['id'];
            $content = array();
            $content['body'] = $body;
            $content['subject'] = $title;//商品的标题/交易标题/订单标题/订单关键字等
            $content['out_trade_no'] = $order;//商户网站唯一订单号
            $content['timeout_express'] = '1d';//该笔订单允许的最晚付款时间
            $content['total_amount'] = floatval($price);//订单总金额(必须定义成浮点型)
            $content['seller_id'] = '';//收款人账号
            $content['product_code'] = 'QUICK_MSECURITY_PAY';//销售产品码，商家和支付宝签约的产品码，为固定值QUICK_MSECURITY_PAY
            $content['store_id'] = '001';//商户门店编号
            $con = json_encode($content);//$content是biz_content的值,将之转化成字符串
//公共参数
            $param = array();
            $Client = new \AopClient();//实例化支付宝sdk里面的AopClient类,下单时需要的操作,都在这个类里面
            $param['app_id'] = C('alipay_config.appid');//支付宝分配给开发者的应用ID
            $param['method'] = C('alipay_config.method');//接口名称
            $param['charset'] = C('alipay_config.charset');//请求使用的编码格式
            $param['sign_type'] = C('alipay_config.sign_type');//商户生成签名字符串所使用的签名算法类型
            $param['timestamp'] = C('alipay_config.timestamp');//发送请求的时间
            $param['version'] = C('alipay_config.version');//调用的接口版本，固定为：1.0
            $param['notify_url'] = C('alipay_config.notify_url');//支付宝服务器主动通知地址
            $param['biz_content'] = $con;//业务请求参数的集合,长度不限,json格式
            //dump($param);die;
//生成签名

            $paramStr = $Client->getSignContent($param);
            $sign = $Client->alonersaSign($paramStr,C('alipay_config.rsaPrivateKey'),'RSA2');
            $param['sign'] = $sign;
            $str = $Client->getSignContentUrlencode($param);
            //return array('url'=>$str);
            //$this->ajaxReturn($str);
            //dump($str);die;
        }

        if($str){
            $zuo = time()-7200;

            $pay= M('pay');

            $pay->where(array('create_time'=>array('LT',$zuo),'trade_status'=>'0'))->delete();

            $add_pay['from'] = 1;//来源：1APP，2公众号
            $add_pay['type'] = 2;//1微信，2支付宝
            $add_pay['pay_type'] = $type;//支付类型：1支付医院，2支付商城订单，3优惠券，4团队费用

            $add_pay['order_id'] = $type_id;
            $add_pay['out_trade_no'] = $order;
            $add_pay['order_name'] = $title;
            $add_pay['money'] = $money;
            $add_pay['user_id'] = $user_info['id'];
            $add_pay['create_time'] = time();
            $pay->add($add_pay);


            $arr = array(
                'code' => 1,
                'res' => '增加成功',
                'orderInfo' => $str,//商户订单号
            );
            $data = json_encode($arr);
            $return['encryption'] =  encrypt1($data);
            $this->ajaxReturn($return,"JSON");
        }else{
            $arr = array(
                'code' => 0,
                'res' => '参数错误'
            );
            $data = json_encode($arr);
            $return['encryption'] =  encrypt1($data);
            $this->ajaxReturn($return,"JSON");
        }

    }




    function notifyurl(){

        $Client = new \AopClient();
        $Client->alipayrsaPublicKey = C('alipay_config.alipayrsaPublicKey');
        $i_ok = $Client->rsaCheckV1($_POST,NULL,'RSA2');


        if($i_ok)//判断成功之后使用getResponse方法判断是否是支付宝发来的异步通知。
        {
            $out_trade_no = $_POST['out_trade_no'];//商户订单号
            $trade_no     = $_POST['trade_no'];//支付宝交易号
            $total_amount    = $_POST['total_amount'];//本次交易支付的订单金额，单位为人民币（元）
            $buyer_pay_amount    = $_POST['buyer_pay_amount'];//用户在交易中支付的金额
            $notify_id    = $_POST['notify_id'];//通知校验ID
            $notify_time    = $_POST['notify_time'];//通知的发送时间。格式为
            //$seller_email = $_POST['seller_email'];
            $buyer_id  = $_POST['buyer_id'];//买家支付宝用户号
            $buyer_logon_id  = $_POST['buyer_logon_id'];//买家支付宝账号
            $trade_status = $_POST['trade_status'];//交易状态
            if($trade_status == 'TRADE_FINISHED'||$trade_status == 'TRADE_SUCCESS') {//交易彻底完成

                $save['trade_no']      = $trade_no;
                $save['total_amount']      = $total_amount;
                $save['notify_id']        = $notify_id;
                $save['notify_time']        = $notify_time;
                $save['buyer_id']     = $buyer_id;
                $save['buyer_logon_id']     = $buyer_logon_id;
                $save['buyer_pay_amount']     = $buyer_pay_amount;
                $save['trade_status']  = $trade_status;
                $save['update_time']        = time();
                $query = M('pay')->where(array('out_trade_no'=>$_POST['out_trade_no']))->save($save);

                if (!$query){
                    echo "response fail";die;
                }
                $user = M('user');
                $pay_info = M('pay')->where(array('out_trade_no'=>$_POST['out_trade_no']))->find();
                $user_info = $user->where(array('id'=>$pay_info['user_id']))->find();

                if($pay_info['pay_type']=='1'){
                    $order_info = M('hospital_order')->where(array('id'=>$pay_info['order_id']))->find();


                    if ($pay_info['money_type']=='2'){
                        $save_order['money'] = $pay_info['money'];
                        $save_order['need_money'] = $pay_info['need_money'];
                        $save_order['status'] = 1;
                        $save_order['money_type'] = 1;
                    }else if ($pay_info['money_type']=='3'){
                        $save_order['money'] = $order_info['money']+$pay_info['money'];
                        $save_order['need_money'] = 0;
                        $save_order['status'] = 2;
                        $save_order['money_type'] = 2;
                    }else{
                        $save_order['money'] =  $pay_info['money'];
                        $save_order['need_money'] = 0;
                        $save_order['status'] = 2;
                        $save_order['money_type'] = 2;
                    }

                    $save_order['user_id'] = $pay_info['user_id'];
                    //$save_order['money'] = $pay_info['money'];
                    //$save_order['status'] = 1;
                    $save_order['update_time'] = time();
                    $save_order['pay_time'] = time();
                    $save_order['ticket_id'] = $pay_info['ticket_id'];
                    $save_order['ticket_money'] = $pay_info['ticket_money'];
                    $save_order['score'] = $pay_info['score'];
                    $save_order['score_money'] = $pay_info['score_money'];

                    M('hospital')->where(array('id'=>$order_info['parent']))->setInc('subscribe',1);
                    M('doctor')->where(array('id'=>$order_info['doctor_id']))->setInc('subscribe',1);
                    //平台抽水
                    //$chou_odds = M('set')->where(array('set_type'=>'hos_order_chou'))->find();
                    /*$shop_type = M('shop')->where(array('hospital_id'=>$order_info['parent']))->field('odds_type,odds')->find();

                    if($shop_type['odds']!=NULL){
                        $chou_odds['set_value']  = $shop_type['odds'];
                    }else{
                        if(!$shop_type['odds_type']){
                            $odds_type_id = $shop_type['odds_type'];
                        }else{
                            $odds_type_id = 1;
                        }
                        $odds_type_odds = M('odds_type')->where(array('id'=>$odds_type_id))->field('odds')->find();
                        $chou_odds['set_value']   = $odds_type_odds['odds'];
                    }

                    if($chou_odds['set_value']!='0'){
                        $chou_money = $order_info['total_money'] - $order_info['cost'] - $order_info['loans'];
                        $odds = $chou_odds['set_value']/100;
                        $chou = $chou_money * $odds;
                        $save_order['chou'] = $chou;
                        $save_order['odds'] = $chou_odds['set_value'];
                    }*/
                    $query = M('hospital_order')->where(array('id'=>$pay_info['order_id']))->save($save_order);
                    if($query){
                        $a = SendSocket($order_info['parent'],$order_info['id']);
                        if ($pay_info['money_type']!='3'){
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

                            if($pay_info['ticket_id']){
                                M('ticket_user')->where(array('id'=>$pay_info['ticket_id']))->save(array('status'=>'1','update_time'=>time()));
                            }
                        }


                        //代理抽水
                        //HosOrderAgentChou($order_info['id']);

                        if ($pay_info['money_type']=='1'){
                            if($user_info['is_agent']=='1'){
                                $add_fan = HosOrderAgentChou($order_info['id'],'3');
                            }
                        }
                    }
                }


                if ($pay_info['pay_type']=='2'){
                    $order_info = M('shop_order')->where(array('id'=>$pay_info['order_id']))->find();
                    $shop_location  = M('shop_location');
                    $location_info = $shop_location->where(array('user_id'=>$pay_info['user_id']))->find();

                    //$save_order['location_id'] = $location_info['id'];
                    //$save_order['location'] = $location_info['province'].$location_info['city'].$location_info['district'].$location_info['street'];
                    //$save_order['mobile'] = $location_info['mobile'];
                    //$save_order['addressee'] = $location_info['addressee'];
                    //$save_order['particular'] = $location_info['particular'];
                    //$save_order['remark'] = $pay_info['remark'];
                    $save_order['pay_time'] = time();
                    $save_order['ticket_id'] = $pay_info['ticket_id'];
                    $save_order['ticket_money'] = $pay_info['ticket_money'];
                    $save_order['pay_type'] = $pay_info['type'];//1微信，2支付宝
                    if ($pay_info['money_type']=='2'){
                        $save_order['money'] = $pay_info['money'];
                        $save_order['need_money'] = $order_info['total_money'] - $order_info['prepay_money']+$order_info['postage_money'];
                        $save_order['status'] = 1;
                        $save_order['money_type'] = 1;
                        $save_order['total_money'] = $order_info['total_money']+$order_info['postage_money'];
                    }else if ($pay_info['money_type']=='3'){
                        $save_order['money'] = $order_info['total_money'];
                        $save_order['need_money'] = 0;
                        $save_order['status'] = 2;
                        $save_order['money_type'] = 2;
                    }else{
                        $save_order['money'] = $pay_info['money'];
                        $save_order['need_money'] = 0;
                        $save_order['status'] = 2;
                        $save_order['money_type'] = 2;
                        $save_order['total_money'] = $order_info['total_money']+$order_info['postage_money'];
                    }




                    $query = M('shop_order')->where(array('id'=>$pay_info['order_id']))->save($save_order);
                    if ($query){

                        if($pay_info['ticket_id']){
                            M('ticket_user')->where(array('id'=>$pay_info['ticket_id']))->save(array('status'=>'1','update_time'=>time()));
                        }

                        $shop_info = M('shop')->where(array('id'=>$order_info['shop_id']))->find();
                        if ($pay_info['money_type']=='1'){
                            $add_system['type'] = 1;//1新的订单，2订单交易成功，3待处理订单
                            $tis = '新订单';
                        }
                        if ($pay_info['money_type']=='2'){
                            $add_system['type'] = 1;//1新的订单，2订单交易成功，3待处理订单
                            $tis = '新订单-已支付预约金';
                        }
                        if ($pay_info['money_type']=='3'){
                            $add_system['type'] = 3;//1新的订单，2订单交易成功，3待处理订单
                            $tis = '支付完成';
                        }
                        //添加消息通知
                        //新版
                        $add_system['user_id'] = $shop_info['user_id'];
                        $add_system['send_user_id'] = $pay_info['user_id'];
                        $add_system['content'] = $tis;
                        $add_system['type_id'] = $order_info['id'];
                        $add_system['status'] = 3;
                        $add_system['create_time'] = time();
                        M('system')->add($add_system);
                        $rong_user[] = $shop_info['user_id'];
                        SendRongSystem($rong_user,$tis);

                        if ($pay_info['money_type']=='1' || $pay_info['money_type']=='3'){
                            $goods_list = M('shop_order_goods')->where(array('order_id'=>$order_info['id']))->field('goods_id')->select();
                            foreach($goods_list as $item){
                                M('shop_goods')->where(array('id'=>$item['goods_id']))->setInc('sales',$order_info['total_num']);
                                M('shop_goods')->where(array('id'=>$item['goods_id']))->setDec('stock',$order_info['total_num']);
                            }
                        }
                        $add_status = AddScore($pay_info['user_id'],$user_info['score'],$user_info['level'],'0',$order_info['id']);

                        if ($pay_info['money_type']=='1'){
                            if($user_info['is_agent']=='1'){
                                $add_fan = ShopOrderAgentChou($order_info['id'],'3');
                            }
                        }

                    }
                }



                if($pay_info['pay_type']=='3'){
                    $ticket_info = M('ticket')->where(array('id'=>$pay_info['order_id']))->find();

                    $add_tic['user_id'] = $user_info['id'];
                    $add_tic['parent'] = $ticket_info['id'];
                    $add_tic['buy_money'] = $ticket_info['price'];
                    $add_tic['rebate_money'] = $ticket_info['rebate'];
                    $add_tic['discount'] = $ticket_info['discount'];
                    $add_tic['create_time'] = time();
                    $query = M('ticket_user')->add($add_tic);
                    M('ticket')->where(array('id'=>$pay_info['order_id']))->setInc('sales','1');
                    if($ticket_info['is_rebate']=='1'){
                        //赠送优惠券
                        AgentTaskSend($user_info['id']);
                    }
                }

                if ($pay_info['pay_type']=='4'){
                    $save_team['status'] = 1;
                    $save_team['update_time'] = time();
                    $query = M('team')->where(array('id'=>$pay_info['order_id']))->save($save_team);
                    if ($query){
                        //赠送优惠券
                        //AgentTaskSend($user_info['id']);
                     /* $give_list =  M('ticket')->where(array('type'=>'2','is_give'=>'1'))->select();
                        if($give_list){
                            $add_ticket['user_id'] = $user_info['id'];
                            $add_ticket['buy_money'] = 0;
                            $add_ticket['rebate_money'] = 0;
                            $add_ticket['create_time'] = time();
                            foreach ($give_list as $item){
                                $is_have = M('ticket_user')->where(array('user_id'=> $user_info['id'],'parent'=>$item['id']))->find();
                                if (!$is_have){
                                    $add_ticket['parent'] = $item['id'];
                                    $add_ticket['discount'] = $item['discount'];
                                    M('ticket_user')->add($add_ticket);
                                }
                            }
                        }*/
                        $user->where(array('id'=>$user_info['id']))->save(array('is_agent'=>'1','team_id'=>$pay_info['order_id'],'update_time'=>time()));
                        //发送星级会员公告
                        SendNoticeUser($user_info['id'],2);
                    }


                }

                if ($pay_info['pay_type']=='6'){
                    $save_team['status'] = 1;
                    $save_team['update_time'] = time();
                    $query = M('gudong')->where(array('id'=>$pay_info['order_id']))->save($save_team);
                    if ($query){
                       //设置用户未股东
                        UserIsGudong($user_info['id']);
                    }
                }

                if ($pay_info['pay_type']=='5'){
                    $save_team['is_pay'] = 1;
                    $save_team['update_time'] = time();
                    $query = M('task')->where(array('id'=>$pay_info['order_id']))->save($save_team);
                    if ($query){
                        $task_info = M('task')->where(array('id'=>$pay_info['order_id']))->find();
                        $send_con['type'] = '1';
                        $send_con['user_id'] = $task_info['user_id'];
                        SendSocket($task_info['user_id'],json_encode($send_con));
                    }
                }





                echo "success";//请不要修改或删除
            }else{
                echo "response fail";die;
            }
        }
        else //验证是否来自支付宝的通知失败
        {
            echo "response fail";die;
        }
    }




}