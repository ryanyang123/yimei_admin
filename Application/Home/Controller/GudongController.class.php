<?php
namespace Home\Controller;
use Think\Controller;
class GudongController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'gudong'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }

    public function index(){

        $user = M('user');
        $gudong = M('gudong');

        $user_id = I('get.user_id');
        if($user_id){
            $where['id'] =  $user_id;
            $search_list['user_id'] = $user_id;
        }

        $status = I('get.status');
        if($status){
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


        $count = $gudong->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条申请</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $gudong->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $user_name = $user->where(array('id'=>$item['user_id']))->field('id,name')->find();

            $list[$key]['user_name'] = $user_name['name'];
            if($item['img_list']){
                $img_list = explode(',',$item['img_list']);
                $list[$key]['img_arr'] = $img_list;
            }

        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'gudong',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }




    function editres($id){
        $gudong = M('gudong');
        $set = M('set');
        $apply_info = $gudong->where(array('id'=>$id))->find();
        if($apply_info['status']!='0'){$arr = array('code'=> 0, 'res'=>'该申请已处理');$this->ajaxReturn($arr,"JSON");}

        $save['status'] = 2;
        $save['is_team_pay'] = 2;
        $save['update_time'] = time();
        $query = $gudong->where(array('id'=>$id))->save($save);

        $res = '通过';
        if($query){
            $set_info = $set->where(array('set_type'=>'gudong_money'))->find();

            if($set_info['set_value']==0){
                UserIsGudong($apply_info['user_id']);
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


    function submitcause($id,$cause){
        $gudong = M('gudong');
        $user = M('user');
        $apply_info = $gudong->where(array('id'=>$id))->find();
        if($apply_info['status']!='0'){$arr = array('code'=> 0, 'res'=>'该申请已处理');$this->ajaxReturn($arr,"JSON");}

        $save['status'] = 3;
        $save['remark'] = $cause;
        $save['update_time'] = time();
        $query = $gudong->where(array('id'=>$id))->save($save);

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


}












