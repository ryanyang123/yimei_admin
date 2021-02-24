<?php
namespace Home\Controller;
use Think\Controller;
class YuyueController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'yuyue'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }

    public function index(){
        $user = M('user');
        $yuyue = M('yuyue');
        $shop = M('shop');
        $store_classify = M('store_classify');



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


        $count = $yuyue->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个预约</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $yuyue->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $user_name = $user->where(array('id'=>$item['user_id']))->field('id,name,phone')->find();

            $list[$key]['user_name'] = $user_name['name'];
            //$list[$key]['phone'] = $user_name['phone'];
           /* $is_img = $cache->where(array('thumb'=>$item['img_url']))->find();
            if ($is_img['url']){
                $list[$key]['img_url'] = $is_img['url'];
            }*/
            $shop_info = $shop->where(array('id'=>$item['shop_id']))->field('name,classify')->find();

            $class_info = $store_classify->where(array('id'=>$shop_info['classify']))->find();


            $list[$key]['class_name'] = $class_info['name'];
            $list[$key]['shop_name'] = $shop_info['name'];




        }


        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'yuyue',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }



    function plus(){
        $is_edit  =  I('get.edit');

        $activity = M('activity');

        if($is_edit){
            $list = $activity->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'shopsh',
            'title'=>'店铺审核',
            'list'=>$list,
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


    function editres($id){
        $team = M('team');
        $user = M('user');
        $apply_info = $team->where(array('id'=>$id))->find();
        if($apply_info['status']!='0'){$arr = array('code'=> 0, 'res'=>'该申请已处理');$this->ajaxReturn($arr,"JSON");}

        $save['status'] = 1;
        $save['update_time'] = time();
        $query = $team->where(array('id'=>$id))->save($save);

        $res = '通过';
        if($query){
            $save_user['is_agent'] = 1;
            $save_user['update_time'] = time();
            if($apply_info['type']=='1'){
                $save_user['team_id'] = $apply_info['id'];
                $save_user['is_team'] = 1;
            }else{
                $save_user['team_id'] = $apply_info['parent'];
            }
            $user->where(array('id'=>$apply_info['user_id']))->save($save_user);

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
        $made = M('made');
        $user = M('user');
        $apply_info = $made->where(array('id'=>$id))->find();
        if($apply_info['is_dispose']!='0'){$arr = array('code'=> 0, 'res'=>'该申请已处理');$this->ajaxReturn($arr,"JSON");}

        $save['is_dispose'] = 1;
        $save['remark'] = $cause;
        $save['update_time'] = time();
        $query = $made->where(array('id'=>$id))->save($save);

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












