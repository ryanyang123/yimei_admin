<?php
namespace Home\Controller;
use Think\Controller;
class ShopController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'shop'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }

    public function index(){

        $user = M('user');
        $shop = M('shop');
        $shop_order = M('shop_order');
        $shop_goods = M('shop_goods');
        $odds_type_db = M('odds_type');
        $hospital = M('hospital');
        $store_classify = M('store_classify');
        $cache = M('cache');

        $class_list = $store_classify->select();
        foreach ($class_list as $item){
            $new_class[$item['id']] = $item['name'];
        }

        $search = I('get.search');
        if($search){
            $where['name'] =  array("like","%".$search."%");;
            $search_list['search'] = $search;
        }


        $user_id = I('get.user_id');
        if($user_id){
            $where['user_id'] =  $user_id;
            $search_list['user_id'] = $user_id;
        }
        $is_open = I('get.is_open');
        if($is_open){
            $where['is_open'] =  $is_open;
            $search_list['is_open'] = $is_open;
        }
        $classify = I('get.classify');
        if($classify){
            $where['classify'] =  $classify;
            $search_list['classify'] = $classify;
        }

        $odds_type = I('get.odds_type');
        if($odds_type){
            $where['odds_type'] =  $odds_type;
            $search_list['odds_type'] = $odds_type;
        }

        $is_freeze = I('get.is_freeze');
        if($is_freeze){
            $where['is_freeze'] =  $is_freeze-1;
            $search_list['is_freeze'] = $is_freeze;
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

        $odds_list = $odds_type_db->select();
        foreach ($odds_list as $item){
            $new_type[$item['id']] = $item['name'];
        }
        $where['status']=1;

        $count = $shop->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $module_name = '/' . MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;//获取路径
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% <input  class="tz_input" type="text" id="tz_input"/> <a class="tz_a_all" id="tz_a_num" href="' . $module_name . '/p/">跳转</a>%HEADER%');

        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个店铺</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $shop->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $user_name = $user->where(array('id'=>$item['user_id']))->field('id,name,phone,username')->find();
            $list[$key]['user_name'] = $user_name['name'];
            $list[$key]['phone'] = $user_name['phone'];
            $list[$key]['username'] = $user_name['username'];

            $list[$key]['type_name'] = $new_type[$item['odds_type']];

            $goods_num = 0;
            $goods_num+=$shop_goods->where(array('shop_id'=>$item['id']))->count();
            $list[$key]['goods_num'] = $goods_num;
        /*    $phone_list = $shop_photo->where(array('shop_id'=>$item['id']))->field('img_url')->select();
            $list[$key]['phone_list'] = $phone_list;*/
            $img_list = array();
            $img_list = explode(',',$item['img_url']);
            foreach ($img_list as $key1=>$val){
                $is_img = $cache->where(array('thumb'=>$val))->find();
                if ($is_img['url']){
                    $img_list[$key1] = $is_img['url'];
                }
            }
            $list[$key]['img_list'] = $img_list;

            if ($item['hospital_id']){
                $hos_info  = $hospital->where(array('id'=>$item['hospital_id']))->field('id,name')->find();
                $list[$key]['hos_name'] = $hos_info['name'];
            }

            $list[$key]['class_name'] = $new_class[$item['classify']];

            $order_money = 0;
            $order_money +=$shop_order->where(array('shop_id'=>$item['id'],'status'=>array('NOT IN','0,3')))->sum('money');
            $list[$key]['zong_money'] = $order_money;
        }


        $data = array(
            'list' =>$list,
            'page' =>$show,
            'search_list' =>$search_list,
            'class_list' =>$class_list,
            'odds_list' =>$odds_list,
            'at' =>'shop',
            'count' =>count($list),
        );
        $this->assign($data);
        $this->display();
    }


    function check(){
        $is_edit  =  I('get.edit');

        $shop = M('shop');
        $user = M('user');
        $shop_order = M('shop_order');
        $shop_goods = M('shop_goods');
        $shop_goods_banner = M('shop_goods_banner');
        $hospital = M('hospital');
        $cache = M('cache');
        $store_classify = M('store_classify');
        $set = M('set');
        $shop_odds = M('shop_odds');
        $odds_type = M('odds_type');
        $ticket = M('ticket');
        $user_odds_new = M('user_odds_new');
        $odds = M('odds');
        $shop_classify = M('shop_classify');



        if($is_edit){
            $list = $shop->where(array('id'=>$is_edit))->find();

            $classify_info = $shop_classify->where(['id'=>$list['classify2']])->field('id,name,type,parent')->find();

            $list['classify_name'] = $classify_info['name'];
            $img_list = array();
            $img_list = explode(',',$list['img_url']);
            foreach ($img_list as $key1=>$val){
                $is_img = $cache->where(array('thumb'=>$val))->find();
                if ($is_img['url']){
                    $img_list[$key1] = $is_img['url'];
                }
            }

            $type_name = $odds_type->where(array('id'=>$list['odds_type']))->field('name,odds')->find();
            $list['type_name'] = $type_name['name'];
            $list['odds_set'] = $type_name['odds'];


            $shop_myself = $user_odds_new->where(array('type'=>'4','user_id'=>$list['id']))->select();
            if($shop_myself){
                foreach ($shop_myself as $item){
                    $new_shop_self[$item['name']]=$item['odds'];
                }
            }

            $odds_value = $odds->where(array('id'=>array('IN','1,2,3,4,6')))->select();

            foreach ($odds_value as $key=>$item){
                $odds_value[$key]['is_system'] = 0;
                if(array_key_exists($item['name'],$new_shop_self)){
                    $odds_value[$key]['odds'] = $new_shop_self[$item['name']];
                }else{
                    $odds_value[$key]['is_system'] = 1;
                    $admin_odds = $user_odds_new->where(array('type'=>'2','name'=>$item['name'].'_'.$list['odds_type']))->find();
                    $odds_value[$key]['odds'] = $admin_odds['odds'];
                }
            }



            /*  $chou_info = $set->where(array('set_type'=>$list['classify'].'_order_chou'))->find();
                   $list['odds_set'] = $chou_info['set_value'];*/

            $list['img_list'] = $img_list;

            $class_name = $store_classify->where(array('id'=>$list['classify']))->field('name')->find();

            $list['class_name'] = $class_name['name'];

            if ($list['hospital_id']){
                $hos_info  = $hospital->where(array('id'=>$list['hospital_id']))->field('id,name')->find();
                $list['hos_name'] = $hos_info['name'];
            }
            $user_name = $user->where(array('id'=>$list['user_id']))->field('id,name,phone,username')->find();
            $list['user_name'] = $user_name['name'];
            $list['phone'] = $user_name['phone'];
            $list['username'] = $user_name['username'];

            $goods_num = 0;
            $goods_num += $shop_goods->where(array('shop_id'=>$list['id']))->count();
            $list['goods_num'] = $goods_num;


            $list['yuyue_list'] = explode('+',$list['yuyue']);
            $order_money = 0;
            $order_money +=$shop_order->where(array('shop_id'=>$list['id'],'status'=>array('NOT IN','0,3')))->sum('money');
            $list['zong_money'] = $order_money;

            $goods_list = $shop_goods->where(array('shop_id'=>$list['id']))->select();
            foreach ($goods_list as $key=>$item){
                if($list['odds']){
                    $goods_list[$key]['odds_set'] = $list['odds'];
                }else{
                    $goods_list[$key]['odds_set'] = $list['odds_set'];
                }

                $phone_list = array();
                $img_list = array();
                $img_list[] = $item['cover'];
                $phone_list = $shop_goods_banner->where(array('goods_id'=>$item['id']))->select();
                foreach ($phone_list as $val){
                    $img_list[] = $val['img_url'];
                }
                $goods_list[$key]['img_list'] = $img_list;
            }
            $list['now_grade'] = $list['grade'];
            $z = floor($list['grade']);
            if($z==$list['grade']){
                for($i=1;$i<=5;$i++){
                    if($i<=$z){
                        $grade[] = 'evaluate_sel_xing@2x.png';
                    }else{
                        $grade[] = 'evaluate_xing@2x.png';
                    }
                }
            }else{
                $yu = $list['grade'] - $z;
                for($i=1;$i<=5;$i++){
                    if($i<=$z){
                        $grade[] = 'evaluate_sel_xing@2x.png';
                    }else{
                        if($yu>=0.5){
                            if(($i-1)<$list['grade']){
                                $grade[] = 'evaluate_banx@2x.png';
                            }else{
                                $grade[] = 'evaluate_xing@2x.png';
                            }
                        }else{
                            $grade[] = 'evaluate_xing@2x.png';
                        }
                    }
                }
            }
            $list['grade'] = $grade;
        }else{
            $list = array();
            $goods_list = array();
        }

        if($list['ticket_id']!=0){
            $ticket_info = $ticket->where(array('id'=>$list['ticket_id']))->find();
            $list['ticket_name'] = $ticket_info['title'];
        }else{
            $list['ticket_name'] = '不赠送';
        }

        $data = array(
            'at'=>'shop',
            'title'=>'店铺',
            'odds_list'=>$odds_value,
            'list'=>$list,
            'goods_list'=>$goods_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }


    function checkhos(){
        $hospital = M('hospital');
        $shop = M('shop');
        $hos_list = $hospital->field('id,name')->select();
        foreach ($hos_list as $item){
            $is_bind = $shop->where(array('hospital_id'=>$item['id']))->field('id')->find();
            if (!$is_bind){
                $list[] = $item;
            }
        }
        $res = '查询';
        if($list){
            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功',
                'list'=> $list
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




    public function editgrade($id,$grade,$is_edit){
        $shop = M('shop');

        if(!$grade){$grade = 0;}



        $save['grade'] = $grade;
        $save['update_time'] = time();
        $query = $shop->where(array('id'=>$id))->save($save);

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




    public function editodds($id,$type,$odds,$is_edit){
        $shop = M('shop');
        $shop_goods = M('shop_goods');
        $shop_odds = M('shop_odds');
        $user_odds_new = M('user_odds_new');

        if(!$odds){$arr = array('code'=> 0, 'res'=>'请输入要修改的抽水比例');$this->ajaxReturn($arr,"JSON");}

        if($type=='1'){
            $query = $shop->where(array('id'=>$id))->save(array('odds'=>$odds,'update_time'=>time()));
        }

        if($type=='2'){
            $query = $shop_goods->where(array('id'=>$id))->save(array('odds'=>$odds,'update_time'=>time()));
        }

        if($type=='3'){
            $is_add = $user_odds_new->where(array('user_id'=>$is_edit,'type'=>'4','name'=>$id))->find();

            $add['name'] = $id;
            $add['odds'] = $odds;

            if ($is_add){
                $add['update_time'] = time();
                $query = $user_odds_new->where(array('id'=>$is_add['id']))->save($add);
            }else{
                $add['user_id'] = $is_edit;
                $add['type'] = 4;
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
        $shop_odds = M('shop_odds');
        $user_odds_new = M('user_odds_new');
        if($type=='1'){
            $query = $shop->where(array('id'=>$id))->save(array('odds'=>NULL,'update_time'=>time()));
        }
        if($type=='2'){
            $query = $shop_goods->where(array('id'=>$id))->save(array('odds'=>NULL,'update_time'=>time()));
        }

        if($type=='3'){
            $query = $user_odds_new->where(array('type'=>'4','name'=>$id,'user_id'=>$is_edit))->delete();

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

    function bindhospital($id,$shop_id){
        $hospital = M('hospital');
        $shop = M('shop');
        $shop_info  =$shop->where(array('id'=>$shop_id))->field('hospital_id')->find();
        if($shop_info['hospital_id']){$arr = array('code'=> 0, 'res'=>'该店铺已绑定医院，无法重复绑定');$this->ajaxReturn($arr,"JSON");}
        $is_bind  =$shop->where(array('hospital_id'=>$id))->field('id')->find();
        if($is_bind){$arr = array('code'=> 0, 'res'=>'该医院已被其他店铺绑定');$this->ajaxReturn($arr,"JSON");}

        $save['hospital_id'] = $id;
        $save['update_time'] = time();
        $query  =$shop->where(array('id'=>$shop_id))->save($save);

        $res = '绑定';
        if($query){
            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功',
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

    function delbind($shop_id){
        $hospital = M('hospital');
        $shop = M('shop');
        $shop_info  =$shop->where(array('id'=>$shop_id))->field('hospital_id')->find();
        if($shop_info['hospital_id']==0){$arr = array('code'=> 0, 'res'=>'该店铺未绑定医院，无法取消');$this->ajaxReturn($arr,"JSON");}

        $save['hospital_id'] = 0;
        $save['update_time'] = time();
        $query  =$shop->where(array('id'=>$shop_id))->save($save);

        $res = '解绑';
        if($query){
            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功',
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



    function plus(){
        $is_edit  =  I('get.edit');

        $shop = M('shop');
        $user = M('user');
        $store_classify = M('store_classify');
        $ticket = M('ticket');
        $odds_type = M('odds_type');
        $shop_classify = M('shop_classify');
        //$shop_photo = M('shop_photo');
        $class_list = $store_classify->select();
        $odds_list = $odds_type->select();

        if($is_edit){
            $list = $shop->where(array('id'=>$is_edit))->find();
            $user_info = $user->where(array('id'=>$list['user_id']))->field('id,username')->find();
           //$phone_list = $shop_photo->where(array('shop_id'=>$is_edit))->field('id,img_url')->select();
            //$list['banner_list'] = $phone_list;
        }else{
            $list = array();
        }
        $classify_list = $shop_classify->where(['classify'=>$list['classify']])->field('id,name,type,parent')->select();
        $ticket_list = $ticket->select();
        $data = array(
            'at'=>'shop',
            'title'=>'店铺',
            'class_list'=>$class_list,
            'odds_list'=>$odds_list,
            'ticket_list'=>$ticket_list,
            'classify_list'=>$classify_list,
            'user_info'=>$user_info,
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }



    public function add($name,
        $ali_number,
        $ali_name,
        $classify2,
        $yuyue,$ticket_id,$odds_type,$phone,$username,$password,$classify,$location,$lng,$lat,$sort,$is_edit){
        $shop = M('shop');
        $shop_goods = M('shop_goods');
        $user = M('user');


        if(!$is_edit){

            if(!$phone){$arr = array('code'=> 0, 'res'=>'请输入手机号');$this->ajaxReturn($arr,"JSON");}
            if(!$username){$arr = array('code'=> 0, 'res'=>'请输入登录账号');$this->ajaxReturn($arr,"JSON");}
            if(!$password){$arr = array('code'=> 0, 'res'=>'请输入登录密码');$this->ajaxReturn($arr,"JSON");}
            //判断输入非法
            if(preg_match("/[\',:;*?？！~`!#$%^&=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$phone)){  //不允许特殊字符
                $arr = array('code'=> 0, 'res'=>'手机号格式错误');$this->ajaxReturn($arr,"JSON");
            }
            $is_user = $user->where(array('phone'=>$phone))->field('id')->find();

            if($is_user){$arr = array('code'=> 0, 'res'=>'该手机号已注册');$this->ajaxReturn($arr,"JSON");}

        }
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入店铺名称');$this->ajaxReturn($arr,"JSON");}
        if(!$classify){$arr = array('code'=> 0, 'res'=>'请选择店铺分类');$this->ajaxReturn($arr,"JSON");}
        if(!$odds_type){$arr = array('code'=> 0, 'res'=>'请选择结算类型');$this->ajaxReturn($arr,"JSON");}



        $where['id'] = array('NEQ',$is_edit);
        $where['name'] = $name;
        $where['status'] = 1;
        $is_name = $shop->where($where)->find();
        if($is_name){$arr = array('code'=> 0, 'res'=>'店铺名称已存在');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            $shop_info = $shop->where(array('id'=>$is_edit))->field('user_id')->find();
            $user_info = $user->where(array('id'=>$shop_info['user_id']))->field('id,username')->find();
            $where_name['id'] = array('NEQ',$user_info['id']);
        }

        $where_name['username|phone'] = $username;
        $is_name = $user->where($where_name)->find();
        if($is_name){
            $arr = array(
                'code'=> 0,
                'res'=>'登录账号已存在'
            );
            $this->ajaxReturn($arr,"JSON");
        }

        if($is_edit){


            $save['name'] = $name;
            $save['classify'] = $classify;
            $save['odds_type'] = $odds_type;
            $save['location'] = $location;
            $save['lng'] = $lng;
            $save['lat'] = $lat;
            $save['classify2'] = $classify2;
            $save['yuyue'] = $yuyue;
            $save['ticket_id'] = $ticket_id;
            $save['sort'] = $sort;

            $save['update_time'] = time();
            $query = $shop->where(array('id'=>$is_edit))->save($save);
            if ($password || $username){
                if($password){
                    $save_user_pass['password'] = md5($password);
                }
                if($username){
                    $save_user_pass['username'] = $username;
                }
                 $save_user_pass['update_time'] = time();
                $user->where(array('id'=>$shop_info['user_id']))->save($save_user_pass);
            }
            $shop_id = $is_edit;
            $res = '修改';
        }else{
            //添加新用户
            $add['phone'] = $phone;
            $add['username'] = $username;
            $add['password'] = md5($password);
            $user_name = '用户';
            $add['token'] = md5('xuan_yimei_User'.$phone.time());
            $add['create_time'] = time();
            $add['head'] = XUANIMG.'/Public/image/usermo.png';
            //$add['phone_at'] = '1';
            $add['signature'] = '听说有个签名，今年会有桃花运';
            $user_id = $user->add($add);
            if ($user_id){
                $save_user['name'] = $user_name.'_'.$user_id;
                $user->where(array('id'=>$user_id))->save($save_user);
                //融云token
                vendor('Rong.rongcloud');
                $RongCloud = new \RongCloud(C('RongCloudConf.key'),C('RongCloudConf.secret'));
                $result = $RongCloud->user()->getToken($user_id, $save_user['name'],$add['head']);
                $rongres =  json_decode($result,true);
                if($result){
                    $user->where(array('id'=>$user_id))->save(array('rong_token'=>$rongres['token'],'update_time'=>time()));
                }
                //注册获得东西
                $add_status = RegGetTask($user_id);
                //发送注册公告
                SendNoticeUser($user_id);

                $add_shop['user_id'] = $user_id;
                $add_shop['logo'] = XUANIP.'/shop_logo.jpg';;
                $add_shop['bg'] = XUANIP.'/shop_bg.jpg';;
                $add_shop['name'] = $name;
                $add_shop['odds_type'] = $odds_type;
                $add_shop['status'] = 1;
                $add_shop['is_show'] = 1;
                $add_shop['service_type'] = 1;
                $add_shop['classify'] = $classify;
                $add_shop['location'] = $location;
                $add_shop['lng'] = $lng;
                $add_shop['yuyue'] = $yuyue;
                $add_shop['lat'] = $lat;
                $add_shop['ticket_id'] = $ticket_id;
                $add_shop['sort'] = $sort;
                $add_shop['create_time'] = time();
                $query = $shop->add($add_shop);
                $shop_id = $query;

                $qr_arr['type'] = '2';
                $qr_arr['type_id'] = $query;
                $qr_val = json_encode($qr_arr);
                $content = encrypt1($qr_val);
                $qr_url = makecode($content,$add_shop['logo']);

                $save_qr['pay_qr'] = $qr_url;
                $save_qr['update_time'] = time();
                $shop->where(array('id'=>$query))->save($save_qr);

            }else{
                $arr = array('code'=> 0, 'res'=>'添加失败');$this->ajaxReturn($arr,"JSON");

            }
        }



        if($query){

            if($ali_number && $ali_name){
                $shop_info = $shop->where(array('id'=>$shop_id))->field('user_id')->find();
                $save_user['ali_number'] = $ali_number;
                $save_user['ali_name'] = $ali_name;
                $save_user['update_time'] = time();
                $user->where(array('id'=>$shop_info['user_id']))->save($save_user);
            }




            if($is_edit){
                $save_goods['lng'] = $lng;
                $save_goods['lat'] = $lat;
                $shop_goods->where(array('shop_id'=>$is_edit))->save($save_goods);



            }else{

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


    function editname($id,$name){
        $shop = M('shop');
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入店铺名称');$this->ajaxReturn($arr,"JSON");}

        $save['name'] = $name;
        $save['update_time'] = time();
        $query = $shop->where(array('id'=>$id))->save($save);
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


}












