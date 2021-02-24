<?php
namespace Home\Controller;
use Think\Controller;
//微信支付类
class WxpayController extends Controller {

    public function _initialize(){
        vendor('Weixinpay.WxPayPubHelper');
    }
    //获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
    public function js_api_call() {
        //使用jsapi接口
        $jsApi = new \JsApi_pub();
        //=========步骤1：网页授权获取用户openid============
        //通过code获得openid


        $token = decrypt1(trim(I('post.token')));
        $type = decrypt1(trim(I('post.type')));//支付类型：1支付医院，2支付商城订单，3优惠券，4团队费用，5支付红包费用,6支付城市合伙人费用
        $type_id = decrypt1(trim(I('post.type_id')));
        $have_id = decrypt1(trim(I('post.have_id')));
        $score = decrypt1(trim(I('post.score')));

        $remark = decrypt1(trim(I('post.remark')));
        $pay_type = decrypt1(trim(I('post.pay_type')));//支付类型：1支付全款，2支付预定金,3支付剩余金额



        $list = payData(1,'APP',$token,$type,$type_id,$pay_type,$have_id,$score,$remark,1);
        if($list['code']!=1){
            $arr = array(
                'code' => $list['code'],
                'res' => $list['res'],
            );
            $data = json_encode($arr);
            $return['encryption'] =  encrypt1($data);
            $this->ajaxReturn($return,"JSON");
        }



        $order = $list['order'];
        $body = $list['body'];
        $total_fee = $list['money']*100;
        $out_trade_no = $order;
        //=========步骤2：使用统一支付接口，获取prepay_id============
        //使用统一支付接口
        $unifiedOrder = new \UnifiedOrder_pub();

        $unifiedOrder->setParameter("body", $body);//商品描述



        $unifiedOrder->setParameter("out_trade_no", $out_trade_no);//商户订单号
        $unifiedOrder->setParameter("total_fee", $total_fee);//总金额
        //$unifiedOrder->setParameter("attach", "order_sn={$res['order_sn']}");//附加数据
        $unifiedOrder->setParameter("notify_url", \WxPayConf_pub::NOTIFY_URL);//通知地址 dump($unifiedOrder);die;
        $unifiedOrder->setParameter("trade_type", "APP");//交易类型
        $unifiedOrder->setParameter("product_id",$out_trade_no);//商品ID



        $prepay_id = $unifiedOrder->getPrepayId();

        if(!$prepay_id){

            $arr = array(
                'code' => 0,
                'res' => '参数错误',
            );
            $data = json_encode($arr);
            $return['encryption'] =  encrypt1($data);
            $this->ajaxReturn($return,"JSON");
        }

        //=========步骤3：使用jsapi调起支付============
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters();

        $wxconf = json_decode($jsApiParameters, true);

        $arr = array(
            'code' => 1,
            'res' => '成功',
            'orderInfo' => $wxconf,

        );


        $data = json_encode($arr);
        $return['encryption'] =  encrypt1($data);
        $this->ajaxReturn($return,"JSON");
    }




    //异步通知url，商户根据实际开发过程设定
    public function notify_url() {
        vendor('Weixinpay.WxPayPubHelper');
        //使用通用通知接口
        $notify = new \Notify_pub();
        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);
        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code", "FAIL");//返回状态码
            $notify->setReturnParameter("return_msg", "签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code", "SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======
        //以log文件形式记录回调信息
        //$log_name = "notify_url.log";//log文件路径
        //$this->log_result($log_name, "【接收到的notify通知】:\n".$xml."\n");
        $parameter = $notify->xmlToArray($xml);
        //$this->log_result($log_name, "【接收到的notify通知】:\n".$parameter."\n");
        if($notify->checkSign() == TRUE){
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                //$this->log_result($log_name, "【通信出错】:\n".$xml."\n");
                //更新订单数据【通信出错】设为无效订单
                echo 'error';
            }
            else if($notify->data["result_code"] == "FAIL"){
                //此处应该更新一下订单状态，商户自行增删操作
                //$this->log_result($log_name, "【业务出错】:\n".$xml."\n");
                //更新订单数据【通信出错】设为无效订单
                echo 'error';
            }
            else{
                //$this->log_result($log_name, "【支付成功】:\n".$xml."\n");
                //我这里用到一个process方法，成功返回数据后处理，返回地数据具体可以参考微信的文档
                if ($this->process($parameter)) {
                    $msg = (array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

                    $pay_info = M('pay')->where(array('out_trade_no'=>$msg['out_trade_no']))->find();
                    if($pay_info['trade_status']=='SUCCESS'){
                        echo 'success';die;
                    }

                    //处理成功后输出success，微信就不会再下发请求了
                    $save['trade_no']      = $msg['transaction_id'];
                    $save['notify_id']        = $msg['nonce_str'];
                    $save['notify_time']        = $msg['time_end'];
                    $save['buyer_id']     = $msg['openid'];
                    $save['total_amount']     = $msg['total_fee'];
                    $save['buyer_pay_amount']     = $msg['total_fee'];
                    $save['trade_status']  = $msg['result_code'];
                    $save['update_time']        = time();
                    $query = M('pay')->where(array('out_trade_no'=>$msg['out_trade_no']))->save($save);
                    if(!$query){
                        echo 'error';die;
                    }

                    payNotify($msg['out_trade_no']);

                    echo 'success';
                }else {
                    //没有处理成功，微信会间隔的发送请求
                    echo 'error';
                }
            }
        }
    }
    //订单处理
    private function process($parameter) {
        //此处应该更新一下订单状态，商户自行增删操作
        /*
        * 返回的数据最少有以下几个
        * $parameter = array(
            'out_trade_no' => xxx,//商户订单号
            'total_fee' => XXXX,//支付金额
            'openid' => XXxxx,//付款的用户ID
        );
        */
        return true;
    }
}
?>