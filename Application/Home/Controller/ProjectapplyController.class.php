<?php
namespace Home\Controller;
use Think\Controller;
class ProjectapplyController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'projectapply'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }

//    function test(){
//        $list = M('project_apply')->where(['status'=>1])->select();
//        foreach ($list as $item){
//            addProjectGoods($item['id']);
//        }
//    }


    public function index(){

        $project_apply = M('project_apply');
        $project_class = M('project_class');
        $project = M('project');
        $shop = M('shop');

        $project_img = M('project_img');
        $project_list = $project->select();
        foreach($project_list as $item){
            $new_pro[$item['id']] = $item['name'];
        }

        $class_list = $project_class->select();
        foreach($class_list as $key=>$item){
            $new_class[$item['id']] = $item;
            $class_list[$key]['project'] = $new_pro[$item['parent']];
        }

        $is_show = I('get.is_show');
        if($is_show){
            $where['is_show'] =  $is_show-1;
            $search_list['is_show'] = $is_show;
        }


        $is_freeze = I('get.is_freeze');
        if($is_freeze){
            $where['is_freeze'] =  $is_freeze-1;
            $search_list['is_freeze'] = $is_freeze;
        }

        $status = I('get.status');
        if($status){
            $where['status'] =  $status-1;
            $search_list['status'] = $status;
        }




        $parent = I('get.parent');
        if($parent){
            $where['parent'] =  $parent;
            $search_list['parent'] = $parent;
        }

        $search = I('get.search');
        if($search){
            $where['name'] =  array("like","%".$search."%");
            $search_list['search'] = $search;
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
        $count = $project_apply->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个申请</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $project_apply->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $img_list = $project_img->where(array('parent'=>$item['id']))->select();
            $list[$key]['img_list'] = $img_list;
            $list[$key]['project'] =  $new_pro[$item['project_id']];
            $list[$key]['class_name'] =  $new_class[$item['parent']]['name'];
            $list[$key]['class_rebate'] =  $new_class[$item['parent']]['rebate'];
            $shop_name = $shop->where(['id'=>$item['shop_id']])->find();
            $list[$key]['shop_name'] = $shop_name['name'];
        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'search_list' =>$search_list,
            'class_list' =>$class_list,
            'is_show' =>$is_show,
            'at' =>'projectapply',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }

    function plus(){
        $is_edit  =  I('get.edit');
        $project_apply = M('project_apply');
        $project_class = M('project_class');
        $project_img = M('project_img');
        $project = M('project');


        $project_list = $project->where(array('is_show'=>1))->select();
        if($is_edit){

            $list = $project_apply->where(array('id'=>$is_edit,'shop_id'=>session(XUANDB.'shop_id')))->find();
            $list['img_list'] = $project_img->where(array('parent'=>$list['id']))->select();

            $class_list = $project_class->where(array('parent'=>$list['project_id'],'is_show'=>1))->select();
        }else{
            $list = array();
            $class_list = array();
        }

        $data = array(
            'at'=>'projectapply',
            'title'=>'项目活动',
            'list'=>$list,
            'project_list'=>$project_list,
            'class_list'=>$class_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function editStatus($id,$remark){
        $project_apply = M('project_apply');
        $list = $project_apply->where(array('id'=>$id,'shop_id'=>session(XUANDB.'shop_id')))->find();
        if($list['status']){
            $arr = array(
                'code'=> 0,
                'res'=> '此申请已审核完毕'
            );
            $this->ajaxReturn($arr,"JSON");
        }
        $save['status'] = 2;
        $save['remark'] = $remark;
        $save['ok_time'] = time();
        $query = $project_apply->where(array('id'=>$id))->save($save);
        if($query){
            $arr = array(
                'code'=> 1,
                'res'=> '操作成功',
                'list'=> $list,
            );
            $this->ajaxReturn($arr,"JSON");
        }else{
            $arr = array(
                'code'=> 0,
                'res'=> '操作失败'
            );
            $this->ajaxReturn($arr,"JSON");
        }
    }


    public function editStatusRebate($id,$rebate,$sales){
        $project_apply = M('project_apply');
        $list = $project_apply->where(array('id'=>$id,'shop_id'=>session(XUANDB.'shop_id')))->find();
        if($list['status']==2){
            $arr = array(
                'code'=> 0,
                'res'=> '此申请无法无法修改'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        $save['status'] = 1;
        $save['rebate'] = $rebate;
        $save['sales'] = $sales;
        $query = $project_apply->where(array('id'=>$id))->save($save);
        if($query){
            addProjectGoods($id);

            M('shop_goods')->where(['project_id'=>$id])->save(['sales'=>$sales,'update_time'=>time()]);
            $arr = array(
                'code'=> 1,
                'res'=> '操作成功',
                'list'=> $list,
            );
            $this->ajaxReturn($arr,"JSON");
        }else{
            $arr = array(
                'code'=> 0,
                'res'=> '操作失败'
            );
            $this->ajaxReturn($arr,"JSON");
        }
    }


    function delete($id){
        $project_apply = M('project_apply');
        $project_img = M('project_img');
        $list = $project_apply->where(array('id'=>$id,'shop_id'=>session(XUANDB.'shop_id')))->find();
        if(!$list){
            $arr = array(
                'code'=> 0,
                'res'=> '没有权限'
            );
            $this->ajaxReturn($arr,"JSON");
        }
        $query = $project_apply->where(array('id'=>$id))->delete();
        if($query){
            $project_img->where(array('parent'=>$id))->delete();
            $arr = array(
                'code'=> 1,
                'res'=> '删除成功',
                'list'=> $list,
            );
            $this->ajaxReturn($arr,"JSON");
        }else{
            $arr = array(
                'code'=> 0,
                'res'=> '没有权限'
            );
            $this->ajaxReturn($arr,"JSON");
        }
    }


    function checkClass($id){
        $project_class = M('project_class');
        $list = $project_class->where(array('parent'=>$id,'is_show'=>1))->order('sort')->select();
        $res = '查询';
        if($list){
            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功',
                'list'=> $list,
            );
            $this->ajaxReturn($arr,"JSON");
        }else{
            $arr = array(
                'code'=> 0,
                'res'=> '此活动暂无分类'
            );
            $this->ajaxReturn($arr,"JSON");
        }
    }

    function add( $name,$price, $project_id, $parent, $content,$img_arr,$is_edit){
        $project_apply = M('project_apply');
        $project_img = M('project_img');
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入项目名称');$this->ajaxReturn($arr,"JSON");}
        if(!$price){$arr = array('code'=> 0, 'res'=>'请输入项目价值');$this->ajaxReturn($arr,"JSON");}
        if(!$content){$arr = array('code'=> 0, 'res'=>'请输入项目描述');$this->ajaxReturn($arr,"JSON");}
        if(!$project_id){$arr = array('code'=> 0, 'res'=>'请选择所属活动');$this->ajaxReturn($arr,"JSON");}
        if(!$parent){$arr = array('code'=> 0, 'res'=>'请选择所属分类');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            $edit_info =  $project_apply->where(array('id'=>$is_edit))->find();
            if($edit_info['shop_id']!=session(XUANDB.'shop_id')){$arr = array('code'=> 0, 'res'=>'没有修改权限');$this->ajaxReturn($arr,"JSON");}
            $save['name'] = $name;
            $save['project_id'] = $project_id;
            $save['parent'] = $parent;
            $save['content'] = $content;
            $save['price'] = $price;
            $save['status'] = 0;
            $save['remark'] = '';
            $save['ok_time'] = 0;
            $save['update_time'] = time();
            $query = $project_apply->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
            if($query){
                $query  = $is_edit;
            }
        }else{
            $add['price'] = $price;
            $add['name'] = $name;
            $add['project_id'] = $project_id;
            $add['parent'] = $parent;
            $add['content'] = $content;
            $add['shop_id'] = session(XUANDB.'shop_id');
            $add['status'] = 0;
            $add['create_time'] = time();
            $query = $project_apply->add($add);
            $res = '新增';
        }


        if($query){
            if($img_arr){
                foreach($img_arr as $item){
                    $add_banner['parent'] = $query;
                    $add_banner['img_url'] = $item;
                    $add_banner['create_time'] = time();
                    $project_img->add($add_banner);
                }
            }
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


    /*上传图片*/
    public function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize  = 3145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->saveName = date('YmdHis').rand(10000,99999);
        $upload->savePath =  'Uploads/goods/';// 设置附件上传目录
        /*if(!$upload->upload()) {// 上传错误提示错误信息
            $msg['success'] =false;
            echo json_encode($msg);
        }else{// 上传成功
            $info =  $upload->getUploadFileInfo();
            $msg['success'] =true;
            $msg['file_path'] ='/'.$info[0]['savepath'].$info[0]['savename'];
            echo json_encode($msg);
        }*/

        $info   =   $upload->uploadOne($_FILES['fileData']);
        if(!$info) {// 上传错误提示错误信息
            $msg['success'] =false;
            echo json_encode($msg);
        }else{// 上传成功 获取上传文件信息

            $msg[] =XUANIP.'/'.'/Public/'.$info['savepath'].$info['savename'];
            $arr  = array(
                'errno'=>0,
                'data'=>$msg
            );

            $this->ajaxReturn($arr,"JSON");
        }


    }

}