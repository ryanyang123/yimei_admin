<?php
namespace Home\Controller;
use Think\Controller;
class ComadminController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'comadmin'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){


        $comment_admin = M('comment_admin');
        $com_classify = M('com_classify');
        $class_list = $com_classify->select();
        foreach ($class_list as $key=>$item){
            $new_class[$item['id']] = $item['name'];
        }
        $search = I('get.search');
        if($search){
            $where['content'] =  array("like","%".$search."%");;
            $search_list['search'] = $search;
        }

        $classify = I('get.classify');
        if($classify){
            $where['classify'] =  $classify;
            $search_list['classify'] = $classify;
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
        $count = $comment_admin->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个默认评论语</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $comment_admin->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $list[$key]['type_name'] = $new_class[$item['classify']];
        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'class_list' =>$class_list,
            'search_list' =>$search_list,
            'at' =>'comadmin',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $comment_admin = M('comment_admin');
        $com_classify = M('com_classify');

        if($is_edit){
            $list = $comment_admin->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $class_list = $com_classify->select();
        $data = array(
            'at'=>'comadmin',
            'title'=>'默认评论语',
            'list'=>$list,
            'class_list'=>$class_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($content,$classify,$is_edit){
        $comment_admin = M('comment_admin');

        if(!$content){$arr = array('code'=> 0, 'res'=>'请输入评论语');$this->ajaxReturn($arr,"JSON");}
        if(!$classify){$arr = array('code'=> 0, 'res'=>'请选择评论语分类');$this->ajaxReturn($arr,"JSON");}

       /* if ($is_edit){
            $where_name['id'] = array('NEQ',$is_edit);
        }
        $where_name['content'] = $content;
        $is_name = $comment_admin->where($where_name)->find();
        if ($is_name){
            $arr = array('code'=> 0, 'res'=>'评论语已存在');$this->ajaxReturn($arr,"JSON");
        }*/
        if($is_edit){
            $save['content'] = $content;
            $save['classify'] = $classify;
            $save['update_time'] = time();
            $query = $comment_admin->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['classify'] = $classify;
            $add['content'] = $content;
            $add['create_time'] = time();
            $query = $comment_admin->add($add);
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


}












