<?php
namespace Home\Controller;
use Think\Controller;
class GameController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'2'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $game = M('game');
        $game_banner = M('game_banner');


        $search = I('get.search');
        if($search){
            $where['name|info|classify'] =  array("like","%".$search."%");;
            $search_list['search'] = $search;
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
        $count = $game->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个游戏</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $game->where($where)->order('sort,create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $list[$key]['banner_list'] = $game_banner->where(array('parent'=>$item['id']))->select();
        }

        $data = array(
            'list' =>$list,
            'search_list' =>$search_list,
            'page' =>$show,
            'is_show' =>$is_show,
            'status' =>$status,
            'at' =>'game',
            'count' =>count($list),

        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $game = M('game');
        $game_banner = M('game_banner');


        if($is_edit){
            $list = $game->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $banner_list = $game_banner->where(array('parent'=>$is_edit))->select();

        $data = array(
            'at'=>'game',
            'title'=>'游戏',
            'banner_list'=>$banner_list,
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($img, $name, $info, $grade, $classify, $size, $beta,
                $sort, $android_versions, $android_bao, $android_url, $ios_url, $is_home, $is_show, $content, $img_arr,$is_edit){
        $game = M('game');
        $game_banner = M('game_banner');

        if(!$img && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传图片');$this->ajaxReturn($arr,"JSON");}
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入游戏名称');$this->ajaxReturn($arr,"JSON");}
        if(!$info){$arr = array('code'=> 0, 'res'=>'请输入游戏信息');$this->ajaxReturn($arr,"JSON");}
        if(!$grade){$arr = array('code'=> 0, 'res'=>'请输入游戏评分');$this->ajaxReturn($arr,"JSON");}
        if(!$size){$arr = array('code'=> 0, 'res'=>'请输入游戏大小');$this->ajaxReturn($arr,"JSON");}
        if(!$classify){$arr = array('code'=> 0, 'res'=>'请输入游戏分类');$this->ajaxReturn($arr,"JSON");}
        if(!$android_versions){$arr = array('code'=> 0, 'res'=>'请输入安卓版本号');$this->ajaxReturn($arr,"JSON");}
        if(!$android_bao){$arr = array('code'=> 0, 'res'=>'请输入安卓版包名');$this->ajaxReturn($arr,"JSON");}
        if(!$android_url){$arr = array('code'=> 0, 'res'=>'请输入安卓版APK地址');$this->ajaxReturn($arr,"JSON");}


        if($is_edit){
            if($img){
                $save['logo'] = $img;
            }
            $save['sort'] = $sort;
            $save['name'] = $name;
            $save['info'] = $info;
            $save['grade'] = $grade;
            $save['classify'] = $classify;
            $save['size'] = $size;
            $save['beta'] = $beta;
            $save['android_versions'] = $android_versions;
            $save['android_bao'] = $android_bao;
            $save['android_url'] = $android_url;
            $save['ios_url'] = $ios_url;
            $save['content'] = $content;
            $save['is_home'] = $is_home;
            $save['is_show'] = $is_show;
            $save['update_time'] = time();
            $query = $game->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['logo'] = $img;
            $add['sort'] = $sort;
            $add['name'] = $name;
            $add['info'] = $info;
            $add['grade'] = $grade;
            $add['classify'] = $classify;
            $add['size'] = $size;
            $add['beta'] = $beta;
            $add['android_versions'] = $android_versions;
            $add['android_bao'] = $android_bao;
            $add['android_url'] = $android_url;
            $add['ios_url'] = $ios_url;
            $add['content'] = $content;
            $add['is_home'] = $is_home;
            $add['is_show'] = $is_show;
            $add['create_time'] = time();
            $query = $game->add($add);
            $res = '新增';
        }

        if($query){
            if ($img_arr){
                if($is_edit){
                    $query = $is_edit;
                }
                //$img_list = explode(',',$img_arr);
                $add_img['parent'] = $query;
                $add_img['create_time'] = time();
                foreach ($img_arr as $item){
                    $add_img['img_url'] = $item;
                    $game_banner->add($add_img);
                }
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












