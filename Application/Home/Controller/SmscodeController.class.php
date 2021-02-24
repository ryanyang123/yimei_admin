<?php
namespace Home\Controller;
use Think\Controller;
class SmscodeController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'smscode'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $sms_code = M('sms_code');
        $user = M('user');


    /*    $uid = "2255";
        $passwd = "7fdf6096659ff66beaba43046368a020";
        $url2 = "http://sms.bamikeji.com:8890/mtPort/account/info?uid=".$uid."&passwd=".$passwd;
        $html = file_get_contents($url2);
        $result1 = json_decode($html,true);
        $sms_num = $result1['availableAmt']-80000;*/



        $params2['account'] = 'N4419285';
        $params2['password'] = 'js8S0k5BD';
        $params2 = json_encode($params2);
        $url2 = 'http://smssh1.253.com/msg/balance/json';
        $con2 = SendPost($url2,$params2);
        $result2 = json_decode($con2,true);
        $sms_num = $result2['balance'];

      /*  $flag = 0;
        $params='';//要post的数据
        //以下信息自己填以下
        //$mobile='14718171817';//手机号
        $argv = array(
            'accesskey'=>'sCBaw5Nb6vBrkwQg',     //必填参数。用户账号
            'secret'=>'np6PwL4sx2iSRmL5yAXtL3gch3mo0ZE7',     //必填参数。（web平台：基本资料中的接口密码）
        );*/
        //print_r($argv);exit;
        //构造要post的字符串
        //echo $argv['content'];
        /*foreach ($argv as $key=>$value) {
            if ($flag!=0) {
                $params .= "&";
                $flag = 1;
            }
            $params.= $key."="; $params.= urlencode($value);// urlencode($value);
            $flag = 1;
        }*/
        //$url = "http://api.1cloudsp.com/query/account?".$params; //提交的url地址
        $params['account'] = 'M9103274';
        $params['password'] = 'XYJp1barm';
        $params = json_encode($params);
        $url = 'http://smssh1.253.com/msg/balance/json';
        $con = SendPost($url,$params);
        $result = json_decode($con,true);
        $sms_num_new = $result['balance'];



        //$where['is_show'] = 1;
        $count = $sms_code->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条群发记录</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $sms_code->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            if ($item['user_id']=='0'){
                $list[$key]['user_name'] = '全部用户';
            }else{
                $user_name = $user->where(array('id'=>$item['user_id']))->field('name')->find();
                $list[$key]['user_name'] = $user_name['name'];
            }

        }
        //$sms_num = M('set')->where(array('set_type'=>'sms_code_num'))->find();
        //$sms_num_new = M('set')->where(array('set_type'=>'sms_code_num_new'))->find();
        $data = array(
            'list' =>$list,
            'page' =>$show,
            'sms_num' =>$sms_num,
            'sms_num_new' =>$sms_num_new,
            'at' =>'smscode',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $sms_code = M('sms_code');

        if($is_edit){
            $list = $sms_code->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'smscode',
            'title'=>'群发短信',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($content,$type,$user_id,$is_edit){

        $time = date('H');

        //if($time>=18 || $time<9){$arr = array('code'=> 0, 'res'=>'发送时段只允许 9：00 至 18：00');$this->ajaxReturn($arr,"JSON");}
        $sms_code = M('sms_code');
        $user = M('user');

        if(!$content){$arr = array('code'=> 0, 'res'=>'请输入短信内容');$this->ajaxReturn($arr,"JSON");}
        if($type=='3'){
            if (!$user_id){
                $arr = array('code'=> 0, 'res'=>'请输入用户ID');$this->ajaxReturn($arr,"JSON");
            }else{
                $user_info = $user->where(array('id'=>$user_id))->field('id')->find();
                if (!$user_info){
                    $arr = array('code'=> 0, 'res'=>'用户ID错误');$this->ajaxReturn($arr,"JSON");
                }
            }
        }



        if (!$user_id){
            $user_id = 0;
        }
        if($type=='1'){
            $send_user = $user->where(array('is_freeze'=>'1'))->field('id,phone')->select();

        }
        if($type=='2'){
            $send_user = $user->where(array('is_freeze'=>'1','is_agent'=>'1'))->field('id,phone')->select();
        }

        if($type=='3'){
            $send_user = $user->where(array('id'=>$user_id))->field('id,phone')->select();
        }

        $send_num = count($send_user);
        if($is_edit){
          /*  $save['type'] = $type;
            $save['user_id'] = $user_id;
            $save['title'] = $title;
            $save['content'] = $content;
            $save['update_time'] = time();
            $query = $notice->where(array('id'=>$is_edit))->save($save);
            $res = '修改';*/
        }else{
            $add['type'] = $type;
            $add['user_id'] = $user_id;
            $add['send_num'] = $send_num;
            $add['content'] = $content;
            $add['create_time'] = time();
            $query = $sms_code->add($add);
            $res = '发送';
        }

        if($query){


      /*
            $uid = "2561";
            $passwd = md5("bm1911152929");*/
            $i = 0;
            $num = 900;
            $zong_num = count($send_user);

            if($zong_num>$num){
                $page = ceil(($zong_num/$num));
                for($j=1;$j<=$page;$j++){
                    $send_arr = array();
                    $params = array();
                    $user_list = array();
                    $phone_val = '';
                    $start = ($j-1)*$num;
                    $user_list = $user->where(array('is_freeze'=>'1'))->limit($start,$num)->field('id,phone')->select();

                    foreach ($user_list as $item){
                        $send_arr[] = $item['phone'];
                    }
                    $phone_val = implode(',',$send_arr);

                    $flag = 0;
                    //$params='';//要post的数据
                    //以下信息自己填以下
                    //$mobile='14718171817';//手机号
                  /*  $argv = array(
                        'accesskey'=>'sCBaw5Nb6vBrkwQg',     //必填参数。用户账号
                        'secret'=>'np6PwL4sx2iSRmL5yAXtL3gch3mo0ZE7',     //必填参数。（web平台：基本资料中的接口密码）
                        'sign'=>'【伊美小天鹅】',   //必填参数。发送内容（1-500 个汉字）UTF-8编码
                        'mobile'=>$phone_val,   //必填参数。手机号码。多个以英文逗号隔开
                        'content'=>$content,   //必填参数。发送内容（1-500 个汉字）UTF-8编码
                    );*/
                    //print_r($argv);exit;
                    //构造要post的字符串
                    //echo $argv['content'];

                 /*   foreach ($argv as $key=>$value) {
                        if ($flag!=0) {
                            $params .= "&";
                            $flag = 1;
                        }
                        $params.= $key."="; $params.= urlencode($value);// urlencode($value);
                        $flag = 1;
                    }*/
                    //$con = SendPost('http://api.1cloudsp.com/api/v2/send',$params);
                    //$url = "http://api.1cloudsp.com/api/v2/send?".$params; //提交的url地址
                    //$con= file_get_contents($url); //获取信息发送后的状态
                    //$result = json_decode($con,true);

                    $params['account'] = 'M9103274';
                    $params['password'] = 'XYJp1barm';
                    $params['msg'] = $content;
                    $params['phone'] = $phone_val;
                    $params['sendtime'] = (String)date('YmdHi');
                    $params['report'] ='true';
                    $params['extend'] ='243323';
                    $params['uid'] = (String)time();

                    $params = json_encode($params);
                    //dump($params);die;
                    $url = 'http://smssh1.253.com/msg/send/json';

                    //$con = SendPost('http://api.1cloudsp.com/api/v2/send',$params);
                    $con = SendPost($url,$params);

                    //$url = "http://api.1cloudsp.com/api/v2/send?".$params; //提交的url地址
                    //$con= file_get_contents($url);
                    $result = json_decode($con,true);
                    $fan[] = $result;
                    if($result['code'] == '0'){
                        $i += count($user_list);
                    }



                  /*  $url = "http://sms.bamikeji.com:8890/mtPort/mt/normal/send?uid=".$uid."&passwd=".$passwd."&phonelist=".$phone_val."&content=".$content;
                    $html = file_get_contents($url);
                    $result = json_decode($html,true);
                    if($result['code'] == 0){
                        $i += count($user_list);
                    }*/
                }

            }else{
                foreach ($send_user as $item){
                    $send_arr[] = $item['phone'];
                }
                $phone_val = implode(',',$send_arr);
                //$phone_val = '17512058030,13066817617';

                $flag = 0;
                $params='';//要post的数据
                //以下信息自己填以下
                //$mobile='14718171817';//手机号
             /*   $argv = array(
                    'accesskey'=>'sCBaw5Nb6vBrkwQg',     //必填参数。用户账号
                    'secret'=>'np6PwL4sx2iSRmL5yAXtL3gch3mo0ZE7',     //必填参数。（web平台：基本资料中的接口密码）
                    'sign'=>'【伊美小天鹅】',   //必填参数。发送内容（1-500 个汉字）UTF-8编码
                    'mobile'=>$phone_val,   //必填参数。手机号码。多个以英文逗号隔开
                    'content'=>$content,   //必填参数。发送内容（1-500 个汉字）UTF-8编码
                );*/


                //print_r($argv);exit;
                //构造要post的字符串
                //echo $argv['content'];
               /* foreach ($argv as $key=>$value) {
                    if ($flag!=0) {
                        $params .= "&";
                        $flag = 1;
                    }
                    $params.= $key."="; $params.= urlencode($value);// urlencode($value);
                    $flag = 1;
                }*/
                $params['account'] = 'M9103274';
                $params['password'] = 'XYJp1barm';
                $params['msg'] = $content;
                $params['phone'] = $phone_val;
                $params['sendtime'] = (String)date('YmdHi');
                $params['report'] ='true';
                $params['extend'] ='243323';
                $params['uid'] = (String)time();

                $params = json_encode($params);
                //dump($params);die;
                $url = 'http://smssh1.253.com/msg/send/json';

                //$con = SendPost('http://api.1cloudsp.com/api/v2/send',$params);
                $con = SendPost($url,$params);

                //$url = "http://api.1cloudsp.com/api/v2/send?".$params; //提交的url地址
                //$con= file_get_contents($url);
                $result = json_decode($con,true);

                if($result['code'] == 0){
                    $i+= $zong_num;
                }
               /* $url = "http://sms.bamikeji.com:8890/mtPort/mt/normal/send?uid=".$uid."&passwd=".$passwd."&phonelist=".$phone_val."&content=".$content;
                $html = file_get_contents($url);
                $result = json_decode($html,true);
                if($result['code'] == 0){
                    $i+= $zong_num;
                }*/
            }


            $sms_code->where(array('id'=>$query))->save(array('success_num'=>$i,'update_time'=>time()));
            M('set')->where(array('set_type'=>'sms_code_num_new'))->setDec('set_value',$i);
            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功'
            );
            $this->ajaxReturn($arr,"JSON");
        }else{
            $arr = array(
                'code'=> 0,
                'res'=> $res.'失败'
            );
            $this->ajaxReturn($arr,"JSON");
        }
    }


}












