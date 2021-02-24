<?php
namespace Home\Controller;
use Think\Controller;
class SetController extends CommonController {

    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'set'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }

    public function index(){
        $set = M('set');
        $ticket = M('ticket');
        $odds_type = M('odds_type');

        $test = M('user_odds')->where(array('id'=>'1'))->find();


        $set_list = $set->select();
        $new_set_list=  array();
        foreach($set_list as $key=>$item){
            $new_set_list[$item['set_type']] = $item;
            if($item['set_type']=='register_task'){
                $task_set = json_decode($item['remark'],true);
            }
            if($item['set_type']=='game_info'){
                $game_info = json_decode($item['remark'],true);
            }
            if($item['set_type']=='agent_send'){
                $agent_send = json_decode($item['remark'],true);
            }
        }
        $type = I('get.type');
        if($type){
            $now_at = $type;
        }else{
            $now_at = 1;
        }

        //体验券
        $ticket_hospital = M('ticket_hospital');
        $ticket_list = M('ticket')->where(array('type'=>'2'))->select();
        foreach($ticket_list as $key=>$item){
            $ticket_list[$key]['discount'] = $item['discount']/10;
            $ticket_list[$key]['hos_list'] = $ticket_hospital
                ->join('lyx_hospital ON lyx_ticket_hospital.hospital_id = lyx_hospital.id')
                ->field('lyx_hospital.id,lyx_hospital.name')
                ->where(array('lyx_ticket_hospital.ticket_id'=>$item['id']))->select();
        }

        $new_set_list['ticket_list'] =$ticket_list;

        $level_type = M('level_type')->select();
        foreach ($level_type as $key=>$item){
            $level_type[$key]['max_day'] = $item['score']*$item['max'];
        }
        $new_set_list['level_type'] =$level_type;
        $odds_type_list = $odds_type->select();

        $level_list = M('level')->order('lv')->select();
        $end_level = end($level_list);
        foreach ($level_list as $key=>$item){
            if($item['id']==$end_level['id']){
                $level_list[$key]['is_del'] = '1';
            }
        }

        $new_set_list['level_list'] =$level_list;


        $tic_list = $ticket->select();


        $odds_list = M('odds')->select();
        $type_list = M('odds_type')->select();
        $set_odds_list = M('user_odds_new')->where(array('type'=>'2'))->select();


        foreach ($set_odds_list as $item){
            $new_set_odds[$item['name']] = $item['odds'];
        }

        foreach ($odds_list as $key=>$item){
            $set_arr = array();
            foreach ($type_list as $val){
                $set_arr2 = array();
                $key_zh = $item['name'].'_'.$val['id'];
                    $set_arr2['name'] = $key_zh;
                    $set_arr2['value'] = $new_set_odds[$key_zh];
                    $set_arr2['type'] = 2;
                    $set_arr[] = $set_arr2;

            }
            $odds_list[$key]['set_list'] = $set_arr;

        }
        $prize_list = M('shop_prize')->order('create_time')->select();

        $data = array(
            'list' =>$new_set_list,
            'task_set' =>$task_set,
            'game_info' =>$game_info,
            'agent_send' =>$agent_send,
            'type_list' =>$type_list,
            'prize_list' =>$prize_list,
            'odds_list' =>$odds_list,
            'odds_type_list' =>$odds_type_list,
            'now_at' =>$now_at,
            'tic_list' =>$tic_list,
            'at' =>'set',
        );




        $this->assign($data);
        $this->display();
    }

    function checkxy($type){
        $set = M('set');
        $list = $set->where(array('set_type'=>$type))->find();
        $res = '查询';
        $arr = array(
            'code'=> 1,
            'res'=> $res.'成功',
            'list'=> $list
        );
        $this->ajaxReturn($arr,"JSON");
    }

    function addlv($score){
        $level = M('level');

        $now_max = $level->order('lv desc')->find();

        if ($score<=$now_max['score']){
            $arr = array(
                'code'=> 0,
                'res'=> '升级分数不能小于等于上级分数'
            );
            $this->ajaxReturn($arr,"JSON");
        }
        $add['lv'] = $now_max['lv']+1;
        $add['score'] = $score;
        $add['create_time'] = time();

        $query = $level->add($add);
        $res = "增加";
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


    function xyedit($content,$type){
        $set = M('set');
        $query = $set->where(array('set_type'=>$type))->save(array('remark'=>$content,'update_time'=>time()));
        $res = "更新";
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


    function Updateodd($name,$value){
        $user_odds_new = M('user_odds_new');
        $save['odds'] = $value;
        $save['update_time'] = time();
        $query = $user_odds_new->where(array('name'=>$name,'type'=>'2'))->save($save);
        $res = "更新";
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

    function updateagentsend($open_money,$city_money,$agent_money,$img,$content,$ticket_id,$type){
        $set = M('set');
        $save['set_value'] = $type;
        $save['update_time'] = time();


        $data['type'] = $type;
        $data['content'] = $content;
        $data['ticket_id'] = $ticket_id;
        $data['img_url'] = $img;
        $save['remark'] = json_encode($data);
        $set->where(array('set_type'=>'team_open_money'))->save(array('set_value'=>$open_money,'update_time'=>time()));
        $set->where(array('set_type'=>'team_city_money'))->save(array('set_value'=>$city_money,'update_time'=>time()));
        $set->where(array('set_type'=>'team_agent_money'))->save(array('set_value'=>$agent_money,'update_time'=>time()));

        $query = $set->where(array('set_type'=>'agent_send'))
            ->save($save);
        $res = "更新";
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
    function updatepay($set_type,$type){
        $set = M('set');
        $query = $set->where(array('set_type'=>$type))->save(array('set_value'=>$set_type,'update_time'=>time()));
        $res = "更新";
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


    function UploadScore($score,$max,$id){
        $level_type = M('level_type');
        $query = $level_type->where(array('id'=>$id))->save(array('score'=>$score,'max'=>$max,'create_time'=>time()));
        $res = "更新";
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


    function UploadGameInfo($start_num,$share_num,$prize_num,$create_game,$game_over_time,$prize_id){
        $set = M('set');

        if(!$start_num){$arr = array('code'=> 0, 'res'=>'请输入玩游戏需要的积分');$this->ajaxReturn($arr,"JSON");}
        if(!$share_num){$arr = array('code'=> 0, 'res'=>'请输入邀请新用户可获得积分');$this->ajaxReturn($arr,"JSON");}
        if(!$prize_num){$arr = array('code'=> 0, 'res'=>'请输入通关获得积分');$this->ajaxReturn($arr,"JSON");}
        if(!$prize_id){$arr = array('code'=> 0, 'res'=>'请选择通关可得奖品');$this->ajaxReturn($arr,"JSON");}
        if(!$create_game){$arr = array('code'=> 0, 'res'=>'请输入创建对局最低的积分');$this->ajaxReturn($arr,"JSON");}
        if(!$game_over_time){$arr = array('code'=> 0, 'res'=>'请输入过期天数');$this->ajaxReturn($arr,"JSON");}

        $data['start_num'] = $start_num;
        $data['share_num'] = $share_num;
        $data['prize_num'] = $prize_num;
        $data['prize_id'] = $prize_id;

        $save['remark'] = json_encode($data);
        $save['set_value'] = $start_num;
        $save['update_time'] = time();

        $query = $set->where(array('set_type'=>'game_info'))->save($save);

        $res = "更新";
        if($query){
            $set->where(array('set_type'=>'create_game'))->save(array('set_value'=>$create_game,'update_time'=>time()));
            $set->where(array('set_type'=>'game_over_time'))->save(array('set_value'=>$game_over_time,'update_time'=>time()));

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




    function UploadRegTask($status,$name,$prize,$need_num,$ticket_id,$score,$money){
        $set = M('set');


        if($status=='1'){
            if(!$name){$arr = array('code'=> 0, 'res'=>'请输入红包名称');$this->ajaxReturn($arr,"JSON");}
            if(!$need_num){$arr = array('code'=> 0, 'res'=>'请输入任务需要的邀请人数');$this->ajaxReturn($arr,"JSON");}
            if($prize=='1'){
                if(!$ticket_id){$arr = array('code'=> 0, 'res'=>'请选择赠送的优惠券');$this->ajaxReturn($arr,"JSON");}
                $data['prize_id'] = $ticket_id;
            }
            if($prize=='2'){
                if(!$score){$arr = array('code'=> 0, 'res'=>'请输入赠送的积分');$this->ajaxReturn($arr,"JSON");}
                if($score<100){$arr = array('code'=> 0, 'res'=>'最低赠送100积分');$this->ajaxReturn($arr,"JSON");}
                $data['score'] = $score;
            }
            if($prize=='3'){
                if(!$money){$arr = array('code'=> 0, 'res'=>'请输入赠送的金额');$this->ajaxReturn($arr,"JSON");}
                if($money<0.1){$arr = array('code'=> 0, 'res'=>'最低赠送0.1元');$this->ajaxReturn($arr,"JSON");}
                $data['money'] = $money;
            }
            $data['name'] = $name;
            $data['need_num'] = $need_num;
            $data['prize'] = $prize;

        }


        $save['remark'] = json_encode($data);
        $save['set_value'] = $status;

        $query = $set->where(array('set_type'=>'register_task'))->save($save);
        $res = "更新";
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



    function shili($str,$value){
        $set = M('set');
        $query = $set->where(array('set_type'=>$value))->save(array('remark'=>$str,'update_time'=>time()));
        $res = "更新";
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

    function updatevip($status_type,$start_time,$end_time,$vip_content){
        $status  = M('status');

        $save['status'] = $status_type;
        if($status_type==1){
            if(!$vip_content){$arr = array('code'=> 0, 'res'=>'请输入活动规则');$this->ajaxReturn($arr,"JSON");}
            $save['content'] = $vip_content;
        }
        if($status_type==2){
            if(!$start_time){$arr = array('code'=> 0, 'res'=>'请选择活动开始时间');$this->ajaxReturn($arr,"JSON");}
            if(!$end_time){$arr = array('code'=> 0, 'res'=>'请选择活动结束时间');$this->ajaxReturn($arr,"JSON");}
            $start_time_s = strtotime($start_time);
            $end_time_s = strtotime($end_time);
            if($end_time_s<$start_time_s){
                $arr = array('code'=> 0, 'res'=>'结束时间不能小于开始时间');$this->ajaxReturn($arr,"JSON");
            }
            if(!$vip_content){$arr = array('code'=> 0, 'res'=>'请输入活动规则');$this->ajaxReturn($arr,"JSON");}
            $save['start_time'] = $start_time_s;
            $save['end_time'] = $end_time_s;
            $save['content'] = $vip_content;
        }

        $query = $status->where(array('type'=>'1'))->save($save);
        $res = "保存";
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



    function updatexs($status_type,$start_time,$end_time,$vip_content){
        $status  = M('status');

        $save['status'] = $status_type;
        if($status_type==1){
            if(!$vip_content){$arr = array('code'=> 0, 'res'=>'请输入活动规则');$this->ajaxReturn($arr,"JSON");}
            $save['content'] = $vip_content;
        }
        if($status_type==2){
            if(!$start_time){$arr = array('code'=> 0, 'res'=>'请选择活动开始时间');$this->ajaxReturn($arr,"JSON");}
            if(!$end_time){$arr = array('code'=> 0, 'res'=>'请选择活动结束时间');$this->ajaxReturn($arr,"JSON");}
            $start_time_s = strtotime($start_time);
            $end_time_s = strtotime($end_time);
            if($end_time_s<$start_time_s){
                $arr = array('code'=> 0, 'res'=>'结束时间不能小于开始时间');$this->ajaxReturn($arr,"JSON");
            }
            if(!$vip_content){$arr = array('code'=> 0, 'res'=>'请输入活动规则');$this->ajaxReturn($arr,"JSON");}
            $save['start_time'] = $start_time_s;
            $save['end_time'] = $end_time_s;
            $save['content'] = $vip_content;
        }

        $query = $status->where(array('type'=>'4'))->save($save);
        $res = "保存";
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


    function updatetj($status_type,$start_time,$end_time,$vip_content,$tj_money,$tj_num){
        $status  = M('status');

        $save['status'] = $status_type;
        $save['money'] = $tj_money;
        $save['num'] = $tj_num;
        if($status_type==1){
            if(!$vip_content){$arr = array('code'=> 0, 'res'=>'请输入活动规则');$this->ajaxReturn($arr,"JSON");}
            $save['content'] = $vip_content;
        }
        if($status_type==2){
            if(!$start_time){$arr = array('code'=> 0, 'res'=>'请选择活动开始时间');$this->ajaxReturn($arr,"JSON");}
            if(!$end_time){$arr = array('code'=> 0, 'res'=>'请选择活动结束时间');$this->ajaxReturn($arr,"JSON");}
            $start_time_s = strtotime($start_time);
            $end_time_s = strtotime($end_time);
            if($end_time_s<$start_time_s){
                $arr = array('code'=> 0, 'res'=>'结束时间不能小于开始时间');$this->ajaxReturn($arr,"JSON");
            }
            if(!$vip_content){$arr = array('code'=> 0, 'res'=>'请输入活动规则');$this->ajaxReturn($arr,"JSON");}
            $save['start_time'] = $start_time_s;
            $save['end_time'] = $end_time_s;
            $save['content'] = $vip_content;
        }

        $query = $status->where(array('type'=>'3'))->save($save);
        $res = "保存";
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

    function updatemonth($status_type,$start_time,$end_time,$vip_content){
        $status  = M('status');

        $save['status'] = $status_type;
        if($status_type==1){
            if(!$vip_content){$arr = array('code'=> 0, 'res'=>'请输入活动规则');$this->ajaxReturn($arr,"JSON");}
            $save['content'] = $vip_content;
        }
        if($status_type==2){
            if(!$start_time){$arr = array('code'=> 0, 'res'=>'请选择活动开始时间');$this->ajaxReturn($arr,"JSON");}
            if(!$end_time){$arr = array('code'=> 0, 'res'=>'请选择活动结束时间');$this->ajaxReturn($arr,"JSON");}
            $start_time_s = strtotime($start_time);
            $end_time_s = strtotime($end_time);
            if($end_time_s<$start_time_s){
                $arr = array('code'=> 0, 'res'=>'结束时间不能小于开始时间');$this->ajaxReturn($arr,"JSON");
            }
            if(!$vip_content){$arr = array('code'=> 0, 'res'=>'请输入活动规则');$this->ajaxReturn($arr,"JSON");}
            $save['start_time'] = $start_time_s;
            $save['end_time'] = $end_time_s;
            $save['content'] = $vip_content;
        }

        $query = $status->where(array('type'=>'2'))->save($save);
        $res = "保存";
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


    function UploadSets($str,$set_value,$remark){
        $set = M('set');

        $query = $set->where(array('set_type'=>$str))->save(array('set_value'=>$set_value,'remark'=>$remark,'update_time'=>time()));

        $res = "更新";
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

    function UploadSetOdds($str,$set_value){
        $set = M('odds_type');

        $query = $set->where(array('id'=>$str))->save(array('odds'=>$set_value,'update_time'=>time()));

        $res = "更新";
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

    function UploadSet($str,$set_value){
        $set = M('set');

        $query = $set->where(array('set_type'=>$str))->save(array('set_value'=>$set_value,'update_time'=>time()));

        $res = "更新";
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