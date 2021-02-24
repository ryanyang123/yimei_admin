<?php
namespace Home\Controller;
use Think\Controller;
class RobotController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'robot'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $user = M('user');

        $is_show = I('get.is_show');
        if($is_show){
            $where['is_show'] =  $is_show-1;
            $search_list['is_show'] = $is_show;
        }

        $status = I('get.status');
        if($status){
            $where['type'] =  $status-1;
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


        $where['id'] = array('NEQ','5666');
        $where['is_robot'] = 1;
        $count = $user->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个机器人</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $user->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

        }


        $data = array(
            'list' =>$list,
            'search_list' =>$search_list,
            'page' =>$show,
            'at' =>'robot',
            'count' =>count($list),

        );
        $this->assign($data);
        $this->display();
    }

    function SearchGoods($search_val)
    {
        $shop_goods = M('shop_goods');
        if (!$search_val) {
            $arr = array('code' => 0, 'res' => '请输入商品名称');
            $this->ajaxReturn($arr, "JSON");
        }
        $where['lyx_shop_goods.name'] = array("like", "%" . $search_val . "%");

        $list = $shop_goods->where($where)->join('lyx_shop ON lyx_shop_goods.shop_id = lyx_shop.id')
            ->field('lyx_shop_goods.id,lyx_shop_goods.name,lyx_shop.name as shop_name')
            ->select();
        $res = '查询';
        if ($list) {
            $arr = array(
                'code' => 1,
                'res' => $res . '成功',
                'list' => $list
            );
            $this->ajaxReturn($arr, "JSON");
        } else {
            $arr = array(
                'code' => 0,
                'res' => $res . '失败'
            );
            $this->ajaxReturn($arr, "JSON");
        }
    }



    function plus(){
        $is_edit  =  I('get.edit');

        $user = M('user');


        if($is_edit){
            $list = $user->where(array('id'=>$is_edit))->find();

        }else{
            $list = array();
        }



        $data = array(
            'at'=>'robot',
            'title'=>'机器人',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($img,$name,$phone,$sex,$lng,$lat,$is_edit){

        $user = M('user');

        if(!$img && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传图片');$this->ajaxReturn($arr,"JSON");}
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入昵称');$this->ajaxReturn($arr,"JSON");}
        if(!$phone){$arr = array('code'=> 0, 'res'=>'请输入手机号');$this->ajaxReturn($arr,"JSON");}
        if(!$lng || !$lat){$arr = array('code'=> 0, 'res'=>'请输入经纬度');$this->ajaxReturn($arr,"JSON");}



        if ($is_edit){
            $where_name['id'] = array('NEQ',$is_edit);
            $where_phone['id'] = array('NEQ',$is_edit);
        }
        $where_name['name'] = $name;
        $is_name = $user->where($where_name)->find();
        if ($is_name){
            $arr = array('code'=> 0, 'res'=>'昵称已存在');$this->ajaxReturn($arr,"JSON");
        }

        $where_phone['phone'] = $phone;
        $is_name = $user->where($where_phone)->find();
        if ($is_name){
            $arr = array('code'=> 0, 'res'=>'手机号已被注册');$this->ajaxReturn($arr,"JSON");
        }
        vendor('Rong.rongcloud');
        if($is_edit){
            $user_info = $user->where(array('id'=>$is_edit))->find();
            if($img){
                $save['head'] = $img;
                $head = $img;
            }else{
                $head = $user_info['head'];
            }
            $save['name'] = $name;
            $save['lng'] = $lng;
            $save['phone'] = $phone;
            $save['sex'] = $sex;
            $save['lat'] = $lat;
            $save['update_time'] = time();
            $query = $user->where(array('id'=>$is_edit))->save($save);
            if($query){
                $query = $is_edit;
            }
            $res = '修改';
        }else{
            $head = $img;
            $add['head'] = $img;
            $add['name'] = $name;
            $add['sex'] = $sex;
            $add['lng'] = $lng;
            $add['lat'] = $lat;
            $add['phone'] = $phone;
            $add['is_robot'] = 1;
            $add['signature'] = '听说有个签名，今年会有桃花运';
            $add['login_time'] = time();
            $add['token'] = md5('xuan_yimei_User'.$phone.time());
            $add['create_time'] = time();
            $query = $user->add($add);
            $res = '新增';
        }

        if($query){
            //融云token
            $RongCloud = new \RongCloud(C('RongCloudConf.key'),C('RongCloudConf.secret'));
            $result = $RongCloud->user()->getToken($query, $name,$head);
            $rongres =  json_decode($result,true);
            if($result){
                $user->where(array('id'=>$query))->save(array('rong_token'=>$rongres['token'],'update_time'=>time()));
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












