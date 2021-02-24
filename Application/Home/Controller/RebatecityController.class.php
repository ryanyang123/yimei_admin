<?php
namespace Home\Controller;
use Think\Controller;
class RebatecityController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'rebatecity'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){
        $city_admin = M('city_admin');
        $city_admin_city = M('city_admin_city');
        $rebate_city = M('rebate_city');
        $user = M('user');

        $where['type'] = 1;
        $where['is_supper'] = 0;

        $search = I('get.search');
        if($search){
            $where['username'] =  array("like","%".$search."%");;
            $search_list['search'] = $search;
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





        $count = $city_admin->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个账号</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $city_admin->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $user_info = $user->where(array('id'=>$item['user_id']))->field('name')->find();
            $list[$key]['user_name'] = $user_info['name'];
            $city_list = $city_admin_city->where(array('admin_id'=>$item['id']))->select();
            $arr_name = array();
            foreach ($city_list as $val){
                $name = $rebate_city->where(array('id'=>$val['city_id']))->find();
                $arr_name[] = $name['city'];
            }
            $list[$key]['city_name'] = implode(',',$arr_name);
        }

        $data = array(
            'list' =>$list,
            'search_list' =>$search_list,
            'page' =>$show,
            'at' =>'rebatecity',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }

    function check(){
        $is_edit  =  I('get.edit');
        $city_admin = M('city_admin');
        $city_admin_city = M('city_admin_city');
        $rebate_city = M('rebate_city');
        $user = M('user');
        $list = $city_admin->where(array('id'=>$is_edit))->find();

        $user_info = $user->where(array('id'=>$list['user_id']))->field('name')->find();
        $list['user_name'] = $user_info['name'];
        $city_list = $city_admin_city->where(array('admin_id'=>$list['id']))->select();
        foreach ($city_list as $key=>$val){
            $name = $rebate_city->where(array('id'=>$val['city_id']))->find();
            $city_list[$key]['info']  = $name;
        }

        $list['city_list'] = $city_list;

        $province_list = $rebate_city->distinct(true)->field('province')->select();
        $data = array(
            'at'=>'rebatecity',
            'title'=>'城市合伙人',
            'list'=>$list,
            'province_list'=>$province_list,
        );

        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $city_admin = M('city_admin');
        $rebate_city = M('rebate_city');
        $province_list = $rebate_city->distinct(true)->field('province')->select();
        if($is_edit){
            $list = $city_admin->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'rebatecity',
            'title'=>'城市合伙人',
            'list'=>$list,
            'province_list'=>$province_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function checkcity($province){
        $rebate_city = M('rebate_city');
        $list = $rebate_city->where(array('province'=>$province))->select();
        $arr = array(
            'code'=> 1,
            'list'=>$list,
            'res'=> '成功'
        );
        $this->ajaxReturn($arr,"JSON");
    }

    public function add($user_id,$name,$username,$password,$is_edit){
        $city_admin = M('city_admin');

        if(!$user_id){$arr = array('code'=> 0, 'res'=>'请输入绑定用户ID');$this->ajaxReturn($arr,"JSON");}
        if(!$username){$arr = array('code'=> 0, 'res'=>'请输入登录账号');$this->ajaxReturn($arr,"JSON");}
        $user_info  = M('user')->where(array('id'=>$user_id))->find();
        if(!$user_info){$arr = array('code'=> 0, 'res'=>'用户ID错误');$this->ajaxReturn($arr,"JSON");}


        if ($is_edit){
            $where_name['id'] = array('NEQ',$is_edit);
        }
        $where_name['username'] = $username;
        $is_name = $city_admin->where($where_name)->find();


        if ($is_name){
            $arr = array('code'=> 0, 'res'=>'登录账号已存在');$this->ajaxReturn($arr,"JSON");
        }
        $edit_info = $city_admin->where(array('id'=>$is_edit))->find();
        if($is_edit){

            //$save['user_id'] = $user_id;
            if($user_id!=$edit_info['user_id']){
                $is_have = $city_admin->where(array('user_id'=>$user_id))->find();
                if($is_have){$arr = array('code'=> 0, 'res'=>'当前绑定用户ID已经有其他账号');$this->ajaxReturn($arr,"JSON");}
            }

            $save['username'] = $username;
            $save['name'] = $name;
            if($password){
                $save['password'] = md5($password);
            }
            $save['update_time'] = time();
            $query = $city_admin->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $is_have = $city_admin->where(array('user_id'=>$user_id))->find();
            if($is_have){$arr = array('code'=> 0, 'res'=>'已为该用户创建账号');$this->ajaxReturn($arr,"JSON");}

            $add['user_id'] = $user_id;
            $add['name'] = $name;
            $add['username'] = $username;
            $add['type'] = 1;
            $add['password'] = md5($password);
            $add['create_time'] = time();
            $query = $city_admin->add($add);
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

    function editcity($id,$edit_id,$city_id,$s_odds,$h_odds,$t_odds,$ti_odds){

        if(!$city_id && $edit_id==0){$arr = array('code'=> 0, 'res'=>'请选择城市');$this->ajaxReturn($arr,"JSON");}
        if($s_odds>100){$arr = array('code'=> 0, 'res'=>'订单返利比例不能大于100');$this->ajaxReturn($arr,"JSON");}
        if($h_odds>100){$arr = array('code'=> 0, 'res'=>'机构买单返利比例不能大于100');$this->ajaxReturn($arr,"JSON");}
        if($t_odds>100){$arr = array('code'=> 0, 'res'=>'团队返利比例不能大于100');$this->ajaxReturn($arr,"JSON");}
        if($ti_odds>100){$arr = array('code'=> 0, 'res'=>'优惠券购买返利比例不能大于100');$this->ajaxReturn($arr,"JSON");}
        $city_admin_city = M('city_admin_city');

        if($edit_id){

            $save['shop_order_odds'] = $s_odds;
            $save['hospital_order_odds'] = $h_odds;
            $save['team_odds'] = $t_odds;
            $save['ticket_odds'] = $ti_odds;
            $save['update_time'] = time;
            $query = $city_admin_city->where(array('id'=>$edit_id))->save($save);
        }else{
            $is_add = $city_admin_city->where(array('city_id'=>$city_id))->find();
            if($is_add){$arr = array('code'=> 0, 'res'=>'该城市已绑定城市合伙人');$this->ajaxReturn($arr,"JSON");}
            $add['admin_id'] = $id;
            $add['city_id'] = $city_id;
            $add['shop_order_odds'] = $s_odds;
            $add['hospital_order_odds'] = $h_odds;
            $add['team_odds'] = $t_odds;
            $add['ticket_odds'] = $ti_odds;
            $add['create_time'] = time();
            $query = $city_admin_city->add($add);
        }

        $res = '操作';
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
    function delete($id){
        $city_admin = M('city_admin');
        $city_admin_city = M('city_admin_city');
        $info = $city_admin->where(array('id'=>$id))->find();
        $query  = $city_admin->where(array('id'=>$id))->delete();
        $res = '删除';
        if($query){
            $city_admin->where(array('user_id'=>$info['user_id']))->delete();
            $city_admin_city->where(array('admin_id'=>$id))->delete();
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












