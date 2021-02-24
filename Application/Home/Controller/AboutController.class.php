<?php
namespace Home\Controller;
use Think\Controller;
class AboutController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'about'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){



        $about = M('about');

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
        $count = $about->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个分类</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $about->where($where)->order('sort,create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

        }

        $data = array(
            'list' =>$list,
            'search_list' =>$search_list,
            'page' =>$show,
            'is_show' =>$is_show,
            'status' =>$status,
            'at' =>'about',
            'count' =>count($list),

        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $about = M('about');


        if($is_edit){
            $list = $about->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'about',
            'title'=>'关于我们',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($img,$title,$sort,$is_edit){
        $about = M('about');

        if(!$img && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传图片');$this->ajaxReturn($arr,"JSON");}
        if(!$title){$arr = array('code'=> 0, 'res'=>'请输入标题');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            if($img){
                $save['img_url'] = $img;
            }
            $save['sort'] = $sort;
            $save['title'] = $title;
            $save['update_time'] = time();
            $query = $about->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['img_url'] = $img;
            $add['sort'] = $sort;
            $add['title'] = $title;
            $add['create_time'] = time();
            $query = $about->add($add);
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












