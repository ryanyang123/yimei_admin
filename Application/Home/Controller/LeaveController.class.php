<?php
namespace Home\Controller;
use Think\Controller;
class LeaveController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'leave'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }

    public function index(){

        $user = M('user');

        $leave = M('leave');
        $user_id = I('get.user_id');
        if($user_id){
            $where['id'] =  $user_id;
            $search_list['user_id'] = $user_id;
        }

        $status = I('get.is_dispose');
        if($status){
            $where['is_dispose'] =  $status-1;
            $search_list['is_dispose'] = $status;
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


        $count = $leave->where($where)->count('distinct user_id');
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条留言</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $leave
            ->group('user_id')
            ->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $user_name = $user->where(array('id'=>$item['user_id']))->field('id,name,phone')->find();
            $list[$key]['user_name'] = $user_name['name'];
            $list[$key]['phone'] = $user_name['phone'];
            $list[$key]['read_num'] = 0;
            $list[$key]['read_num']+=$leave->where(array('user_id'=>$item['user_id'],'type'=>'1','is_read'=>'0'))->count();
        }


        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'leave',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }



    function check(){
        $is_edit  =  I('get.edit');

        $user = M('user');
        $leave = M('leave');

        if($is_edit){
            $list = $user->where(array('id'=>$is_edit))->field('id,name,head')->find();
            $leave_list = $leave->where(array('user_id'=>$is_edit))->order('create_time desc')->select();
            $leave->where(array('user_id'=>$is_edit,'type'=>'1','is_read'=>'0'))->save(array('is_read'=>'1','update_time'=>time()));
        }else{
            $list = array();
        }
        $data = array(
            'at'=>'leave',
            'title'=>'留言管理',
            'list'=>$list,
            'leave_list'=>$leave_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
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


    function sendleave($id,$content){
        $leave = M('leave');
        $user = M('user');
        if(!$content){$arr = array('code'=> 0, 'res'=>'请输入回复内容');$this->ajaxReturn($arr,"JSON");}

        $add['user_id'] = $id;
        $add['content'] = $content;
        $add['type'] = 2;
        $add['is_read'] = 0;
        $add['create_time'] = time();
        $query = $leave->add($add);

        $res = '回复';
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


    function submitcause($user_id){
        $user = M('user');

        $query  = $user->where(array('id'=>$user_id))->field('id')->find();


        if($query){

            $arr = array(
                'code'=> 1,
                'res'=> '成功'
            );
            $this->ajaxReturn($arr,"JSON");
        }else{
            $arr = array(
                'code'=> 0,
                'res'=> '用户ID不存在'
            );
            $this->ajaxReturn($arr,"JSON");
        }
    }





}












