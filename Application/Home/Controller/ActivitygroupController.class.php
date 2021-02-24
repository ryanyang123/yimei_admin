<?php
namespace Home\Controller;
use Think\Controller;
class ActivitygroupController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'activitygroup'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $activity = M('activity');
        $activity_group = M('activity_group');
        $status = I('get.status');


        $search = I('get.search');
        if($search){
            $where['location'] =  array("like","%".$search."%");;
            $search_list['search'] = $search;
        }
        if($status){
            $where['status'] =  $status-1;
            $search_list['status'] = $status;
        }
        $type = I('get.type');
        if($type){
            $where['type'] =  $type;
            $search_list['type'] = $type;
        }

        $user_id = I('get.user_id');
        if($user_id){
            $where['user_id'] =  $user_id;
            $search_list['user_id'] = $user_id;
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
        $count = $activity_group->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个抽奖组</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $activity_group->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            //$list[$key]['total'] = $comment_admin->where(array('classify'=>$item['id']))->count();
            $a_info = $activity->where(array('id'=>$item['type']))->find();
            $list[$key]['max_num'] = $a_info['group_num'];
        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'activitygroup',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $activity_group = M('activity_group');

        if($is_edit){
            $list = $activity_group->where(array('id'=>$is_edit))->find();
            $list['open_time_val'] = date('Y-m-d H:i',$list['open_time']);
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'activitygroup',
            'title'=>'抽奖组',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($time,$location,$is_edit){
        $activity_group = M('activity_group');

        if(!$time){$arr = array('code'=> 0, 'res'=>'请选择开奖时间');$this->ajaxReturn($arr,"JSON");}
        if(!$location){$arr = array('code'=> 0, 'res'=>'请输入开奖地址');$this->ajaxReturn($arr,"JSON");}


        if($is_edit){
            $save['open_time'] = strtotime($time);
            $save['location'] = $location;
            $save['update_time'] = time();
            $query = $activity_group->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $arr = array(
                'code'=> 0,
                'res'=> '不可新增'
            );
            $this->ajaxReturn($arr,"JSON");
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












