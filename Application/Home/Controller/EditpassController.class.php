<?php
namespace Home\Controller;
use PhpOffice\PhpSpreadsheet\Reader\Xls\MD5;
use Think\Controller;
class EditpassController extends CommonController {
    public function index(){
        $data = array(
            'at' =>'editpass',
        );


        $this->assign($data);
        $this->display();
    }



    function edit($oldpwd,$pwd,$type){

        if($type=='2'){
            if(session(XUANDB.'user_type')!='1'){
                $arr = array(
                    'code'=> 0,
                    'res'=> '没有修改权限'
                );
                $this->ajaxReturn($arr,"JSON");
            }
            $set = M('set');
            $is_ok  = $set->where(array('set_type'=>'pay_pass'))->find();
            if($is_ok['set_value']!= md5(md5($oldpwd))){
                $arr = array(
                    'code'=> 0,
                    'res'=> '当前支付密码输入错误'
                );
                $this->ajaxReturn($arr,"JSON");
            }
            if(strlen($pwd)<6 || strlen($pwd)>16){
                $arr = array(
                    'code'=> 0,
                    'res'=> '密码长度为6-16位字符'
                );
                $this->ajaxReturn($arr,"JSON");
            }

            $save['set_value'] = md5(md5($pwd));
            $save['update_time'] = time();
            $query = $set->where(array('set_type'=>'pay_pass'))->save($save);
        }else{
            $admin = M('admin');
            $where['admin_id'] = session(XUANDB.'user_id');
            $where['password'] = md5($oldpwd);
            $is_ok  = $admin->where($where)->find();
            if(!$is_ok){
                $arr = array(
                    'code'=> 0,
                    'res'=> '密码输入错误'
                );
                $this->ajaxReturn($arr,"JSON");
            }
            if(strlen($pwd)<6 || strlen($pwd)>16){
                $arr = array(
                    'code'=> 0,
                    'res'=> '密码长度为6-16位字符'
                );
                $this->ajaxReturn($arr,"JSON");
            }

            $save['password'] = md5($pwd);
            $save['update_time'] = time();
            $query = $admin->where(array('id'=>$is_ok['id']))->save($save);
        }



        if($query){
            $arr = array(
                'code'=> 1,
                'res'=> '修改成功'
            );
            $this->ajaxReturn($arr,"JSON");
        }else{
            $arr = array(
                'code'=> 0,
                'res'=> '修改失败'
                );
            $this->ajaxReturn($arr,"JSON");
        }

    }





}