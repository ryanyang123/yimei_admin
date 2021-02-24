<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'user'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }

    public function index(){

        $user = M('user');
        $team_money = M('team_money');
        $order = M('shop_order');
        $ticket_user = M('ticket_user');
        $hospital_order = M('hospital_order');

        $search = I('get.search');
        if($search){
            $where['phone|name'] =  array("like","%".$search."%");;
            $search_list['search'] = $search;
        }

        $user_id = I('get.user_id');
        if($user_id){
            $where['id'] =  $user_id;
            $search_list['user_id'] = $user_id;
        }
        $sex = I('get.sex');
        if($sex){
            $where['sex'] =  $sex;
            $search_list['sex'] = $sex;
        }
        $is_freeze = I('get.is_freeze');
        if($is_freeze){
            $where['is_freeze'] =  $is_freeze-1;
            $search_list['is_freeze'] = $is_freeze;
        }
    $is_agent = I('get.is_agent');
        if($is_agent){
            $where['is_agent'] =  $is_agent-1;
            $search_list['is_agent'] = $is_agent;
        }

        $is_service = I('get.is_service');
        if($is_service){
            $where['is_service'] =  $is_service-1;
            $search_list['is_service'] = $is_service;
        }
        $vip = I('get.vip');
        if($vip){
            $where['vip'] =  $vip-1;
            $search_list['vip'] = $vip;
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


        $where['is_robot'] = 0;
        $where['is_service'] = 0;
        $count = $user->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $module_name = '/' . MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;//获取路径
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% <input  class="tz_input" type="text" id="tz_input"/> <a class="tz_a_all" id="tz_a_num" href="' . $module_name . '/p/">跳转</a>%HEADER%');

        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个用户</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $user->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $order_money = 0;
            $order_money += $order->where(array('user_id'=>$item['id'],'status'=>array('NEQ','0')))->sum('total_money');
            $order_money += $hospital_order->where(array('user_id'=>$item['id'],'status'=>array('NEQ','0')))->sum('money');
            $order_money += $ticket_user->where(array('user_id'=>$item['id'],'status'=>array('NEQ','3')))->sum('buy_money');
            $order_money -= $ticket_user->where(array('user_id'=>$item['id'],'status'=>array('NEQ','3')))->sum('rebate_money');


            $list[$key]['zong_money'] = $order_money;


            $list[$key]['total_revenue'] = 0;
            $list[$key]['total_revenue'] += $team_money->where(array('user_id'=>$list['id']))->sum('money');

        }


        $data = array(
            'list' =>$list,
            'search_list' =>$search_list,
            'page' =>$show,
            'at' =>'user',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }

    function check(){
        $is_edit  =  I('get.edit');

        $team = M('team');
        $user = M('user');
        $order = M('shop_order');
        $ticket_user = M('ticket_user');
        $team_money = M('team_money');

        $user_odds_new = M('user_odds_new');
        $odds = M('odds');
        $odds_type = M('odds_type');


        $set = M('set');
        $set_list = $set->select();
        $a_user_num = 0;
        $b_user_num = 0;
        foreach ($set_list as $item){
            $set_info[$item['set_type']] = $item['set_value'];
        }

        $is_one = 0;
        if($is_edit){
            $list = $user->where(array('id'=>$is_edit))->find();
            $order_money = 0;
            $order_money += $order->where(array('user_id'=>$is_edit,'status'=>array('NEQ','0')))->sum('money');
            $list['zong_money'] = $order_money;


                $tic_list = $ticket_user
                    ->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')
                    ->field('lyx_ticket_user.*,lyx_ticket.title,lyx_ticket.type')
                    ->where(array('lyx_ticket_user.user_id'=>$is_edit))
                    ->order('lyx_ticket_user.create_time desc')->select();

            foreach ($tic_list as $key=>$item){
                $tic_list[$key]['discount'] = $item['discount']/10;
            }
            $list['ticket_list'] =$tic_list;

            $where_a1['team_id']  = $list['team_id'];
            $where_a1['parent']  = $list['id'];
            $where_a1['id'] = array('NEQ',$list['id']);
            $a_list =  $user->where($where_a1)->field('id,head,name,phone')->select();
            if($a_list){
                $a_user_num = count($a_list);
                foreach ($a_list as $key=>$item){
                    $b_list = array();
                    $a_user_list = array();
                    $a_id_list = array();
                    $a_user_list =  $user->where(array('parent'=>$item['id'],'id'=>array('NEQ',$list['id'])))->field('id')->select();
                    $a_id_list[] = $item['id'];
                    foreach ($a_user_list as $val_a){
                        $a_id_list[] = $val_a['id'];
                    }
                    $a_id_where = implode(',',$a_id_list);

                    $where_a_team['user_id'] = $list['id'];
                    $where_a_team['buy_user_id'] = array('IN',$a_id_where);
                    $a_rebate = 0;
                    $a_rebate += $team_money->where($where_a_team)->sum('money');
                    $a_list[$key]['rebate'] =(String)$a_rebate;

                    $where_b1['team_id']  = $list['team_id'];
                    $where_b1['parent']  = $item['id'];
                    $where_b1['id'] = array('NEQ',$list['id']);
                    $where_b1['is_agent']  = 1;

                    $b_list =  $user->where($where_b1)->field('id,head,name,phone')->select();
                    if($b_list){
                        $b_user_num += count($b_list);
                        foreach ($b_list as $key1=>$val){
                            $b_user_list = array();
                            $b_id_list = array();
                            $b_user_list =  $user->where(array('parent'=>$val['id'],'id'=>array('NEQ',$list['id'])))->field('id')->select();
                            $b_id_list[] = $val['id'];
                            foreach ($b_user_list as $val_b){
                                $b_id_list[] = $val_b['id'];
                            }
                            $b_id_where = implode(',',$b_id_list);


                            $where_b_team['user_id'] = $list['id'];
                            $where_b_team['buy_user_id'] = array('IN',$b_id_where);
                            $b_rebate = 0;
                            $b_rebate += $team_money->where($where_b_team)->sum('money');
                            $b_list[$key1]['rebate'] =(String)$b_rebate;
                        }
                        $a_list[$key]['b_list'] = $b_list;
                        $a_list[$key]['b_num'] = (String)count($b_list);
                    }else{
                        $a_list[$key]['b_num'] = '0';
                        $a_list[$key]['b_list'] = array();
                    }
                }
            }else{
                $a_list = array();
            }


            $total_revenue = 0;
            $total_revenue += $team_money->where(array('user_id'=>$list['id']))->sum('money');
            $list['total_revenue'] = (String)$total_revenue;

            $revenue_no = 0;
            $revenue_no += $team_money->where(array('user_id'=>$list['id'],'is_get'=>'0'))->sum('money');
            $list['revenue_no'] = (String)$revenue_no;

            $revenue_yes = 0;
            $revenue_yes += $team_money->where(array('user_id'=>$list['id'],'is_get'=>'1'))->sum('money');
            $list['revenue_yes'] = (String)$revenue_yes;

            if ($list['is_agent']=='0'){
                $team_info = $team->where(array('user_id'=>$list['id']))->find();
            }else{
                $team_info = array();
            }

            $list['team_info'] = $team_info;


            if ($list['team_id']=='0'){
                $list['team_id'] = -1;
            }
            $where['parent'] = array('NEQ',$list['id']);
            $where['code_user'] = $list['id'];
            /*
            $where['team_id'] = array('NEQ',$list['team_id']);
            $where['is_agent'] = 0;*/
            $code_num= 0;
            $code_list = $user->where($where)->field('id,head,name,phone')->select();

            $where_fl['user_id'] = $list['id'];
            foreach ($code_list as $key=>$item){
                //返利
                $where_fl['buy_user_id'] = $item['id'];
                $code_list[$key]['rebate'] = 0;
                $code_list[$key]['rebate'] += $team_money->where($where_fl)->sum('money');
            }
            $code_num += count($code_list);


            if ($list['parent']){
                $parent_info = $user->where(array('id'=>$list['parent']))->field('id,parent,team_id,name')->find();
                if ($parent_info['team_id']== $list['team_id']){
                    if ($parent_info['parent']){
                        $one_info = $user->where(array('id'=>$parent_info['parent']))->field('id,parent,team_id,name')->find();
                        if ($list['team_id']==$one_info['team_id']){
                            $is_one = 1;
                        }
                    }
                }
            }

            if ($list['code']){
                $code_user = $user->where(array('id'=>$list['code_user']))->field('id,name,head')->find();
            }

        }else{
            $list = array();
        }

        $odds_list = $odds->select();
        $type_list = $odds_type->select();
        $user_odds_list = $user_odds_new->where(array('user_id'=>$list['id'],'type'=>'1'))->select();
        $set_odds_list = $user_odds_new->where(array('type'=>'2'))->select();

        foreach ($user_odds_list as $item){
            $new_user_odds[$item['name']] = $item['odds'];
        }

        foreach ($set_odds_list as $item){
            $new_set_odds[$item['name']] = $item['odds'];
        }

        foreach ($odds_list as $key=>$item){
            $set_arr = array();
            foreach ($type_list as $val){
                $set_arr2 = array();
                $key_zh = $item['name'].'_'.$val['id'];
                if($new_user_odds[$key_zh]){
                    $set_arr2['name'] = $key_zh;
                    $set_arr2['value'] = $new_user_odds[$key_zh];
                    $set_arr2['type'] = 1;
                }else{
                    $set_arr2['name'] = $key_zh;
                    $set_arr2['value'] = $new_set_odds[$key_zh];
                    $set_arr2['type'] = 2;
                }
                $set_arr[] = $set_arr2;

            }

            $odds_list[$key]['set_list'] = $set_arr;
        }


        $data = array(
            'at'=>'user',
            'title'=>'用户',
            'list'=>$list,
            'a_list'=>$a_list,
            'code_list'=>$code_list,
            'code_num'=>$code_num,
            'parent_info'=>$parent_info,
            'code_user'=>$code_user,
            'is_one'=>$is_one,
            'one_info'=>$one_info,
            'odds_list'=>$odds_list,
            'type_list'=>$type_list,
            'a_num'=>$a_user_num,
            'b_num'=>$b_user_num,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    function plus(){
        $is_edit  =  I('get.edit');

        $activity = M('user');

        if($is_edit){
            $list = $activity->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'user',
            'title'=>'用户信息',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }





    public function editodds($type,$odds,$is_edit){
        if(session(XUANDB.'user_type')!='1'){
            $arr = array(
                'code'=> 0,
                'res'=> '没有修改权限'
            );
            $this->ajaxReturn($arr,"JSON");
        }
        $user = M('user');
        $user_odds_new = M('user_odds_new');

        if(!$odds){$arr = array('code'=> 0, 'res'=>'请输入要修改的抽水比例');$this->ajaxReturn($arr,"JSON");}
        $is_add = $user_odds_new->where(array('user_id'=>$is_edit,'name'=>$type,'type'=>'1'))->find();

        $user_info = $user->where(array('id'=>$is_edit))->find();
        $add['odds'] = $odds;

        if ($is_add){

            $add['update_time'] = time();
            $query = $user_odds_new->where(array('id'=>$is_add['id']))->save($add);

        }else{
            $add['user_id'] = $is_edit;
            $add['name'] = $type;
            $add['type'] = 1;
            $add['update_time'] = time();
            $add['create_time'] = time();
            $query = $user_odds_new->add($add);
        }


        $res = '修改';

        if($query){
            if ($type=='next_1' || $type=='next_2' || $type=='next_3' || $type=='next_4'|| $type=='next_5'){

                $where_one['parent'] = $is_edit;
                $where_one['team_id'] = $user_info['team_id'];


                $one_list = $user->where($where_one)->field('id')->select();

                if($one_list){
                    $arr_id = explode('_',$type);
                    $type_new = 'agent_rebate_'.$arr_id[1];
                    foreach ($one_list as $item){
                        $is_have = $user_odds_new->where(array('user_id'=>$item['id'],'name'=>$type,'type'=>'1'))->find();
                        $add_one['odds'] = $odds;
                        if ($is_have){
                            $add_one['update_time'] = time();
                            $query = $user_odds_new->where(array('id'=>$is_have['id']))->save($add_one);
                        }else{
                            $add_one['name'] = $type_new;
                            $add_one['type'] = 1;
                            $add_one['update_time'] = time();
                            $add_one['user_id'] = $item['id'];
                            $add_one['create_time'] = time();
                            $query = $user_odds_new->add($add_one);
                        }
                    }
                }

            }


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

    function deleteUser($id){
        $is_shop = M('shop')->where(array('user_id'=>$id))->find();
        if($is_shop){
            $arr = array(
                'code'=> 0,
                'res'=> '用户已开启机构无法删除'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        $is_city = M('city_admin')->where(array('user_id'=>$id))->find();
        if($is_city){
            $arr = array(
                'code'=> 0,
                'res'=> '用户已绑定城市合伙人账号，无法删除'
            );
            $this->ajaxReturn($arr,"JSON");
        }
        $query = M('user')->where(array('id'=>$id))->delete();
        $res = '注销';
        if($query){
            M('activity_user')->where(array('user_id'=>$id))->delete();
            M('activity_user_log')->where(array('user_id'=>$id))->delete();
            M('ali')->where(array('user_id'=>$id))->delete();
            M('ali_get')->where(array('user_id'=>$id))->delete();
            M('apply_company')->where(array('user_id'=>$id))->delete();
            M('blogs_comment')->where(array('user_id'=>$id))->delete();
            M('blogs_reply')->where(array('user_id'=>$id))->delete();
            M('blogs_zan')->where(array('user_id'=>$id))->delete();
            M('collect')->where(array('user_id'=>$id))->delete();
            M('game')->where(array('user_id'=>$id))->delete();
            M('game_log')->where(array('user_id'=>$id))->delete();
            M('game_log_share')->where(array('user_id'=>$id))->delete();
            M('get')->where(array('user_id'=>$id))->delete();
            M('gudong')->where(array('user_id'=>$id))->delete();
            M('hospital_comment')->where(array('user_id'=>$id))->delete();
            M('leave')->where(array('user_id'=>$id))->delete();
            M('level_log')->where(array('user_id'=>$id))->delete();
            M('made')->where(array('user_id'=>$id))->delete();
            M('notice')->where(array('user_id'=>$id))->delete();
            M('order_comment')->where(array('user_id'=>$id))->delete();
            M('pay')->where(array('user_id'=>$id))->delete();
            M('report')->where(array('user_id'=>$id))->delete();
            M('sms_code')->where(array('user_id'=>$id))->delete();
            M('system')->where(array('user_id'=>$id))->delete();
            M('task')->where(array('user_id'=>$id))->delete();
            M('task_prize')->where(array('user_id'=>$id))->delete();
            M('task_user')->where(array('user_id'=>$id))->delete();
            M('team')->where(array('user_id'=>$id))->delete();
            M('team_money')->where(array('user_id'=>$id))->delete();
            M('team_qr')->where(array('user_id'=>$id))->delete();
            M('ticket_give')->where(array('user_id'=>$id))->delete();
            M('ticket_user')->where(array('user_id'=>$id))->delete();
            M('tix_message')->where(array('user_id'=>$id))->delete();
            M('tui')->where(array('user_id'=>$id))->delete();
            M('tui_agent')->where(array('user_id'=>$id))->delete();
            M('user_blogs')->where(array('user_id'=>$id))->delete();
            M('user_card')->where(array('user_id'=>$id))->delete();
            M('user_odds')->where(array('user_id'=>$id))->delete();
            M('wx')->where(array('user_id'=>$id))->delete();
            M('yuyue')->where(array('user_id'=>$id))->delete();
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


    public function editmo($type,$is_edit){
        if(session(XUANDB.'user_type')!='1'){
            $arr = array(
                'code'=> 0,
                'res'=> '没有修改权限'
            );
            $this->ajaxReturn($arr,"JSON");
        }
        $user_odds_new = M('user_odds_new');

        $query = $user_odds_new->where(array('user_id'=>$is_edit,'name'=>$type,'type'=>'1'))->delete();
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

    public function delCodeQr($id){
        $user = M('user');

        $save['code_qr'] = null;
        $save['update_time'] = time();
        $query = $user->where(array('id'=>$id))->save($save);
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


    public function editcode($code,$is_edit){
        if(session(XUANDB.'user_type')!='1'){
            $arr = array(
                'code'=> 0,
                'res'=> '没有修改权限'
            );
            $this->ajaxReturn($arr,"JSON");
        }
        $user = M('user');

        if ($code){
            $code_user = $user->where(array('phone'=>$code))->field('id')->find();
            if (!$code_user){
                $arr = array(
                    'code'=> 0,
                    'res'=> '邀请码错误'
                );
                $this->ajaxReturn($arr,"JSON");
            }
            if ($code_user['id']==$is_edit){
                $arr = array(
                    'code'=> 0,
                    'res'=> '不能绑定客户本身'
                );
                $this->ajaxReturn($arr,"JSON");
            }
            $save['code_user'] = $code_user['id'];
        }else{
            $code = '';
            $save['code_user'] = 0;
        }


        $save['code'] = $code;
        $save['update_time'] = time();

        $query = $user->where(array('id'=>$is_edit))->save($save);
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

    public function editrebate($rebate,$is_edit){
        if(session(XUANDB.'user_type')!='1'){
            $arr = array(
                'code'=> 0,
                'res'=> '没有修改权限'
            );
            $this->ajaxReturn($arr,"JSON");
        }
        $user = M('user');
        if (!$rebate<0){
            $arr = array(
                'code'=> 0,
                'res'=> '返利金额不能小于0'
            );
            $this->ajaxReturn($arr,"JSON");
        }



        $save['rebate_money'] = $rebate;
        $save['update_time'] = time();

        $query = $user->where(array('id'=>$is_edit))->save($save);
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

    public function EditAgent($id){
        if(session(XUANDB.'user_type')!='1'){
            $arr = array(
                'code'=> 0,
                'res'=> '没有修改权限'
            );
            $this->ajaxReturn($arr,"JSON");
        }
        $user = M('user');
        $team = M('team');

        $user_info = $user->where(array('id'=>$id))->find();

        if (!$user_info){
            $arr = array(
                'code'=> 0,
                'res'=> '用户信息错误'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        if ($user_info['is_agent']=='1'){
            $arr = array(
                'code'=> 0,
                'res'=> '设置失败，此用户已经是代理'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        if (!$user_info['ali_user_id'] && !$user_info['ali_number']){
            $arr = array(
                'code'=> 0,
                'res'=> '此用户还未绑定支付宝账号，无法进行此操作'
            );
            $this->ajaxReturn($arr,"JSON");
        }




        $team_info = $team->where(array('user_id'=>$user_info['id']))->find();
        if ($team_info){
            $save_team['status'] = 1;
            $save_team['type'] = 3;
            $save_team['update_time'] = time();
            $query = M('team')->where(array('id'=>$team_info['id']))->save($save_team);

        }else{
            $add['user_id'] = $user_info['id'];
            $add['status'] = 1;
            $add['type'] = 3;
            $add['phone'] = $user_info['phone'];
            $add['sex'] = $user_info['sex'];
            $add['name'] = $user_info['name'];
            $add['age'] = $user_info['age'];
            $add['create_time'] = time();
            $add['update_time'] = time();
            $query = $team->add($add);
            $team_info['id'] = $query;
        }

        $res = '设置';

        if($query){

            //赠送优惠券
            //AgentTaskSend($user_info['id']);
           /* $give_list = M('ticket')->where(array('type' => '2', 'is_give' => '1'))->select();
            if ($give_list) {
                $add_ticket['user_id'] = $user_info['id'];
                $add_ticket['buy_money'] = 0;
                $add_ticket['rebate_money'] = 0;
                $add_ticket['create_time'] = time();
                foreach ($give_list as $item) {
                    $is_have = M('ticket_user')->where(array('user_id'=> $user_info['id'],'parent'=>$item['id']))->find();
                    if (!$is_have){
                        $add_ticket['parent'] = $item['id'];
                        $add_ticket['discount'] = $item['discount'];
                        M('ticket_user')->add($add_ticket);
                    }
                }
            }*/
            //发送星级会员公告
            SendNoticeUser($user_info['id'],2);
            $parent_id = 0;
            $one_id = 0;
            $rebate_city = $user_info['rebate_city'];
            $team_id = $team_info['id'];
            if($user_info['code_user']){
                $parent = $user->where(array('id'=>$user_info['code_user']))->find();
                if($parent){
                    $parent_id = $parent['id'];
                    $one_id = $parent['parent'];
                    $team_id = $parent['team_id'];
                    $rebate_city = $parent['rebate_city'];
                }
            }


            $user->where(array('id' => $user_info['id']))->save(array(
                'is_agent' => '1',
                'rebate_city' => $rebate_city,
                'team_id' => $team_id,
                'parent' => $parent_id,
                'one' => $one_id,
                'update_time' => time()
            ));

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

    function testdata(){
            die;
//        $zs_region = M('zs_region');
//        $list = $zs_region->where(array('level'=>2))->select();
//

        $lat_lng = '114.057868,22.543099';


        //$a = getaddress($lat_lng);

        $user = M('user');


        $where['is_agent'] = 0;
        $where['code_user'] = array('NEQ',0);
        //$where['rebate_city'] = 0;
        $where['is_check_city'] = 0;
        $where['lng'] = array('GT',0);
        $list = $user
            ->limit(100)
            ->where($where)->select();
        $i = 0;
        $j = 0;
        $shibai = 0;

        dump('$list:'.count($list));
        foreach ($list as $item){
            $lat_lng = $item['lng'].','.$item['lat'];

            $parent = $user->where(array('id'=>$item['code_user']))->find();
            if($parent['code_user']){
                $city_id = $parent['rebate_city'];
                $j++;
            }else{
                $city_id = getaddress($lat_lng);
            }
            if($city_id){
                $i++;
                $user->where(array('id'=>$item['id']))->save(array('rebate_city'=>$city_id,'is_check_city'=>1));
            }else{
                $shibai++;
                $user->where(array('id'=>$item['id']))->save(array('is_check_city'=>1));
            }
        }
        dump('$i:'.$i);
        dump('$j:'.$j);
        dump('$shibai:'.$shibai);
        dump($list);
        die;
        dump('$agent_num:'.$agent_num);

        $no_city_num = $user->where(array('city'=>''))->count();
        dump('$no_city_num:'.$no_city_num);
    }

    public function delagent($id){
        if(session(XUANDB.'user_type')!='1'){
            $arr = array(
                'code'=> 0,
                'res'=> '没有修改权限'
            );
            $this->ajaxReturn($arr,"JSON");
        }
        $user = M('user');
        $team = M('team');
        $team_money = M('team_money');
        $ticket_user = M('ticket_user');

        $user_info = $user->where(array('id'=>$id))->find();

        if (!$user_info){
            $arr = array(
                'code'=> 0,
                'res'=> '用户信息错误'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        if ($user_info['is_agent']!='1'){
            $arr = array(
                'code'=> 0,
                'res'=> '设置失败，此用户不是代理'
            );
            $this->ajaxReturn($arr,"JSON");
        }
        $where['user_id'] = $user_info['id'];
        $where['is_get'] =0;

        $is_have =  $team_money->where($where)->field('id')->find();
        if ($is_have){
            $arr = array(
                'code'=> 0,
                'res'=> '此代理存有未结算抽成，请结算完成后进行此操作!'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        $parent_num = $user->where(array('parent'=>$user_info['id']))->count();
        if ($parent_num>0){
            $arr = array(
                'code'=> 0,
                'res'=> '此代理已有下级代理，无法进行此操作!'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        $save['team_id'] = 0;
        $save['is_team'] = 0;
        $save['is_team_pay'] = 0;
        $save['is_agent'] = 0;
        $save['is_gudong'] = 0;
        $save['update_time'] = time();
        $query =  $user->where(array('id'=>$user_info['id']))->save($save);

        $res = '设置';

        if($query){
            $where_have['lyx_ticket.type'] = 2;
            $where_have['lyx_ticket_user.user_id'] = $user_info['id'];
            $where_have['lyx_ticket_user.status'] = 0;
            $have_list = $ticket_user->where($where_have)
                ->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')
                ->field('lyx_ticket_user.id')
                ->select();
            if ($have_list){
                foreach ($have_list as $item){
                    $ticket_user->where(array('id'=>$item['id']))->delete();
                }
            }

            $team->where(array('user_id'=>$user_info['id']))->save(array('status'=>3,'update_time'=>time()));

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












