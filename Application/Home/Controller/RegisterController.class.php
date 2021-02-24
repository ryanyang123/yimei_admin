<?php
namespace Home\Controller;
use Think\Controller;
class RegisterController extends Controller
{
    public function index(){


        $this->display();
    }
    public function pass(){


        $this->display();
    }

    function add($name,$phone,$pass1,$code,$img,$bao){

        $verify = M('verify');
        $app = M('app');
        if(!$img){$arr = array('code'=> 0, 'res'=>'请上传APP的logo');$this->ajaxReturn($arr,"JSON");}
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入游戏名称');$this->ajaxReturn($arr,"JSON");}
        if(!$phone){$arr = array('code'=> 0, 'res'=>'请输入手机号码');$this->ajaxReturn($arr,"JSON");}
        if(!$code){$arr = array('code'=> 0, 'res'=>'请输入邀请码');$this->ajaxReturn($arr,"JSON");}
        if(!$pass1){$arr = array('code'=> 0, 'res'=>'请输入密码');$this->ajaxReturn($arr,"JSON");}
        if(!$bao){$arr = array('code'=> 0, 'res'=>'请输入安卓包名');$this->ajaxReturn($arr,"JSON");}

        $is_reg = $app->where(array('phone'=>$phone))->find();
        if($is_reg){
            $arr = array(
                'code'=> 0,
                'res'=>'该手机号码已注册'
            );
            $this->ajaxReturn($arr,"JSON");
        }


        $where_code['code'] = $code;
        $where_code['phone'] = $phone;
        $where_code['type'] = 3;
        $is_code = $verify->where($where_code)->order('create_time desc')->find();
        if(!$is_code){
            $arr = array(
                'code'=> 0,
                'res'=>'验证码错误'
            );
            $this->ajaxReturn($arr,"JSON");
        }


        if(strlen($pass1)>16 || strlen($pass1)<6){
            $arr = array(
                'code'=> 0,
                'res'=>'密码长度为6-16'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        //判断输入非法
        if(preg_match("/[\',:;*?？！~`!$%^&=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$pass1)){  //不允许特殊字符
            $arr = array(
                'code'=> 0,
                'res'=>'密码由字母、数字、下划线或@、#组成'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        $add['key'] = getRandomString(8);
        $add['name'] = $name;
        $add['phone'] = $phone;
        $add['logo'] = $img;
        $add['bao'] = $bao;
        $add['pass'] = md5($pass1);
        $add['create_time'] = time();
        $query = $app->add($add);
        if ($query){
            $arr = array(
                'code'=> 1,
                'res'=>'注册成功'
            );
        }else{

            $arr = array(
                'code'=> 0,
                'res'=>'注册失败'
            );
        }
        $this->ajaxReturn($arr,"JSON");

    }


    function edit($phone,$pass1,$code){

        $verify = M('verify');
        $app = M('app');
        if(!$phone){$arr = array('code'=> 0, 'res'=>'请输入手机号码');$this->ajaxReturn($arr,"JSON");}
        if(!$code){$arr = array('code'=> 0, 'res'=>'请输入邀请码');$this->ajaxReturn($arr,"JSON");}
        if(!$pass1){$arr = array('code'=> 0, 'res'=>'请输入密码');$this->ajaxReturn($arr,"JSON");}

        $is_reg = $app->where(array('phone'=>$phone))->find();
        if(!$is_reg){
            $arr = array(
                'code'=> 0,
                'res'=>'该手机号码未注册'
            );
            $this->ajaxReturn($arr,"JSON");
        }


        $where_code['code'] = $code;
        $where_code['phone'] = $phone;
        $where_code['type'] = 4;
        $is_code = $verify->where($where_code)->order('create_time desc')->find();
        if(!$is_code){
            $arr = array(
                'code'=> 0,
                'res'=>'验证码错误'
            );
            $this->ajaxReturn($arr,"JSON");
        }


        if(strlen($pass1)>16 || strlen($pass1)<6){
            $arr = array(
                'code'=> 0,
                'res'=>'密码长度为6-16'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        //判断输入非法
        if(preg_match("/[\',:;*?？！~`!$%^&=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$pass1)){  //不允许特殊字符
            $arr = array(
                'code'=> 0,
                'res'=>'密码由字母、数字、下划线或@、#组成'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        $save['pass'] = md5($pass1);
        $save['update_time'] = time();
        $query = $app->where(array('id'=>$is_reg['id']))->save($save);
        if ($query){
            $arr = array(
                'code'=> 1,
                'res'=>'修改成功'
            );
        }else{
            $arr = array(
                'code'=> 0,
                'res'=>'修改失败'
            );
        }
        $this->ajaxReturn($arr,"JSON");

    }


    /**
     * 注册发送验证码
     */
    public function sendcode($phone){
        DeleteOldCode();
        //接收数据
        if(!$phone){
            $arr = array(
                'code'=> 0,
                'res'=> '请输入手机号'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        if(strlen($phone)!=11){
            $arr = array(
                'code'=> 0,
                'res'=> '手机号码格式错误'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        //判断输入非法
        if(preg_match("/[\',:;*?？！~`!#$%^&=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$phone)){  //不允许特殊字符
            $arr = array(
                'code'=> 0,
                'res'=>'手机号码格式错误'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        //实例化数据表
        $app = M('app');
        $verify = M('verify');


        $is_reg = $app->where(array('phone'=>$phone))->find();
        if($is_reg){
            $arr = array(
                'code'=> 0,
                'res'=>'该手机号已注册'
            );
            $this->ajaxReturn($arr,"JSON");
        }else{

            $rand = rand(100000,999999);
            //$rand =123456;

            $uid = "2255";
            $passwd = "7fdf6096659ff66beaba43046368a020";
            $content = "您的验证码为：".$rand."，为了保护您的账户安全，验证码请勿转发他人，有效时间15分钟。【小时光】";
            $url = "http://sms.bamikeji.com:8890/mtPort/mt/normal/send?uid=".$uid."&passwd=".$passwd."&phonelist=".$phone."&content=".$content;
            $html = file_get_contents($url);
            $result = json_decode($html,true);
            //$con= substr( file_get_contents($url), 0, 1 );  //获取信息发送后的状态
            //$result['code'] = 0;
            if($result['code'] == 0){
                $where['phone'] = $phone;
                $where['type'] = 3;
                $is_code = $verify->where($where)->find();
                if($is_code){
                    $save['code'] = $rand;
                    $save['create_time'] = time();
                    $verify->where(array('id'=>$is_code['id']))->save($save);
                }else{
                    $add['code'] = $rand;
                    $add['phone'] = $phone;
                    $add['type'] = 3;//注册，2修改密码
                    $add['create_time'] = time();
                    $verify->add($add);
                }
                $arr = array(
                    'code'=> 1,
                    'res'=>'发送成功',
                    'phone'=>$phone
                );

            }else{
                $arr = array(
                    'code'=> 0,
                    'res'=>'发送失败',
                    'phone'=>$phone
                );

            }
            $this->ajaxReturn($arr,"JSON");

        }

    }



    /**
     * 注册发送验证码
     */
    public function sendcode2($phone){
        DeleteOldCode();
        //接收数据
        if(!$phone){
            $arr = array(
                'code'=> 0,
                'res'=> '请输入手机号'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        if(strlen($phone)!=11){
            $arr = array(
                'code'=> 0,
                'res'=> '手机号码格式错误'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        //判断输入非法
        if(preg_match("/[\',:;*?？！~`!#$%^&=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$phone)){  //不允许特殊字符
            $arr = array(
                'code'=> 0,
                'res'=>'手机号码格式错误'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        //实例化数据表
        $app = M('app');
        $verify = M('verify');


        $is_reg = $app->where(array('phone'=>$phone))->find();
        if(!$is_reg){
            $arr = array(
                'code'=> 0,
                'res'=>'该手机号未注册'
            );
            $this->ajaxReturn($arr,"JSON");
        }else{

            $rand = rand(100000,999999);
            //$rand =123456;

            $uid = "2255";
            $passwd = "7fdf6096659ff66beaba43046368a020";
            $content = "您的验证码为：".$rand."，为了保护您的账户安全，验证码请勿转发他人，有效时间15分钟。【小时光】";
            $url = "http://sms.bamikeji.com:8890/mtPort/mt/normal/send?uid=".$uid."&passwd=".$passwd."&phonelist=".$phone."&content=".$content;
            $html = file_get_contents($url);
            $result = json_decode($html,true);
            //$con= substr( file_get_contents($url), 0, 1 );  //获取信息发送后的状态
            //$result['code'] = 0;
            if($result['code'] == 0){
                $where['phone'] = $phone;
                $where['type'] = 3;
                $is_code = $verify->where($where)->find();
                if($is_code){
                    $save['code'] = $rand;
                    $save['create_time'] = time();
                    $verify->where(array('id'=>$is_code['id']))->save($save);
                }else{
                    $add['code'] = $rand;
                    $add['phone'] = $phone;
                    $add['type'] = 4;//注册，2修改密码
                    $add['create_time'] = time();
                    $verify->add($add);
                }
                $arr = array(
                    'code'=> 1,
                    'res'=>'发送成功',
                    'phone'=>$phone
                );

            }else{
                $arr = array(
                    'code'=> 0,
                    'res'=>'发送失败',
                    'phone'=>$phone
                );

            }
            $this->ajaxReturn($arr,"JSON");

        }

    }


    public function thumb(){

        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize  = 2145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->saveName = date('YmdHis').rand(10000,99999);
        $upload->savePath =  'Uploads/applogo/';// 设置附件上传目录
        /*if(!$upload->upload()) {// 上传错误提示错误信息
            $msg['success'] =false;
            echo json_encode($msg);
        }else{// 上传成功
            $info =  $upload->getUploadFileInfo();
            $msg['success'] =true;
            $msg['file_path'] ='/'.$info[0]['savepath'].$info[0]['savename'];
            echo json_encode($msg);
        }*/
        $info   =   $upload->uploadOne($_FILES['photoimage']);

        if(!$info) {// 上传错误提示错误信息
            $msg  =false;
            $this->ajaxReturn($msg,'JSON');
        }else{// 上传成功 获取上传文件信息
            //$msg['success'] =true;
            $msg= XUANIMG.'/Public/'.$info['savepath'].$info['savename'];

            $this->ajaxReturn($msg,'JSON');
        }
    }



    public function check($username,$password){
        $app = M('app');
        $where['phone'] = $username;
        $where['pass'] = md5($password);
        $where['is_freeze'] = 0;
        $query = $app->where($where)->find();

        if($query){
            $arr = array(
                'code'=>1,
                'res'=>$query['key']
            );

        }else{
            $arr = array(
                'code'=>0,
                'res'=>'手机号或密码错误，请重新输入'
            );

        }
        $this->ajaxReturn($arr);
    }



}

