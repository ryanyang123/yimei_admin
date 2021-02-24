<?php
namespace Home\Controller;
use Think\Controller;
class ApplycompanyController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'10'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }

    public function index(){



        $user = M('user');
        $apply_company = M('apply_company');
        $cache = M('cache');
        $shop = M('shop');

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


        $count = $apply_company->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条申请</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $apply_company->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $user_name = $user->where(array('id'=>$item['user_id']))->field('id,name')->find();

            $list[$key]['user_name'] = $user_name['name'];
            $is_img = $cache->where(array('thumb'=>$item['img_url']))->find();
            if ($is_img['url']){
                $list[$key]['img_url'] = $is_img['url'];
            }
        }


        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'applycompany',
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
            'at'=>'activity',
            'title'=>'活动',
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
        $apply_company = M('apply_company');
        $user = M('user');
        $apply_info = $apply_company->where(array('id'=>$id))->find();
        if($apply_info['status']!='0'){$arr = array('code'=> 0, 'res'=>'该申请已处理');$this->ajaxReturn($arr,"JSON");}

        $save['status'] = 1;
        $save['update_time'] = time();
        $query = $apply_company->where(array('id'=>$id))->save($save);

        $res = '通过';
        if($query){
            $save_user['company_at'] = 1;
            $save_user['update_time'] = time();
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
        $apply_company = M('apply_company');
        $user = M('user');
        $apply_info = $apply_company->where(array('id'=>$id))->find();
        if($apply_info['status']!='0'){$arr = array('code'=> 0, 'res'=>'该申请已处理');$this->ajaxReturn($arr,"JSON");}

        $save['status'] = 2;
        $save['cause'] = $cause;
        $save['update_time'] = time();
        $query = $apply_company->where(array('id'=>$id))->save($save);

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












