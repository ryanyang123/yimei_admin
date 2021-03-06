<?php
namespace Home\Controller;
use Think\Controller;
class HoschouController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'hoschou'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $user = M('user');
        $team_money = M('team_money');
        $hospital_order = M('hospital_order');
        $hospital = M('hospital');


        $page_type = I('get.page_type');
        $page_type = $page_type?$page_type:1;
        $search_list['page_type'] = $page_type;
        if($page_type==1){
            $where['is_da'] =  1;
            $where['is_get'] =  0;
        }

        $user_id = I('get.user_id');
        if($user_id){
            $where['user_id'] =  $user_id;
            $search_list['user_id'] = $user_id;
        }

        $buy_user_id = I('get.buy_user_id');
        if($buy_user_id){
            $where['buy_user_id'] =  $buy_user_id;
            $search_list['buy_user_id'] = $buy_user_id;
        }
        $team_id = I('get.team_id');
        if($team_id){
            $team_id1 = $team_id-1;
            if ($team_id1==0){
                $where['team_id'] =  $team_id1;
            }else{
                $where['team_id'] =  array('NEQ',0);
            }
            $search_list['team_id'] = $team_id;
        }

        $type = I('get.type');
        if($type){
            $where['type'] =  $type;
            $search_list['type'] = $type;
        }


        $is_get = I('get.is_get');
        if($is_get){
            $where['is_get'] =  $is_get-1;
            $search_list['is_get'] = $is_get;
        }

        $is_loans = I('get.is_loans');
        if($is_loans){
            $where['is_loans'] =  $is_loans-1;
            $search_list['is_loans'] = $is_loans;
        }

        $is_shou = I('get.is_shou');
        if($is_shou){
            $where['is_shou'] =  $is_shou-1;
            $search_list['is_shou'] = $is_shou;
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

        //$where['status'] = 4;
        $where['type'] = 1;

        $where_1['status'] = 3;
        $where_1['refund_status'] = array('IN','4,5');
        $where_2['status'] = 4;
        $where['_complex'] = array(
            $where_1,
            $where_2,
            '_logic' => 'or'
        );


        $count = $hospital_order->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条数据</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $hospital_order->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $a = $hospital_order->getLastSql();

        foreach($list as $key=>$item){

            $shop_info  = M('shop')->where(array('id'=>$item['shop_id']))->find();

            $shop_user = $user->where(array('id'=>$shop_info['user_id']))->field('ali_number,ali_name')->find();
            $list[$key]['ali_number'] = $shop_user['ali_number'];
            $list[$key]['ali_name'] = $shop_user['ali_name'];


            $user_name = $user->where(array('id'=>$item['user_id']))->field('name')->find();
            $list[$key]['user_name'] = $user_name['name'];
            if($item['status']=='3'){
                $list[$key]['get_money'] = $item['money'] - $item['refund_money'] - $item['chou'];
            }else{
                $list[$key]['get_money'] = $item['total_money'] - $item['loans'] - $item['chou'];
            }
        }

        unset($where['is_get']);
        $total['total_money'] = 0;
        $total['total_money'] += $hospital_order->where($where)->sum('chou');


        $total['total_loans'] = 0;
        $total['total_loans'] += $hospital_order->where($where)->sum('loans');

        $where['is_shou'] = 1;
        $total['total_loans_yes'] = 0;
        $total['total_loans_yes'] += $hospital_order->where($where)->sum('loans');

        $where['is_shou'] = 0;
        $total['total_loans_no'] = 0;
        $total['total_loans_no'] += $hospital_order->where($where)->sum('loans');

        unset($where['is_shou']);
        $where['is_get'] = 1;
        $total['total_yes'] = 0;
        $total['total_yes'] += $hospital_order->where($where)->sum('money');
        $total['total_yes'] -= $hospital_order->where($where)->sum('chou');

        $where['is_get'] = 0;
        $total['total_no'] = 0;
        $total['total_no'] += $hospital_order->where($where)->sum('money');
        $total['total_no'] -= $hospital_order->where($where)->sum('chou');

        $data = array(
            'list' =>$list,
            'total' =>$total,
            'search_list' =>$search_list,
            'page' =>$show,
            'at' =>'hoschou',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $diary_classify = M('diary_classify');

        if($is_edit){
            $list = $diary_classify->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'diaryclassify',
            'title'=>'日志分类',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($name,$sort,$is_edit){
        $diary_classify = M('diary_classify');

        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入群分类名称');$this->ajaxReturn($arr,"JSON");}

        if ($is_edit){
            $where_name['id'] = array('NEQ',$is_edit);
        }
        $where_name['name'] = $name;
        $is_name = $diary_classify->where($where_name)->find();
        if ($is_name){
            $arr = array('code'=> 0, 'res'=>'分类名称已存在');$this->ajaxReturn($arr,"JSON");
        }
        if($is_edit){
            $save['name'] = $name;
            $save['sort'] = $sort;
            $save['update_time'] = time();
            $query = $diary_classify->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['name'] = $name;
            $add['sort'] = $sort;
            $add['create_time'] = time();
            $query = $diary_classify->add($add);
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












