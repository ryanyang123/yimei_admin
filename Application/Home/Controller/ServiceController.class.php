<?php
namespace Home\Controller;
use Think\Controller;
class ServiceController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'service'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
        vendor('Rong.rongcloud');
    }
    public function index(){

        $user = M('user');

        $search = I('get.search');
        if($search){
            $where['name|phone'] =  array("like","%".$search."%");
            $search_list['search'] = $search;
        }

        //时间搜索
        $search_date  = I('get.search_date');
        $search_date2  = I('get.search_date2');
        //$data['search_date'] = $search_date;
        //$data['search_date2'] = $search_date2;
        $newtime = strtotime($search_date);
        $newtime1 = strtotime($search_date2)+86399;
        if($search_date && $search_date2){
            $where['create_time'] = array(array('EGT',$newtime),array('ELT',$newtime1),'AND');
        }else if($search_date && !$search_date2){
            $where['create_time'] = array('EGT',$newtime);
        }else if(!$search_date && $search_date2){
            $where['create_time'] = array('ELT',$newtime1);
        }
        $search_list['search_date'] = $search_date;
        $search_list['search_date2'] = $search_date2;
        //时间搜索


        $where['is_service'] = 1;
        //$where['is_robot'] = 1;
        $count = $user->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个客服</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $user->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'search_list' =>$search_list,
            'at' =>'service',
            'count' =>count($list),

        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $banner = M('user');


        if($is_edit){
            $list = $banner->where(array('id'=>$is_edit,'is_service'=>'1'))->find();
        }else{
            $list = array();
        }


        $data = array(
            'at'=>'service',
            'title'=>'客服',

            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($name,$img,$phone,$password,$is_edit){
        $user = M('user');

        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入客服名称');$this->ajaxReturn($arr,"JSON");}
        if(!$phone){$arr = array('code'=> 0, 'res'=>'请输入客服登录账号');$this->ajaxReturn($arr,"JSON");}
        if(!$img && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传客服头像');$this->ajaxReturn($arr,"JSON");}
        if(!$password && !$is_edit){$arr = array('code'=> 0, 'res'=>'请输入客服登录密码');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            $where_phone['id']  = array('NEQ',$is_edit);
        }
        $where_phone['phone'] = $phone;
        $is_reg = $user->where($where_phone)->find();
        if($is_reg){
            $arr = array(
                'code'=> 0,
                'res'=>'登录账号已存在'
            );
            $data = json_encode($arr);
            $return['encryption'] =  encrypt1($data);
            $this->ajaxReturn($return,"JSON");
        }

        if($is_edit){
            $where_name['id']  = array('NEQ',$is_edit);
        }
        $where_name['name'] = $name;
        $is_name = $user->where($where_phone)->find();
        if($is_name){
            $arr = array(
                'code'=> 0,
                'res'=>'名称已存在'
            );
            $data = json_encode($arr);
            $return['encryption'] =  encrypt1($data);
            $this->ajaxReturn($return,"JSON");
        }


        $token =  md5('xuan_xsg_User'.$phone.time());
        if($is_edit){
            $user_info  = $user->where(array('id'=>$is_edit))->field('head')->find();
            if($img){
                $save['head'] = $img;
                $save_img = $img;
            }else{
                $save_img = $user_info['head'];
            }
            $save['name'] = $name;
            if ($password){
                $save['password'] = md5($password);
                $save['token'] = $token;
            }

            $save['update_time'] = time();
            $query = $user->where(array('id'=>$is_edit))->save($save);

            $RongCloud = new \RongCloud(C('RongCloudConf.key'),C('RongCloudConf.secret'));
            $result = $RongCloud->user()->refresh($is_edit, $save['name'],$save_img);
            $res = '修改';
        }else{
            $id = GetUserID();
            $add['id'] = $id;
            $add['name'] = $name;
            $add['phone'] = $phone;
            $add['password'] = md5($password);
            $add['token'] = $token;
            $add['head'] = $img;
            $add['is_robot'] = 1;
            $add['is_service'] = 1;
            $add['signature'] = '听说有个签名，今年会有桃花运';
            $add['create_time'] = time();
            $query = $user->add($add);
            //融云token
            $RongCloud = new \RongCloud(C('RongCloudConf.key'),C('RongCloudConf.secret'));
            $result = $RongCloud->user()->getToken($query, $add['name'],$add['head']);

            $rongres =  json_decode($result,true);
            if($result){
                $user->where(array('id'=>$query))->save(array('rong_token'=>$rongres['token'],'update_time'=>time()));
            }
            $result = $RongCloud->user()->refresh($query, $add['name'],$add['head']);
            $res = '新增';
        }
        if($query){
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












