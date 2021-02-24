<?php
namespace Home\Controller;
use Think\Controller;
class TicketController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'ticket'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){
        $ticket = M('ticket');
        $ticket_hospital = M('ticket_hospital');
        $hospital = M('hospital');
        $project = M('project');
        $ticket_user = M('ticket_user');

        $hos_list = $hospital->field('id,name')->select();
        foreach ($hos_list as $item){
            $new_hos[$item['id']] = $item['name'];
        }
        $search = I('get.search');
        if($search){
            $where['title|info'] =  array("like","%".$search."%");
            $search_list['search'] = $search;
        }
        $hospital_id = I('get.hospital_id');
        if($hospital_id){
            $check_arr = $ticket_hospital->where(array('hospital_id'=>$hospital_id))->select();
            if ($check_arr){
                foreach ($check_arr as $item){
                    $check_id_arr[] = $item['ticket_id'];
                }
                $in_str = implode(',',$check_id_arr);
                $where['id'] =  array('IN',$in_str);
            }else{
                $where['id'] =  0;
            }
            $search_list['hospital_id'] = $hospital_id;
        }

        $status = I('get.status');
        if($status){
            $where['type'] =  $status-1;
            $search_list['status'] = $status;
        }

        $type = I('get.type');
        if($type){
            $where['type'] =  $type;
            $search_list['type'] = $type;
        }

        $id = I('get.id');
        if($id){
            $where['id'] =  $id;
            //$search_list['id'] = $id;
        }


        //时间搜索
        $search_date  = I('get.search_date');
        $search_date2  = I('get.search_date2');
        //$data['search_date'] = $search_date;
        //$data['search_date2'] = $search_date2;
        $newtime = strtotime($search_date);
        $newtime1 = strtotime($search_date2);
        if($search_date && $search_date2){
            $where_chao['create_time'] = array(array('EGT',$newtime),array('ELT',$newtime1),'AND');
        }else if($search_date && !$search_date2){
            $where_chao['create_time'] = array('EGT',$newtime);
        }else if(!$search_date && $search_date2){
            $where_chao['create_time'] = array('ELT',$newtime1);
        }
        $search_list['search_date'] = $search_date;
        $search_list['search_date2'] = $search_date2;
        //时间搜索


        $count = $ticket->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  张优惠券</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $ticket->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $pro_list = $project->select();
        foreach ($pro_list as $item){
            $new_pro[$item['id']] = $item['name'];
        }
        foreach($list as $key=>$item){
            if($item['ticket_type']==3){
                $list[$key]['project'] = $new_pro[$item['project_id']];
            }
            if($item['use_type']==1){
                $list[$key]['discount'] = $item['discount']/10;
            }

            $list[$key]['hos_list'] = $ticket_hospital->where(array('ticket_id'=>$item['id']))->select();
            $list[$key]['have_count'] =  $ticket_user->where(array('parent'=>$item['id'],'status'=>0))->count();
        }

        $data = array(
            'search_list' =>$search_list,
            'list' =>$list,
            'hos_list' =>$hos_list,
            'new_hos' =>$new_hos,
            'page' =>$show,
            'at' =>'ticket',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }
    function check(){
        $hospital = M('hospital');
        $shop = M('shop');
        $ticket = M('ticket');
        $ticket_hospital = M('ticket_hospital');
        $project = M('project');
        $is_edit  =  I('get.edit');
        if($is_edit){
            $list =  $ticket->where(array('id'=>$is_edit))->find();
            if($list['use_type']==1){
                $list['discount'] = $list['discount']/10;
            }
            if($list['ticket_type']==1){
                $list['hos_list'] =
                    $ticket_hospital
                        ->join('lyx_shop ON lyx_ticket_hospital.hospital_id  = lyx_shop.id')
                        ->field('lyx_ticket_hospital.hospital_id as shop_id,lyx_shop.name,lyx_ticket_hospital.id,lyx_ticket_hospital.money')
                        ->where(array('lyx_ticket_hospital.ticket_id'=>$list['id']))->select();
            }else{
                if($list['ticket_type']==3){
                    $pro = $project->where(array('id'=>$list['project_id']))->find();
                    $list['project'] = $pro['name'];
                }else{
                    $goods_info  = $ticket_hospital
                        ->join('lyx_shop_goods ON lyx_ticket_hospital.hospital_id  = lyx_shop_goods.id')
                        ->where(array('lyx_ticket_hospital.ticket_id'=>$list['id']))
                        ->field('lyx_ticket_hospital.hospital_id as goods_id,lyx_shop_goods.name,lyx_ticket_hospital.id')
                        ->select();
                    $list['goods_list'] = $goods_info;
                }

            }


        }else{
            $list = array();
        }



        $data = array(
            'at'=>'ticket',
            'title'=>'优惠券',
            'list'=>$list,
        );

        $this->assign($data);
        $this->display();
    }

