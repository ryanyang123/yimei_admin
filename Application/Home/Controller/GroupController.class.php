<?php
namespace Home\Controller;
use Think\Controller;
class GroupController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'13'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }

    public function index(){



        $user = M('user');
        $group = M('group');
        $group_user = M('group_user');
        $group_type = M('group_type');

        $search = I('get.search');
        if($search){
            $where['group_name'] =  array("like","%".$search."%");;
            $search_list['search'] = $search;
        }

        $group_id = I('get.group_id');
        if($group_id){
            $where['id'] =  array("like","%".$group_id."%");;
            $search_list['group_id'] = $group_id;
        }

        $user_id = I('get.user_id');
        if($user_id){
            $where['user_id'] =  $user_id;
            $search_list['user_id'] = $user_id;
        }

        $classify_id = I('get.classify_id');
        if($classify_id){
            $where['classify_id'] =  $classify_id;
            $search_list['classify_id'] = $classify_id;
        }
        $max = I('get.max');
        if($max){
            $where['max'] =  $max;
            $search_list['max'] = $max;
        }
        $is_freeze = I('get.is_freeze');
        if($is_freeze){
            $where['is_freeze'] =  $is_freeze-1;
            $search_list['is_freeze'] = $is_freeze;
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

        $type_list = $group_type->select();
        foreach ($type_list as $item){
            $new_type[$item['id']] = $item['type_name'];
        }


        $count = $group->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个群</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $group->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $user_name = $user->where(array('id'=>$item['user_id']))->field('id,name')->find();
            $list[$key]['user_name'] = $user_name['name'];

            $group_num = 0;
            $group_num += $group_user->where(array('group_id'=>$item['id']))->count();
            $list[$key]['group_num'] = $group_num;


            $list[$key]['type_name'] = $new_type[$item['classify_id']];

        }


        $data = array(
            'list' =>$list,
            'type_list' =>$type_list,
            'page' =>$show,
            'at' =>'group',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function check(){
        $is_edit  =  I('get.edit');

        $group = M('group');
        $user = M('user');
        $group_user = M('group_user');
        $group_type = M('group_type');

        $type_list = $group_type->select();
        foreach ($type_list as $item){
            $new_type[$item['id']] = $item['type_name'];
        }


        if($is_edit){
            $list = $group->where(array('id'=>$is_edit))->find();

            $user_name = $user->where(array('id'=>$list['user_id']))->field('id,name')->find();
            $list['user_name'] = $user_name['name'];

            $group_num = 0;
            $group_num += $group_user->where(array('group_id'=>$list['id']))->count();
            $list['group_num'] = $group_num;


            $list['type_name'] = $new_type[$list['classify_id']];

            $user_list = $group_user
                ->where(array('lyx_group_user.group_id'=>$list['id']))
                ->join('lyx_user ON lyx_group_user.user_id = lyx_user.id')
                ->order('lyx_group_user.type,lyx_group_user.create_time')
                ->field('lyx_group_user.user_id,lyx_group_user.type,lyx_user.name,lyx_user.head,lyx_group_user.create_time')
                ->select();

        }else{
            $list = array();
            $user_list = array();
        }

        $data = array(
            'at'=>'group',
            'title'=>'群组',
            'list'=>$list,
            'user_list'=>$user_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }



    function plus(){
        $is_edit  =  I('get.edit');

        $group = M('group');
        $group_type = M('group_type');
        $type_list = $group_type->select();
       /* foreach ($type_list as $item){
            $new_type[$item['id']]= $item['name'];
        }*/
        if($is_edit){
            $list = $group->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'group',
            'title'=>'群组',
            'list'=>$list,
            'type_list'=>$type_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }



    public function add($name,$classify_id,$introduce,$notice,$img1,$is_edit){
        $group = M('group');

        if(!$is_edit){$arr = array('code'=> 0, 'res'=>'不可新增');$this->ajaxReturn($arr,"JSON");}
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入群名称');$this->ajaxReturn($arr,"JSON");}
        if(!$introduce){$arr = array('code'=> 0, 'res'=>'请输入群介绍');$this->ajaxReturn($arr,"JSON");}


        $group_info = $group->where(array('id'=>$is_edit))->find();

            $save['group_name'] = $name;
            $save['classify_id'] = $classify_id;
            $save['introduce'] = $introduce;
            if ($group_info['notice']!=$notice){
                $save['notice'] = $notice;
                $save['notice_time'] = time();
            }
            if ($img1){
                $save['logo'] = $img1;
            }
            $save['update_time'] = time();
            $query = $group->where(array('id'=>$is_edit))->save($save);
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

    function deleteuser($id){
        $group_user = M('group_user');
        $user_friend_apply = M('user_friend_apply');
        $group_user_info  = $group_user->where(array('id'))->find();
        if (!$group_user_info){
            $arr = array(
                'code'=> 0,
                'res'=> '用户不存在'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        $query = $group_user->where(array('id'))->delete();
        $res = '删除';
        if($query){
            $user_friend_apply->where(array('type'=>'2','group_id'=>$id,'friend_user_id'))->delete();
            $RongCloud = new \RongCloud(C('RongCloudConf.key'),C('RongCloudConf.secret'));
            $result = $RongCloud->group()->quit($group_user['user_id'],$group_user['group_id']);


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
        $group = M('group');
        $group_user = M('group_user');
        $user_friend_apply = M('user_friend_apply');
        vendor('Rong.rongcloud');
        $group_info = $group->where(array('id'=>$id))->find();
        if (!$group_info){
            $arr = array(
                'code'=> 0,
                'res'=> '群组不存在'
            );
            $this->ajaxReturn($arr,"JSON");
        }
        $query = $group->where(array('id'=>$id))->delete();
        $res = '删除';
        if($query){
            $group_user->where(array('group_id'=>$id))->delete();
            $user_friend_apply->where(array('type'=>'2','group_id'=>$id))->delete();
            $RongCloud = new \RongCloud(C('RongCloudConf.key'),C('RongCloudConf.secret'));
            $result = $RongCloud->group()->dismiss($group_info['user_id'],$id);


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


    function editfreeze($id,$str){
        $group = M('group');
        $group_user = M('group_user');
        $user_friend_apply = M('user_friend_apply');
        vendor('Rong.rongcloud');
        $group_info = $group->where(array('id'=>$id))->find();
        if (!$group_info){
            $arr = array(
                'code'=> 0,
                'res'=> '群组不存在'
            );
            $this->ajaxReturn($arr,"JSON");
        }


        $query = $group->where(array('id'=>$id))->save(array('is_freeze'=>$str));
        $res = "操作";
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












