<?php
namespace Home\Controller;
use Think\Controller;
use think\helper\hash\Md5;

class AlizzController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $this->redirect('/home/home/index');
        }
    }
    public function index(){


        $ali_get = M('ali_get');
        $user = M('user');
        $ali = M('ali');
        $is_get = I('get.is_get');
        if($is_get){
            $where['is_get'] =  $is_get-1;
            $search_list['is_get'] = $is_get;
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
        $count = $ali_get->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条记录</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $ali_get->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $user_info  = $user->where(array('id'=>$item['user_id']))->field('id,name,head,ali_user_id,ali_number,ali_name')->find();
            $list[$key]['ali_user_id'] = $user_info['ali_user_id'];
            $list[$key]['ali_number'] = $user_info['ali_number'];
            $list[$key]['ali_name'] = $user_info['ali_name'];
            $list[$key]['name'] = $user_info['name'];
            $list[$key]['head'] = $user_info['head'];
            if($user_info['ali_user_id']){
                $ali_info  = $ali->where(array('ali_user_id'=>$user_info['ali_user_id']))->find();
                if ($ali_info['head']){
                    $list[$key]['head'] = $ali_info['head'];
                }
                if ($ali_info['name']){
                    $list[$key]['ali_name'] = $ali_info['name'];
                }
            }
            if(!$user_info['ali_name']){
                $list[$key]['ali_name'] = '未设置昵称';
            }
        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'search_list' =>$search_list,
            'at' =>'alizz',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $ali_get = M('ali_get');

        if($is_edit){
            $list = $ali_get->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'alizz',
            'title'=>'支付宝转账',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    function checkuser($user_id){
        $user = M('user');
        $ali = M('ali');
        if(!$user_id){$arr = array('code'=> 0, 'res'=>'请输入用户ID');$this->ajaxReturn($arr,"JSON");}
        $user_info  =$user->where(array('id'=>$user_id))->field('id,name,head,ali_number,ali_name,ali_user_id')->find();
        if(!$user_info){$arr = array('code'=> 0, 'res'=>'用户不存在');$this->ajaxReturn($arr,"JSON");}

        $user_info['is_bind'] = 0;
        if($user_info['ali_user_id']){
            $user_info['is_bind'] = 1;
            $ali_info  = $ali->where(array('ali_user_id'=>$user_info['ali_user_id']))->find();
            if ($ali_info['head']){
                $user_info['head'] = $ali_info['head'];
            }
            if ($ali_info['name']){
                $user_info['ali_name'] = $ali_info['name'];
            }
        }else{
            if($user_info['ali_number']){
                $user_info['is_bind'] = 1;
            }
        }
        if(!$user_info['ali_name']){
            $user_info['ali_name'] = '未设置昵称';
        }
        $res = '查询';

        $arr = array(
            'code'=> 1,
            'res'=> $res.'成功',
            'list'=>$user_info
        );
        $this->ajaxReturn($arr,"JSON");

    }

    public function add($money,$remark,$user_id,$pay_pass){

        $ali_get = M('ali_get');
        $user = M('user');
        if(!$pay_pass){$arr = array('code'=> 0, 'res'=>'请输入支付密码');$this->ajaxReturn($arr,"JSON");}

        $set_info = M('set')->where(array('set_type'=>'pay_pass'))->find();
        if(md5(md5($pay_pass))!=$set_info['set_value']){$arr = array('code'=> 0, 'res'=>'支付密码错误');$this->ajaxReturn($arr,"JSON");}

        if(!$money){$arr = array('code'=> 0, 'res'=>'请输入转账金额');$this->ajaxReturn($arr,"JSON");}
        if($money<0.1){$arr = array('code'=> 0, 'res'=>'转账金额最低0.1元');$this->ajaxReturn($arr,"JSON");}

        if(!$user_id){$arr = array('code'=> 0, 'res'=>'用户未绑定支付宝，无法转账');$this->ajaxReturn($arr,"JSON");}
        $user_info  =$user->where(array('id'=>$user_id))->field('id,name,head,ali_number,ali_name,ali_user_id')->find();
        if(!$user_info){$arr = array('code'=> 0, 'res'=>'用户未绑定支付宝，无法转账');$this->ajaxReturn($arr,"JSON");}

        $user_info['is_bind'] = 0;
        if($user_info['ali_user_id']){
            $user_info['is_bind'] = 1;
        }else{
            if($user_info['ali_number']){
                $user_info['is_bind'] = 1;
            }
        }
        if($user_info['is_bind']!=1){$arr = array('code'=> 0, 'res'=>'用户未绑定支付宝，无法转账');$this->ajaxReturn($arr,"JSON");}


        $add['user_id'] = $user_id;
        $add['remark'] = $remark;
        $add['money'] = $money;
        $add['create_time'] = time();
        $query = $ali_get->add($add);
        $res = '提交';

        if($query){
            $aliget = A('aliget');
            $data['money'] = $money;
            $data['parent'] = $user_info['id'];
            $data['type'] = 10;
            $data['order_number'] = '';
            $data['type_id'] = $query;
            $data['remark'] = $remark;
            $is_ok = $aliget->index($data);

            if($is_ok){
                $arr = array(
                    'code'=> 1,
                    'res'=> $res.'成功'
                );
            }else{
                $arr = array(
                    'code'=> 0,
                    'res'=> $res.'失败'
                );
            }


        }else{
            $arr = array(
                'code'=> 0,
                'res'=> $res.'失败'
            );
        }
        $this->ajaxReturn($arr,"JSON");
    }


}












