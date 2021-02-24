<?php
class Wap{
    private $param = array(
        'mch_id'=>124,//商户号
        'app_id'=>10013,//应用编号
        'out_trade_no'=>'',//订单号
        'money'=>1,//金额，分为单位
        'time'=>'',//当前时间戳
        'body'=>'商品购买',//订单说明
        'attach'=>'',
        'notify_url'=>'http://120.78.208.218/sellcard/home/ajax/wrnotify',//异步通知地址
        'redirect_url'=>'http://120.78.208.218/sellcard_user2'//同步回调地址
    );

    private $url = 'http://h5pay.zskdgame.com/pay/pay/index.php';//跳转地址
    private $key = 'DQa9rXyVzodq8FoVec2S6pYqHQG85ooM';//应用秘钥

    public function __construct($body,$hui_url)
    {
        $this->param['body'] = $body;
        $this->param['redirect_url'] = $hui_url;
        $this->param['time'] = time();
    }

    /**
     * 下单方法
     * @param $out_trade_no
     * @param $money
     */
    public function unifiedOrder($out_trade_no,$money){
        $this->param['out_trade_no'] = $out_trade_no;
        $this->param['money'] = $money;
        $data = $this->param;
        $data['sign'] = $this->generateSignature($this->param,$this->key);
        $this->log($data);
        $str = http_build_query($data);
       /* header("Location:{$this->url}?$str");*/
        return "{$this->url}?$str";
        exit;
    }


    public function test(){
        return '123213';
    }

    /**
     * 回调方法
     * @param $data
     * @return bool
     */
    public function notify_url($data){
        $this->log($data);
        if($data['sign'] == $this->generateSignature($data,$this->key)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 加密
     * @param $data
     * @param $key
     * @param string $signType
     * @return string
     * @throws Exception
     */
    private function generateSignature($data,$key,$signType = 'MD5')
    {
        $combineStr = '';
        $keys = array_keys($data);
        asort($keys);  // 排序
        foreach ($keys as $k) {
            $v = $data[$k];
            if ($k == 'sign') {
                continue;
            } elseif ((is_string($v) && strlen($v) > 0) || is_numeric($v)) {
                $combineStr = "${combineStr}${k}=${v}&";
            } elseif (is_string($v) && strlen($v) == 0) {
                continue;
            } else {
                throw new \Exception('Invalid data, cannot generate signature: ' . json_encode($data));
            }
        }
        $combineStr = "${combineStr}key=${key}";
        if ($signType === 'MD5'){
            return strtoupper(md5($combineStr));
        }else{
            throw new \Exception('Invalid sign_type: ' . $signType);
        }
    }

    /**
     * 日志
     * @param $data
     */
    private function log($data){
        file_put_contents('log.txt',date ( "Y-m-d H:i:s" ) .var_export($data,true) . "\r\n", FILE_APPEND);
    }
}