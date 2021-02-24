<?php
namespace Home\Controller;
use Think\Controller;
class CreateorderController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'createorder'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }



    function index(){
        $is_edit  =  I('get.edit');

        $hospital = M('hospital');
        $shop_goods = M('shop_goods');
        //$shop_photo = M('shop_photo');
        $list = $hospital->field('id,name')->select();


        $goods_list= $shop_goods->order('lyx_shop_goods.show_num desc,lyx_shop_goods.create_time desc')->limit(5)->join('lyx_shop ON lyx_shop_goods.shop_id = lyx_shop.id')
            ->field('lyx_shop_goods.id,lyx_shop_goods.name,lyx_shop.name as shop_name')
            ->select();
        foreach ($goods_list as $item){
            $new_list[$item['id']] = $item;
        }
        $data = array(
            'at'=>'createorder',
            'title'=>'生成医院订单',
            'list'=>$list,
            'goods_list'=>$new_list,
            'goods_arr'=>json_encode($new_list),
            'is_edit'=>$is_edit
        );



        $this->assign($data);
        $this->display();
    }


    function checkdoctor($parent,$type){
        $doctor = M('doctor');
        $ticket_hospital = M('ticket_hospital');



        $list = $doctor->where(array('hospital_id'=>$parent))->field('id,name')->select();

        if ($type=='1'){
            $where_ticket['lyx_ticket_hospital.hospital_id'] = $parent;
            $where_ticket['lyx_ticket.type'] = 2;
        }else{
            $where_ticket['lyx_ticket_hospital.hospital_id'] = $parent;
            $where_ticket['lyx_ticket.type'] = array('NEQ','2');
            $where_ticket['lyx_ticket.use_type'] = 2;
        }

        $tic_list = $ticket_hospital->where($where_ticket)
            ->join('lyx_ticket ON lyx_ticket_hospital.ticket_id = lyx_ticket.id')
            ->field('lyx_ticket.id,lyx_ticket.title')
            ->select();
        $res = '查询';


        if($tic_list){
            $is_tic = 1;
        }else{
            $is_tic = 0;
        }
        if($list){

            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功',
                'list'=> $list,
                'is_tic'=> $is_tic,
                'tic_list'=> $tic_list
            );

            $this->ajaxReturn($arr,"JSON");
        }else{
            $arr = array(
                'code'=> 0,
                'res'=> '此医院还未添加医生',
                'is_tic'=> $is_tic,
                'tic_list'=> $tic_list,
            );
            $this->ajaxReturn($arr,"JSON");
        }
    }



    public function add($type,$prepay_money,$goods_id,$use_type,$parent,$doctor_id,$total_money,$cost,$loans,$remark,$tmp,$is_edit){
        $hospital = M('hospital');
        $hospital_order = M('hospital_order');
        $shop_goods = M('shop_goods');
        $shop = M('shop');
        if(!$goods_id){$arr = array('code'=> 0, 'res'=>'请选择对应商品');$this->ajaxReturn($arr,"JSON");}

        if(!$parent){$arr = array('code'=> 0, 'res'=>'请选择医院');$this->ajaxReturn($arr,"JSON");}
        if(!$doctor_id){$arr = array('code'=> 0, 'res'=>'请选择医生');$this->ajaxReturn($arr,"JSON");}
        if ($type=='2'){
            $use_type = '2';
        }
        if($type!='2' && $use_type!='2'){
            if(!$total_money){$arr = array('code'=> 0, 'res'=>'请输入订单总金额');$this->ajaxReturn($arr,"JSON");}
            if($total_money<=0){$arr = array('code'=> 0, 'res'=>'订单金额不能小于0');$this->ajaxReturn($arr,"JSON");}
        }else{
            if ($type!='2'){
                if(!$total_money){$arr = array('code'=> 0, 'res'=>'请输入订单总金额');$this->ajaxReturn($arr,"JSON");}
                if($total_money<=0){$arr = array('code'=> 0, 'res'=>'订单金额不能小于0');$this->ajaxReturn($arr,"JSON");}
            }
            if(!$tmp){$arr = array('code'=> 0, 'res'=>'请选择可用的优惠券');$this->ajaxReturn($arr,"JSON");}
        }
        //if(!$goods_id){$arr = array('code'=> 0, 'res'=>'请选择对应商品');$this->ajaxReturn($arr,"JSON");}

        if($goods_id){
            $shop_id = $shop->where(array('hospital_id'=>$parent))->field('id')->find();
            $shop_id2 = $shop_goods->where(array('id'=>$goods_id))->field('shop_id')->find();

            if($shop_id['id']!=$shop_id2['shop_id']){$arr = array('code'=> 0, 'res'=>'此商品不属于当前选择医院');$this->ajaxReturn($arr,"JSON");}

        }
        $hos_order_cost = M('set')->where(array('set_type'=>'hos_order_cost'))->find();
        if($loans>$total_money){$arr = array('code'=> 0, 'res'=>'贷款金额不能大于订单总金额');$this->ajaxReturn($arr,"JSON");}
        $cost_bai = ($cost/$total_money)*100;
        if($cost_bai>$hos_order_cost['set_value']){$arr = array('code'=> 0, 'res'=>'杂费不能大于总金额的 '.$hos_order_cost['set_value'].' %');$this->ajaxReturn($arr,"JSON");}



        if(!$prepay_money){$prepay_money = 0;}
        if(!$cost){$cost = 0;}
        if(!$loans){$loans = 0;}

        $add['order_number'] = time().rand(100000,9999999);

        $add['use_type'] = $use_type;
        $add['parent'] = $parent;
        $add['ticket_list'] = $tmp;
        $add['goods_id'] = $goods_id;
        $add['doctor_id'] = $doctor_id;
        $add['type'] = $type;

        if($type=='1'){
            $add['pay_money'] = $total_money-$loans;
            $add['prepay_money'] = $prepay_money;
            if($prepay_money>0){
                $add['is_prepay'] = 1;
                if($prepay_money>=$add['pay_money']){
                    $arr = array('code'=> 0, 'res'=>'定金不能大于需要支付金额');$this->ajaxReturn($arr,"JSON");
                }
            }else{
                $add['is_prepay'] = 0;
            }



            $add['total_money'] = $total_money;

            $add['cost'] = $cost;

            if($loans>0){
                $add['is_loans'] = 1;

            }else{
                $add['is_loans'] = 0;
            }
            $add['loans'] = $loans;

        }
        $add['remark'] = $remark;

        $add['create_time'] = time();
        $query = $hospital_order->add($add);
        $res = '创建';

        if($query){
            $hos_info = $hospital->where(array('id'=>$parent))->find();
            $qr_arr['type'] = '1';
            $qr_arr['type_id'] = $query;
            $qr_val = json_encode($qr_arr);
            $content = encrypt1($qr_val);
            $qr_url = makecode($content,$hos_info['logo']);
            $hospital_order->where(array('id'=>$query))->save(array('qr_url'=>$qr_url));

            $list['order_number'] = $add['order_number'];
            $list['qr_url'] = $qr_url;
            $list['total_money'] = $add['total_money'];
            $list['cost'] = $add['cost'];
            $list['loans'] = $add['loans'];
            $list['pay_money'] = $add['pay_money'];

            M('shop_goods')->where(array('id'=>$goods_id))->setInc('show_num',1);

            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功',
                'list'=> $list
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

    function SearchGoods($search_val,$type,$hospital_id){

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
            if($hospital_id){
                $shop_info = M('shop')->where(array('hospital_id'=>$hospital_id))->field('id')->find();
                if($shop_info){
                    $where['lyx_shop_goods.shop_id'] = $shop_info['id'];
                }
            }

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



}












