<?php
namespace Home\Controller;
use Think\Controller;
class ShopclassifyController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'shopclassify'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $made_classify = M('shop_classify');
        $store_classify = M('store_classify');
        $class_list = $store_classify->select();
        foreach ($class_list as $item){
            $new_class[$item['id']] = $item['name'];
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


        $where['type'] = 1;
        $count = $made_classify->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个分类</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $made_classify->where($where)->order('sort,create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $list[$key]['class_name'] =  $new_class[$item['classify']]?$new_class[$item['classify']]:'-';
            $list[$key]['xia_list'] = $made_classify->where(array('type'=>'2','parent'=>$item['id']))->select();
        }

        $data = array(
            'list' =>$list,
            'search_list' =>$search_list,
            'page' =>$show,
            'is_show' =>$is_show,
            'status' =>$status,
            'at' =>'shopclassify',
            'count' =>count($list),

        );
        $this->assign($data);
        $this->display();
    }

    function test(){
        $shop = M('shop');
        $made_classify = M('shop_classify');
        $class_list = $made_classify->select();

        foreach ($class_list as $item){
            if($item['class'])
            $new_class[$item['id']] = $item;
        }
        $shop_goods = M('shop_goods');

        $list  = $shop->select();
        foreach ($list as $item){
            if($item['classify']>13){
                $shop->where(['id'=>$item['id']])->save(['classify2'=>28,'classify'=>13]);
            }
        }

//        $list = $shop_goods->select();
//        $i = 0;
//       foreach ($list as $item){
//           $shop_info = $shop->where(['id'=>$item['shop_id']])->find();
//           $shop_goods->where(['id'=>$item['id']])->save(['class1'=>$shop_info['classify']]);
//           $i++;
//       }

    }


    function plus(){
        $is_edit  =  I('get.edit');
        $type  =  I('get.type');
        $parent  =  I('get.parent');
        $made_classify = M('shop_classify');
        $store_classify = M('store_classify');
        if(!$type){
            $type='1';
            $title = "商城分类";
        }else{
            $parent_info = $made_classify->where(array('id'=>$parent))->find();
            $title = '《'.$parent_info['name']."》 - 二级分类";
        }
        if(!$parent){$parent='0';}

        $class2_list = $store_classify->select();

        if($is_edit){
            $list = $made_classify->where(array('id'=>$is_edit))->find();
            $parent_info['id'] = $list['parent'];
        }else{
            $list = array();
        }

        $class_list = $made_classify->where(array('type'=>'1'))->select();

        $data = array(
            'at'=>'shopclassify',
            'title'=>$title,
            'parent_info'=>$parent_info,
            'class_list'=>$class_list,
            'class2_list'=>$class2_list,
            'type'=>$type,
            'parent'=>$parent,
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($img,$img2,$classify,$name,$sort,$type,$parent,$is_edit){
        $made_classify = M('shop_classify');

        if(!$img && !$is_edit ){$arr = array('code'=> 0, 'res'=>'请上传图片');$this->ajaxReturn($arr,"JSON");}
        if($type=='2' && !$parent ){$arr = array('code'=> 0, 'res'=>'请选择上级分类');$this->ajaxReturn($arr,"JSON");}
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入分类名称');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            if($img){
                $save['logo'] = $img;
            }
            if($img2){
                $save['img'] = $img2;
            }
            $save['sort'] = $sort;
            $save['name'] = $name;
            $save['update_time'] = time();
            $save['type'] = $type;
            if($type=='1'){
                $save['classify'] = $classify;
            }
            if($type=='2'){
                $save['parent'] = $parent;
            }
            $query = $made_classify->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            if($type=='1'){
                $add['classify'] = $classify;
            }
            if($type=='2'){
                $add['parent'] = $parent;
            }
            $add['type'] = $type;
            $add['logo'] = $img;
            $add['img'] = $img2;
            $add['sort'] = $sort;
            $add['name'] = $name;
            $add['create_time'] = time();
            $query = $made_classify->add($add);
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












