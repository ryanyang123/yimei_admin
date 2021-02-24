<?php
namespace Home\Controller;
use Think\Controller;
class ShopprizeController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'shopprize'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $shop_prize = M('shop_prize');


        $is_show = I('get.is_show');
        if($is_show){
            $where['is_show'] =  $is_show-1;
        }

        $is_home = I('get.is_home');
        if($is_home){
            $where['is_home'] =  $is_home-1;
        }
        $shop_id = I('get.shop_id');
        if($shop_id){
            $where['id'] =  $shop_id;
        }


        $search = I('get.search');
        if($search){
            $where['name'] =  array("like","%".$search."%");
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
        //时间搜索

        //$where['is_show'] = 1;
        $count = $shop_prize->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $module_name = '/' . MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;//获取路径
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% <input  class="tz_input" type="text" id="tz_input"/> <a class="tz_a_all" id="tz_a_num" href="' . $module_name . '/p/">跳转</a>%HEADER%');

        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个商品</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $shop_prize->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();



        $data = array(
            'list' =>$list,
            'page' =>$show,
            'is_show' =>$is_show,
            'is_home' =>$is_home,
            'at' =>'shopprize',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }

    function plus(){
        $is_edit  =  I('get.edit');

        $shop_prize = M('shop_prize');
        $shop_prize_photo = M('shop_prize_photo');
        $shop_prize_select = M('shop_prize_select');
        $shop_prize_banner = M('shop_prize_banner');
        $select_num = 1;
        if($is_edit){
            $list = $shop_prize->where(array('id'=>$is_edit))->find();
            $content = $shop_prize_photo->where(array('prize_id'=>$is_edit))->field('content')->find();
            $list['content'] = $content['content'];
            $select_list = $shop_prize_select->where(array('prize_id'=>$is_edit))->order('id')->field('id,select_name,value,add_price')->select();

            $banner_list = $shop_prize_banner->where(array('prize_id'=>$is_edit))->field('id,img_url')->select();

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


        $data = array(
            'at'=>'shopprize',
            'title'=>'商品',
            'list'=>$list,
            'select_num'=>$select_num,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }


    function add( $name, $score, $price, $sort, $img_url, $content,$arr_select,$img_arr,$is_edit){

        $shop_prize = M('shop_prize');
        $shop_prize_banner = M('shop_prize_banner');
        $shop_prize_photo = M('shop_prize_photo');
        $shop_prize_select = M('shop_prize_select');
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入商品名称');$this->ajaxReturn($arr,"JSON");}
        if(!$score || $score<0){$arr = array('code'=> 0, 'res'=>'购买积分不能小于0');$this->ajaxReturn($arr,"JSON");}
        if(!$price || $price<0){$arr = array('code'=> 0, 'res'=>'参考市场价格不能小于0');$this->ajaxReturn($arr,"JSON");}

        if(!$img_url && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传商品图片');$this->ajaxReturn($arr,"JSON");}

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
                        if(!$val[0]){$arr = array('code'=> 0, 'res'=>'请输入规格值');$this->ajaxReturn($arr,"JSON");}
                        if($val[1]==''){$arr = array('code'=> 0, 'res'=>'请输入增加金额');$this->ajaxReturn($arr,"JSON");}
                    }

                }
            }
        }


        if($is_edit){
            $save['name'] = $name;
            $save['score'] = $score;
            $save['price'] = $price;

            if($img_url){
                $save['cover'] = $img_url;
            }

            $save['sort'] = $sort;
            $save['update_time'] = time();
            $query = $shop_prize->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
            if($query){
                $query  = $is_edit;
            }
        }else{
            $add['name'] = $name;
            $add['score'] = $score;
            $add['price'] = $price;

            $add['cover'] = $img_url;
            $add['sort'] = $sort;
            $add['create_time'] = time();
            $query = $shop_prize->add($add);
            $res = '新增';
        }



        if($query){
            //商品规格
            $shop_prize_select->where(array('prize_id'=>$query))->delete();

            $add_select['create_time'] = time();
            $add_select['prize_id'] = $query;
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
                            if($val[2]){
                                $add_select['id'] = $val[2];
                            }else{
                                unset($add_select['id']);
                            }
                            $add_select['value'] = $val[0];
                            $add_select['add_price'] = $val[1];
                            $shop_prize_select->add($add_select);
                        }

                    }
                }
            }



            //商品描述
            $is_content = $shop_prize_photo->where(array('prize_id'=>$query))->field('id')->find();
            if($is_content){
                $save_photo['content'] = $content;
                $save_photo['create_time'] = time();
                $shop_prize_photo->where(array('id'=>$is_content['id']))->save($save_photo);
            }else{
                $add_photo['content'] = $content;
                $add_photo['create_time'] = time();
                $add_photo['prize_id'] = $query;
                $shop_prize_photo->add($add_photo);
            }


            if($img_arr){
                foreach($img_arr as $item){
                    $add_banner['prize_id'] = $query;
                    $add_banner['img_url'] = $item;
                    $add_banner['create_time'] = time();
                    $shop_prize_banner->add($add_banner);
                }

            }

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



    /*上传图片*/
    public function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize  = 3145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->saveName = date('YmdHis').rand(10000,99999);
        $upload->savePath =  'Uploads/prize/';// 设置附件上传目录
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

            $msg[] =XUANIP.'/Public/'.$info['savepath'].$info['savename'];
            $arr  = array(
                'errno'=>0,
                'data'=>$msg
            );

            $this->ajaxReturn($arr,"JSON");
        }


    }

}