<?php
namespace Home\Controller;
use Think\Controller;
class TicketchouController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'ticketchou'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $user = M('user');
        $hospital_order = M('hospital_order');
        $ticket_user = M('ticket_user');


        $page_type = I('get.page_type');
        $page_type = $page_type?$page_type:1;
        $search_list['page_type'] = $page_type;
        if($page_type==1){
            $where['is_da'] =  1;
            $where['lyx_ticket_user.is_rebate'] =  0;
        }


        $user_id = I('get.user_id');
        if($user_id){
            $where['lyx_ticket_user.user_id'] =  $user_id;
            $search_list['user_id'] = $user_id;
        }


        $team_id = I('get.team_id');
        if($team_id){
            $team_id1 = $team_id-1;
            if ($team_id1==0){
                $where['team_id'] =  $team_id1;
            }else{
                $where['team_id'] =  array('NEQ',0);
            }
            $search_list['team_id'] = $team_id;
        }

        $type = I('get.type');
        if($type){
            $where['lyx_ticket.type'] =  $type;
            $search_list['type'] = $type;
        }else{
            $where['lyx_ticket.type'] = array('IN','1,3');
        }


        $status = I('get.status');
        if($status){
            $where['lyx_ticket_user.status'] =  $status-1;
            $search_list['status'] = $status;
        }



        $id = I('get.id');
        if($id){
            $where['lyx_ticket_user.id'] =  $id;
        }


        $is_rebate = I('get.is_rebate');
        if($is_rebate){
            $where['lyx_ticket_user.is_rebate'] =  $is_rebate-1;
            $search_list['is_rebate'] = $is_rebate;
        }

        //时间搜索
        $search_date  = I('get.search_date');
        $search_date2  = I('get.search_date2');
        //$data['search_date'] = $search_date;
        //$data['search_date2'] = $search_date2;
        $newtime = strtotime($search_date);
        $newtime1 = strtotime($search_date2)+86399;
        if($search_date && $search_date2){
            $where['lyx_ticket_user.create_time'] = array(array('EGT',$newtime),array('ELT',$newtime1),'AND');
        }else if($search_date && !$search_date2){
            $where['lyx_ticket_user.create_time'] = array('EGT',$newtime);
        }else if(!$search_date && $search_date2){
            $where['lyx_ticket_user.create_time'] = array('ELT',$newtime1);
        }
        $search_list['search_date'] = $search_date;
        $search_list['search_date2'] = $search_date2;
        //时间搜索


        $count = $ticket_user
            ->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')
            ->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条数据</p>');
        $show       = $Page->show();// 分页显示输出

        $list = $ticket_user
            ->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')
            ->field('lyx_ticket.title,lyx_ticket_user.*,lyx_ticket.type')
            ->where($where)->order('lyx_ticket_user.create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $user_name = $user->where(array('id'=>$item['user_id']))->field('name,ali_number,ali_name')->find();
            $list[$key]['user_name'] = $user_name['name'];
            $list[$key]['ali_number'] = $user_name['ali_number'];
            $list[$key]['ali_name'] = $user_name['ali_name'];

            $list[$key]['discount'] = $item['discount']/10;

            if($item['status']=='1' || $item['status']=='1'){
                $order_info  = $hospital_order->where(array('ticket_id'=>$item['id']))->field('order_number')->find();

                $list[$key]['order_number'] = $order_info['order_number'];
            }


        }
        $where['lyx_ticket_user.status'] = array('NEQ','3');
        $total['total_money'] = 0;
        $total['total_money'] += $ticket_user->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')->where($where)->sum('buy_money');

        //$where['is_get'] = 1;
        $where['lyx_ticket_user.is_rebate'] = 1;
        $total['total_yes'] = 0;
        $total['total_yes'] += $ticket_user->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')->where($where)->sum('rebate_money');

        //$where['is_get'] = 0;
        $where['lyx_ticket_user.is_rebate'] = 0;
        $total['total_no'] = 0;
        $total['total_no'] += $ticket_user->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')->where($where)->sum('rebate_money');

        $data = array(
            'list' =>$list,
            'total' =>$total,
            'search_list' =>$search_list,
            'page' =>$show,
            'at' =>'ticketchou',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $diary_classify = M('diary_classify');

        if($is_edit){
            $list = $diary_classify->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'diaryclassify',
            'title'=>'日志分类',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($name,$sort,$is_edit){
        $diary_classify = M('diary_classify');

        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入群分类名称');$this->ajaxReturn($arr,"JSON");}

        if ($is_edit){
            $where_name['id'] = array('NEQ',$is_edit);
        }
        $where_name['name'] = $name;
        $is_name = $diary_classify->where($where_name)->find();
        if ($is_name){
            $arr = array('code'=> 0, 'res'=>'分类名称已存在');$this->ajaxReturn($arr,"JSON");
        }
        if($is_edit){
            $save['name'] = $name;
            $save['sort'] = $sort;
            $save['update_time'] = time();
            $query = $diary_classify->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['name'] = $name;
            $add['sort'] = $sort;
            $add['create_time'] = time();
            $query = $diary_classify->add($add);
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












