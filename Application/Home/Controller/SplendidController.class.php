<?php

namespace Home\Controller;

use Think\Controller;

class SplendidController extends CommonController
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


        $ad = M('splendid');
        $shop_goods = M('shop_goods');
        $user_blogs = M('user_blogs');

        //$where['is_show'] = 1;
        $count = $ad->count();
        $Page = new \Think\Page($count, 10);
        $Page->rollPage = 5;
        $Page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header', '<p class="rows">共 %TOTAL_ROW%  个推荐</p>');
        $show = $Page->show();// 分页显示输出
        $list = $ad->order('create_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $key => $item) {
            if ($item['type']=='2'){
                $goods_info =  $shop_goods->where(array('id'=>$item['type_id']))->field('name')->find();
                $list[$key]['goods_name'] = $goods_info['name'];
            }else{
                $goods_info =  $user_blogs->where(array('id'=>$item['type_id']))->field('content')->find();
                $list[$key]['goods_name'] = $goods_info['content'];
            }

        }

        $data = array(
            'list' => $list,
            'page' => $show,
            'at' => 'splendid',
            'count' => count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus()
    {
        $is_edit = I('get.edit');

        $ad = M('splendid');
        $shop_goods = M('shop_goods');
        $user_blogs = M('user_blogs');

        if ($is_edit) {
            $list = $ad->where(array('id' => $is_edit))->find();
            $type = $list['type'];

            if ($type=='1'){
                $where['lyx_user_blogs.id'] = $list['type_id'];
                $goods_info = $user_blogs->where($where)->join('lyx_diary_classify ON lyx_user_blogs.class_type = lyx_diary_classify.id')
                    ->field('lyx_user_blogs.id,lyx_user_blogs.content as name,lyx_diary_classify.name as shop_name')
                    ->find();
            }else{
                $where['lyx_shop_goods.id'] = $list['type_id'];
                $goods_info = $shop_goods->where($where)->join('lyx_shop ON lyx_shop_goods.shop_id = lyx_shop.id')
                    ->field('lyx_shop_goods.id,lyx_shop_goods.name,lyx_shop.name as shop_name')
                    ->find();
            }


        } else {
            $list = array();
            $goods_info['id'] = 0;
            $goods_info['name'] = '';
            $goods_info['shop_name'] = '';
        }
        if (!$type){$type=1;}
        $data = array(
            'at' => 'splendid',
            'title' => '精彩推荐',
            'type' => $type,
            'list' => $list,
            'goods_info' => $goods_info,
            'is_edit' => $is_edit
        );

        $this->assign($data);
        $this->display();
    }


    function SearchGoods($search_val,$type) {

        if ($type=='1'){
            $user_blogs = M('user_blogs');
            $diary_classify = M('diary_classify');
            $where['content'] = array("like", "%" . $search_val . "%");

            $list = $user_blogs->where($where)->field('id,content as name,class_type')->select();
            foreach ($list as $key=>$item){
                $class_name = $diary_classify->where(array('id'=>$item['class_type']))->field('name')->find();
                $list[$key]['class_name'] = $class_name['name'];
            }

            foreach ($list as $item){
               $new_list[$item['id']] = $item;
            }


        }else{
            $shop_goods = M('shop_goods');
            if (!$search_val) {
                $arr = array('code' => 0, 'res' => '请输入商品名称');

                $this->ajaxReturn($arr, "JSON");
            }
            $where['lyx_shop_goods.name'] = array("like", "%" . $search_val . "%");

            $list = $shop_goods->where($where)->join('lyx_shop ON lyx_shop_goods.shop_id = lyx_shop.id')
                ->field('lyx_shop_goods.id,lyx_shop_goods.name,lyx_shop.name as shop_name')
                ->select();

            foreach ($list as $item){
                $new_list[$item['id']] = $item;
            }
        }
        $res = '查询';


        if ($list) {
            $arr = array(
                'code' => 1,
                'res' => $res . '成功',
                'list' => $new_list
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

    public function add($type, $sort, $xz_id,$is_edit)
    {
        $splendid = M('splendid');
        $user_blogs = M('user_blogs');
        $shop_goods = M('shop_goods');

        if($type=='1'){
            if (!$xz_id) {$arr = array('code' => 0, 'res' => '请选择日志');$this->ajaxReturn($arr, "JSON");}
            $blogs_info = $user_blogs->where(array('id'=>$xz_id))->find();
            if (!$blogs_info) {$arr = array('code' => 0, 'res' => '日志参数错误');$this->ajaxReturn($arr, "JSON");}
            if ($blogs_info['is_show']=='0') {$arr = array('code' => 0, 'res' => '日志当前状态为不显示，无法选取');$this->ajaxReturn($arr, "JSON");}

            if ($is_edit){
                $where_1['id'] = array('NEQ',$is_edit);
            }
            $where_1['type'] = 1;
            $where_1['type_id'] = $xz_id;
            $is_add = $splendid->where($where_1)->find();
            if ($is_add) {$arr = array('code' => 0, 'res' => '您已添加过该日志推荐，请勿重复添加');$this->ajaxReturn($arr, "JSON");}
        }else{
            if (!$xz_id) {$arr = array('code' => 0, 'res' => '请选择商品');$this->ajaxReturn($arr, "JSON");}
            $goods_info = $shop_goods->where(array('id'=>$xz_id))->find();
            if (!$goods_info) {$arr = array('code' => 0, 'res' => '商品参数错误');$this->ajaxReturn($arr, "JSON");}
            if ($goods_info['is_freeze']=='0') {$arr = array('code' => 0, 'res' => '商品已被冻结');$this->ajaxReturn($arr, "JSON");}
            if ($goods_info['is_show']=='0') {$arr = array('code' => 0, 'res' => '商品已被下架');$this->ajaxReturn($arr, "JSON");}

            if ($is_edit){
                $where_2['id'] = array('NEQ',$is_edit);
            }
            $where_2['type'] = 2;
            $where_2['type_id'] = $xz_id;
            $is_add = $splendid->where($where_2)->find();
            if ($is_add) {$arr = array('code' => 0, 'res' => '您已添加过该商品推荐，请勿重复添加');$this->ajaxReturn($arr, "JSON");}
        }


        if ($is_edit) {

            $save['type_id'] = $xz_id;
            $save['type'] = $type;
            $save['sort'] = $sort;

            $save['update_time'] = time();
            $query = $splendid->where(array('id' => $is_edit))->save($save);
            $res = '修改';
        } else {
            $add['type_id'] = $xz_id;
            $add['type'] = $type;
            $add['sort'] = $sort;
            $add['create_time'] = time();
            $query = $splendid->add($add);
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












