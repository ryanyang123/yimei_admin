<?php
namespace Home\Controller;
use Think\Controller;
class VehicleController extends CommonController {

    public function index(){


        $vehicle_type = M('vehicle_type');
        $vehicle = M('vehicle');

        $type_list =  $vehicle_type->select();
        foreach($type_list as $item){
            $new_type[$item['id']] = $item['name'];
        }

        $count = $vehicle->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  台车</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $vehicle->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $list[$key]['type_name'] = $new_type[$item['vehicle_type']];
        }


        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'vehicle',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }



    function plus(){
        $is_edit  =  I('get.edit');

        $vehicle_type = M('vehicle_type');
        $vehicle = M('vehicle');

        if($is_edit){
            $list = $vehicle->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }
        $type_list = $vehicle_type->select();

        $data = array(
            'at'=>'vehicle',
            'title'=>'车辆列表',
            'type_list'=>$type_list,
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }



    public function add($vehicle_number,$vehicle_type,$is_edit){
        $vehicle = M('vehicle');

        if(!$vehicle_number){$arr = array('code'=> 0, 'res'=>'请输入车牌号');$this->ajaxReturn($arr,"JSON");}


        if($is_edit){
            $where['id'] = array('NEQ',$is_edit);
        }
        $where['vehicle_number'] = $vehicle_number;
        $is_have = $vehicle->where($where)->find();

        if($is_have){
            $arr = array('code'=> 0, 'res'=>'车牌号已存在');$this->ajaxReturn($arr,"JSON");
        }
        if($is_edit){

            $save['vehicle_number'] = $vehicle_number;
            $save['vehicle_type'] = $vehicle_type;

            $save['update_time'] = time();
            $query = $vehicle->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['vehicle_number'] = $vehicle_number;
            $add['vehicle_type'] = $vehicle_type;
            $add['create_time'] = time();
            $query = $vehicle->add($add);
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












