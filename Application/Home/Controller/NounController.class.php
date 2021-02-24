<?php
namespace Home\Controller;
use Think\Controller;
class NounController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'noun'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $noun = M('noun');


        $search = I('get.search');
        if($search){
            $where['name|info'] =  array("like","%".$search."%");
            $search_list['search'] = $search;
        }
        $is_show = I('get.is_show');
        if($is_show){
            $where['is_show'] =  $is_show-1;
            $search_list['is_show'] = $is_show;
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
        $count = $noun->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个保险</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $noun->where($where)->order('sort,create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){

        }


        $data = array(
            'list' =>$list,
            'search_list' =>$search_list,
            'page' =>$show,
            'is_show' =>$is_show,
            'status' =>$status,
            'at' =>'noun',
            'count' =>count($list),

        );
        $this->assign($data);
        $this->display();
    }

    function SearchGoods($search_val)
    {
        $shop_goods = M('shop_goods');
        if (!$search_val) {
            $arr = array('code' => 0, 'res' => '请输入商品名称');
            $this->ajaxReturn($arr, "JSON");
        }
        $where['lyx_shop_goods.name'] = array("like", "%" . $search_val . "%");

        $list = $shop_goods->where($where)->join('lyx_shop ON lyx_shop_goods.shop_id = lyx_shop.id')
            ->field('lyx_shop_goods.id,lyx_shop_goods.name,lyx_shop.name as shop_name')
            ->select();
        $res = '查询';
        if ($list) {
            $arr = array(
                'code' => 1,
                'res' => $res . '成功',
                'list' => $list
            );
            $this->ajaxReturn($arr, "JSON");
        } else {
            $arr = array(
                'code' => 0,
                'res' => $res . '失败'
            );
            $this->ajaxReturn($arr, "JSON");
        }
    }



    function plus(){
        $is_edit  =  I('get.edit');

        $noun = M('noun');



        if($is_edit){
            $list = $noun->where(array('id'=>$is_edit))->find();
            if ($list){

            }
        }else{
            $list = array();
        }



        $data = array(
            'at'=>'noun',
            'title'=>'平安保险',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($img,$name,$title,$key,$info,$url,$money,$type,$sort,$is_edit){
        $noun = M('noun');
        if(!$img && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传图片');$this->ajaxReturn($arr,"JSON");}
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入保险名称');$this->ajaxReturn($arr,"JSON");}
        if(!$info){$arr = array('code'=> 0, 'res'=>'请输入保险副标题');$this->ajaxReturn($arr,"JSON");}
        if(!$url){$arr = array('code'=> 0, 'res'=>'请输入跳转地址');$this->ajaxReturn($arr,"JSON");}
        if(!$key){$arr = array('code'=> 0, 'res'=>'请输入key');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            $where_name['id'] = array('NEQ',$is_edit);
            $where_url['id'] = array('NEQ',$is_edit);
            $where_key['id'] = array('NEQ',$is_edit);
        }
        $where_name['name'] = $name;
        $is_name = $noun->where($where_name)->field('id')->find();
        if($is_name){$arr = array('code'=> 0, 'res'=>'保险名称已存在');$this->ajaxReturn($arr,"JSON");}


        $where_url['url'] = $name;
        $is_url = $noun->where($where_url)->field('id')->find();
        if($is_url){$arr = array('code'=> 0, 'res'=>'跳转地址已存在');$this->ajaxReturn($arr,"JSON");}

        $where_key['noun_key'] = $key;
        $is_key = $noun->where($where_key)->field('id')->find();
        if($is_key){$arr = array('code'=> 0, 'res'=>'key已存在');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            if($img){
                $save['cover'] = $img;
            }
            $save['name'] = $name;
            $save['info'] = $info;
            $save['url'] = $url;
            $save['title'] = $title;
            $save['money'] = $money;
            $save['sort'] = $sort;
            $save['type'] = $type;
            $save['noun_key'] = $key;
            $save['update_time'] = time();
            $query = $noun->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['cover'] = $img;
            $add['sort'] = $sort;
            $add['name'] = $name;
            $add['info'] = $info;
            $add['title'] = $title;
            $add['noun_key'] = $key;
            $add['url'] = $url;
            $add['money'] = $money;
            $add['type'] = $type;
            $add['create_time'] = time();
            $query = $noun->add($add);
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












