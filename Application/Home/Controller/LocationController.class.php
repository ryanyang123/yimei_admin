<?php
namespace Home\Controller;
use Think\Controller;
class LocationController extends CommonController {

    public function index(){


        $location_type = M('location_type');
        $location = M('location');

        $type_list = $location_type->select();
        foreach($type_list as $item){
            $new_type[$item['id']] = $item['name'];
        }

        $where['type'] = 1;
        $count = $location->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个地点</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $location->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $list[$key]['type_name'] = $new_type[$item['type_id']];
        }


        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'location',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }



    function plus(){
        $is_edit  =  I('get.edit');

        $location_type = M('location_type');
        $location = M('location');

        if($is_edit){
            $list = $location->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }
        $type_list = $location_type->where(array('type'=>'1'))->select();

        $data = array(
            'at'=>'location',
            'title'=>'上车地点',
            'type_list'=>$type_list,
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }



    public function add($name,$price,$type_id,$add_time,$is_edit){
        $location = M('location');

        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入上车分类名称');$this->ajaxReturn($arr,"JSON");}


        if($is_edit){
            $where['id'] = array('NEQ',$is_edit);
        }
        $where['name'] = $name;
        $where['type'] = '1';
        $is_have = $location->where($where)->find();

        if($is_have){
            $arr = array('code'=> 0, 'res'=>'上车地点已存在');$this->ajaxReturn($arr,"JSON");
        }
        if($is_edit){

            $save['name'] = $name;
            $save['type'] = 1;
            $save['price'] = $price;
            $save['add_time'] = $add_time;
            $save['type_id'] = $type_id;

            $save['update_time'] = time();
            $query = $location->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['name'] = $name;
            $add['type'] = 1;
            $add['price'] = $price;
            $add['add_time'] = $add_time;
            $add['type_id'] = $type_id;
            $add['create_time'] = time();
            $query = $location->add($add);
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












