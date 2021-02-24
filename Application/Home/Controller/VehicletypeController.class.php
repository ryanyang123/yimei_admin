<?php
namespace Home\Controller;
use Think\Controller;
class VehicletypeController extends CommonController {

    public function index(){


        $vehicle_type = M('vehicle_type');


        //$where['is_show'] = 1;
        $count = $vehicle_type->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个车辆类型</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $vehicle_type->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

        }


        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'vehicletype',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }






    function plus(){
        $is_edit  =  I('get.edit');

        $vehicle_type = M('vehicle_type');

        if($is_edit){
            $list = $vehicle_type->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'vehicletype',
            'title'=>'车辆类型',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }



    public function add($name,$img,$is_edit){
        $vehicle_type = M('vehicle_type');

        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入车辆类型名称');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            if($img){
                $save['icon'] = $img;
            }
            $save['name'] = $name;

            $save['update_time'] = time();
            $query = $vehicle_type->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['icon'] = $img;
            $add['name'] = $name;
            $add['create_time'] = time();
            $query = $vehicle_type->add($add);
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












