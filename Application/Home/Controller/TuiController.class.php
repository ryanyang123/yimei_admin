<?php
namespace Home\Controller;
use Think\Controller;
class TuiController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'tui'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $user = M('user');
        $tui = M('tui');
        $shop_order = M('shop_order');
        $hospital_order = M('hospital_order');
        $ticket_user = M('ticket_user');
        $ticket = M('ticket');


        $user_id = I('get.user_id');
        if($user_id){
            $where['user_id'] =  $user_id;
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
            $where['type'] =  $type;
            $search_list['type'] = $type;
        }else{
            $where['type'] =  array('IN','1,2,4');
        }


        $status = I('get.status');
        if($status){
            $where['status'] =  $status-1;
            $search_list['status'] = $status;
        }


        $is_rebate = I('get.is_rebate');
        if($is_rebate){
            $where['lyx_ticket_user.is_rebate'] =  $is_rebate-1;
            $search_list['is_rebate'] = $is_rebate;
        }

        $page_type = I('get.page_type');
        $page_type = $page_type?$page_type:1;
        $search_list['page_type'] = $page_type;
        if($page_type==1){
            $where['is_da'] =  1;
            $where['status'] =  0;
            $where['type'] =  array('IN','1,2,4');
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

        $count = $tui
            ->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条数据</p>');
        $show       = $Page->show();// 分页显示输出

        $list = $tui->where($where)->order('status,create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

            if ($item['type']=='1'){
                $type_info = $shop_order->where(array('id'=>$item['type_id']))->field('user_id,order_number,id')->find();
            }
            if ($item['type']=='2'){
                $type_info = $ticket_user->where(array('id'=>$item['type_id']))->field('user_id,id,parent')->find();
                $ticket_info = $ticket->where(array('id'=>$type_info['parent']))->field('title')->find();
                $type_info['title'] = $ticket_info['title'];
            }

            if ($item['type']=='4'){
                $type_info = $hospital_order->where(array('id'=>$item['type_id']))->field('user_id,order_number,id')->find();

            }
            $list[$key]['type_info'] = $type_info;

            $user_name = $user->where(array('id'=>$type_info['user_id']))->field('name,ali_number,ali_name')->find();


            $list[$key]['user_name'] = $user_name['name'];
            $list[$key]['ali_number'] = $user_name['ali_number'];
            $list[$key]['ali_name'] = $user_name['ali_name'];

        }
       unset($where['status']);
        $total['total_money'] = 0;
        $total['total_money'] += $tui->where($where)->sum('money');

        //$where['is_get'] = 1;
        $where['status'] = 1;
        $total['total_yes'] = 0;
        $total['total_yes'] += $tui->where($where)->sum('money');

        //$where['is_get'] = 0;
        $where['status'] = array('IN','0,2');
        $total['total_no'] = 0;
        $total['total_no'] += $tui->where($where)->sum('money');

        $data = array(
            'list' =>$list,
            'total' =>$total,
            'search_list' =>$search_list,
            'page' =>$show,
            'at' =>'tui',
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












