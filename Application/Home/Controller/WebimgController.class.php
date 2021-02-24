<?php
namespace Home\Controller;
use Think\Controller;
class WebimgController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'webimg'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){


        $web_banner = M('web_img');
        $type = I('get.type');
        if($type){
            $where['type'] =  $type;
            $search_list['type'] = $type;
        }

        //$where['is_show'] = 1;
        $count = $web_banner->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条数据</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $web_banner->where($where)->order('type')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

        }


        $data = array(
            'list' =>$list,
            'search_list' =>$search_list,
            'page' =>$show,
            'is_show' =>$is_show,
            'status' =>$status,
            'at' =>'webimg',
            'count' =>count($list),

        );
        $this->assign($data);
        $this->display();
    }

    function SearchGoods($search_val)
    {
        $shop_goods = M('shop_goods');
        if (!$search_val) {
            $arr = array('code' => 0, 'res' => '请输入商品名称');
            $this->ajaxReturn($arr, "JSON");
        }
        $where['lyx_shop_goods.name'] = array("like", "%" . $search_val . "%");

        $list = $shop_goods->where($where)->join('lyx_shop ON lyx_shop_goods.shop_id = lyx_shop.id')
            ->field('lyx_shop_goods.id,lyx_shop_goods.name,lyx_shop.name as shop_name')
            ->select();
        $res = '查询';
        if ($list) {
            $arr = array(
                'code' => 1,
                'res' => $res . '成功',
                'list' => $list
            );
            $this->ajaxReturn($arr, "JSON");
        } else {
            $arr = array(
                'code' => 0,
                'res' => $res . '失败'
            );
            $this->ajaxReturn($arr, "JSON");
        }
    }



    function plus(){
        $is_edit  =  I('get.edit');

        $banner = M('web_img');

        if($is_edit){
            $list = $banner->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }


        $data = array(
            'at'=>'webimg',
            'title'=>'创业课堂',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($img,$sort,$is_edit){
        $banner = M('web_img');

        if(!$img && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传图片');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            if($img){
                $save['img_url'] = $img;
            }
            $save['sort'] = $sort;
            $save['update_time'] = time();
            $query = $banner->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $save['sort'] = $sort;
            $save['img_url'] = $img;
            $save['type'] = 1;
            $save['update_time'] = time();
            $query = $banner->add($save);
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












