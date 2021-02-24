<?php
namespace Home\Controller;
use Think\Controller;
class TaskregController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'task'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){



        $task = M('task_reg');
        $user = M('user');
        $admin = M('admin');
        $ticket = M('ticket');
        $task_prize = M('task_prize');


        $where['is_pay'] = 1;
        $count = $task->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个红包设置</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $task->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $user_name  = $user->where(array('id'=>$item['user_id']))->field('name')->find();
            $list[$key]['user_name'] = $user_name['name'];
            if($item['type']=='2'){
                $user_name2  = $user->where(array('id'=>$item['parent']))->field('name')->find();
                $list[$key]['parent_name'] = $user_name2['name'];
            }
            if($item['type']=='3'){
                $user_name2  = $admin->where(array('id'=>$item['parent']))->field('admin_name')->find();
                $list[$key]['parent_name'] = $user_name2['admin_name'];
            }

            if($item['prize']=='1'){
                //$prize_info = $task_prize->where(array('id'=>$item['prize_id']))->field('parent')->find();
                $ticket_info = $ticket->where(array('id'=>$item['prize_id']))->field('id,title')->find();
                $list[$key]['ticket_id'] = $ticket_info['parent'];
                $list[$key]['ticket_name'] = $ticket_info['title'];
            }
            $list[$key]['prize_num'] = floatval($item['prize_num']);
        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'taskreg',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $task = M('task_reg');
        $ticket = M('ticket');

        if($is_edit){
            $list = $task->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }
        $ticket_list = $ticket->select();

        $data = array(
            'at'=>'taskreg',
            'title'=>'红包设置',
            'list'=>$list,
            'ticket_list'=>$ticket_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($type,$name,$classes,$prize,$need_num,$money,$score,$ticket_id){
        $task = M('task_reg');


        if(!$type){$arr = array('code'=> 0, 'res'=>'选择设置类型');$this->ajaxReturn($arr,"JSON");}
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入红包名称');$this->ajaxReturn($arr,"JSON");}
        if($classes=='1'){
            if(!$need_num){$arr = array('code'=> 0, 'res'=>'请输入任务需要的邀请人数');$this->ajaxReturn($arr,"JSON");}
        }

        if($prize=='1'){
            if(!$ticket_id){$arr = array('code'=> 0, 'res'=>'请选择赠送的优惠券');$this->ajaxReturn($arr,"JSON");}
        }
        if($prize=='2'){
            if(!$score){$arr = array('code'=> 0, 'res'=>'请输入赠送的积分');$this->ajaxReturn($arr,"JSON");}
            if($score<100){$arr = array('code'=> 0, 'res'=>'最低赠送100积分');$this->ajaxReturn($arr,"JSON");}
        }
        if($prize=='3'){
            if(!$money){$arr = array('code'=> 0, 'res'=>'请输入赠送的金额');$this->ajaxReturn($arr,"JSON");}
            if($money<0.1){$arr = array('code'=> 0, 'res'=>'最低赠送0.1元');$this->ajaxReturn($arr,"JSON");}
        }



        $add['type'] = $type;
        $add['name'] = $name;
        $add['classes'] = $classes;
        $add['prize'] = $prize;
        $add['prize_id'] = $ticket_id;
        $add['need_num'] = $need_num;

        if($prize=='2'){
            $add['prize_num'] = $score;
        }
        if($prize=='3'){
            $add['prize_num'] = $money;
        }


        $add['parent'] = session(XUANDB.'id');
        $add['create_time'] = time();
        $query = $task->add($add);
        $res = '添加';

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












