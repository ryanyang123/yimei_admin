<?php
namespace Home\Controller;
use Think\Controller;
class GroupclassifyController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'12'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){


        $group_type = M('group_type');


        //$where['is_show'] = 1;
        $count = $group_type->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个分类</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $group_type->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'groupclassify',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $group_type = M('group_type');

        if($is_edit){
            $list = $group_type->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'groupclassify',
            'title'=>'群分类',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($name,$is_edit){
        $group_type = M('group_type');

        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入群分类名称');$this->ajaxReturn($arr,"JSON");}

        if ($is_edit){
            $where_name['id'] = array('NEQ',$is_edit);
        }
        $where_name['type_name'] = $name;
        $is_name = $group_type->where($where_name)->find();
        if ($is_name){
            $arr = array('code'=> 0, 'res'=>'群分类名称已存在');$this->ajaxReturn($arr,"JSON");
        }
        if($is_edit){
            $save['type_name'] = $name;
            $save['update_time'] = time();
            $query = $group_type->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['type_name'] = $name;
            $add['create_time'] = time();
            $query = $group_type->add($add);
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












