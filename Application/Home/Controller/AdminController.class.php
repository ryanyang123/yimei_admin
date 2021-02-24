<?php
namespace Home\Controller;
use Think\Controller;
class AdminController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $this->redirect('/home/home/index');
        }
    }
    public function index(){


        $admin = M('admin');
        $limits = M('limits');

        $search = I('get.search');
        if($search){
            $where['admin_id|admin_name'] =  array("like","%".$search."%");
            $search_list['search'] = $search;
        }
        $type_id = I('get.type_id');
        if($type_id){
            $where['type'] =  $type_id;
            $search_list['type_id'] = $type_id;
        }else{
            $where['type'] = array('NEQ','1');
        }

        //时间搜索
        $search_date  = I('get.search_date');
        $search_date2  = I('get.search_date2');
        //$data['search_date'] = $search_date;
        //$data['search_date2'] = $search_date2;
        $newtime = strtotime($search_date);
        $newtime1 = strtotime($search_date2);
        if($search_date && $search_date2){
            $where_chao['create_time'] = array(array('EGT',$newtime),array('ELT',$newtime1),'AND');
        }else if($search_date && !$search_date2){
            $where_chao['create_time'] = array('EGT',$newtime);
        }else if(!$search_date && $search_date2){
            $where_chao['create_time'] = array('ELT',$newtime1);
        }
        $search_list['search_date'] = $search_date;
        $search_list['search_date2'] = $search_date2;
        //时间搜索


        $count = $admin->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个账号</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $admin->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

        }


        $data = array(
            'search_list' =>$search_list,
            'list' =>$list,
            'page' =>$show,
            'at' =>'admin',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $admin = M('admin');
        $limits = M('limits');
        $limits_log = M('limits_log');
        $limits_list = $limits->select();
        if($is_edit){
            $list = $admin->where(array('id'=>$is_edit))->find();
            foreach($limits_list as $key=>$item){
                $is_log = $limits_log->where(array('admin_id'=>$is_edit,'lid'=>$item['db_name']))->find();
                if($is_log){
                    $limits_list[$key]['is_on'] = '1';
                }else{
                    $limits_list[$key]['is_on'] = '0';
                }
            }
        }else{
            $list = array();
        }


        $data = array(
            'at'=>'admin',
            'title'=>'账号',
            'list'=>$list,
            'limits_list'=>$limits_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($admin_id,$admin_name,$password,$limits,$limits_name,$is_edit){
        $admin = M('admin');
        $limits_log = M('limits_log');


        if(!$admin_id){$arr = array('code'=> 0, 'res'=>'请输入登陆账号');$this->ajaxReturn($arr,"JSON");}
        if(strlen($admin_id)<6 || strlen($admin_id)>12){$arr = array('code'=> 0, 'res'=>'登陆账号长度为6-12字符');$this->ajaxReturn($arr,"JSON");}
        if(!$admin_name){$arr = array('code'=> 0, 'res'=>'请输入账号名称');$this->ajaxReturn($arr,"JSON");}
        if(!$password && !$is_edit){$arr = array('code'=> 0, 'res'=>'请输入密码');$this->ajaxReturn($arr,"JSON");}

        $limits_arr = explode(',',$limits);
        if($is_edit){
            $where_name['id'] = array('NEQ',$is_edit);
        }

        $where_name['admin_id'] = $admin_id;
        $is_username = $admin->where($where_name)->find();
        if($is_username){
            $arr = array('code'=> 0, 'res'=>'登陆账号已存在');$this->ajaxReturn($arr,"JSON");
        }

        if($is_edit){

            $save['admin_id'] = $admin_id;
            if($password){
                $save['password'] = md5($password);
            }

            $save['admin_name'] = $admin_name;
            $save['limits_name'] = $limits_name;
            $save['update_time'] = time();
            $query = $admin->where(array('id'=>$is_edit))->save($save);
            $add_id = $is_edit;
            $res = '修改';
        }else{

            $add['type'] = 2;
            $add['admin_id'] = $admin_id;
            $add['admin_name'] = $admin_name;
            $add['limits_name'] = $limits_name;
            $add['password'] = md5($password);
            $add['create_time'] = time();
            $query = $admin->add($add);
            $add_id = $query;
            $res = '新增';
        }

        if($query){
            $limits_log->where(array('admin_id'=>$add_id))->delete();
            if(count($limits_arr)>0){
                $add_limits_log['admin_id'] = $add_id;
                foreach($limits_arr as $item){
                    $add_limits_log['lid'] = $item;
                    $limits_log->add($add_limits_log);
                }
            }


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

    function editfreeze($id,$str){
        $admin = M('admin');
        $query = $admin->where(array('id'=>$id))->save(array('is_freeze'=>$str,'update_time'=>time()));
        $res = "操作";
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












