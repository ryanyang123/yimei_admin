<?php
namespace Home\Controller;
use Think\Controller;
class TaskController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'task'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $task = M('task');
        $user = M('user');
        $admin = M('admin');
        $ticket = M('ticket');
        $task_prize = M('task_prize');
        $is_get = I('get.is_get');
        if($is_get){
            $where['is_get'] =  $is_get-1;
            $search_list['is_get'] = $is_get;
        }
        $search = I('get.search');
        if($search){
            $where['name'] =  array("like","%".$search."%");;
            $search_list['search'] = $search;
        }
        $user_id = I('get.user_id');
        if($user_id){
            $where['user_id'] =  $user_id;
            $search_list['user_id'] = $user_id;
        }

        $prize = I('get.prize');
        if($prize){
            $where['prize'] =  $prize;
            $search_list['prize'] = $prize;
        }
        $classes = I('get.classes');
        if($classes){
            $where['classes'] =  $classes;
            $search_list['classes'] = $classes;
        }
        $status = I('get.status');
        if($status){
            if($status==1){
                $where['classes'] =  1;
            }
            $where['status'] =  $status-1;
            $search_list['status'] = $status;
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

        $where['is_pay'] = 1;
        $count = $task->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个红包</p>');
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
                $prize_info = $task_prize->where(array('id'=>$item['prize_id']))->field('parent')->find();
                $ticket_info = $ticket->where(array('id'=>$prize_info['parent']))->field('title')->find();
                $list[$key]['ticket_id'] = $prize_info['parent'];
                $list[$key]['ticket_name'] = $ticket_info['title'];
            }
            $list[$key]['prize_num'] = floatval($item['prize_num']);
        }

        $data = array(
            'search_list' =>$search_list,
            'list' =>$list,
            'page' =>$show,
            'at' =>'task',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $task = M('task');
        $ticket = M('ticket');

        if($is_edit){
            $list = $task->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }
        $ticket_list = $ticket->select();

        $data = array(
            'at'=>'task',
            'title'=>'红包',
            'list'=>$list,
            'ticket_list'=>$ticket_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($user_id,$name,$classes,$prize,$need_num,$money,$score,$ticket_id,$pay_pass){
        $task = M('task');
        $user = M('user');
        $ticket = M('ticket');
        $task_prize = M('task_prize');
        if(!$pay_pass){$arr = array('code'=> 0, 'res'=>'请输入支付密码');$this->ajaxReturn($arr,"JSON");}

        $set_info = M('set')->where(array('set_type'=>'pay_pass'))->find();
       if(md5(md5($pay_pass))!=$set_info['set_value']){$arr = array('code'=> 0, 'res'=>'支付密码错误');$this->ajaxReturn($arr,"JSON");}


        if(!$user_id){$arr = array('code'=> 0, 'res'=>'请输入要赠送的用户ID');$this->ajaxReturn($arr,"JSON");}
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


        $user_info = $user->where(array('id'=>$user_id))->field('id')->find();
        if(!$user_info){$arr = array('code'=> 0, 'res'=>'用户ID不存在');$this->ajaxReturn($arr,"JSON");}


        $add['user_id'] = $user_id;
        $add['type'] = 3;
        $add['name'] = $name;
        $add['is_pay'] = 1;
        $add['classes'] = $classes;
        $add['prize'] = $prize;
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
        $res = '发送';

        if($query){
            if($prize=='1'){
                $ticket_info = $ticket->where(array('id'=>$ticket_id))->find();
                $add_prize['user_id'] = 0;
                $add_prize['task_id'] = $query;
                $add_prize['parent'] = $ticket_id;
                $add_prize['buy_money'] = 0;
                $add_prize['rebate_money'] = 0;
                $add_prize['discount'] = $ticket_info['discount'];
                $add_prize['dis_title'] = $ticket_info['dis_title'];
                $add_prize['create_time'] = time();
                $prize_id = $task_prize->add($add_prize);
                $task->where(array('id'=>$query))->save(array('prize_id'=>$prize_id));
            }
            $send_con['type'] = '1';
            $send_con['user_id'] = $user_id;
            SendSocket($user_id,json_encode($send_con));
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












