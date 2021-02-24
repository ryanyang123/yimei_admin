<?php
namespace Home\Controller;
use Think\Controller;
class PhonecodeController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'phonecode'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){


        $diary_classify = M('phone_code');


        $phone = I('get.phone');
        if($phone){
            $where['phone'] =  array("like","%".$phone."%");;
            $search_list['phone'] = $phone;
        }
        //$where['is_show'] = 1;
        $count = $diary_classify->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条记录</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $diary_classify->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'search_list' =>$search_list,
            'at' =>'phonecode',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $diary_classify = M('phone_code');

        if($is_edit){
            $list = $diary_classify->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'phonecode',
            'title'=>'免验证码',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($phone,$password,$is_edit){
        $diary_classify = M('phone_code');

        if(!$phone){$arr = array('code'=> 0, 'res'=>'请输入手机号');$this->ajaxReturn($arr,"JSON");}
        if(!$password){$arr = array('code'=> 0, 'res'=>'请输入验证码');$this->ajaxReturn($arr,"JSON");}

        if ($is_edit){
            $where_name['id'] = array('NEQ',$is_edit);
        }
        $where_name['phone'] = $phone;
        $is_name = $diary_classify->where($where_name)->find();
        if ($is_name){
            $arr = array('code'=> 0, 'res'=>'手机号已添加');$this->ajaxReturn($arr,"JSON");
        }
        if($is_edit){
            $save['phone'] = $phone;
            $save['password'] = $password;
            $save['update_time'] = time();
            $query = $diary_classify->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['phone'] = $phone;
            $add['password'] = $password;
            $add['create_time'] = time();
            $query = $diary_classify->add($add);
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












