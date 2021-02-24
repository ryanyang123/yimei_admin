<?php
namespace Home\Controller;
use Think\Controller;
class SensitivityController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'16'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){


        $sensitivity = M('sensitivity');


        //$where['is_show'] = 1;
        $count = $sensitivity->count();
        $Page       = new \Think\Page($count,50);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个敏感词</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $sensitivity->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'sensitivity',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $sensitivity = M('sensitivity');

        if($is_edit){
            $list = $sensitivity->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'sensitivity',
            'title'=>'敏感词汇',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($content,$is_edit){
        $sensitivity = M('sensitivity');

        if(!$content){$arr = array('code'=> 0, 'res'=>'请输入词汇名称');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            $save['content'] = $content;
            $save['update_time'] = time();
            $query = $sensitivity->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $sen_num = $sensitivity->where(array('create_time'=>array('EGT','0')))->count();
            if ($sen_num>=50){
                $arr = array('code'=> 0, 'res'=>'敏感词最多不能超过50个');$this->ajaxReturn($arr,"JSON");
            }
            $add['content'] = $content;
            $add['create_time'] = time();
            $query = $sensitivity->add($add);
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












