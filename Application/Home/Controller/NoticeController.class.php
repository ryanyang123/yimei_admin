<?php
namespace Home\Controller;
use Think\Controller;
class NoticeController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'notice'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){


        $notice = M('notice');
        $user = M('user');


        //$where['is_show'] = 1;
        $count = $notice->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条公告</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $notice->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            if ($item['user_id']=='0'){
                $list[$key]['user_name'] = '全部用户';
            }else{
                $user_name = $user->where(array('id'=>$item['user_id']))->field('name')->find();
                $list[$key]['user_name'] = $user_name['name'];
            }

        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'notice',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $notice = M('notice');

        if($is_edit){
            $list = $notice->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'notice',
            'title'=>'系统公告',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($title,$content,$type,$user_id,$is_edit){
        $notice = M('notice');
        $user = M('user');

        if(!$title){$arr = array('code'=> 0, 'res'=>'请输入公告标题');$this->ajaxReturn($arr,"JSON");}
        if(!$content){$arr = array('code'=> 0, 'res'=>'请输入公告内容');$this->ajaxReturn($arr,"JSON");}
        if($type=='1'){
            if (!$user_id){
                $arr = array('code'=> 0, 'res'=>'请输入用户ID');$this->ajaxReturn($arr,"JSON");
            }else{
                $user_info = $user->where(array('id'=>$user_id))->field('id')->find();
                if (!$user_info){
                    $arr = array('code'=> 0, 'res'=>'用户ID错误');$this->ajaxReturn($arr,"JSON");
                }
            }
        }



        if (!$user_id){
            $user_id = 0;
        }

        if($is_edit){
            $save['type'] = $type;
            $save['user_id'] = $user_id;
            $save['title'] = $title;
            $save['content'] = $content;
            $save['update_time'] = time();
            $query = $notice->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{


            $add['type'] = $type;
            $add['user_id'] = $user_id;
            $add['title'] = $title;
            $add['content'] = $content;
            $add['create_time'] = time();
            $query = $notice->add($add);


            $res = '发送';
        }

        if($query){
            $tis = '《'.$title.'》';
            if ($user_id==0){
                if ($type=='2'){
                    $where_send['is_agent'] = 1;
                }
                $where_send['is_robot'] = 0;
                $where_send['is_service'] = 0;
                $user_num = $user->where($where_send)->count();

                $num = 990;
                $page =  $user_num/$num;
                $zong = ceil($page);
                for ($i=1;$i<=$zong;$i++){
                    $start = ($i-1)*$num;
                    $rong_user = array();
                    $user_list = $user->where($where_send)->order('create_time desc')->limit($start,$num)->field('id')->select();
                    foreach ($user_list as $item){
                        $rong_user[] = $item['id'];
                    }

                    SendRongSystem($rong_user,$tis);
                    sleep(2);
                }


            }else{
                $rong_user[] = $user_id;
                SendRongSystem($rong_user,$tis);
            }



            $add_system['user_id'] = $user_id;
            $add_system['status'] = 5;
            $add_system['type'] = $type;

            $add_system['type_id'] = $query;
            $add_system['content'] = $title;
            $add_system['create_time'] = time();
            M('system')->add($add_system);


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












