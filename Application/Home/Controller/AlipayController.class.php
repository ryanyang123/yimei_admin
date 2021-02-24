<?php
namespace Home\Controller;
use Think\Controller;
class AlipayController extends Controller
{
    public function _initialize() {
        vendor('appalipay.AopSdk');// 加载类库
    }

    public function create(){
        $token = decrypt1(trim(I('post.token')));
        $type = decrypt1(trim(I('post.type')));//支付类型：1支付医院，2支付商城订单，3优惠券，4团队费用,5支付红包,6支付城市合伙人费用
        $type_id = decrypt1(trim(I('post.type_id')));
        $have_id = decrypt1(trim(I('post.have_id')));
        $score = decrypt1(trim(I('post.score')));

        $remark = decrypt1(trim(I('post.remark')));
        $pay_type = decrypt1(trim(I('post.pay_type')));//支付类型：1支付全款，2支付预定金,3支付剩余金额

       /* $token = 'bf8739cb479fa5d17c98dfbb7beb1452';
        $type = 2;
        $type_id = 3351;
        $pay_type = 1;*/

        $list = payData(2,'APP',$token,$type,$type_id,$pay_type,$have_id,$score,$remark,1);
        if($list['code']!=1){
            $arr = array(
                'code' => $list['code'],
                'res' => $list['res'],
            );
            $data = json_encode($arr);
            $return['encryption'] =  encrypt1($data);
            $this->ajaxReturn($return,"JSON");
        }
//构造业务请求参数的集合(订单信息)

        $body = $list['body'];
        $title = $list['title'];
        $price = $list['money'];

        //$price = 0.01;
        $order = $list['order'];

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

        if($str){
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
                $pay_info = M('pay')->where(array('out_trade_no'=>$_POST['out_trade_no']))->find();
                if($pay_info['trade_status']=='TRADE_FINISHED' || $pay_info['trade_status']=='TRADE_SUCCESS'){
                    echo "response fail";die;
                }

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
                payNotify($_POST['out_trade_no']);


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