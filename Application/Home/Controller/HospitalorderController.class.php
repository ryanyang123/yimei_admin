<?php
namespace Home\Controller;
use Think\Controller;
class HospitalorderController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'hospitalorder'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }

    public function index(){

        $user = M('user');
        $doctor = M('doctor');
        $hospital = M('hospital');
        $hospital_order = M('hospital_order');
        $ticket_user = M('ticket_user');
        $cache = M('cache');

        $hos_list= $hospital->field('id,name')->select();
        foreach ($hos_list as $item){
            $new_hos[$item['id']] = $item['name'];
        }

        $search = I('get.search');
        if($search){
            $where['order_number'] =  array("like","%".$search."%");;
            $search_list['search'] = $search;
        }


        $order_number = I('get.order_number');
        if($order_number){
            $where['order_number'] = $order_number;
        }

        $user_id = I('get.user_id');
        if($user_id){
            $where['user_id'] =  $user_id;
            $search_list['user_id'] = $user_id;
        }

        $parent = I('get.parent');
        if($parent){
            $where['parent'] =  $parent;
            $search_list['parent'] = $parent;
        }

        $status = I('get.status');
        if($status){
            $where['status'] =  $status-1;
            $search_list['status'] = $status;
        }


        $type = I('get.type');
        if($type){
            $where['type'] =  $type;
            $search_list['type'] = $type;
        }


        $page_type = I('get.page_type');
        $page_type = $page_type?$page_type:1;
        $search_list['page_type'] = $page_type;
        if($page_type==1){
            $where['status'] =  array('IN','2,3');
            $where['refund_status'] =  array('NOT IN','2,4,5,6');
        }

        //时间搜索
        $search_date  = I('get.search_date');
        $search_date2  = I('get.search_date2');
        //$data['search_date'] = $search_date;
        //$data['search_date2'] = $search_date2;
        $newtime = strtotime($search_date);
        $newtime1 = strtotime($search_date2)+86399;
        if($search_date && $search_date2){
            $where['pay_time'] = array(array('EGT',$newtime),array('ELT',$newtime1),'AND');
        }else if($search_date && !$search_date2){
            $where['pay_time'] = array('EGT',$newtime);
        }else if(!$search_date && $search_date2){
            $where['pay_time'] = array('ELT',$newtime1);
        }
        $search_list['search_date'] = $search_date;
        $search_list['search_date2'] = $search_date2;
        //时间搜索




        $count = $hospital_order->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条订单</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $hospital_order->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $user_name = $user->where(array('id'=>$item['user_id']))->field('id,name,phone')->find();
            $list[$key]['user_name'] = $user_name['name'];
            $list[$key]['phone'] = $user_name['phone'];

            $list[$key]['hos_name'] = $new_hos[$item['parent']];

            $doc_info = $doctor->where(array('id'=>$item['doctor_id']))->field('name')->find();
            $list[$key]['doc_name'] = $doc_info['name'];

            $ticket_info = $ticket_user
                ->where(array('lyx_ticket_user.id'=>$item['ticket_id']))
                ->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')
                ->field('lyx_ticket.title')->find();
            $list[$key]['tic_name'] = $ticket_info['title'];
        }


        $data = array(
            'list' =>$list,
            'search_list' =>$search_list,
            'hos_list' =>$hos_list,
            'page' =>$show,
            'at' =>'hospitalorder',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function check(){
        $is_edit  =  I('get.edit');

        $user = M('user');
        $doctor = M('doctor');
        $hospital = M('hospital');
        $hospital_order = M('hospital_order');
        $ticket_user = M('ticket_user');
        $ticket = M('ticket');
        $hospital_comment = M('order_comment');
        $hospital_comment_photo = M('order_comment_photo');


        if($is_edit){
            $list = $hospital_order->where(array('id'=>$is_edit))->find();

            $user_name = $user->where(array('id'=>$list['user_id']))->field('id,name,phone')->find();
            $list['user_name'] = $user_name['name'];
            $list['phone'] = $user_name['phone'];


            $hos_info = $hospital->where(array('id'=>$list['parent']))->field('name')->find();
            $list['hos_name'] = $hos_info['name'];


            if($list['type']=='2'){
                $ticket_list = explode(',',$list['ticket_list']);
                foreach ($ticket_list as $item){
                    $new_ticket[$item]['id'] = $item;
                    $ticket_info = $ticket->where(array('id'=>$item))->field('title')->find();
                    $new_ticket[$item]['title'] = $ticket_info['title'];
                }

                $list['tic_list'] = $new_ticket;
            }


            $doc_info = $doctor->where(array('id'=>$list['doctor_id']))->field('name')->find();
            $list['doc_name'] = $doc_info['name'];

            $ticket_info = $ticket_user
                ->where(array('lyx_ticket_user.id'=>$list['ticket_id']))
                ->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')
                ->field('lyx_ticket.title,lyx_ticket_user.discount')->find();
            $list['tic_name'] = $ticket_info['title'];
            $list['tic_discount'] = $ticket_info['discount']/10;

            $com_info = $hospital_comment->where(array('order_id'=>$list['id'],'order_type'=>'2'))->field('id,type,content,grade')->find();

            if($com_info['type']=='1'){
                $com_info['no_xing'] = 5 - $com_info['grade'];
                $commnet_img = $hospital_comment_photo->where(array('blogs_id'=>$com_info['id']))->select();
                foreach ($commnet_img as $item){
                    $img_list[]= $item['img_url'];
                }
                $com_info['img_list'] = $img_list;
            }

        }else{
            $list = array();
            $goods_list = array();
        }

        $data = array(
            'at'=>'hospitalorder',
            'title'=>'医院订单',
            'list'=>$list,
            'com_info'=>$com_info,
            'goods_list'=>$goods_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }



    function plus(){
        $is_edit  =  I('get.edit');

        $shop = M('shop');
        //$shop_photo = M('shop_photo');

        if($is_edit){
            $list = $shop->where(array('id'=>$is_edit))->find();
           //$phone_list = $shop_photo->where(array('shop_id'=>$is_edit))->field('id,img_url')->select();
            //$list['banner_list'] = $phone_list;
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'shop',
            'title'=>'店铺',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }



    public function add($name,$classify,$mobile,$introduce,$way,$img1,$img_arr,$is_edit){
        $shop = M('shop');
        $shop_photo = M('shop_photo');


        if(!$is_edit){$arr = array('code'=> 0, 'res'=>'不可新增店铺');$this->ajaxReturn($arr,"JSON");}
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入店铺名称');$this->ajaxReturn($arr,"JSON");}
        if(!$classify){$arr = array('code'=> 0, 'res'=>'请输入店铺分类');$this->ajaxReturn($arr,"JSON");}
        if(!$mobile){$arr = array('code'=> 0, 'res'=>'请输入店铺电话');$this->ajaxReturn($arr,"JSON");}
        if(!$introduce){$arr = array('code'=> 0, 'res'=>'请输入店铺服务介绍');$this->ajaxReturn($arr,"JSON");}
        if(!$way){$arr = array('code'=> 0, 'res'=>'请输入店铺服务方式');$this->ajaxReturn($arr,"JSON");}




        $save['shop_name'] = $name;
        $save['classify'] = $classify;
        $save['mobile'] = $mobile;
        $save['introduce'] = $introduce;
        $save['way'] = $way;
        if($img1){
            $save['logo'] = $img1;
        }



        $save['update_time'] = time();
        $query = $shop->where(array('id'=>$is_edit))->save($save);
        $res = '修改';


        if($query){
            if ($img_arr){
                $add_photo['create_time'] = time();
                $add_photo['shop_id'] = $is_edit;
                foreach ($img_arr as $item){
                    $add_photo['img_url'] = $item;
                    $shop_photo->add($add_photo);
                }
            }
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

    function CompleteOrder($id,$cost,$loans){
        $hospital_order = M('hospital_order');
        $shop_goods = M('shop_goods');
        $order_info = $hospital_order->where(array('id'=>$id))->find();
        if($order_info['status']!='2'){$arr = array('code'=> 0, 'res'=>'订单当前状态无法完成交易操作');$this->ajaxReturn($arr,"JSON");}

        //平台抽水
        $shop_type = M('shop')->where(array('hospital_id'=>$order_info['parent']))->field('odds_type,odds')->find();

        if($loans){
            if($loans>$order_info['total_money']){$arr = array('code'=> 0, 'res'=>'贷款金额不能大于订单总金额');$this->ajaxReturn($arr,"JSON");}
            $save_order['is_loans'] = 1;
            $save_order['loans'] = $loans;
        }else{
            $loans = $order_info['loans'];
        }

        if($cost){
            $hos_order_cost = M('set')->where(array('set_type'=>'hos_order_cost'))->find();
            $cost_bai = ($cost/$order_info['total_money'])*100;
            if($cost_bai>$hos_order_cost['set_value']){$arr = array('code'=> 0, 'res'=>'杂费不能大于总金额的 '.$hos_order_cost['set_value'].' %');$this->ajaxReturn($arr,"JSON");}
            $save_order['cost'] = $cost;
        }else{
            $cost = $order_info['cost'];
        }


        if($shop_type['odds']!=NULL){
            $chou_odds['set_value']  = $shop_type['odds'];
        }else{
            if(!$shop_type['odds_type']){
                $odds_type_id = $shop_type['odds_type'];
            }else{
                $odds_type_id = 1;
            }
            $odds_type_odds = M('odds_type')->where(array('id'=>$odds_type_id))->field('odds')->find();
            $chou_odds['set_value']   = $odds_type_odds['odds'];
        }

        if($chou_odds['set_value']!='0'){
            $chou_money = $order_info['total_money'] - $cost - $loans;
            $odds = $chou_odds['set_value']/100;
            $chou = $chou_money * $odds;
            if($chou<0){
                $chou = 0;
            }
            $save_order['chou'] = $chou;
            $save_order['odds'] = $chou_odds['set_value'];
        }

        $save_order['is_ok'] = 1;
        $save_order['status'] = 4;
        $save_order['ok_time'] = time();
        $save_order['update_time'] = time();
        $query = $hospital_order->where(array('id'=>$order_info['id']))->save($save_order);
        $res = '操作';
        if($query){
            //代理抽水
            HosOrderAgentChou($order_info['id']);

            //城市合伙人抽水
            cityRebateMoney(2,$order_info['id']);

            //交易完成，添加对应商品销量
            if($order_info['goods_id']){
                $shop_goods->where(array('id'=>$order_info['goods_id']))->setInc('sales',1);
            }
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


    function TuiMoney($id,$type,$money){
        if($type=='2'){
            if($money<=0){$arr = array('code'=> 0, 'res'=>'退款金额不能小于等于0');$this->ajaxReturn($arr,"JSON");}

        }
        $hospital_order = M('hospital_order');
        $order_info = $hospital_order->where(array('id'=>$id))->find();
        if($order_info['status']!=3){
            if($order_info['refund_status']!=1 && $order_info['refund_status']!=3){
                $arr = array('code'=> 0, 'res'=>'此订单当前状态无法进行退款操作');$this->ajaxReturn($arr,"JSON");
            }
        }
        if($money>$order_info['money']){$arr = array('code'=> 0, 'res'=>'退款金额不能大于实际支付金额');$this->ajaxReturn($arr,"JSON");}

        $res = '操作';
        if($type=='2'){
            $save_order['refund_status'] = 6;
            $save_order['refund_money'] = $money;
            $save_order['update_time'] = time();
            $add_system['type'] = 5;//1申请退款，2客服介入，3订单退款中，4取消退款，5订单退款成功,6医院拒绝退款
            $tis = '退款成功';
        }else{
            $save_order['refund_status'] = 2;
            $save_order['refund_money'] = 0;
            $save_order['update_time'] = time();
            $add_system['type'] = 6;//1申请退款，2客服介入，3订单退款中，4取消退款，5订单退款成功,6医院拒绝退款
            $tis = '退款失败';
        }
        $query = $hospital_order->where(array('id'=>$order_info['id']))->save($save_order);


        if($query){

            //新版
            $add_system['user_id'] = $order_info['user_id'];
            $add_system['send_user_id'] ='0';
            $add_system['content'] = $tis;
            $add_system['type_id'] = $order_info['id'];
            $add_system['status'] = 6;
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

    }


}












