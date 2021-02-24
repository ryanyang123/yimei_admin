<?php
namespace Home\Controller;
use Think\Controller;
vendor('Ys.demo');

class YsdfController extends Controller {



    function create(){
        $Ys = new \demo();
        $user = M('user');
        $user_bank = M('user_bank');
        //$a = $Ys->get_money();
        //dump($a);die;
        $token = decrypt1(trim(I('post.token')));
        $money = decrypt1(trim(I('post.money')));
        $pass = decrypt1(trim(I('post.pass')));
        $op = md5(md5($pass));
        //$token = '51a14fd5f628ef33f3c3cff254d7fa43';
        //$money = '1';
        $bank_name = decrypt1(trim(I('post.bank_name')));//银行名称，为了保证代付交易成功，银行名称最好具体到分行，eg：中国银行深圳民治支行
        $bank_city = decrypt1(trim(I('post.bank_city')));//开户行所在城市，eg：深圳市
        $bank_account_no = decrypt1(trim(I('post.bank_account_no')));//银行账户
        $bank_account_name = decrypt1(trim(I('post.bank_account_name')));//银行帐号用户名
        $bank_account_type = decrypt1(trim(I('post.bank_account_type')));//收款方银行账户类型，此处必填 corporate :对公账户;personal:对私账户
        $bank_card_type = decrypt1(trim(I('post.bank_card_type')));//支持卡类型，此处必填 debit:借记卡;credit:信用卡 unit:单位结算卡
        $type = decrypt1(trim(I('post.type')));//1手动输入，2使用已记录银行卡信息
        $bank_id = decrypt1(trim(I('post.bank_id')));//1手动输入，2使用已记录银行卡信息

        if (!$type){$type='1';}
        //$bank_name = '深圳市布吉支行';
        //$bank_city = '深圳市';
        //$bank_account_no = '6217582000005354865';
        //$bank_account_name = '凌永炫';

        $user_info = $user->where(array('token' => $token))->find();
        if (!$user_info) {$arr = array('code' => 2001, 'res' => 'token错误');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");}
        if ($user_info['is_freeze'] == '0') {$arr = array('code' => 0, 'res' => '您的帐号已被禁止登陆，如有疑问，请联系工作人员');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");}

        if (!$user_info['pay_pass']){
            $arr = array('code' => 0, 'res' => '您还没有设置交易密码');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");
        }

        if ($op!=$user_info['pay_pass']){
            $arr = array('code' => 0, 'res' => '交易密码错误');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");
        }

        if (!is_numeric($money)){
            $arr = array('code' => 0, 'res' => '只能提现整数金额');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");
        }
        if ($money<100){
            $arr = array('code' => 0, 'res' => '最低提现100元起');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");
        }
        if($user_info['money']<$money){
            $arr = array('code' => 0, 'res' => '您的余额不足');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");
        }

        if ($type=='1'){
            if (!$bank_name){
                $arr = array('code' => 0, 'res' => '请输入银行分行名称');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");
            }
            if (!$bank_city){
                $arr = array('code' => 0, 'res' => '请选择开户行所在城市');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");
            }
            if (!$bank_account_no){
                $arr = array('code' => 0, 'res' => '请输入银行账户');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");
            }
            if (!$bank_account_name){
                $arr = array('code' => 0, 'res' => '请输入银行账户用户名');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");
            }
            if (!$bank_card_type){$bank_card_type='debit';}
            if (!$bank_account_type){$bank_account_type='personal';}

            $is_have = $user_bank->where(array('id'=>$bank_id))->find();
            if ($is_have && $user_info['id'] == $is_have['user_id']){
                $save_bank['bank_name'] = $bank_name;
                $save_bank['bank_city'] = $bank_city;
                $save_bank['bank_account_name'] = $bank_account_name;
                $save_bank['bank_card_type'] = $bank_card_type;
                $save_bank['bank_account_type'] = $bank_account_type;
                $save_bank['update_time'] = time();
                $user_bank->where(array('id'=>$is_have['id']))->save($save_bank);

            }else{
                $add_bank['user_id'] = $user_info['id'];
                $add_bank['bank_name'] = $bank_name;
                $add_bank['bank_city'] = $bank_city;
                $add_bank['bank_account_no'] = $bank_account_no;
                $add_bank['bank_account_name'] = $bank_account_name;
                $add_bank['bank_card_type'] = $bank_card_type;
                $add_bank['bank_account_type'] = $bank_account_type;
                $add_bank['create_time'] = time();
                $is_add_bank = $user_bank->add($add_bank);
                if ($is_add_bank){
                    $user->where(array('id'=>$user_info['id']))->save(array('bank_at'=>'1','update_time'=>time()));
                }


            }


        }else{

            if (!$bank_id){
                $arr = array('code' => 0, 'res' => '请传入银行卡ID');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");
            }
            $bank_info = $user_bank->where(array('id'=>$bank_id))->find();
            if (!$bank_info || $user_info['id'] != $bank_info['user_id']){
                $arr = array('code' => 0, 'res' => '银行卡ID错误');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");
            }
            $bank_name = $bank_info['bank_name'];
            $bank_city = $bank_info['bank_city'];
            $bank_account_no = $bank_info['bank_account_no'];
            $bank_account_name = $bank_info['bank_account_name'];
            $bank_card_type = $bank_info['bank_card_type'];
            $bank_account_type = $bank_info['bank_account_type'];

        }
        //$money = '1';





        $new_money = $user_info['money'] - $money;
        $query = $user->where(array('id'=>$user_info['id']))->save(array('money'=>$new_money,'update_time'=>time()));
        if ($query){
            $subject = '小时光提现-'.$money.'元';
            $oder = $Ys->datetime2string(date('Y-m-d H:i:s')).$user_info['id'];
            $df_info = array(
                "out_trade_no" => $oder,
                "total_amount" => $money,
                "subject" => $subject,
                "bank_name" => $bank_name,
                "bank_city" => $bank_city,
                "bank_account_no" => $bank_account_no,
                "bank_account_name" => $bank_account_name,
                "bank_account_type" => $bank_account_type,
                "bank_card_type" => $bank_card_type
            );

            $add_tx['user_id'] = $user_info['id'];
            $add_tx['out_trade_no'] = $oder;
            $add_tx['total_amount'] = $money;
            $add_tx['subject'] = $subject;
            $add_tx['bank_name'] = $bank_name;
            $add_tx['bank_city'] = $bank_city;
            $add_tx['bank_account_no'] = $bank_account_no;
            $add_tx['bank_account_name'] = $bank_account_name;
            $add_tx['bank_account_type'] = $bank_account_type;
            $add_tx['bank_card_type'] = $bank_card_type;
            $add_tx['type'] = 3;//1支付宝，2微信，3银联
            $add_tx['create_time'] = time();
            $is_add = M('tixian')->add($add_tx);
            if ($is_add){
                $t = $Ys->curl_https_df($Ys->get_dfjj($df_info));
                if ($t){
                    $arr = array(
                        'code' => 1,
                        'res' => '申请成功',
                    );
                }else{
                    $user->where(array('id'=>$user_info['id']))->save(array('money'=>$user_info['money'],'update_time'=>time()));
                    $arr = array(
                        'code' => 0,
                        'res' => '申请失败',
                    );
                }
            }else{
                $user->where(array('id'=>$user_info['id']))->save(array('money'=>$user_info['money'],'update_time'=>time()));
                $arr = array(
                    'code' => 0,
                    'res' => '申请失败',
                );
            }



        }else{
            $arr = array(
                'code' => 0,
                'res' => '申请失败',
            );

        }
        $data = json_encode($arr);
        $return['encryption'] =  encrypt1($data);
        $this->ajaxReturn($return,"JSON");

    }
    function test(){
        $Ys = new \demo();

        //返回的数据处理
        @$sign = trim($_POST['sign']);
        $result = $_POST;
        unset($result['sign']);
        ksort($result);
        $url = "";
        foreach ($result as $key => $val) {
            if ($val) $url .= $key . '=' . $val . '&';
        }
        $data = trim($url, '&');
        /* 验证签名 仅作基础验证*/
        /*写入日志*/
        //$file = BASE_PATH . "log/notify.txt";
        if ($Ys->sign_check($sign, $data)==true) {


            $out_trade_no = $result['out_trade_no'];
            $trade_no = $result['trade_no'];
            $trade_status = $result['trade_status'];
            $save_tx['return_data'] =  json_encode($result);;
            $save_tx['return_time'] = time();
            $save_tx['trade_no'] = $trade_no;
            $save_tx['trade_status'] = $trade_status;
            M('tixian')->where(array('out_trade_no'=>$out_trade_no))->save($save_tx);
            $tx_info =  M('tixian')->where(array('out_trade_no'=>$out_trade_no))->find();
            if ($trade_status=='TRADE_SUCCESS'){
                //金额交易记录
                $add_money['user_id'] = $tx_info['user_id'];
                $add_money['money'] = $tx_info['total_amount'];
                $add_money['type'] = 2;
                $add_money['add_or_del'] = 2;//1增加，2减去
                $add_money['pay_type'] = 3;//1微信，2支付宝，3银联，4余额
                $add_money['create_time'] = time();
                $add_money['out_trade_no'] = $out_trade_no;
                $add_money['create_type'] = 3;//1管理员操作，2系统操作，3用户操作，4业务员操作
                M('money_log')->add($add_money);
                //金额交易记录
            }else{
                $user_money = M('user')->where(array('id'=>$tx_info['user_id']))->field('money')->find();
                $new_money = $user_money['money'] + $tx_info['total_amount'];
                M('user')->where(array('id'=>$tx_info['user_id']))->save(array('money'=>$new_money,'update_time'=>time()));
            }

        } else {

        }



        echo 'success';
        exit;

    }
}
?>