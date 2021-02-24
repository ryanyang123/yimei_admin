<?php
namespace Home\Controller;
use Think\Controller;
class OrderController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'order'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }

    public function index(){


        $shop_order = M('shop_order');
        $user = M('user');
        $shop = M('shop');
        $shop_goods = M('shop_goods');

        $search = I('get.search');
        if($search){
            $where['lyx_shop_order.order_number'] =  array("like","%".$search."%");;
            $search_list['search'] = $search;
        }

        $search2 = I('get.search2');
        if($search2){
            $where['lyx_shop_order.mobile|lyx_shop_order.addressee|lyx_shop_order.location|lyx_shop_order.particular|lyx_shop_goods.name'] =  array("like","%".$search2."%");;
            $search_list['search2'] = $search2;
        }

        $order_number = I('get.order_number');
        if($order_number){
            $where['lyx_shop_order.order_number'] = $order_number;
        }

        $user_id = I('get.user_id');
        if($user_id){
            $where['lyx_shop_order.user_id'] =  $user_id;
            $search_list['user_id'] = $user_id;
        }

        $money_type = I('get.money_type');
        if($money_type){
            $where['lyx_shop_order.money_type'] =  $money_type;
            $search_list['money_type'] = $money_type;
        }

        $pay_type = I('get.pay_type');
        if($pay_type){
            $where['lyx_shop_order.pay_type'] =  $pay_type;
            $search_list['pay_type'] = $pay_type;
        }

        $status = I('get.status');
        if($status){
            $where['lyx_shop_order.status'] =  $status;
            $search_list['status'] = $status;
        }else{
            $where['lyx_shop_order.status'] =  array("NEQ",'0');
        }


        $refund_status = I('get.refund_status');
        if($refund_status){
            $where['lyx_shop_order.refund_status'] =  $refund_status-1;
            $search_list['refund_status'] = $refund_status;
        }

        $is_ti = I('get.is_ti');
        if($is_ti){
            $where['lyx_shop_order.is_ti'] =  $is_ti-1;
            $search_list['is_ti'] = $is_ti;
        }


        $page_type = I('get.page_type');
        $page_type = $page_type?$page_type:1;
        $search_list['page_type'] = $page_type;
        if($page_type==1){
            $where['lyx_shop_order.status'] =  array('IN','1,2,3');
            $where['lyx_shop_order.refund_status'] =  array('NOT IN','4,5');
            $where['lyx_shop.service_type'] =  1;
        }

        //时间搜索
        $search_date  = I('get.search_date');
        $search_date2  = I('get.search_date2');
        //$data['search_date'] = $search_date;
        //$data['search_date2'] = $search_date2;
        $newtime = strtotime($search_date);
        $newtime1 = strtotime($search_date2)+86399;
        if($search_date && $search_date2){
            $where['lyx_shop_order.pay_time'] = array(array('EGT',$newtime),array('ELT',$newtime1),'AND');
        }else if($search_date && !$search_date2){
            $where['lyx_shop_order.pay_time'] = array('EGT',$newtime);
        }else if(!$search_date && $search_date2){
            $where['lyx_shop_order.pay_time'] = array('ELT',$newtime1);
        }
        $search_list['search_date'] = $search_date;
        $search_list['search_date2'] = $search_date2;
        //时间搜索

        //$where['lyx_shop_order.pay_status'] = 1;
        $count = $shop_order
            ->join('lyx_shop ON lyx_shop_order.shop_id = lyx_shop.id')
            ->join('lyx_shop_goods ON lyx_shop_order.goods_id = lyx_shop_goods.id')->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个订单</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $shop_order->where($where)
            ->join('lyx_shop_goods ON lyx_shop_order.goods_id = lyx_shop_goods.id')
            ->join('lyx_shop ON lyx_shop_order.shop_id = lyx_shop.id')
            ->field('lyx_shop_order.*,lyx_shop_goods.name,lyx_shop_goods.cover')
            ->order('lyx_shop_order.create_time desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();
        foreach($list as $key=>$item){
            $user_name = $user->where(array('id'=>$item['user_id']))->field('id,name')->find();
            $list[$key]['user_name'] = $user_name['name'];

            //店铺名称
            $shop_name = $shop->where(array('id'=>$item['shop_id']))->field('name')->find();
            $list[$key]['shop_name'] = $shop_name['name'];

           /* $goods_info = $shop_goods->where(array('id'=>$item['goods_id']))->field('name,cover')->find();
            $list[$key]['goods_info'] = $goods_info;*/

        }


        $data = array(
            'search_list' =>$search_list,
            'list' =>$list,
            'page' =>$show,
            'at' =>'order',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }



    function check(){
        $is_edit  =  I('get.edit');

        $shop_order = M('shop_order');
        $user = M('user');
        $shop = M('shop');
        $shop_goods = M('shop_goods');
        $shop_order_goods = M('shop_order_goods');
        $order_comment = M('order_comment');
        $order_comment_photo = M('order_comment_photo');



        if($is_edit){
            $list = $shop_order->where(array('id'=>$is_edit))->find();


            $user_name = $user->where(array('id'=>$list['user_id']))->field('id,name')->find();
            $list['user_name'] = $user_name['name'];

            //店铺名称
            $shop_name = $shop->where(array('id'=>$list['shop_id']))->field('name,user_id')->find();
            $shop_user = $user->where(array('id'=>$shop_name['user_id']))->field('phone')->find();
            $list['shop_name'] = $shop_name['name'];
            $list['shop_phone'] = $shop_user['phone'];

            $goods_info = $shop_goods->where(array('id'=>$list['goods_id']))->field('name,cover,price')->find();
            $list['goods_info'] = $goods_info;


            $select_info = $shop_order_goods->where(array('order_id'=>$list['id']))->find();
            $list['select_info'] = $select_info;

            if($list['status']=='6'){
                $com_info = $order_comment->where(array('order_id'=>$list['id'],'order_type'=>'1'))->field('id,type,content,grade')->find();
                $com_info['no_xing'] = 5 - $com_info['grade'];
                if($com_info['type']=='1'){
                    $commnet_img = $order_comment_photo->where(array('blogs_id'=>$com_info['id']))->select();
                    foreach ($commnet_img as $item){
                        $img_list[]= $item['img_url'];
                    }
                    $com_info['img_list'] = $img_list;
                }
            }
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'order',
            'title'=>'订单详细',
            'list'=>$list,
            'com_info'=>$com_info,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }


    function wuliu($order_id){
        $shop_order = M('shop_order');
        $shop_order_express = M('shop_order_express');

        $order_info = $shop_order->where(array('id'=>$order_id))->find();
        if ($order_info['express_type']!=1) {$arr = array('code' => 0, 'res' => '此订单无物流信息！');$this->ajaxReturn($arr, "JSON");}
        $is_bao = $shop_order_express->where(array('parent'=>$order_info['id']))->find();
        $head_info['express_number'] = $order_info['express_number'];

        if($is_bao){
            $head_info['express_name'] = $order_info['express_name'];
            $data = json_decode($is_bao['data'],true);
            $list = $data['showapi_res_body']['data'];
            $arr = array(
                'code'=> 1,
                'res'=>'查询成功',
                'head_info'=>$head_info,
                'list'=>$list,
            );
        }else{
            if (!$order_info['express_phone']) {$arr = array('code' => 0, 'res' => '未查询到此订单物流信息！');$this->ajaxReturn($arr, "JSON");}
            if (!$order_info['express_number']) {$arr = array('code' => 0, 'res' => '未查询到此订单物流信息！');$this->ajaxReturn($arr, "JSON");}

            if($order_info['express_code']){
                $com = $order_info['express_code'];
            }else{
                $com = 'auto';
            }
            $host = "https://ali-deliver.showapi.com";
            $path = "/showapi_expInfo";
            $method = "GET";
            $appcode = "cfb98726e660404280d1a73d271fc1e6";
            $headers = array();
            array_push($headers, "Authorization:APPCODE " . $appcode);
            //$com = 'auto';
            $number = $order_info['express_number'];
            $phone = $order_info['express_phone'];
            $querys = "com=".$com."&nu=".$number."&receiverPhone=".$phone;
            $bodys = "";
            $url = $host . $path . "?" . $querys;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_FAILONERROR, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            if (1 == strpos("$".$host, "https://"))
            {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            }
            $res = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($res,true);
            $status = $data['showapi_res_body']['status'];
            if($data['showapi_res_code']==0){
                if($status==4){
                    $add['parent'] = $order_info['id'];
                    $add['data'] = $res;
                    $add['create_time'] = time();
                    $shop_order_express->add($add);
                }
                if (!$order_info['express_name']) {
                    $save['express_name'] = $data['showapi_res_body']['expTextName'];
                    $save['update_time'] = time();
                    $shop_order->where(array('id'=>$order_info['id']))->save($save);
                    $head_info['express_name'] = $data['showapi_res_body']['expTextName'];
                }else{
                    $head_info['express_name'] = $order_info['express_name'];
                }

                $list = $data['showapi_res_body']['data'];
                $arr = array(
                    'code'=> 1,
                    'res'=>'查询成功',
                    'head_info'=>$head_info,
                    'list'=>$list,
                );

            }else{
                $arr = array('code' => 0, 'res' => $arr['showapi_res_error']);$this->ajaxReturn($arr, "JSON");
            }

        }

        $this->ajaxReturn($arr,"JSON");

    }

    function edittui($id,$type,$money){
        $shop_order = M('shop_order');
        $save_a['update_time'] = time();
        $save_a['refund_status'] = '5';
        $order_info = M('shop_order')->where(array('id'=>$id))->find();
        if($order_info['status']=='3' && $order_info['refund_status']=='3'){
            if($type=='2'){
                if($money<=0){
                    $arr = array(
                        'code'=> 0,
                        'res'=> '退款金额不能小于0'
                    );
                    $this->ajaxReturn($arr,"JSON");
                }
                if($money > $order_info['money']){
                    $arr = array(
                        'code'=> 0,
                        'res'=> '退款金额不能大于支付金额'
                    );
                    $this->ajaxReturn($arr,"JSON");
                }
            }
            $query = $shop_order->where(array('id'=>$id))->save($save_a);
            $res = "退款";
            if($query){
                $add_tui['user_id'] = $order_info['user_id'];
                $add_tui['type'] = 1;
                $add_tui['type_id'] = $order_info['id'];
                if($type=='2'){
                    $add_tui['money'] = $money;
                }else{
                    $add_tui['money'] = $order_info['money'];
                   /* $where_add['user_id'] = $order_info['user_id'];
                    $where_add['order_id'] = $order_info['id'];
                    $where_add['type'] = 2;
                    $jian = M('team_money')->where($where_add)->sum('money');
                    if($jian){
                        $add_tui['money'] = bcsub($order_info['money'],$jian,2);
                    }*/
                }

                $add_tui['create_time'] = time();
                M('tui')->add($add_tui);
                $tis = '退款中';

                M('shop_goods')->where(array('id'=>$order_info['goods_id']))->setDec('sales',$order_info['total_num']);
                M('shop_goods')->where(array('id'=>$order_info['goods_id']))->setInc('stock',$order_info['total_num']);

                //新版
                $add_system['user_id'] = $order_info['user_id'];
                $add_system['send_user_id'] = '';
                $add_system['content'] = $tis;
                $add_system['type'] = 3;//1预约成功，2交易成功，3退款成功，4退款失败
                $add_system['type_id'] = $order_info['id'];
                $add_system['status'] = 2;
                $add_system['create_time'] = time();
                M('system')->add($add_system);

                $rong_user[] = $order_info['user_id'];

                SendRongSystem($rong_user,$tis);
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
        }else{
            $arr = array(
                'code'=> 0,
                'res'=> '订单状态错误'
            );
            $this->ajaxReturn($arr,"JSON");
        }

    }

    function anpai($id,$driver_id,$vehicle_id,$remark){
        $order = M('order');
        if(!$driver_id){$arr = array('code'=> 0, 'res'=>'请选择司机');$this->ajaxReturn($arr,"JSON");}
        if(!$vehicle_id){$arr = array('code'=> 0, 'res'=>'请选择车辆');$this->ajaxReturn($arr,"JSON");}
        $order_info = $order->where(array('id'=>$id))->find();
        if(!$order_info){
            $arr = array('code'=> 0, 'res'=>'订单错误');$this->ajaxReturn($arr,"JSON");
        }
        if($order_info['status']!='0'){
            $arr = array('code'=> 0, 'res'=>'订单已安排');$this->ajaxReturn($arr,"JSON");
        }

        $save['status'] = 1;
        $save['driver'] = $driver_id;
        $save['vehicle'] = $vehicle_id;
        $save['remark'] = $remark;
        $save['arrange_time'] = time();
        $query = $order->where(array('id'=>$id))->save($save);
        $res = '安排';
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

    public function add($title,$content,$is_edit){
        $activity = M('activity');

        if(!$title){$arr = array('code'=> 0, 'res'=>'请输入标题');$this->ajaxReturn($arr,"JSON");}
        if(!$content){$arr = array('code'=> 0, 'res'=>'请输入内容');$this->ajaxReturn($arr,"JSON");}


        if($is_edit){

            $save['title'] = $title;
            $save['content'] = $content;

            $save['update_time'] = time();
            $query = $activity->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['title'] = $title;
            $add['content'] = $content;

            $add['create_time'] = time();
            $query = $activity->add($add);
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












