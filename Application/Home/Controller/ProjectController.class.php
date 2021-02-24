<?php
namespace Home\Controller;
use Think\Controller;
class ProjectController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'project'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){


        $project = M('project');


        //$where['is_show'] = 1;
        $count = $project->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个活动</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $project->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){


        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'project',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $notice = M('project');

        if($is_edit){
            $list = $notice->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'project',
            'title'=>'项目活动',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($name,$bg,$content,$info,$is_edit){
        $notice = M('project');
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入活动名称');$this->ajaxReturn($arr,"JSON");}
        if(!$info){$arr = array('code'=> 0, 'res'=>'请输入活动简介');$this->ajaxReturn($arr,"JSON");}
        if(!$content){$arr = array('code'=> 0, 'res'=>'请输入活动内容');$this->ajaxReturn($arr,"JSON");}



        if($is_edit){
            if($bg){
                $save['bg'] = $bg;
            }
            $save['name'] = $name;
            $save['info'] = $info;
            $save['content'] = $content;
            $save['update_time'] = time();
            $query = $notice->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['bg'] = $bg;
            $add['name'] = $name;
            $add['info'] = $info;
            $add['content'] = $content;
            $add['create_time'] = time();
            $query = $notice->add($add);


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












