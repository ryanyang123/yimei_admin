<?php

namespace Home\Controller;

use Think\Controller;

class ShopjxController extends CommonController
{
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'shopjx'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index()
    {


        $shop_jx = M('shop_jx');
        $shop_goods = M('shop_goods');




        //$where['is_show'] = 1;
        $count = $shop_jx->count();
        $Page = new \Think\Page($count, 10);
        $Page->rollPage = 5;
        $Page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header', '<p class="rows">共 %TOTAL_ROW%  个精选</p>');
        $show = $Page->show();// 分页显示输出
        $list = $shop_jx->order('create_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $key => $item) {
           $goods_info1 =  $shop_goods->where(array('id'=>$item['goods_id1']))->field('name')->find();
           $list[$key]['goods_name1'] = $goods_info1['name'];

            $goods_info2 =  $shop_goods->where(array('id'=>$item['goods_id2']))->field('name')->find();
            $list[$key]['goods_name2'] = $goods_info2['name'];

            $goods_info3 =  $shop_goods->where(array('id'=>$item['goods_id3']))->field('name')->find();
            $list[$key]['goods_name3'] = $goods_info3['name'];
        }

        $data = array(
            'list' => $list,
            'page' => $show,
            'at' => 'shopjx',
            'count' => count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus()
    {
        $is_edit = I('get.edit');

        $shop_jx = M('shop_jx');
        $shop_goods = M('shop_goods');

        if ($is_edit) {
            $list = $shop_jx->where(array('id' => $is_edit))->find();

            $where['lyx_shop_goods.id'] = $list['goods_id1'];
            $goods_info1 = $shop_goods->where($where)->join('lyx_shop ON lyx_shop_goods.shop_id = lyx_shop.id')
                ->field('lyx_shop_goods.id,lyx_shop_goods.name,lyx_shop.name as shop_name')
                ->find();

            $where['lyx_shop_goods.id'] = $list['goods_id2'];
            $goods_info2 = $shop_goods->where($where)->join('lyx_shop ON lyx_shop_goods.shop_id = lyx_shop.id')
                ->field('lyx_shop_goods.id,lyx_shop_goods.name,lyx_shop.name as shop_name')
                ->find();

            $where['lyx_shop_goods.id'] = $list['goods_id3'];
            $goods_info3 = $shop_goods->where($where)->join('lyx_shop ON lyx_shop_goods.shop_id = lyx_shop.id')
                ->field('lyx_shop_goods.id,lyx_shop_goods.name,lyx_shop.name as shop_name')
                ->find();


        } else {
            $list = array();
            $goods_info1['id'] = 0;
            $goods_info1['name'] = '';
            $goods_info1['shop_name'] = '';

            $goods_info2['id'] = 0;
            $goods_info2['name'] = '';
            $goods_info2['shop_name'] = '';

            $goods_info3['id'] = 0;
            $goods_info3['name'] = '';
            $goods_info3['shop_name'] = '';
        }

        $data = array(
            'at' => 'shopjx',
            'title' => '精选商品',
            'list' => $list,
            'goods_info1' => $goods_info1,
            'goods_info2' => $goods_info2,
            'goods_info3' => $goods_info3,
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

    public function add($img,$sort, $xz_id1, $xz_id2,$xz_id3,$is_edit)
    {
        $shop_jx = M('shop_jx');
        $shop_goods = M('shop_goods');
        if(!$img && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传图片');$this->ajaxReturn($arr,"JSON");}


        if (!$xz_id1) {$arr = array('code' => 0, 'res' => '请选择商品一');$this->ajaxReturn($arr, "JSON");}
        if (!$xz_id2) {$arr = array('code' => 0, 'res' => '请选择商品二');$this->ajaxReturn($arr, "JSON");}
        if (!$xz_id3) {$arr = array('code' => 0, 'res' => '请选择商品三');$this->ajaxReturn($arr, "JSON");}


        $goods_info1 = $shop_goods->where(array('id'=>$xz_id1))->find();
        if (!$goods_info1) {$arr = array('code' => 0, 'res' => '商品一参数错误');$this->ajaxReturn($arr, "JSON");}
        if ($goods_info1['is_freeze']=='0') {$arr = array('code' => 0, 'res' => '商品一已被冻结');$this->ajaxReturn($arr, "JSON");}
        if ($goods_info1['is_show']=='0') {$arr = array('code' => 0, 'res' => '商品一已下架');$this->ajaxReturn($arr, "JSON");}

        $goods_info2 = $shop_goods->where(array('id'=>$xz_id2))->find();
        if (!$goods_info2) {$arr = array('code' => 0, 'res' => '商品二参数错误');$this->ajaxReturn($arr, "JSON");}
        if ($goods_info2['is_freeze']=='0') {$arr = array('code' => 0, 'res' => '商品二已被冻结');$this->ajaxReturn($arr, "JSON");}
        if ($goods_info2['is_show']=='0') {$arr = array('code' => 0, 'res' => '商品二已下架');$this->ajaxReturn($arr, "JSON");}

        $goods_info3 = $shop_goods->where(array('id'=>$xz_id3))->find();
        if (!$goods_info3) {$arr = array('code' => 0, 'res' => '商品三参数错误');$this->ajaxReturn($arr, "JSON");}
        if ($goods_info3['is_freeze']=='0') {$arr = array('code' => 0, 'res' => '商品三已被冻结');$this->ajaxReturn($arr, "JSON");}
        if ($goods_info3['is_show']=='0') {$arr = array('code' => 0, 'res' => '商品三已下架');$this->ajaxReturn($arr, "JSON");}

        if ($is_edit) {
            $save['goods_id1'] = $xz_id1;
            $save['goods_id2'] = $xz_id2;
            $save['goods_id3'] = $xz_id3;
            $save['cover'] = $img;
            $save['sort'] = $sort;


            $save['update_time'] = time();
            $query = $shop_jx->where(array('id' => $is_edit))->save($save);
            $res = '修改';
        } else {
            $add['goods_id1'] = $xz_id1;
            $add['goods_id2'] = $xz_id2;
            $add['goods_id3'] = $xz_id3;
            $add['cover'] = $img;
            $add['sort'] = $sort;

            $add['create_time'] = time();
            $query = $shop_jx->add($add);
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












