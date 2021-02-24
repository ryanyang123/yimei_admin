<?php
namespace Home\Controller;
use Think\Controller;
class TeamqrController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'teamqr'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){


        $diary_classify = M('team_qr');
        $user = M('user');

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

        $is_show = I('get.is_show');
        if($is_show){
            $where['is_show'] =  $is_show-1;
            $search_list['is_show'] = $is_show;
        }
        $type = I('get.type');
        if($type){
            $where['type'] =  $type-1;
            $search_list['type'] = $type;
        }

        $is_use = I('get.is_use');
        if($is_use){
            $where['is_use'] =  $is_use-1;
            $search_list['is_use'] = $is_use;
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
        $count = $diary_classify->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个二维码</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $diary_classify->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

            if($item['user_id']!=0){
                $user_name = $user->where(array('id'=>$item['user_id']))->field('id,name')->find();
                $list[$key]['user_name'] = $user_name['name'];
            }
        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'search_list' =>$search_list,
            'at' =>'teamqr',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $diary_classify = M('team_qr');




        if($is_edit){
            $list = $diary_classify->where(array('id'=>$is_edit))->find();
            if($list['type']==1){
                $list['start_time'] = date('Y-m-d',$list['start_time']);
                $list['end_time'] = date('Y-m-d',$list['end_time']);
            }
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'teamqr',
            'title'=>'星级会员二维码',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($name,$user_id, $type, $start_time, $end_time, $num, $price, $agent_money,$city_money,$is_edit){
        $diary_classify = M('team_qr');
        $user = M('user');

        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入二维码名称');$this->ajaxReturn($arr,"JSON");}
        if(!$price){$arr = array('code'=> 0, 'res'=>'请输入需要支付的价格');$this->ajaxReturn($arr,"JSON");}

        if($type==1){
            if(!$start_time){$arr = array('code'=> 0, 'res'=>'请选择开始时间');$this->ajaxReturn($arr,"JSON");}
            if(!$end_time){$arr = array('code'=> 0, 'res'=>'请选择结束时间');$this->ajaxReturn($arr,"JSON");}

            $start_time = strtotime($start_time);
            $end_time = strtotime($end_time);
            if($end_time<=$start_time){$arr = array('code'=> 0, 'res'=>'结束时间不能小于开始时间');$this->ajaxReturn($arr,"JSON");}

        }
        if($type==2){
            if(!$num){$arr = array('code'=> 0, 'res'=>'请输入可使用次数');$this->ajaxReturn($arr,"JSON");}
        }

        if(!$agent_money){$agent_money=0;}
        if(!$user_id){$user_id=0;}

        if($user_id!=0){
            $user_info = $user->where(array('id'=>$user_id))->field('id')->find();
            if(!$user_info){$arr = array('code'=> 0, 'res'=>'用户ID错误');$this->ajaxReturn($arr,"JSON");}
        }


        if($is_edit){
            $save['name'] = $name;
            $save['user_id'] = $user_id;
            $save['price'] = $price;
            $save['agent_money'] = $agent_money;
            $save['start_time'] = $start_time;
            $save['end_time'] = $end_time;
            $save['city_money'] = $city_money;
            $save['type'] = $type;
            $save['num'] = $num;
            $save['update_time'] = time();
            $query = $diary_classify->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['name'] = $name;
            $add['user_id'] = $user_id;
            $add['price'] = $price;
            $add['agent_money'] = $agent_money;
            $add['city_money'] = $city_money;
            $add['start_time'] = $start_time;
            $add['end_time'] = $end_time;
            $add['type'] = $type;
            $add['num'] = $num;
            $add['create_time'] = time();
            $query = $diary_classify->add($add);
            $res = '新增';
        }

        if($query){
            if(!$is_edit){
                $qr_arr['from'] = 'yimei_app';
                $qr_arr['type'] = 3;
                $qr_arr['id'] = $query;
                $data = json_encode($qr_arr);
                $data =  encrypt1($data);
                //生成二维码
                $qr_url = makecode($data,'http://api.ymxte.com/logo.jpg');
                $diary_classify->where(array('id'=>$query))->save(array('qr_url'=>$qr_url));
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


}












