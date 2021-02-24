<?php
namespace Home\Controller;
use Think\Controller;
class ActivityController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'activity'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){


        $activity = M('activity');
        $hospital = M('hospital');


        //$where['is_show'] = 1;
        $count = $activity->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个活动</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $activity->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $hos_list = array();
            $hos_arr = explode(',',$item['hospital_list']);
            foreach ($hos_arr as $val){
                $hos_name = $hospital->where(array('id'=>$val))->field('id,name')->find();
                $hos_list[] = $hos_name;
            }
            $list[$key]['hos_list'] = $hos_list;
        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'activity',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $activity = M('activity');
        $hospital = M('hospital');

        if($is_edit){
            $list = $activity->where(array('id'=>$is_edit))->find();
            $hos_arr = explode(',',$list['hospital_list']);
        }else{
            $list = array();
            $hos_arr = array();
        }

        $hos_list = $hospital->where(array('type'=>'1'))->field('id,name')->select();
        foreach ($hos_list as $key=>$item){
            $is_have = in_array($item['id'],$hos_arr);
            if ($is_have){
                $hos_list[$key]['is_on'] = '1';
            }else{
                $hos_list[$key]['is_on'] = '0';
            }
        }

        $data = array(
            'at'=>'activity',
            'title'=>'活动',
            'list'=>$list,
            'hos_list'=>$hos_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($title,$share_info,$img,$name,$num,$group_num,$rule,$hos_val,$is_edit){
        $activity = M('activity');

        if(!$title){$arr = array('code'=> 0, 'res'=>'请输入分享标题');$this->ajaxReturn($arr,"JSON");}
        if(!$share_info){$arr = array('code'=> 0, 'res'=>'请输入分享内容');$this->ajaxReturn($arr,"JSON");}
        if(!$share_info){$arr = array('code'=> 0, 'res'=>'请输入模板名称');$this->ajaxReturn($arr,"JSON");}
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入模板名称');$this->ajaxReturn($arr,"JSON");}
        if(!$num){$arr = array('code'=> 0, 'res'=>'请输入需要邀请人数');$this->ajaxReturn($arr,"JSON");}
        if($num>99){$arr = array('code'=> 0, 'res'=>'邀请人数不能大于99');$this->ajaxReturn($arr,"JSON");}
        if(!$group_num){$arr = array('code'=> 0, 'res'=>'请输入抽奖分组人数');$this->ajaxReturn($arr,"JSON");}
        if(!$rule){$arr = array('code'=> 0, 'res'=>'请输入规则');$this->ajaxReturn($arr,"JSON");}
        if(!$hos_val){$arr = array('code'=> 0, 'res'=>'请选择参与医院');$this->ajaxReturn($arr,"JSON");}


        if($is_edit){
            if($img){
                $save['share_icon'] = $img;
            }
            $save['title'] = $title;
            $save['share_info'] = $share_info;
            $save['name'] = $name;
            $save['num'] = $num;
            $save['group_num'] = $group_num;
            $save['rule'] = $rule;
            $save['hospital_list'] = $hos_val;
            $save['update_time'] = time();
            $query = $activity->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $arr = array(
                'code'=> 0,
                'res'=> '不可新增'
            );
            $this->ajaxReturn($arr,"JSON");
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


}












