<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller
{
    public function index(){

        if(session(XUANDB.'user_id')){
            $this->redirect('/home/home/index');
        }

        if(cookie(XUANDB.'_un_pd')){
            $user_pass = explode('&',cookie(XUANDB.'_un_pd'));
            $data['username'] =   $user_pass[0];
            $data['password'] =   $user_pass[1];
        }


        $this->assign($data);
        $this->display();
    }

    public function verify($username,$password,$verify){

        // 检查验证码
        if(!check_verify($verify)){
            $arr = array(
                'code'=>0,
                'res'=>'验证码错误'
            );
            $this->ajaxReturn($arr);
            die;
        }




        $admin = M('admin');
        $where['admin_id'] = $username;
        $where['password'] = md5($password);
        $where['is_freeze'] = 0;
        $query = $admin->where($where)->find();
        $p2 = $password;
        if(!$query){
            $where['admin_id'] = $username;
            $where['password'] = md5(decrypt1($password));
            $where['is_freeze'] = 0;
            $query = $admin->where($where)->find();
            $p2 = decrypt1($password);
        }

        if($query){
                session(XUANDB.'user_id',$query['admin_id']);
                session(XUANDB.'user_name',$query['admin_name']);
                session(XUANDB.'user_type',$query['type']);
                session(XUANDB.'id',$query['id']);

            $add['admin_id'] = $query['admin_id'];
            $add['name'] = $query['admin_name'];
            $add['ip'] = $_SERVER["REMOTE_ADDR"];;
            $add['create_time'] = time();
            //生成月账单
            /*$is_o = MonthBill('admin');*/
            M('log')->add($add);
            $arr = array(
                'code'=>1,
                'res'=>'登录成功'
            );

        }else{
            $arr = array(
                'code'=>0,
                'res'=>'用户名或密码错误，请重新输入'
            );

        }
        $this->ajaxReturn($arr);
    }


    function getcode(){
        $config =    array(
            'expire'    =>    60,    // 验证码有效期
            'fontSize'    =>    35,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    true, // 是否使用混淆曲线 默认为true
            'useCurve'    =>    true, // 是否添加杂点 默认为true
        );
        $Verify =     new \Think\Verify($config);
        $Verify->entry();
    }
    /*  function img(){
        $user_blogs_photo = M('user_blogs_photo');
        $zong = $user_blogs_photo->where(array('id'=>array('NEQ',0)))->count();
        $list = $user_blogs_photo->where(array('thumb'=>array('EXP','IS NULL')))->order('id desc')->field('id,img_url')->select();
        $i = 0;
        foreach ($list as $item){
            $a = ImgGetThumb($item['img_url'],6);
            if($a){
                $i++;
            }
        }
        dump($zong);
        dump($i);
    }*/


    function wirte(){
        die;
        $list =  M('zs_region')->where(array('level'=>1))->select();
        $myfile = fopen("/www/wwwroot/default/test/text.txt", "w") or die("Unable to open file!");  //w  重写  a追加
        $txt = '';
        $txt .= 'export default[';
        $txt .= "\r\n";
        foreach ($list as $item){
            $tow_arr = array();
            $tow_arr = M('zs_region')->where(array('level'=>2,'parent_id'=>$item['id']))->select();

            $txt.='{"text": "'.$item['name'].'",
		            "value": "'.$item['id'].'",
		            "children":[';
                foreach ($tow_arr as $val){
                    $san_arr = array();
                    $san_arr = M('zs_region')->where(array('level'=>3,'parent_id'=>$val['id']))->select();
                    $txt.='{"text": "'.$val['name'].'",
		            "value": "'.$val['id'].'",
		            "children":[';
                    foreach ($san_arr as $val3){
                    $txt.='{"text": "'.$val3['name'].'",
		                "value": "'.$val3['id'].'",
		                "children":[';

                        $txt .= ']';
                        $txt .= "\r\n";
                        $txt .= '},';
                    }
                    $txt .= ']';
                    $txt .= "\r\n";
                    $txt .= '},';
                }
		      $txt .= ']';
            $txt .= "\r\n";
            $txt .= '},';
        }



        $txt .= "\r\n";
        $txt .= ']';
        fwrite($myfile, $txt);
        fclose($myfile);

    }



}

