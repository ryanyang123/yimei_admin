<?php
namespace Home\Controller;
use Think\Controller;
class PostageController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'postage'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){



        $postage = M('postage');


        //$where['is_show'] = 1;
        $count = $postage->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个模板</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $postage->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            //$list[$key]['total'] = $comment_admin->where(array('classify'=>$item['id']))->count();
            $list[$key]['data'] = json_decode($item['data'],true);

            foreach ($list[$key]['data'] as $key2=>$item2){
                $loca = array();
                $loca = explode('+',$item2['location']);
                if(count($loca)>3){
                    $list[$key]['data'][$key2]['loca'] = implode(',',array_slice($loca,0,3)).'.....';
                }else{
                    $list[$key]['data'][$key2]['loca'] = implode(',',$loca);
                }
            }
        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'at' =>'postage',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }

    function check(){
        $is_edit  =  I('get.edit');
        $postage = M('postage');

        if($is_edit){
            $list = $postage->where(array('id'=>$is_edit))->find();
            $list['data'] = json_decode($list['data'],true);
            foreach ($list['data'] as $key2=>$item2){
               $list['data'][$key2]['loca'] = str_replace('+',',',$item2['location']);
            }
        }else{
            $list = array();
        }

        $data = array(
            'at'=>'postage',
            'title'=>'运费模板',
            'list'=>$list,
            'is_edit'=>$is_edit
        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');

        $postage_area = M('postage_area');
        $postage = M('postage');

        $area_list2 = array();
        $area_list = $postage_area->select();
        foreach ($area_list as $key=>$item){
            if($item['level']==1){
                $area_list2[$item['id']]['name'] = $item['name'];
            }else{
                $area_list2[$item['parent']]['list'][] = $item;
            }
        }

        if($is_edit){
            $list = $postage->where(array('id'=>$is_edit))->find();
            $list['now_num'] = 1;
            $list['data'] = json_decode($list['data'],true);

            if($list['data']){
                $list['now_num'] = count($list['data']);
                foreach ($list['data'] as $key2=>$item2){
                    $data_arr[$key2+1] = explode('+',$item2['location']);

                    $list['data'][$key2]['loca'] = str_replace('+',',',$item2['location']);
                }
            }

        }else{
            $list = array();
            $data_arr = array();
        }

        $data = array(
            'at'=>'postage',
            'title'=>'运费模板',
            'list'=>$list,
            'data_arr'=>json_encode($data_arr),
            'area_list'=>$area_list2,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($name, $status, $is_bao, $bao_type, $bao_num, $one_num, $one_money, $more_num, $more_money, $tmp,$is_edit){
        $postage = M('postage');

        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入模板名称');$this->ajaxReturn($arr,"JSON");}


        if ($status==1){

            if($is_bao==1){
                if(!$bao_num){$arr = array('code'=> 0, 'res'=>'请输入包邮条件');$this->ajaxReturn($arr,"JSON");}
                if($bao_num<0){$arr = array('code'=> 0, 'res'=>'包邮条件不能小于0');$this->ajaxReturn($arr,"JSON");}
                if(!is_numeric($bao_num)){$arr = array('code'=> 0, 'res'=>'包邮条件请输入整数');$this->ajaxReturn($arr,"JSON");}
            }
            if($one_num==null){$arr = array('code'=> 0, 'res'=>'请输入默认首件数');$this->ajaxReturn($arr,"JSON");}
            if($one_num<0){$arr = array('code'=> 0, 'res'=>'默认首件数不能小于0');$this->ajaxReturn($arr,"JSON");}
            if(!is_numeric($one_num)){$arr = array('code'=> 0, 'res'=>'默认首件数请输入整数');$this->ajaxReturn($arr,"JSON");}


            if($one_money==null){$arr = array('code'=> 0, 'res'=>'请输入默认首费');$this->ajaxReturn($arr,"JSON");}
            if($one_money<0){$arr = array('code'=> 0, 'res'=>'默认首费不能小于0');$this->ajaxReturn($arr,"JSON");}


            if($more_num==null){$arr = array('code'=> 0, 'res'=>'请输入默认续件数');$this->ajaxReturn($arr,"JSON");}
            if($more_num<0){$arr = array('code'=> 0, 'res'=>'默认续件数不能小于0');$this->ajaxReturn($arr,"JSON");}
            if(!is_numeric($more_num)){$arr = array('code'=> 0, 'res'=>'默认续件数请输入整数');$this->ajaxReturn($arr,"JSON");}

            if($more_money==null){$arr = array('code'=> 0, 'res'=>'请输入默认续件费');$this->ajaxReturn($arr,"JSON");}
            if($more_money<0){$arr = array('code'=> 0, 'res'=>'默认续件费不能小于0');$this->ajaxReturn($arr,"JSON");}

        }
        $data = array();
        $zdy_arr = json_decode($tmp,true);
        if($zdy_arr){
            $i = 0;

            foreach ($zdy_arr as $item){
                if($item[0]!= '未添加地区' ){
                    if($item[1]==null){$arr = array('code'=> 0, 'res'=>'请输入自定义首件数');$this->ajaxReturn($arr,"JSON");}
                    if($item[1]<0){$arr = array('code'=> 0, 'res'=>'自定义首件数不能小于0');$this->ajaxReturn($arr,"JSON");}
                    if(!is_numeric($item[1])){$arr = array('code'=> 0, 'res'=>'自定义首件数请输入整数');$this->ajaxReturn($arr,"JSON");}

                    if($item[2]==null){$arr = array('code'=> 0, 'res'=>'请输入自定义首费');$this->ajaxReturn($arr,"JSON");}
                    if($item[2]<0){$arr = array('code'=> 0, 'res'=>'自定义首费不能小于0');$this->ajaxReturn($arr,"JSON");}


                    if($item[3]==null){$arr = array('code'=> 0, 'res'=>'请输入自定义续件数');$this->ajaxReturn($arr,"JSON");}
                    if($item[3]<0){$arr = array('code'=> 0, 'res'=>'自定义续件数不能小于0');$this->ajaxReturn($arr,"JSON");}
                    if(!is_numeric($item[3])){$arr = array('code'=> 0, 'res'=>'自定义续件数请输入整数');$this->ajaxReturn($arr,"JSON");}

                    if($item[4]==null){$arr = array('code'=> 0, 'res'=>'请输入自定义续件费');$this->ajaxReturn($arr,"JSON");}
                    if($item[4]<0){$arr = array('code'=> 0, 'res'=>'自定义续件费不能小于0');$this->ajaxReturn($arr,"JSON");}

                    $data[$i]['location'] = $item[0];
                    $data[$i]['one_num'] = $item[1];
                    $data[$i]['one_money'] = $item[2];
                    $data[$i]['more_num'] = $item[3];
                    $data[$i]['more_money'] = $item[4];
                    $i++;
                }
            }
        }



        if ($is_edit){
            $where_name['id'] = array('NEQ',$is_edit);
        }

        $where_name['shop_id'] = 0;
        $where_name['name'] = $name;
        $is_name = $postage->where($where_name)->find();
        if ($is_name){
            $arr = array('code'=> 0, 'res'=>'模板名称已存在');$this->ajaxReturn($arr,"JSON");
        }
        if($is_edit){
            $save['shop_id'] = 0;
            $save['name'] = $name;
            $save['is_bao'] = $is_bao;
            $save['bao_type'] = $bao_type;
            $save['bao_num'] = $bao_num;
            $save['status'] = $status;
            $save['one_num'] = $one_num;
            $save['one_money'] = $one_money;
            $save['more_num'] = $more_num;
            $save['more_money'] = $more_money;
            $save['data'] = json_encode($data);
            $save['update_time'] = time();
            $query = $postage->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['shop_id'] = 0;
            $add['name'] = $name;
            $add['status'] = $status;
            $add['is_bao'] = $is_bao;
            $add['bao_type'] = $bao_type;
            $add['bao_num'] = $bao_num;
            $add['one_num'] = $one_num;
            $add['one_money'] = $one_money;
            $add['more_num'] = $more_num;
            $add['more_money'] = $more_money;
            $add['data'] = json_encode($data);
            $add['create_time'] = time();
            $query = $postage->add($add);
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












