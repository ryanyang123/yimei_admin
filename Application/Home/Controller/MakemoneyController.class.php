<?php
namespace Home\Controller;
use Think\Controller;
class MakemoneyController extends Controller{
    public function index(){

        $set = M('set');

        $set_info = $set->where(array('set_type'=>'make_money'))->find();

        $list['title'] = '赚钱攻略';
        $list['content'] = $set_info['remark'];
        $data = array(
            'list' =>$list,
            'at' =>'show',
            'count' =>count($list),
        );

        $this->assign($data);
        $this->display();

    }





}