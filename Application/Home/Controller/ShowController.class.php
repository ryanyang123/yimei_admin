<?php
namespace Home\Controller;
use Think\Controller;
class ShowController extends Controller{
    public function index(){

        $question = M('question');
        $id = I('get.id');
        $list = $question->where(array('id'=>$id))->find();


        $data = array(
            'list' =>$list,
            'at' =>'show',
            'count' =>count($list),
        );

        $this->assign($data);
        $this->display();

    }
    function interaction(){
        $this->display();
    }
    function test(){
        $this->display();
    }





}