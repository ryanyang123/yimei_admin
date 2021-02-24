<?php
namespace Home\Controller;
use Think\Controller;
class ShopsearchController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'shopsearch'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){


        $shop_search_hot = M('shop_search_hot');


        //$where['is_show'] = 1;
        $count = $shop_search_hot->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个搜索</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $shop_search_hot->order('sort,create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'shopsearch',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $shop_search_hot = M('shop_search_hot');

        if($is_edit){
            $list = $shop_search_hot->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'shopsearch',
            'title'=>'热门搜索',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($antistop,$sort,$is_fen,$is_edit){
        $shop_search_hot = M('shop_search_hot');

        if(!$antistop){$arr = array('code'=> 0, 'res'=>'请输入关键词');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            $save['antistop'] = $antistop;
            $save['is_fen'] = $is_fen;
            $save['sort'] = $sort;
            $save['update_time'] = time();
            $query = $shop_search_hot->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['antistop'] = $antistop;
            $add['is_fen'] = $is_fen;
            $add['sort'] = $sort;
            $add['create_time'] = time();
            $query = $shop_search_hot->add($add);
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












