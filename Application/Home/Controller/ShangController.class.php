<?php
namespace Home\Controller;
use Think\Controller;
class ShangController extends CommonController {

    public function index(){


        $location_type = M('location_type');



        $count = $location_type->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个分类</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $location_type->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'shang',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }



    function plus(){
        $is_edit  =  I('get.edit');

        $location_type = M('location_type');

        if($is_edit){
            $list = $location_type->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }


        $data = array(
            'at'=>'shang',
            'title'=>'上/下车地点分类',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }



    public function add($name,$type,$is_edit){
        $location_type = M('location_type');

        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入上车分类名称');$this->ajaxReturn($arr,"JSON");}


        if($is_edit){
            $where['id'] = array('NEQ',$is_edit);
        }
        $where['name'] = $name;
        $where['type'] = $type;
        $is_have = $location_type->where($where)->find();

        if($is_have){
            $arr = array('code'=> 0, 'res'=>'上/下车分类名已存在');$this->ajaxReturn($arr,"JSON");
        }
        if($is_edit){

            $save['name'] = $name;
            $save['type'] = $type;

            $save['update_time'] = time();
            $query = $location_type->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['name'] = $name;
            $add['type'] = $type;
            $add['create_time'] = time();
            $query = $location_type->add($add);
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












