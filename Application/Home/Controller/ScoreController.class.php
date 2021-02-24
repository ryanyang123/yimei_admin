<?php
namespace Home\Controller;
use Think\Controller;
class ScoreController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'score'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){


        $shop_order = M('shop_order');
        $shop_prize_order = M('shop_prize_order');
        $hospital_order = M('hospital_order');
        $level_type = M('level_type');
        $user = M('user');
        $shop_score_log = M('shop_score_log');


        $user_id = I('get.user_id');
        if($user_id){
            $where['user_id'] = $user_id;
            $search_list['user_id'] = $user_id;
        }

        $type_list = $level_type->select();
        foreach ($type_list as $item){
            $new_type[$item['tid']] = $item['name'];
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

        $where['is_show'] = 1;
        $count = $shop_score_log->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条积分明细</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $shop_score_log->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();


        foreach($list as $key=>$item){
            $user_name = $user->where(array('id'=>$item['user_id']))->field('name')->find();
            $list[$key]['user_name'] = $user_name['name'];


            if($item['type']=='1'){
                $list[$key]['type_name'] = '兑换礼品';
            }else if ($item['type']=='2'){
                $list[$key]['type_name'] = '支付医院订单抵消';
            }else if ($item['type']=='3'){
                $money = $shop_order->where(array('id'=>$item['order_id']))->field('total_money')->find();
                $list[$key]['type_name'] = '消费'.$money['total_money'].'元';
                $list[$key]['total_money'] = $money['total_money'];
            }else if ($item['type']=='4'){
                $show_name = M('level_type')->where(array('tid'=>$item['type_id']))->find();
                $list[$key]['type_name'] = $show_name['name'];
            }else if ($item['type']=='5'){
                $list[$key]['type_name'] = '注册获得';
            }else if ($item['type']=='6'){
                $list[$key]['type_name'] = '邀请好友获得';
            }else if ($item['type']=='7'){
                $list[$key]['type_name'] = '发送红包';
            }else if ($item['type']=='8'){
                $list[$key]['type_name'] = '领取红包';
            }else if ($item['type']=='9'){
                $list[$key]['type_name'] = '医院退款返还';
            }else if ($item['type']=='10'){
                $list[$key]['type_name'] = '游戏扣除';
            }else if ($item['type']=='11'){
                $list[$key]['type_name'] = '游戏获得';
            }else if ($item['type']=='12'){
                $list[$key]['type_name'] = '游戏通关奖励';
            }else if ($item['type']=='13'){
                $list[$key]['type_name'] = '游戏退还';
            }else if ($item['type']=='14'){
                $list[$key]['type_name'] = '邀请好友玩游戏获得';
            }else{
                $list[$key]['type_name'] = '-';
            }
            //$list[$key]['order_number'] = $order_info['order_number'];

        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'search_list' =>$search_list,
            'user_id' =>$user_id,
            'at' =>'score',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $shop_classify = M('shop_classify');

        if($is_edit){
            $list = $shop_classify->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'score',
            'title'=>'分类',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($name,$sort,$is_edit){
        $shop_classify = M('shop_classify');

        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入分类名称');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            $save['name'] = $name;
            $save['sort'] = $sort;
            $save['update_time'] = time();
            $query = $shop_classify->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['name'] = $name;
            $add['sort'] = $sort;
            $add['create_time'] = time();
            $query = $shop_classify->add($add);
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












