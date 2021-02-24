<?php
namespace Home\Controller;
use Think\Controller;
class HomeadController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'banner'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $home_ad = M('home_ad');
        $shop = M('shop');
        $ticket = M('ticket');
        $shop_goods = M('shop_goods');


        $is_show = I('get.is_show');
        if($is_show){
            $where['is_show'] =  $is_show-1;
            $search_list['is_show'] = $is_show;
        }

        $status = I('get.status');
        if($status){
            $where['type'] =  $status-1;
            $search_list['status'] = $status;
        }

        //时间搜索
        $search_date  = I('get.search_date');
        $search_date2  = I('get.search_date2');
        //$data['search_date'] = $search_date;
        //$data['search_date2'] = $search_date2;
        $newtime = strtotime($search_date);
        $newtime1 = strtotime($search_date2)+86399;
        if($search_date && $search_date2){
            $where['create_time'] = array(array('EGT',$newtime),array('ELT',$newtime1),'AND');
        }else if($search_date && !$search_date2){
            $where['create_time'] = array('EGT',$newtime);
        }else if(!$search_date && $search_date2){
            $where['create_time'] = array('ELT',$newtime1);
        }
        $search_list['search_date'] = $search_date;
        $search_list['search_date2'] = $search_date2;
        //时间搜索


        //$where['is_show'] = 1;
        $count = $home_ad->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个弹出视频</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $home_ad->where($where)->order('sort,create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            if ($item['type']==2){
                $hos_name = $shop->where(array('id'=>$item['to_url']))->field('name')->find();
                $list[$key]['hos_name'] = $hos_name['name'];
            }
            if ($item['type']==3){
                $hos_name = $ticket->where(array('id'=>$item['to_url']))->field('title')->find();
                $list[$key]['hos_name'] = $hos_name['title'];
            }
            if ($item['type']==4){
                $hos_name = $shop_goods->where(array('id'=>$item['to_url']))->field('name')->find();
                $list[$key]['hos_name'] = $hos_name['name'];
            }
        }


        $data = array(
            'list' =>$list,
            'search_list' =>$search_list,
            'page' =>$show,
            'is_show' =>$is_show,
            'status' =>$status,
            'at' =>'homead',
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

        $home_ad = M('home_ad');
        $shop = M('shop');
        $ticket = M('ticket');


        if($is_edit){
            $list = $home_ad->where(array('id'=>$is_edit))->find();
            if ($list){
                if ($list['type']=='4'){
                    $where['lyx_shop_goods.id'] = $list['to_url'];
                    $goods_info = M('shop_goods')->where($where)->join('lyx_shop ON lyx_shop_goods.shop_id = lyx_shop.id')
                        ->field('lyx_shop_goods.id,lyx_shop_goods.name,lyx_shop.name as shop_name')
                        ->find();
                }
            }
        }else{
            $list = array();
            $goods_info = array();
        }
        $game_list = $shop->where(array('status'=>'1'))->field('id,name')->select();
        $ticket_list = $ticket->select();


        $data = array(
            'at'=>'homead',
            'title'=>'弹出视频',
            'ticket_list'=>$ticket_list,
            'goods_info'=>$goods_info,
            'game_list'=>$game_list,
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($img,$show_time,$ad_type,$banner_type,$url_link,$game_id,$sort,$is_edit){
        $home_ad = M('home_ad');
        $cache = M('cache');

        if($ad_type=='1'){
            if(!$img && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传图片');$this->ajaxReturn($arr,"JSON");}

        }else{
            if(!$img && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传视频');$this->ajaxReturn($arr,"JSON");}

        }
        if($show_time<1){$arr = array('code'=> 0, 'res'=>'显示时间最低1秒');$this->ajaxReturn($arr,"JSON");}

        if($banner_type==1){
            if(!$url_link){$arr = array('code'=> 0, 'res'=>'请输入跳转的外部链接URL');$this->ajaxReturn($arr,"JSON");}
            $save['to_url'] = $url_link;
            $add['to_url'] = $url_link;
        }

        if($banner_type==2){
            if(!$game_id){$arr = array('code'=> 0, 'res'=>'请选择机构');$this->ajaxReturn($arr,"JSON");}
            $save['to_url'] = $game_id;
            $add['to_url'] = $game_id;
        }
        if($banner_type==3){
            if(!$game_id){$arr = array('code'=> 0, 'res'=>'请选择优惠券');$this->ajaxReturn($arr,"JSON");}
            $save['to_url'] = $game_id;
            $add['to_url'] = $game_id;
        }
        if($banner_type==4){
            if(!$game_id){$arr = array('code'=> 0, 'res'=>'请选择商品');$this->ajaxReturn($arr,"JSON");}
            $save['to_url'] = $game_id;
            $add['to_url'] = $game_id;
        }

        if($is_edit){
            if($img){
                $save['img_url'] = $img;
            }
            $save['sort'] = $sort;
            $save['ad_type'] = $ad_type;
            if($ad_type=='1'){
                $size_info = getimagesize($img);
                $save['width'] = $size_info[0];
                $save['height'] = $size_info[1];
            }else{
                $cache_info = $cache->where(array('url'=>$img))->find();
                $save['width'] = $cache_info['width'];
                $save['height'] = $cache_info['height'];
                $save['thumb'] = $cache_info['thumb'];
            }
            $save['type'] = $banner_type;
            $save['show_time'] = $show_time;
            $save['update_time'] = time();
            $query = $home_ad->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['img_url'] = $img;
            $add['sort'] = $sort;
            $add['ad_type'] = $ad_type;
            $add['type'] = $banner_type;
            $add['show_time'] = $show_time;
            $add['create_time'] = time();
            $query = $home_ad->add($add);
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












