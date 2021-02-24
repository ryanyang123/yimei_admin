<?php
namespace Home\Controller;
use Think\Controller;
class AligetController extends Controller
{
    public function _initialize() {
        vendor('appalipay.AopSdk');// 加载类库
    }

    public function index($data){

        $from = MODULE_NAME."/".CONTROLLER_NAME."/".ACTION_NAME;

        if($from=='Home/Ds/getmoney' || $from=='Home/Ds/getmoneyrebate'  || $from=='Home/Ds/getmoneytui'  || $from=='Home/Alizz/add' ){
            //type:1打款给医院,2打钱给商家,3商品退款,4优惠券退款,5优惠券返利,6领取现金红包,7抵消券返利医院，8医院订单退款,9退款订单-打款给医院(当前使用1),10系统手动打钱,
            //11 打钱给代理,12星级会员费用返利给代理,13返利给城市合伙人
            if($data['type']=='1'){
                $shop_info = M('shop')->where(array('id'=>$data['parent']))->field('user_id')->find();
                if($shop_info){
                    $user_info = M('user')->where(array('id'=>$shop_info['user_id']))->field('id,ali_number,ali_name,ali_user_id')->find();
                    if($user_info['ali_user_id']){
                        $payee_account = $user_info['ali_user_id'];
                        $payee_name = '';
                        $payee_type = 'ALIPAY_USERID';
                    }else{
                        $payee_account = $user_info['ali_number'];
                        $payee_name = $user_info['ali_name'];
                        $payee_type = 'ALIPAY_LOGONID';
                    }
                    $add_get['user_id'] = $user_info['id'];
                }else{
                    $hos_info = M('hospital')->where(array('id'=>$data['parent']))->field('ali_number,ali_name')->find();
                    $payee_account = $hos_info['ali_number'];
                    $payee_name = $hos_info['ali_name'];
                    $payee_type = 'ALIPAY_LOGONID';
                }


                $remark = '医院订单：'.$data['order_number'].' - 收益';//备注
            }


            //打钱给商家
            if($data['type']=='2'){
                $shop_info = M('shop')->where(array('id'=>$data['parent']))->field('user_id')->find();
                $user_info = M('user')->where(array('id'=>$shop_info['user_id']))->field('id,ali_number,ali_name,ali_user_id')->find();

                if(!$shop_info || !$user_info){
                    return false;die;
                }
                $add_get['user_id'] = $user_info['id'];
                if($user_info['ali_user_id']){
                    $payee_account = $user_info['ali_user_id'];
                    $payee_name = '';
                    $payee_type = 'ALIPAY_USERID';
                }else{
                    $payee_account = $user_info['ali_number'];
                    $payee_name = $user_info['ali_name'];
                    $payee_type = 'ALIPAY_LOGONID';
                }
                $remark = '商品订单：'.$data['order_number'].' - 付款';//备注
            }


            //商城商品退款
            if($data['type']=='3'){
                $tui_info = M('tui')->where(array('id'=>$data['type_id']))->find();
                if(!$tui_info){
                    return false;die;
                }
                if($tui_info['status']==1){
                    return false;die;
                }

                $user_info = M('user')->where(array('id'=>$data['parent']))->field('id,ali_number,ali_name,ali_user_id')->find();
                if(!$user_info){
                    return false;die;
                }
                if($tui_info['user_id']!=$user_info['id']){
                    return false;die;
                }
                $add_get['user_id'] = $user_info['id'];
                if($user_info['ali_user_id']){
                    $payee_account = $user_info['ali_user_id'];
                    $payee_name = '';
                    $payee_type = 'ALIPAY_USERID';
                }else{
                    $payee_account = $user_info['ali_number'];
                    $payee_name = $user_info['ali_name'];
                    $payee_type = 'ALIPAY_LOGONID';
                }
                $remark = '伊美小天鹅商品 - 退款';//备注
            }

            //优惠券退款
            if($data['type']=='4'){
                $tui_info = M('tui')->where(array('id'=>$data['type_id']))->find();
                if(!$tui_info){
                    return false;die;
                }
                if($tui_info['status']==1){
                    return false;die;
                }
                $user_info = M('user')->where(array('id'=>$data['parent']))->field('id,ali_number,ali_name,ali_user_id')->find();
                if(!$user_info){
                    return false;die;
                }
                if($tui_info['user_id']!=$user_info['id']){
                    return false;die;
                }
                $add_get['user_id'] = $user_info['id'];
                if($user_info['ali_user_id']){
                    $payee_account = $user_info['ali_user_id'];
                    $payee_name = '';
                    $payee_type = 'ALIPAY_USERID';
                }else{
                    $payee_account = $user_info['ali_number'];
                    $payee_name = $user_info['ali_name'];
                    $payee_type = 'ALIPAY_LOGONID';
                }
                $remark = '伊美小天鹅优惠券 - 退款';//备注
            }

            //优惠券返利
            if($data['type']=='5'){
                $have_info = M('ticket_user')->where(array('id'=>$data['type_id']))->find();
                if(!$have_info){
                    return false;die;
                }
                $user_info = M('user')->where(array('id'=>$data['parent']))->field('id,ali_number,ali_name,ali_user_id')->find();
                if(!$user_info){
                    return false;die;
                }
                if($have_info['user_id']!=$user_info['id']){
                    return false;die;
                }
                if($have_info['is_rebate']==1){
                    return false;die;
                }
                $add_get['user_id'] = $user_info['id'];
                if($user_info['ali_user_id']){
                    $payee_account = $user_info['ali_user_id'];
                    $payee_name = '';
                    $payee_type = 'ALIPAY_USERID';
                }else{
                    $payee_account = $user_info['ali_number'];
                    $payee_name = $user_info['ali_name'];
                    $payee_type = 'ALIPAY_LOGONID';
                }
                $remark = '伊美小天鹅优惠券 - 返利';//备注
            }

            //领取现金红包
            if($data['type']=='6'){
                $task_info = M('task')->where(array('id'=>$data['type_id']))->field('id,user_id,status,prize')->find();
                if(!$task_info){
                    return false;
                    die;
                }
                if($task_info['status']==2){
                    return false;die;
                }
                if($task_info['prize']!=3){
                    return false;die;
                }


                $user_info = M('user')->where(array('id'=>$data['parent']))->field('id,ali_number,ali_name,ali_user_id')->find();
                if($task_info['user_id']!=$user_info['id']){
                    return false;die;
                }
                if($user_info['ali_user_id']){
                    $payee_account = $user_info['ali_user_id'];
                    $payee_name = '';
                    $payee_type = 'ALIPAY_USERID';
                }else{
                    $payee_account = $user_info['ali_number'];
                    $payee_name = $user_info['ali_name'];
                    $payee_type = 'ALIPAY_LOGONID';
                }
                $add_get['user_id'] = $data['parent'];
                $remark = '伊美小天鹅 - 红包领取';//备注
            }

            //7抵消券返利医院
            if($data['type']=='7'){
                $order_info = M('hospital_order')->where(array('id'=>$data['parent']))->field('shop_id')->find();
                $shop_info = M('shop')->where(array('id'=>$order_info['shop_id']))->field('user_id')->find();
                if(!$order_info){
                    return false;
                    die;
                }
                if($shop_info){
                    $user_info = M('user')->where(array('id'=>$shop_info['user_id']))->field('id,ali_number,ali_name,ali_user_id')->find();
                    if($user_info['ali_user_id']){
                        $payee_account = $user_info['ali_user_id'];
                        $payee_name = '';
                        $payee_type = 'ALIPAY_USERID';
                    }else{
                        $payee_account = $user_info['ali_number'];
                        $payee_name = $user_info['ali_name'];
                        $payee_type = 'ALIPAY_LOGONID';
                    }
                    $add_get['user_id'] = $user_info['id'];
                }else{
                    $hos_info = M('hospital')->where(array('id'=>$order_info['parent']))->field('ali_number,ali_name')->find();
                    $payee_account = $hos_info['ali_number'];
                    $payee_name = $hos_info['ali_name'];
                    $payee_type = 'ALIPAY_LOGONID';
                }

                $remark = '医院订单抵消券：'.$data['order_number'].' - 收益';//备注


            }

            //8医院订单退款
            if($data['type']=='8'){
                $tui_info = M('tui')->where(array('id'=>$data['type_id']))->find();
                if(!$tui_info){
                    return false;die;
                }
                if($tui_info['status']==1){
                    return false;die;
                }
                $user_info = M('user')->where(array('id'=>$data['parent']))->field('id,ali_number,ali_name,ali_user_id')->find();
                if(!$user_info){
                    return false;die;
                }
                $add_get['user_id'] = $user_info['id'];
                if($user_info['ali_user_id']){
                    $payee_account = $user_info['ali_user_id'];
                    $payee_name = '';
                    $payee_type = 'ALIPAY_USERID';
                }else{
                    $payee_account = $user_info['ali_number'];
                    $payee_name = $user_info['ali_name'];
                    $payee_type = 'ALIPAY_LOGONID';
                }
                $remark = '伊美小天鹅 - 医院订单退款';//备注
            }
            //10系统手动打钱
            if($data['type']=='10'){
                $user_info = M('user')->where(array('id'=>$data['parent']))->field('id,ali_number,ali_name,ali_user_id')->find();
                if(!$user_info){
                    return false;die;
                }
                $add_get['user_id'] = $user_info['id'];
                if($user_info['ali_user_id']){
                    $payee_account = $user_info['ali_user_id'];
                    $payee_name = '';
                    $payee_type = 'ALIPAY_USERID';
                }else{
                    $payee_account = $user_info['ali_number'];
                    $payee_name = $user_info['ali_name'];
                    $payee_type = 'ALIPAY_LOGONID';
                }
                if($data['remark']){
                    $remark = '伊美小天鹅 - '.$data['remark'];//备注
                }else{
                    $remark = '伊美小天鹅 - 转账';//备注
                }
            }

            //打钱给代理
            if($data['type']=='11'){
                $team_info = M('team_money')->where(array('id'=>$data['type_id']))->field('id,user_id,money,is_get')->find();
                if(!$team_info){
                    return false;
                    die;
                }
                if($team_info['is_get']==1){
                    return false;die;
                }
                if($team_info['money']!=$data['money']){
                    return false;die;
                }

                $user_info = M('user')->where(array('id'=>$data['parent']))->field('id,ali_number,ali_name,ali_user_id')->find();
                if($team_info['user_id']!=$user_info['id']){
                    return false;die;
                }
                if($user_info['ali_user_id']){
                    $payee_account = $user_info['ali_user_id'];
                    $payee_name = '';
                    $payee_type = 'ALIPAY_USERID';
                }else{
                    $payee_account = $user_info['ali_number'];
                    $payee_name = $user_info['ali_name'];
                    $payee_type = 'ALIPAY_LOGONID';
                }
                $add_get['user_id'] = $data['parent'];
                $remark = '伊美小天鹅 - 团队收益';//备注
            }

            //星级会员支付返利给代理
            if($data['type']=='12'){
                $user_info = M('user')->where(array('id'=>$data['parent']))->field('id,ali_number,ali_name,ali_user_id')->find();
                if($user_info['ali_user_id']){
                    $payee_account = $user_info['ali_user_id'];
                    $payee_name = '';
                    $payee_type = 'ALIPAY_USERID';
                }else{
                    $payee_account = $user_info['ali_number'];
                    $payee_name = $user_info['ali_name'];
                    $payee_type = 'ALIPAY_LOGONID';
                }
                $add_get['user_id'] = $data['parent'];
                $remark = '伊美小天鹅 - 下属用户开通团队获得返利';//备注
            }

            if(!$payee_type){$payee_type='ALIPAY_LOGONID';}
            if($payee_type=='ALIPAY_LOGONID'){
                if(!$payee_account){return false;}
                if(!$payee_name){return false;}
            }

            //城市合伙人返利
            if($data['type']=='13'){
                $user_info = M('user')->where(array('id'=>$data['parent']))->field('id,ali_number,ali_name,ali_user_id')->find();
                if($user_info['ali_user_id']){
                    $payee_account = $user_info['ali_user_id'];
                    $payee_name = '';
                    $payee_type = 'ALIPAY_USERID';
                }else{
                    $payee_account = $user_info['ali_number'];
                    $payee_name = $user_info['ali_name'];
                    $payee_type = 'ALIPAY_LOGONID';
                }
                $add_get['user_id'] = $data['parent'];
                $remark = '伊美小天鹅 - 城市合伙人返利';//备注
            }

            if(!$payee_type){$payee_type='ALIPAY_LOGONID';}
            if($payee_type=='ALIPAY_LOGONID'){
                if(!$payee_account){return false;}
                if(!$payee_name){return false;}
            }

            $out_biz_no = rand(10000,99999).time().'tx';
            $money = $data['money'];
            $add_get['type'] = $data['type'];
            $add_get['type_id'] = $data['type_id'];
            $get = M('get');

            $is_get = $get->where($add_get)->find();

            if ($is_get){
                $get_id  = $is_get['id'];

                if($is_get['status']=='1'){return false;}
            }else{
                $add_get['out_biz_no'] = $out_biz_no;
                $add_get['money'] = $money;

                $add_get['status'] = 0;
                $add_get['create_time'] = time();
                $get_id = $get->add($add_get);
            }
            if($money<=0){return false;}

            $aop = new \AopClient();
            $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
            $aop->appId =    C('alipay_config.appid');
            $aop->rsaPrivateKey  = C('alipay_config.rsaPrivateKey');
            $aop->alipayrsaPublicKey =  C('alipay_config.alipayrsaPublicKey');
            //$aop->alipayrsaPublicKey=C('alipay_config.alipayrsaPublicKey');
            $aop->signType = 'RSA2';
            $aop->apiVersion = '1.0';
            $aop->postCharset='UTF-8';
            $aop->format='json';
            $show_name= '伊美小天鹅';
            $request = new \AlipayFundTransToaccountTransferRequest ();
            $request->setBizContent("{" .
                "\"out_biz_no\":\"".$out_biz_no."\"," .
                "\"payee_type\":\"".$payee_type."\"," .
                "\"payee_account\":\"".$payee_account."\"," .
                "\"amount\":\"".$money."\"," .
                "\"payer_show_name\":\"".$show_name."\"," .
                "\"payee_real_name\":\"".$payee_name."\"," .
                "\"remark\":\"".$remark."\"" .
                "}");
            $result = $aop->execute ( $request);

            $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
            $resultCode = $result->$responseNode->code;

            if(!empty($resultCode)&&$resultCode == 10000){
                if($data['type']=='1'){
                    $set_db = M('hospital_order');
                    $save_order['is_get'] = 1;
                    $save_order['get_id'] = $get_id;
                    $save_order['get_time'] = time();
                    $save_order['update_time'] = time();
                }
                if($data['type']=='2'){
                    $set_db = M('shop_order');
                    $save_order['is_get'] = 1;
                    $save_order['get_id'] = $get_id;
                    $save_order['get_time'] = time();
                    $save_order['update_time'] = time();
                }
                if($data['type']=='3'){
                    $set_db = M('tui');
                    $save_order['status'] = 1;
                    $save_order['tui_time'] = time();
                    $save_order['update_time'] = time();
                    M('shop_order')->where(array('id'=>$data['tui_type_id']))->save(array('refund_status'=>'4','update_time'=>time()));
                }
                if($data['type']=='4'){
                    $set_db = M('tui');
                    $save_order['status'] = 1;
                    $save_order['tui_time'] = time();
                    $save_order['update_time'] = time();
                    M('ticket_user')->where(array('id'=>$data['tui_type_id']))->save(array('status'=>'3','update_time'=>time()));
                }

                if($data['type']=='5'){
                    $set_db = M('ticket_user');
                    $save_order['is_rebate'] = 1;
                    $save_order['rebate_time'] = time();
                    $save_order['update_time'] = time();
                }


                if($data['type']=='6'){
                    $set_db = M('task');
                    $save_order['is_get'] = 1;
                    $save_order['get_id'] = $get_id;
                    $save_order['get_time'] = time();
                    $save_order['update_time'] = time();
                }

                if($data['type']=='7'){

                    $set_db = M('tui');
                    $save_order['status'] = 1;
                    $save_order['tui_time'] = time();
                    $save_order['update_time'] = time();
                }

                if($data['type']=='8'){
                    $set_db = M('tui');
                    $save_order['status'] = 1;
                    $save_order['tui_time'] = time();
                    $save_order['update_time'] = time();
                }

                if($data['type']=='10'){
                    $set_db = M('ali_get');
                    $save_order['is_get'] = 1;
                    $save_order['get_time'] = time();
                    $save_order['update_time'] = time();
                }

                if($data['type']=='11'){
                    $set_db = M('team_money');
                    $save_order['is_get'] = 1;
                    $save_order['get_id'] = $get_id;
                    $save_order['get_time'] = time();
                    $save_order['update_time'] = time();
                }

                if($data['type']=='12'){
                    $set_db = M('tui_agent');
                    $save_order['status'] = 1;
                    $save_order['tui_time'] = time();
                    $save_order['update_time'] = time();
                }

                if($data['type']=='13'){
                    $set_db = M('city_rebate');
                    $save_order['status'] = 1;
                    $save_order['tui_time'] = time();
                    $save_order['update_time'] = time();
                }



                $set_db->where(array('id'=>$data['type_id']))->save($save_order);

                $save_get['status'] = 1;
                $save_get['data'] = json_encode($result->$responseNode);
                $save_get['update_time'] = time();
                $get->where(array('id'=>$get_id))->save($save_get);


                if($data['type']=='3'){
                    //添加消息通知
                    //新版
                    $tis = '商品已退款';
                    $add_system['type'] = 3;//1已发货，2交易成功，3退款成功，4退款失败,5订单价格修改
                    $add_system['user_id'] = $data['parent'];
                    $add_system['send_user_id'] = '';
                    $add_system['content'] = $tis;
                    $add_system['type_id'] = $data['tui_type_id'];
                    $add_system['status'] = 2;
                    $add_system['create_time'] = time();
                    M('system')->add($add_system);
                    $rong_user[] = $data['parent'];
                    SendRongSystem($rong_user,$tis);
                }

            } else {
                $add_tis['code'] = $resultCode;
                $add_tis['content'] = $result->$responseNode->sub_msg;
                $add_tis['sub_code'] = $result->$responseNode->sub_code;
                $add_tis['out_biz_no'] = $result->$responseNode->out_biz_no;
                $add_tis['status'] = 0;
                $add_tis['type'] = 2;
                $add_tis['type_id'] = $get_id;
                $add_tis['data'] = json_encode($result->$responseNode);
                $add_tis['create_time'] = time();
                  if($resultCode==40004){
                      $add_tis['type'] = 1;
                  }

                  $is_add = true;
                if($add_tis['sub_code']=='PAYEE_USER_INFO_ERROR'){
                    $add_tis['user_id'] = $user_info['id'];
                    $where_tis['user_id'] = $user_info['id'];
                    $where_tis['sub_code'] = 'PAYEE_USER_INFO_ERROR';
                    $where_tis['status'] = 0;
                    $is_have2 = M('tix_message')->where($where_tis)->field('id')->find();
                    if($is_have2){
                        $is_add = false;
                    }
                }
                if($is_add){
                    M('tix_message')->add($add_tis);
                    if($add_tis['sub_code']=='PAYER_BALANCE_NOT_ENOUGH'){
                        M('set')->where(array('set_type'=>'tix_status'))->save(array('set_value'=>'0','update_time'=>time()));
                    }
                }
            }
        }
        return true;
    }




}