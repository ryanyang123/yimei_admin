<?php
namespace Home\Controller;
use Think\Controller;
class ProjectclassController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'projectclass'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $project_class = M('project_class');
        $project = M('project');
        $project_list = $project->select();
        foreach ($project_list as $item){
            $new_pro[$item['id']] = $item['name'];
        }



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


        //$where['is_show'] = 1;
        $count = $project_class->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个分类</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $project_class->where($where)->order('sort,create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $list[$key]['project'] = $new_pro[$item['parent']];
        }



        $data = array(
            'list' =>$list,
            'search_list' =>$search_list,
            'page' =>$show,
            'is_show' =>$is_show,
            'project_list' =>$project_list,
            'at' =>'projectclass',
            'count' =>count($list),

        );
        $this->assign($data);
        $this->display();
    }



    function plus(){
        $is_edit  =  I('get.edit');

        $project_class = M('project_class');
        $project = M('project');
        $project_list = $project->select();
        foreach ($project_list as $item){
            $new_pro[$item] = $item['name'];
        }

        if($is_edit){
            $list = $project_class->where(array('id'=>$is_edit))->find();

        }else{
            $list = array();

        }


        $data = array(
            'at'=>'projectclass',
            'title'=>'活动分类',
            'project_list'=>$project_list,
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($img,$name,$info,$rebate,$parent,$sort,$is_edit){
        $project_class = M('project_class');
        if(!$img && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传图片');$this->ajaxReturn($arr,"JSON");}

        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入分类名称');$this->ajaxReturn($arr,"JSON");}
        if(!$info){$arr = array('code'=> 0, 'res'=>'请输入分类简介');$this->ajaxReturn($arr,"JSON");}
        if(!$parent){$arr = array('code'=> 0, 'res'=>'请选择所属活动');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            $where_name['id'] = array('NEQ',$is_edit);
        }

        $where_name['name']  =$name;
        $where_name['parent']  =$parent;
        $is_name = $project_class->where($where_name)->find();
        if($is_name){$arr = array('code'=> 0, 'res'=>'当前分类名称已存在');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            if($img){
                $save['icon'] = $img;
            }
            $save['sort'] = $sort;
            $save['rebate'] = $rebate;
            $save['name'] = $name;
            $save['info'] = $info;
            $save['parent'] = $parent;
            $save['update_time'] = time();
            $query = $project_class->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['rebate'] = $rebate;
            $add['icon'] = $img;
            $add['sort'] = $sort;
            $add['name'] = $name;
            $add['info'] = $info;
            $add['parent'] = $parent;
            $add['create_time'] = time();
            $query = $project_class->add($add);
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












