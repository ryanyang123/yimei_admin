<?php
namespace Home\Controller;
use Think\Controller;
class DriverController extends CommonController {

    public function index(){


        $driver = M('driver');


        //$where['is_show'] = 1;
        $count = $driver->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个司机</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $driver->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'driver',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }





    function plus(){
        $is_edit  =  I('get.edit');

        $driver = M('driver');

        if($is_edit){
            $list = $driver->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'driver',
            'title'=>'司机',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }



    public function add($name,$phone,$id_card,$img,$is_edit){
        $driver = M('driver');

        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入司机名称');$this->ajaxReturn($arr,"JSON");}
        if(!$phone){$arr = array('code'=> 0, 'res'=>'请输入司机手机号');$this->ajaxReturn($arr,"JSON");}
        if(!$id_card){$arr = array('code'=> 0, 'res'=>'请输入司机身份证号');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            if($img){
                $save['img_url'] = $img;
            }
            $save['name'] = $name;
            $save['phone'] = $phone;
            $save['id_card'] = $id_card;

            $save['update_time'] = time();
            $query = $driver->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['img_url'] = $img;
            $add['name'] = $name;
            $add['phone'] = $phone;
            $add['id_card'] = $id_card;
            $add['create_time'] = time();
            $query = $driver->add($add);
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












