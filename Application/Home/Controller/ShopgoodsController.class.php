<?php
namespace Home\Controller;
use Think\Controller;
class ShopgoodsController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'shopgoods'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $shop_goods = M('shop_goods');
        $shop_classify = M('shop_classify');
        $shop = M('shop');
        $set = M('set');
        $postage = M('postage');
        $odds_type = M('odds_type');

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

        $is_home = I('get.is_home');
        if($is_home){
            $where['is_home'] =  $is_home-1;
            $search_list['is_home'] = $is_home;
        }


        $is_top = I('get.is_top');
        if($is_top){
            $where['is_top'] =  $is_top-1;
            $search_list['is_top'] = $is_top;
        }

        $shop_id = I('get.shop_id');
        if($shop_id){
            $where['id'] =  $shop_id;
        }

        $classify_id = I('get.classify_id');
        if($classify_id){
            $where['classify'] =  $classify_id;
            $search_list['classify_id'] = $classify_id;
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


        $where['is_del'] = 0;
        $count = $shop_goods->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $module_name = '/' . MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;//获取路径
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% <input  class="tz_input" type="text" id="tz_input"/> <a class="tz_a_all" id="tz_a_num" href="' . $module_name . '/p/">跳转</a>%HEADER%');

        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个商品</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $shop_goods->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $classify_list = $shop_classify->field('id,name,type,parent')->select();

        $new_classify = array();
        foreach($classify_list as $item){
            $new_classify[$item['id']] = $item['name'];
        }
        foreach($classify_list as $key=>$item){
           if ($item['type']=='2'){
               $classify_list[$key]['name'] = $new_classify[$item['parent']].'-'.$item['name'];
               $new_classify[$item['id']] = $new_classify[$item['parent']].'-'.$item['name'];
           }
        }

        foreach($list as $key=>$item){
            $list[$key]['classify_name'] =  $new_classify[$item['classify']];
            $shop_name = $shop->where(array('id'=>$item['shop_id']))->field('name,classify,odds,odds_type')->find();
            $list[$key]['shop_name'] = $shop_name['name'];

           /* $chou_info = $set->where(array('set_type'=>$shop_name['classify'].'_order_chou'))->find();
            $list[$key]['odds_set'] = $chou_info['set_value'];*/


            if($shop_name['odds']){
                $list[$key]['odds_set'] = $shop_name['odds'];
            }else{
                //$chou_info = $set->where(array('set_type'=>$shop_name['classify'].'_order_chou'))->find();
                $system_odds = $odds_type->where(array('id'=>$shop_name['odds_type']))->field('name,odds')->find();
                $list[$key]['odds_set'] = $system_odds['odds'];
            }


            $post_info = $postage->where(array('id'=>$item['postage_id']))->find();
            $list[$key]['postage_name'] = $post_info['name'];
        }

        $data = array(
            'list' =>$list,
            'page' =>$show,
            'search_list' =>$search_list,
            'classify_list' =>$classify_list,
            'is_show' =>$is_show,
            'at' =>'shopgoods',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }



    function check(){
        $is_edit  =  I('get.edit');
        $shop_classify = M('shop_classify');
        $shop = M('shop');
        $user = M('user');
        $shop_goods = M('shop_goods');
        $shop_goods_banner = M('shop_goods_banner');
        $hospital = M('hospital');
        $cache = M('cache');
        $store_classify = M('store_classify');
        $set = M('set');
        $shop_odds = M('shop_odds');
        $odds = M('odds');
        $odds_type = M('odds_type');
        $user_odds_new = M('user_odds_new');
        $postage = M('postage');



        if($is_edit){
            $list = $shop_goods->where(array('id'=>$is_edit))->find();
            $post_info = $postage->where(array('id'=>$list['postage_id']))->find();
            $list['postage_name'] = $post_info['name'];
            $list['sms_url'] = 'http://sms.ymxte.com/'.base64_encode($list['id']);
            $img_list = array();
            $img_arr = $shop_goods_banner->where(array('goods_id'=>$list['id']))->select();

            foreach ($img_arr as $item){
                $img_list[] = $item['img_url'];
            }
            $list['img_list'] = $img_list;
            $classify_list = $shop_classify->field('id,name,type,parent')->select();

            $new_classify = array();
            foreach($classify_list as $item){
                $new_classify[$item['id']] = $item['name'];
            }
            foreach($classify_list as $key=>$item){
                if ($item['type']=='2'){
                    $classify_list[$key]['name'] = $new_classify[$item['parent']].'-'.$item['name'];
                    $new_classify[$item['id']] = $new_classify[$item['parent']].'-'.$item['name'];
                }
            }

            $list['classify_name'] =  $new_classify[$list['classify']];
            $shop_name = $shop->where(array('id'=>$list['shop_id']))->field('name,classify,odds,odds_type')->find();
            $list['shop_name'] = $shop_name['name'];

            if($shop_name['odds']){
                $list['odds_set'] = $shop_name['odds'];
            }else{
                //$chou_info = $set->where(array('set_type'=>$shop_name['classify'].'_order_chou'))->find();
                $system_odds = $odds_type->where(array('id'=>$shop_name['odds_type']))->field('name,odds')->find();
                $list['odds_set'] = $system_odds['odds'];
            }

            $odds_myself = $user_odds_new->where(array('type'=>'3','user_id'=>$is_edit))->select();
            if($odds_myself){
                foreach ($odds_myself as $item){
                    $new_self[$item['name']]=$item['odds'];
                }
            }

            $shop_myself = $user_odds_new->where(array('type'=>'4','user_id'=>$list['shop_id']))->select();
            if($shop_myself){
                foreach ($shop_myself as $item){
                    $new_shop_self[$item['name']]=$item['odds'];
                }
            }


            $odds_value = $odds->where(array('id'=>array('IN','1,2,3,4')))->select();


            foreach ($odds_value as $key=>$item){
                $odds_value[$key]['is_system'] = 0;
                if(array_key_exists($item['name'],$new_self)){
                    $odds_value[$key]['odds'] = $new_self[$item['name']];
                }else{
                    $odds_value[$key]['is_system'] = 1;
                    if(array_key_exists($item['name'],$new_shop_self)){
                        $odds_value[$key]['odds'] = $new_shop_self[$item['name']];
                    }else{

                        $admin_odds = $user_odds_new->where(array('type'=>'2','name'=>$item['name'].'_'.$shop_name['odds_type']))->find();
                        $odds_value[$key]['odds'] = $admin_odds['odds'];
                    }
                }
            }










        }else{
            $list = array();
        }

        $data = array(
            'at'=>'shopgoods',
            'title'=>'商城商品',
            'odds_list'=>$odds_value,
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }





    function plus(){
        $is_edit  =  I('get.edit');

        $shop_goods = M('shop_goods');
        $shop_goods_photo = M('shop_goods_photo');
        $shop_classify = M('shop_classify');
        $shop_select = M('shop_select');
        $shop_goods_banner = M('shop_goods_banner');
        $postage = M('postage');
        $select_num = 1;
        if($is_edit){
            $list = $shop_goods->where(array('id'=>$is_edit))->find();
            $content = $shop_goods_photo->where(array('goods_id'=>$is_edit))->field('content')->find();
            $list['content'] = $content['content'];
            $select_list = $shop_select->where(array('goods_id'=>$is_edit))->order('id')->field('id,select_name,value,add_price,add_cost')->select();


            $banner_list = $shop_goods_banner->where(array('goods_id'=>$is_edit))->field('id,img_url')->select();

            $list['banner_list'] = $banner_list;


            $new_select = array();
            if($select_list){
                foreach($select_list as $item){
                    $new_select[$item['select_name']][] = $item;
                }
                $list['select_list'] = $new_select;
                $select_num = count($new_select);
                //dump($new_select);die;
                $list['is_select'] = '1';
            }else{
                $list['is_select'] = '0';
            }






        }else{
            $list = array();
        }
        $classify_list = $shop_classify->where(['classify'=>$list['class1']])->field('id,name,type,parent')->select();
        foreach($classify_list as $item){
            $new_classify[$item['id']] = $item['name'];
        }
        foreach($classify_list as $key=>$item){
            if ($item['type']=='2'){
                $classify_list[$key]['name'] = $new_classify[$item['parent']].'-'.$item['name'];
            }
        }
        $postage_list = $postage->where(array('shop_id'=>0))->select();
        $data = array(
            'at'=>'shopgoods',
            'title'=>'商品',
            'list'=>$list,
            'select_num'=>$select_num,
            'postage_list'=>$postage_list,
            'classify_list'=>$classify_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }


    function add($max, $name,$postage_id, $is_prepay, $prepay, $stock,$sales,$sort, $classify_id, $img_url, $content,$arr_select,$img_arr,$is_edit){

        $shop_goods = M('shop_goods');
        $shop_goods_photo = M('shop_goods_photo');
        $shop_classify = M('shop_classify');
        $shop_select = M('shop_select');
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入商品名称');$this->ajaxReturn($arr,"JSON");}
        if($is_prepay=='1'){
            if(!$prepay || $prepay<0){$arr = array('code'=> 0, 'res'=>'预约金 不能小于0');$this->ajaxReturn($arr,"JSON");}
        }
        if(!$stock || $stock<0){$arr = array('code'=> 0, 'res'=>'请输入库存');$this->ajaxReturn($arr,"JSON");}
        if(!$img_url && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传商品图片');$this->ajaxReturn($arr,"JSON");}
        if(!$arr_select){$arr = array('code'=> 0, 'res'=>'请输入规格');$this->ajaxReturn($arr,"JSON");}
        $price_list = array();
        if(!$postage_id){$postage_id=0;}

        if (!$max){$max=0;}
        foreach($arr_select as $key=>$item){
            if($key>0){

                foreach($item as $key2=>$val){

                    if($key2==0){
                        if(!$val){
                            $add_select['select_name'] = '颜色/规格分类';
                            //$arr = array('code'=> 0, 'res'=>'请输入规格名称');$this->ajaxReturn($arr,"JSON");
                        }else{
                            $add_select['select_name'] = $val;
                        }
                    }else{
                        if(!$val[0]){$arr = array('code'=> 0, 'res'=>'请输入规格值');$this->ajaxReturn($arr,"JSON");}
                        if($val[1]==''){$arr = array('code'=> 0, 'res'=>'请输入金额');$this->ajaxReturn($arr,"JSON");}
                        $price_list[] = $val[1];

                        if ($is_prepay=='1') {
                            if ($prepay>=$val[1]) {$arr = array('code' => 0, 'res' => '预约金额不能大于商品价格');$this->ajaxReturn($arr,"JSON");}
                        }
                       /* if($val[2]==''){$arr = array('code'=> 0, 'res'=>'请输入增加成本');$this->ajaxReturn($arr,"JSON");}*/
                    }

                }
            }
        }
        $price = min($price_list);
        $class_info  = $shop_classify->where(['id'=>$classify_id])->find();
        if($class_info['type']==1){
            $class2 = $class_info['id'];
        }else{
            $class2 = $class_info['parent'];
        }
        if($is_edit){
            $save['sales'] = $sales;
            $save['sort'] = $sort;
            $save['max'] = $max;
            $save['name'] = $name;
            $save['postage_id'] = $postage_id;
            $save['is_prepay'] = $is_prepay;
            $save['class2'] = $class2;
            $save['prepay'] = $prepay;
            $save['stock'] = $stock;
            if($img_url){
                $save['cover'] = $img_url;
            }

            $save['price'] = $price;
            $save['classify'] = $classify_id;
            $save['update_time'] = time();
            $query = $shop_goods->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
            if($query){
                $query  = $is_edit;
            }
        }else{
            $add['sales'] = $sales;
            $add['sort'] = $sort;
            $add['max'] = $max;
            $add['price'] = $price;
            $add['name'] = $name;
            $save['postage_id'] = $postage_id;
            $save['is_prepay'] = $is_prepay;
            $save['prepay'] = $prepay;
            $add['stock'] = $stock;
            $add['cover'] = $img_url;
            $add['classify'] = $classify_id;
            $add['create_time'] = time();
            $query = $shop_goods->add($add);
            $res = '新增';
        }



        if($query){

            //商品规格
            $shop_select->where(array('goods_id'=>$query))->delete();

            $add_select['create_time'] = time();
            $add_select['goods_id'] = $query;
            foreach($arr_select as $key=>$item){
                if($key>0){
                    foreach($item as $key2=>$val){
                        if($key2==0){
                            if(!$val){
                                $arr = array('code'=> 0, 'res'=>'请输入规格名称');$this->ajaxReturn($arr,"JSON");
                            }else{
                                $add_select['select_name'] = $val;
                            }
                        }else{
                            if($val[3]){
                                $add_select['id'] = $val[3];
                            }else{
                                unset($add_select['id']);
                            }
                            $add_select['value'] = $val[0];
                            $add_select['add_price'] = $val[1];
                            $add_select['add_cost'] = $val[2];
                            $shop_select->add($add_select);
                        }

                    }
                }
            }



            //商品描述
            $is_content = $shop_goods_photo->where(array('goods_id'=>$query))->field('id')->find();
            if($is_content){
                $save_photo['content'] = $content;
                $save_photo['create_time'] = time();
                $shop_goods_photo->where(array('id'=>$is_content['id']))->save($save_photo);
            }else{
                $add_photo['content'] = $content;
                $add_photo['create_time'] = time();
                $add_photo['goods_id'] = $query;
                $shop_goods_photo->add($add_photo);
            }

            if($img_arr){
                foreach($img_arr as $item){
                    $add_banner['goods_id'] = $query;
                    $add_banner['img_url'] = $item;
                    $add_banner['create_time'] = time();
                    M('shop_goods_banner')->add($add_banner);
                }

            }

        }

        if($query){
            $img_status = ImgGetThumb($img_url,6);
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



    function checkbanner($id){
        $shop_goods_banner = M('shop_goods_banner');
        $list = $shop_goods_banner->where(array('goods_id'=>$id))->order('sort')->select();
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
                'res'=> '此商品没有banner图'
            );
            $this->ajaxReturn($arr,"JSON");
        }
    }



    public function editodds($id,$type,$odds,$is_edit){
        $shop = M('shop');
        $shop_goods = M('shop_goods');
        $user_odds_new = M('user_odds_new');

        if(!$odds){$arr = array('code'=> 0, 'res'=>'请输入要修改的抽水比例');$this->ajaxReturn($arr,"JSON");}

        if($type=='1'){
            $query = $shop->where(array('id'=>$id))->save(array('odds'=>$odds,'update_time'=>time()));
        }

        if($type=='2'){
            $query = $shop_goods->where(array('id'=>$id))->save(array('odds'=>$odds,'update_time'=>time()));
        }
        if($type=='3'){
            $is_add = $user_odds_new->where(array('user_id'=>$is_edit,'type'=>'3','name'=>$id))->find();

            $add['name'] = $id;
            $add['odds'] = $odds;

            if ($is_add){
                $add['update_time'] = time();
                $query = $user_odds_new->where(array('id'=>$is_add['id']))->save($add);
            }else{
                $add['user_id'] = $is_edit;
                $add['type'] = 3;
                $add['update_time'] = time();
                $add['create_time'] = time();
                $query = $user_odds_new->add($add);
            }
        }

        $res = '修改';

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


    public function editmo($id,$type,$is_edit){
        $shop = M('shop');
        $shop_goods = M('shop_goods');
        $user_odds_new = M('user_odds_new');
        if($type=='1'){
            $query = $shop->where(array('id'=>$id))->save(array('odds'=>NULL,'update_time'=>time()));
        }
        if($type=='2'){
            $query = $shop_goods->where(array('id'=>$id))->save(array('odds'=>NULL,'update_time'=>time()));
        }
        if($type=='3'){
           $query = $user_odds_new->where(array('type'=>'3','name'=>$id,'user_id'=>$is_edit))->delete();
        }

        $res = '修改';

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

    function baocbanner($id,$sort){
        $shop_goods_banner = M('shop_goods_banner');
        $save['sort'] = $sort;
        $save['update_time'] = time();
        $query = $shop_goods_banner->where(array('id'=>$id))->save($save);
        $res = '修改';
        if($query){
            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功',
            );
            $this->ajaxReturn($arr,"JSON");
        }else{
            $arr = array(
                'code'=> 0,
                'res'=> $res.'失败',
            );
            $this->ajaxReturn($arr,"JSON");
        }
    }

    function delete($id){
        $shop_goods = M('shop_goods');
        $shop_goods_photo = M('shop_goods_photo');
        $shop_select = M('shop_select');

        $query = $shop_goods->where(array('id'=>$id))->delete();
        $res = '删除';
        if($query){
            $shop_goods_photo->where(array('goods_id'=>$id))->delete();
            $shop_select->where(array('goods_id'=>$id))->delete();
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