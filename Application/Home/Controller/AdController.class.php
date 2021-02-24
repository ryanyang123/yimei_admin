<?php

namespace Home\Controller;

use Think\Controller;

class AdController extends CommonController
{
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'ad'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index()
    {


        $ad = M('ad');
        $shop_goods = M('shop_goods');




        //$where['is_show'] = 1;
        $count = $ad->count();
        $Page = new \Think\Page($count, 10);
        $Page->rollPage = 5;
        $Page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header', '<p class="rows">共 %TOTAL_ROW%  个广告</p>');
        $show = $Page->show();// 分页显示输出
        $list = $ad->order('create_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $key => $item) {
           $goods_info =  $shop_goods->where(array('id'=>$item['goods_id']))->field('name')->find();
           $list[$key]['goods_name'] = $goods_info['name'];
        }

        $data = array(
            'list' => $list,
            'page' => $show,
            'at' => 'ad',
            'count' => count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus()
    {
        $is_edit = I('get.edit');

        $ad = M('ad');
        $shop_goods = M('shop_goods');

        if ($is_edit) {
            $list = $ad->where(array('id' => $is_edit))->find();
            if($list['type']=='1'){
                $list['start_time'] = date('Y-m-d',$list['start_time']);
                $list['end_time'] = date('Y-m-d',$list['end_time']);

            }
            $where['lyx_shop_goods.id'] = $list['goods_id'];
            $goods_info = $shop_goods->where($where)->join('lyx_shop ON lyx_shop_goods.shop_id = lyx_shop.id')
                ->field('lyx_shop_goods.id,lyx_shop_goods.name,lyx_shop.name as shop_name')
                ->find();
        } else {
            $list = array();
            $goods_info['id'] = 0;
            $goods_info['name'] = '';
            $goods_info['shop_name'] = '';
        }

        $data = array(
            'at' => 'ad',
            'title' => '广告',
            'list' => $list,
            'goods_info' => $goods_info,
            'is_edit' => $is_edit
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

    public function add($type, $start_time, $end_time,$show_num, $xz_id,$is_edit)
    {
        $ad = M('ad');
        $shop_goods = M('shop_goods');

        if($type=='1'){
            if (!$start_time) {$arr = array('code' => 0, 'res' => '请选择显示开始时间');$this->ajaxReturn($arr, "JSON");}
            if (!$end_time) {$arr = array('code' => 0, 'res' => '请选择显示结束时间');$this->ajaxReturn($arr, "JSON");}
            $start_time_s = strtotime($start_time);
            $end_time_s = strtotime($end_time);
            if($end_time_s<$start_time_s){$arr = array('code' => 0, 'res' => '结束时间不能小于开始时间');$this->ajaxReturn($arr, "JSON");}
            if($end_time_s<time()){$arr = array('code' => 0, 'res' => '结束时间不能小于当前时间');$this->ajaxReturn($arr, "JSON");}

        }else{
            if ($show_num=='') {$arr = array('code' => 0, 'res' => '请输入显示次数');$this->ajaxReturn($arr, "JSON");}
        }

        if (!$xz_id) {$arr = array('code' => 0, 'res' => '请选择广告商品');$this->ajaxReturn($arr, "JSON");}
        $goods_info = $shop_goods->where(array('id'=>$xz_id))->find();
        if (!$goods_info) {$arr = array('code' => 0, 'res' => '商品参数错误');$this->ajaxReturn($arr, "JSON");}
        if ($goods_info['is_freeze']=='0') {$arr = array('code' => 0, 'res' => '商品已被冻结');$this->ajaxReturn($arr, "JSON");}
        if ($goods_info['is_show']=='0') {$arr = array('code' => 0, 'res' => '商品已下架');$this->ajaxReturn($arr, "JSON");}


        if ($is_edit) {

            $save['goods_id'] = $xz_id;
            $save['is_freeze'] = $goods_info['is_freeze'];
            $save['type'] = $type;
            if($type=='1'){
                $save['start_time'] = strtotime($start_time);
                $save['end_time'] = strtotime($end_time);
                if( strtotime($start_time)<time()){
                    $save['is_take'] = 1;
                }else{
                    $save['is_take'] = 0;
                }
            }else{
                $save['show_num'] = $show_num;
                $ad_info = $ad->where(array('id'=>$is_edit))->find();
                $save['is_take'] = 1;
                if($show_num!='0'){
                    if($ad_info['now_show']>=$show_num){
                        $save['is_take'] = 0;
                    }
                }
            }

            $save['update_time'] = time();
            $query = $ad->where(array('id' => $is_edit))->save($save);
            $res = '修改';
        } else {
            $add['goods_id'] = $xz_id;
            $add['is_freeze'] = $goods_info['is_freeze'];
            $add['type'] = $type;
            if($type=='1'){
                $add['start_time'] = strtotime($start_time);
                $add['end_time'] = strtotime($end_time);
                if( strtotime($start_time)<time()){
                    $add['is_take'] = 1;
                }else{
                    $add['is_take'] = 0;
                }
            }else{
                $add['show_num'] = $show_num;
                $add['is_take'] = 1;
            }
            $add['create_time'] = time();
            $query = $ad->add($add);
            $res = '新增';
        }

        if ($query) {
            $arr = array(
                'code' => 1,
                'res' => $res . '成功'
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


}