    function addsel($xz_id,$is_edit,$type){
        if($type==1){
            if(!$xz_id){$arr = array('code'=> 0, 'res'=>'请选择机构');$this->ajaxReturn($arr,"JSON");}

        }else{
            if(!$xz_id){$arr = array('code'=> 0, 'res'=>'请选择商品');$this->ajaxReturn($arr,"JSON");}

        }
        $ticket = M('ticket');
        $ticket_hospital = M('ticket_hospital');
        $ticket_info = $ticket->where(array('id'=>$is_edit))->find();

        if(!$ticket_info){$arr = array('code'=> 0, 'res'=>'优惠券信息错误');$this->ajaxReturn($arr,"JSON");}

        $where['ticket_id'] = $ticket_info['id'];
        $where['hospital_id'] = $xz_id;
        $is_have = $ticket_hospital->where($where)->find();
        if($type==1){
            $where['ticket_type'] = 1;
            if($is_have){$arr = array('code'=> 0, 'res'=>'已添加此机构');$this->ajaxReturn($arr,"JSON");}
        }else{
            $where['ticket_type'] = 2;
            if($is_have){$arr = array('code'=> 0, 'res'=>'已添加此商品');$this->ajaxReturn($arr,"JSON");}
        }


        $where['create_time'] = time();
        $query = $ticket_hospital->add($where);
        $res = "新增";
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
    function addgoods(){
        $ticket = M('ticket');
        $is_edit  =  I('get.edit');
        $type  =  I('get.type');
        if($is_edit){
            $list =  $ticket->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }
        $data = array(
            'at'=>'ticket',
            'title'=>'优惠券新增适用'.$type==1?'机构':'商品',
            'list'=>$list,
            'type'=>$type,
            'is_edit'=>$is_edit,
        );



        $this->assign($data);
        $this->display();
    }

    function SearchGoods($search_val,$type)
    {

        if (!$search_val) {
            $arr = array('code' => 0, 'res' => '请输入名称');
            $this->ajaxReturn($arr, "JSON");
        }
        if($type==1){
            $shop_goods = M('shop');

            $where['name'] = array("like", "%" . $search_val . "%");

            $list = $shop_goods->where($where)
                ->field('id,name')
                ->select();
        }else{
            $shop_goods = M('shop_goods');

            $where['lyx_shop_goods.name'] = array("like", "%" . $search_val . "%");

            $list = $shop_goods->where($where)->join('lyx_shop ON lyx_shop_goods.shop_id = lyx_shop.id')
                ->field('lyx_shop_goods.id,lyx_shop_goods.name,lyx_shop.name as shop_name')
                ->select();
        }
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

        $ticket = M('ticket');
        $ticket_hospital = M('ticket_hospital');
        $project = M('project');
        $hospital = M('hospital');
        $hos_list = $hospital->where(array('type'=>'1'))->field('id,name')->select();
        foreach ($hos_list as $key=>$item){
            $is_have = $ticket_hospital->where(array('ticket_id'=>$is_edit,'hospital_id'=>$item['id']))->find();
            if ($is_have){
                $hos_list[$key]['is_on'] = '1';
            }else{
                $hos_list[$key]['is_on'] = '0';
            }
        }
        if($is_edit){
            $list = $ticket->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }
        $pro_list = $project->select();

        $data = array(
            'at'=>'ticket',
            'title'=>'优惠券',
            'pro_list'=>$pro_list,
            'hos_list'=>$hos_list,
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }



    function plus2(){
        $is_edit  =  I('get.edit');

        $ticket = M('ticket');
        $ticket_hospital = M('ticket_hospital');
        $hospital = M('shop');
        $hos_list = $hospital->field('id,name')->select();
        foreach ($hos_list as $key=>$item){
            $is_have = $ticket_hospital->where(array('ticket_id'=>$is_edit,'hospital_id'=>$item['id']))->find();
            if ($is_have){
                $hos_list[$key]['is_on'] = '1';
            }else{
                $hos_list[$key]['is_on'] = '0';
            }
        }
        if($is_edit){
            $list = $ticket->where(array('id'=>$is_edit))->find();

        }else{
            $list = array();
        }

        $data = array(
            'at'=>'ticket',
            'title'=>'优惠券',
            'hos_list'=>$hos_list,
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }


    function editmoney($id,$money,$is_edit){
        $ticket = M('ticket');
        $ticket_hospital = M('ticket_hospital');
        if($money<0){$arr = array('code'=> 0, 'res'=>'返还金额不能小于0');$this->ajaxReturn($arr,"JSON");}

        $save['money'] = $money;
        $save['update_time'] = time();
        $query = $ticket_hospital->where(array('id'=>$id))->save($save);
        $res = '修改';
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

    public function add($title, $info,$dis_title, $sales,$explain,$price, $project_id,$rebate,$city_money,$is_rebate, $discount, $type, $use_type, $sort,$max,$ticket_type,$is_edit){
        $ticket = M('ticket');
        $ticket_hospital = M('ticket_hospital');


        if(!$title){$arr = array('code'=> 0, 'res'=>'请输入优惠券标题');$this->ajaxReturn($arr,"JSON");}
        if(!$info){$arr = array('code'=> 0, 'res'=>'请输入优惠券副标题');$this->ajaxReturn($arr,"JSON");}


        if(!$type){$arr = array('code'=> 0, 'res'=>'请选择优惠券类型');$this->ajaxReturn($arr,"JSON");}


        if ($use_type=='1' || $use_type=='3'){
            if(!$discount){$arr = array('code'=> 0, 'res'=>'请输入折扣/金额');$this->ajaxReturn($arr,"JSON");}
        }
        if(!$dis_title){$arr = array('code'=> 0, 'res'=>'请填写显示文字');$this->ajaxReturn($arr,"JSON");}

        if (!$max){$max=0;}

        if($ticket_type==3){
            $type = 1;
            $use_type = 2;
        }

        if($is_edit){
            $save['title'] = $title;
            $save['dis_title'] = $dis_title;
            $save['use_type'] = $use_type;
            $save['is_rebate'] = $is_rebate;
            $save['project_id'] = $project_id;
            $save['sales'] = $sales;
            $save['info'] = $info;
            $save['city_money'] = $city_money;
            $save['explain'] = $explain;
            $save['price'] = $price;
            $save['rebate'] = $rebate;
            if($use_type=='2'){
                $save['discount'] = 0;
            }else{
                $save['discount'] = $discount;
            }
            $add['ticket_type'] = $ticket_type;
            $save['type'] = $type;
            $save['sort'] = $sort;
            $save['max'] = $max;
            $save['update_time'] = time();
            $query = $ticket->where(array('id'=>$is_edit))->save($save);
            if($query){
                $ticket_hospital->where(array('ticket_id'=>$is_edit))->delete();
            }
            $res = '修改';
        }else{
            $add['explain'] = $explain;
            $add['title'] = $title;
            $add['use_type'] = $use_type;
            $add['dis_title'] = $dis_title;
            $add['is_rebate'] = $is_rebate;
            $add['sales'] = $sales;
            $add['ticket_type'] = $ticket_type;
            $add['info'] = $info;
            $add['city_money'] = $city_money;
            $add['price'] = $price;
            $add['project_id'] = $project_id;
            $add['rebate'] = $rebate;
            $add['discount'] = $discount;
            $add['type'] = $type;
            $add['sort'] = $sort;
            $add['max'] = $max;
            $add['create_time'] = time();
            $query = $ticket->add($add);
            $add_id = $query;
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

    function editfreeze($id,$str){
        $admin = M('admin');
        $query = $admin->where(array('id'=>$id))->save(array('is_freeze'=>$str,'update_time'=>time()));
        $res = "操作";
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

    function delete($id){
        $ticket = M('ticket');
        $ticket_user = M('ticket_user');
        $ticket_hospital = M('ticket_hospital');
        $query = $ticket->where(array('id'=>$id))->delete();
        $res = '删除';
        if($query){
            $ticket_user->where(array('parent'=>$id))->delete();
            $ticket_hospital->where(array('ticket_id'=>$id))->delete();
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












