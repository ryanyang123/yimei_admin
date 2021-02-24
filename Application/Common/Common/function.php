<?php
//自动添加项目对应商品
function addProjectGoods($project_id){
    $project_apply = M('project_apply');
    $shop_goods = M('shop_goods');
    $shop_goods_banner = M('shop_goods_banner');
    $shop_goods_photo = M('shop_goods_photo');
    $shop_select = M('shop_select');
    $project_img = M('project_img');
    $shop = M('shop');
    $is_add = $shop_goods->where(['project_id'=>$project_id])->find();
    if($is_add){
        return false;
    }
    $list = $project_apply->where(['id'=>$project_id])->find();
    $img_list = $project_img->where(['parent'=>$project_id])->select();
    $shop_info  = $shop->where(['id'=>$list['shop_id']])->find();
    $add['shop_id'] = $shop_info['id'];
    $add['classify'] = $shop_info['classify2'];
    $add['class2'] = $shop_info['classify2'];
    $add['class1'] = $shop_info['classify'];
    $add['name'] = $list['name'];
    $add['cover'] = $img_list[0]['img_url'];
    $add['cost'] = 0;
    $add['price'] = $list['price'];
    $add['project_id'] = $list['id'];
    $add['is_project'] = 1;
    $add['stock'] = 9999;
    $add['is_show'] = 1;
    $add['lng'] = $shop_info['lng'];
    $add['lat'] = $shop_info['lat'];
    $add['create_time'] = time();
    $query = $shop_goods->add($add);
    if($query){
        //添加banner
        if(count($img_list)>1){
            foreach ($img_list as $key=>$item){
                if($key!=0){
                    $add_img['img_url'] = $item['img_url'];
                    $add_img['goods_id'] = $query;
                    $add_img['create_time'] = time();
                    $shop_goods_banner->add($add_img);
                }
            }
        }
        //添加内容
        $content = '<p>'.$list['content'].'</p></br>';
        $content .='<p>';
        foreach ($img_list as $item){
            $content .='<img src="'.$item['img_url'].'">';
        }
        $content .='</p>';
        $add_con['goods_id'] = $query;
        $add_con['content'] = $content;
        $add_con['create_time'] = time();
        $shop_goods_photo->add($add_con);

        //添加规格
        $add_sel['select_name'] = '默认规格';
        $add_sel['value'] = '默认';
        $add_sel['add_price'] = $list['price'];
        $add_sel['add_cost'] = $list['price'];
        $add_sel['goods_id'] = $query;
        $add_sel['create_time'] = time();
        $shop_select->add($add_sel);
        return true;
    }
    return false;
}



//返回机构分类名称
function getShopClassify($type){
    $store_classify = M('store_classify');
    $list = $store_classify->where(array('id'=>$type))->find();
    return $list['name'];
}
//更新没有医院的店铺
function updateShopHos($shop_id){
    $shop  = M('shop');
    $hospital  = M('hospital');
    $shop_info = $shop->where(array('id'=>$shop_id))->find();
    if($shop_info['hospital_id']){
        return ;
    }
    $add['type'] = 1;
    $add['name'] = $shop_info['name'];
    $add['logo'] = $shop_info['logo'];
    $add['bg'] = $shop_info['bg'];
    $add['lat'] = $shop_info['lat'];
    $add['lng'] = $shop_info['lng'];
    $add['location'] = $shop_info['location'];
    $add['create_time'] = time();
    $query = $hospital->add($add);
    if($query){
        $save['hospital_id'] = $query;
        $save['update_time'] = time();
        $shop->where(array('id'=>$shop_info['id']))->save($save);
    }

    return true;
}



//城市合伙人订单抽水
function cityRebateMoney($type,$order_id,$other_money = 0){
    //type:1商城订单，2医院订单，3团队费用，4优惠券
    $team_money = M('team_money');
    if($type==1){
        $order_info = M('shop_order')->where(array('id'=>$order_id))->find();
        if($order_info['status']!=5){
            return true;
        }
        if($order_info['money']<0.1){
            return true;
        }

        $user_info = M('user')->where(array('id'=>$order_info['user_id']))->find();
        if($user_info['rebate_city']==0){
            return true;
        }
        $odds_val = 'shop_order_odds';

        $jian_money = $team_money->where(array('type'=>2,'order_id'=>$order_info['id']))->sum('money');
        $money = bcsub($order_info['money'],$jian_money,2);

    }

    if($type==2){
        $order_info = M('hospital_order')->where(array('id'=>$order_id))->find();
        if($order_info['status']!=4){
            return true;
        }
        if($order_info['money']<0.1){
            return true;
        }
        $user_info = M('user')->where(array('id'=>$order_info['user_id']))->find();
        if($user_info['rebate_city']==0){
            return true;
        }


        $odds_val = 'hospital_order_odds';


        $jian_money = $team_money->where(array('type'=>1,'order_id'=>$order_info['id']))->sum('money');
        $j_money = $order_info['money']-$order_info['cost'];
        $money = bcsub($j_money,$jian_money,2);
        //$money = $order_info['money'];
    }
    if($type==3){
        $user_info = M('user')->where(array('id'=>$order_id))->find();
        if($user_info['rebate_city']==0){
            return true;
        }
        $odds_val = 'hospital_order_odds';
        $money = $other_money;
    }


    if($type==4){
        $ticket_info = M('ticket_user')->where(array('id'=>$order_id))->find();
        $ticket = M('ticket')->where(array('id'=>$ticket_info['parent']))->find();
        $user_info = M('user')->where(array('id'=>$ticket_info['user_id']))->find();
        if($user_info['rebate_city']==0){
            return true;
        }
        $odds_val = 'hospital_order_odds';

        $money = $ticket['city_money'];
    }

    $city_info = M('city_rebate_city')->where(array('city_id'=>$user_info['rebate_city']))->find();
    if($city_info[$odds_val]>0){
        $fan_money = $money*($city_info[$odds_val]/100);
        if($fan_money<0.1){
            return true;
        }
    }else{
        return true;
    }
    $city_info2 = M('city_admin')->where(array('id'=>$city_info['admin_id']))->find();
    $add['user_id']  =$city_info2['user_id'];
    $add['type']  = $type;
    $add['type_id']  = $order_id;
    $add['money']  = $fan_money;
    $add['city_id']  = $city_info['city_id'];
    $add['status']  = 0;
    $add['create_time']  = time();
    $query = M('city_rebate')->add($add);
    return $query;
}

/**
 * 根据经纬度 获取地址
 * @param $address23.2322,12.15544
 * @return mixed
*/
function getaddress($address){
    $url="http://restapi.amap.com/v3/geocode/regeo?output=json&location=".$address."&key=d247076aa6dafc8ca726046e514f0eec";
    if($result=file_get_contents($url))
    {
        $result = json_decode($result,true);
        if(!empty($result['status'])&&$result['status']==1){
            $province = $result['regeocode']['addressComponent']['province'];
            $city = $result['regeocode']['addressComponent']['city'];
            if(!$city){
                $city =  $province;
            }
            if(!$province){
                return 0;
            }
            $city_id = updateRebateCity($province,$city);

            return $city_id;
        }else{
            return false;
        }
    }
}

//更新返利城市表
function updateRebateCity($province,$city){
    $rebate_city = M('rebate_city');
    $is_have = $rebate_city->where(array('province'=>$province,'city'=>$city))->find();
    if(!$is_have){
        $add['province'] = $province;
        $add['city'] = $city;
        $add['create_time'] = time();
        $query = $rebate_city->add($add);
        $city_id = $query;
    }else{
        $city_id = $is_have['id'];
    }
    return $city_id;
}



function geturl($url){
    $headerArray =array("Content-type:application/json;","Accept:application/json");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);
    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output,true);
    return $output;
}

function send_post($url, $post_data) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    // POST数据
    curl_setopt($ch, CURLOPT_POST, 1);

    // 把post的变量加上
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $output = curl_exec($ch);

    curl_close($ch);

    return $output;

}


//支付数据处理
function payData($from,$from_name,$token,$type,$type_id,$pay_type,$have_id,$score,$remark,$from_1=1,$qr_id=0){
    //type:支付类型：1支付医院，2支付商城订单，3优惠券，4团队费用,5支付红包,6支付城市合伙人费用
    //pay_type支付类型：1支付全款，2支付预定金,3支付剩余金额
    //$from:1微信，2支付宝，3小程序员
    $user = M('user');
    $shop_order = M('shop_order');
    $hospital_order = M('hospital_order');
    $ticket_user = M('ticket_user');
    $ticket_hospital = M('ticket_hospital');
    $ticket = M('ticket');
    $team = M('team');
    $set = M('set');

    $user_info = $user->where(array('token' => $token))->find();
    if (!$user_info) return array('code' => 2001, 'res' => 'token错误');
    if ($user_info['is_freeze'] == '0') return  array('code' => 0, 'res' => '您的帐号已被禁止登陆，如有疑问，请联系工作人员');

    if($type!='2'){
        if (!$user_info['ali_user_id'] && !$user_info['ali_number']) return array('code' => 3001, 'res' => '请先绑定支付宝后进行此操作');
    }

    if ($type=='1'){
        $order_info = $hospital_order->where(array('id'=>$type_id))->find();
        if (!$order_info) return array('code' => 0, 'res' => '订单信息错误');
        if ($order_info['use_type']!='1') return array('code' => 0, 'res' => '订单信息错误');
        if($order_info['user_id']){
            if ($order_info['user_id']!=$user_info['id']) return array('code' => 0, 'res' => '二维码已失效');
        }


        // if ($order_info['status']!='0') {$arr = array('code' => 0, 'res' => '此订单已支付');$data = json_encode($arr);$return['encryption'] = encrypt1($data);$this->ajaxReturn($return, "JSON");}
        $pay_money = $order_info['pay_money'];

        $add_pay['money_type'] = $pay_type;
        if ($pay_type=='2'){
            if ($order_info['is_prepay']=='0')return array('code' => 0, 'res' => '该订单不支持支付预定金');
            if ($order_info['status']!='0')return array('code' => 0, 'res' => '订单已支付，请勿重复支付');
            $now_money = $order_info['prepay_money'];
        }else if ($pay_type=='3'){
            if ($order_info['status']!='1')return array('code' => 0, 'res' => '订单信息错误');
            $now_money = $order_info['need_money'];
        }else{
            if ($order_info['status']!='0')return array('code' => 0, 'res' => '订单已支付，请勿重复支付');
            $now_money = $order_info['pay_money'];
        }
        if($pay_type!='3'){
            if ($have_id){
                $is_have = $ticket_user->where(array('id'=>$have_id))->find();

                if (!$is_have || $is_have['status']!='0') return array('code' => 0, 'res' => '未拥有此优惠券或已使用');
                $where_use['lyx_ticket.price'] = 0;
                $where_use['lyx_ticket_user.user_id'] = $user_info['id'];
                $where_use['lyx_ticket_user.status'] = array('IN','1,2');
                $is_have_use = $ticket_user
                    ->join('lyx_ticket ON lyx_ticket_user.parent = lyx_ticket.id')
                    ->where($where_use)
                    ->field('lyx_ticket_user.id')->find();
                if ($is_have_use) return array('code' => 0, 'res' => '已使用过同类型免费优惠券，无法重复使用');

                $where_check['hospital_id'] = $order_info['shop_id'];
                $where_check['ticket_id'] = $is_have['parent'];
                $where_check['ticket_type'] = 1;
                $is_use = $ticket_hospital->where($where_check)->find();
                if (!$is_use) return array('code' => 0, 'res' => '此优惠券无法在当前医院使用');
                if($is_have){
                    $odds = $is_have['discount']/100;
                    $pay_money1 = ($order_info['total_money']-$order_info['cost']) * $odds;
                }else{
                    $pay_money1 = $order_info['need_money'] - $is_have['discount'];
                }

                $pay_money = $pay_money1 - $order_info['loans'] + $order_info['cost'];
                if ($pay_money<=0) return array('code' => 0, 'res' => '此优惠券无法使用');

                $ticket_info = $ticket->where(array('id'=>$is_have['parent']))->find();
                if ($ticket_info['use_type']=='2') return array('code' => 0, 'res' => '参数错误');

                $add_pay['ticket_id'] = $have_id;
                $add_pay['ticket_money'] = $order_info['pay_money']  - $pay_money;
            }
            if ($score){
                $socre_num = $set->where(array('set_type'=>'score_pay'))->find();
                $socre_max = $set->where(array('set_type'=>'score_use_max'))->find();
                if ($score < $socre_num['set_value']) return array('code' => 0, 'res' => '最低使用'.$socre_num['set_value'].'积分');
                if ($score%$socre_num['set_value']!=0) return array('code' => 0, 'res' => '积分只能输入'.$socre_num['set_value'].'或其倍数');

                $max_money1 = $order_info['pay_money'] *($socre_max['set_value']/100);
                $max_money = floor($max_money1);
                $max = ($max_money/$socre_num['remark'])*$socre_num['set_value'];


                if ($score>$max) return array('code' => 0, 'res' => '此订单最大使用'.$max.'积分');

                $bei = $score/$socre_num['set_value'];
                $score_money = $bei*$socre_num['remark'];

                $pay_money -= $score_money;

                $add_pay['score'] = $score;
                $add_pay['score_money'] = $score_money;
            }
        }



        if ($pay_type=='2'){
            $money = $now_money;
            $need_money = $pay_money - $now_money;
            $add_pay['need_money'] = $need_money;
            if($need_money<=0){
                return array('code' => 0, 'res' => '此订单优惠后可直接全额支付');
            }
        }else if ($pay_type=='3'){
            $money = $now_money;
        }else{
            $money = $pay_money;
        }

        if($pay_type=='1'){
            if($user_info['is_agent']=='1'){
                $fan_money = HosOrderAgentChou($type_id,'2');
                if($fan_money){
                    $money = bcsub($money,$fan_money,2);
                }
            }
        }

    }

    if ($type=='2'){
        $order_info = $shop_order->where(array('id'=>$type_id))->field('id,status,total_money,prepay_money,is_prepay,shop_id,need_money,goods_id,total_num')->find();
        if($order_info){
            $shop_location  = M('shop_location');
            $location_info = $shop_location->where(array('user_id'=>$user_info['id']))->find();
            if(!$location_info){
                return array(
                    'code' => 0,
                    'res' => '请先完善收货地址'
                );
            }

            $add_pay['remark'] = $remark;
            $add_pay['money_type'] = $pay_type;
            $num = $order_info['total_num'];
            $goods_info = M('shop_goods')->where(array('id'=>$order_info['goods_id']))->field('price,add_score,stock,prepay,is_prepay,shop_id,max,is_show,is_freeze,is_fan_my')->find();
            if($goods_info['price']==0){
                $pay_type = 1;
            }
            if($goods_info['max']>0){
                $where_buy['user_id'] = $user_info['id'];
                $where_buy['goods_id'] = $goods_info['goods_id'];
                $where_buy['status'] = array('NEQ',0);
                $where_buy['refund_status'] = array('NOT IN','4,5');
                $buy_num = $shop_order->where($where_buy)->sum('total_num');
                $now_num = $num+$buy_num;
                if ($now_num>$goods_info['max']){
                    return array(
                        'code'=> 0,
                        'res'=>'此商品限购'.$goods_info['max'].'件'
                    );

                }
            }
            if(($goods_info['stock'])-$num<0){
                return array(
                    'code'=> 0,
                    'res'=>'商品库存不足，请稍后购买或浏览其他商品！'
                );
            }

            if($pay_type==1 || $pay_type==2){
                $se_info = M('shop_order_goods')->where(array('order_id'=>$order_info['id']))->field('select_id')->find();
                $postage_money = JsPostageMoney($se_info['select_id'],$order_info['total_num'],$location_info['id']);
                $order_info['total_money'] += $postage_money;
                //$save_order['total_money'] = $order_info['total_money'];
                $save_order['postage_money'] = $postage_money;
            }

            if ($pay_type=='2'){
                if ($order_info['is_prepay']=='0') return array('code' => 0, 'res' => '该商品不支持支付预定金');
                if ($order_info['status']!='0') return array('code' => 0, 'res' => '订单已支付，请勿重复支付');
                $money = $order_info['prepay_money'];
            }else if ($pay_type=='3'){
                if ($order_info['status']!='1')return array('code' => 0, 'res' => '订单信息错误');
                $money = $order_info['need_money'];
            }else{
                if ($order_info['status']!='0')return array('code' => 0, 'res' => '订单已支付，请勿重复支付');
                $money = $order_info['total_money'];
            }


            if($have_id){
                if ($pay_type!=1)return  array('code' => 0, 'res' => '当前订单状态不允许使用优惠券');
                $ajax_controller = A('ajax');
                $ticket_list = $ajax_controller->ShopOrderTicket($type_id,$token,$from_1);
                foreach ($ticket_list as $item){
                    if($item['have_id'] == $have_id){
                        $t_info = $item;
                        break;
                    }
                }
                if($t_info){
                    $add_pay['ticket_id'] = $have_id;
                    $ticket_money = $money - $t_info['ticket_money'];
                    $money = $t_info['ticket_money'];
                    $add_pay['ticket_money'] = $ticket_money;
                }
            }

            $save_order['is_confirm'] = 1;
            $save_order['location_id'] = $location_info['id'];
            $save_order['location'] = $location_info['province'].$location_info['city'].$location_info['district'].$location_info['street'];
            $save_order['mobile'] = $location_info['mobile'];
            $save_order['addressee'] = $location_info['addressee'];
            $save_order['particular'] = $location_info['particular'];
            $save_order['remark'] = $remark;

            if ($pay_type=='2'){
                $save_order['money_type'] = 1;
            }else if ($pay_type=='3'){
                $save_order['money_type'] = 2;
            }else{
                $save_order['money_type'] = 2;
            }
            M('shop_order')->where(array('id'=>$type_id))->save($save_order);

            if($pay_type=='1' && !$have_id){
                if($user_info['is_agent']=='1'){
                    if($goods_info['is_fan_my']=='1'){
                        $fan_money = 0;
                    }else{
                        $fan_money = ShopOrderAgentChou($type_id,'2');
                    }

                    if($fan_money['money']>0){
                        $money = bcsub($money,$fan_money['money'],2);
                    }
                }
            }
        }else{
            return array(
                'code' => 0,
                'res' => '订单不存在'
            );

        }
    }
    if ($type=='3'){
        if (!$user_info['ali_user_id'] && !$user_info['ali_number']){
            return array('code' => 3001, 'res' => '请先绑定支付宝后购买');
        }
        $ticket_info = $ticket->where(array('id'=>$type_id))->find();

        if (!$ticket_info) return array('code' => 0, 'res' => '优惠券不存在');
        if ($ticket_info['type']=='2')return array('code' => 0, 'res' => '无法购买体验券');

        if ($ticket_info['type']=='3') {
            if ($user_info['is_agent']!='1'){
                return array('code' => 0, 'res' => '没有购买权限');
            }
        }
        if ($ticket_info['max']>0){

            $buy_num = $ticket_user->where(array('user_id'=>$user_info['id'],'parent'=>$type_id))->count();
            if ($buy_num>=$ticket_info['max']) return array('code' => 0, 'res' => '此优惠券限购：'.$ticket_info['max'].'张');
        }
        $money = $ticket_info['price'];
    }

    if ($type=='4'){
        if ($user_info['is_agent'] == '1') return array('code' => 0, 'res' => '已经开通团队');
        //$team_info = $team->where(array('user_id'=>$user_info['id']))->find();
        //if (!$team_info) return array('code' => 0, 'res' => '您还未申请开通团队');
        //if ($team_info['status'] != '2') return array('code' => 0, 'res' => '状态错误');
        if (!$user_info['ali_user_id'] && !$user_info['ali_number']) return array('code' => 3001, 'res' => '请先绑定支付宝后进行此操作');
        $set_info = $set->where(array('set_type'=>'team_open_money'))->find();
        $qr_info = array();
        if($qr_id){
            $qr_info = M('team_qr')->where(array('id'=>$qr_id))->find();

            if($qr_info){
                if($qr_info['is_use']==0){
                    $qr_info = array();
                }
                //验证是否过期
                if($qr_info['type']==2){
                    if($qr_info['use_num']==$qr_info['num']){
                        $qr_info = array();
                    }
                }
                if($qr_info['type']==1){
                    if($qr_info['end_time']<time()){
                        $qr_info = array();
                    }
                }
            }
        }
       if($qr_info){
           $add_pay['qr_id'] = $qr_info['id'];
           $money = $qr_info['price'];
       }else{
           $money = $set_info['set_value'];
       }
        $type_id = 0;
    }

    if ($type=='5'){
        $task_info  = M('task')->where(array('id'=>$type_id))->find();
        if($user_info['id']!=$task_info['parent']) return array('code' => 0, 'res' => '没有权限');
        if($task_info['is_pay']=='1') return array('code' => 0, 'res' => '该红包已支付');
        $money = $task_info['prize_num'];
    }

    if ($type=='6'){
        if ($user_info['is_gudong'] == '1') return array('code' => 0, 'res' => '已经是城市合伙人');
        $gudong_info = M('gudong')->where(array('user_id'=>$user_info['id']))->find();
        if (!$gudong_info) return array('code' => 0, 'res' => '您还申请成为城市合伙人');
        if ($gudong_info['status'] != '2') return array('code' => 0, 'res' => '状态错误');
        if (!$user_info['ali_user_id'] && !$user_info['ali_number']) return array('code' => 3001, 'res' => '请先绑定支付宝后进行此操作');
        $set_info = $set->where(array('set_type'=>'gudong_money'))->find();
        $money = $set_info['set_value'];
        $type_id = $gudong_info['id'];
    }

    if ($type=='7'){
        if ($user_info['is_agent'] != '1') return array('code' => 0, 'res' => '订单错误');
        //$team_info = $team->where(array('user_id'=>$user_info['id']))->find();
        //if (!$team_info) return array('code' => 0, 'res' => '您还未申请开通团队');
        //if ($team_info['status'] != '2') return array('code' => 0, 'res' => '状态错误');
        if (!$user_info['ali_user_id'] && !$user_info['ali_number']) return array('code' => 3001, 'res' => '请先绑定支付宝后进行此操作');
        $money = 398;
        if($user_info['id']==1){
            $money = 0.01;
        }
        $type_id = 0;
    }

    if($money==0){
        if($type==2){
            $is_pay = Pay0Money('1',$type_id,$user_info['id'],'2');
            if($is_pay){
                return  array(
                    'code' => 0,
                    'res' => '支付成功'
                );

            }
        }
        if($type==3){
            $is_pay = Pay0Money('2',$type_id,$user_info['id'],'2');
            if($is_pay){
                return  array(
                    'code' => 0,
                    'res' => '支付成功'
                );
            }
        }
        return  array(
            'code' => 0,
            'res' => '当前价格无法支付！'
        );
    }else{
        $order = time().rand(10000,99999).$user_info['id'];

        if($from==3){
            $body = "伊美天鹅-小程序支付：".$money.'元';
            $title = "伊美天鹅支付订单(".$money.")元-小程序支付";
        }else if($from==2){
            $body = "伊美天鹅-支付宝支付：".$money.'元';
            $title = "伊美天鹅支付订单(".$money.")元-支付宝支付";
        }else{
            $body = "伊美天鹅-微信支付：".$money.'元';
            $title = "伊美天鹅支付订单(".$money.")元-微信支付";
        }



        $zuo = time()-7200;
        $pay= M('pay');
        $pay->where(array('create_time'=>array('LT',$zuo),'trade_status'=>'0'))->delete();
        $add_pay['from'] = $from_name;//来源：1APP，2公众号,
        $add_pay['type'] = $from;//1微信，2支付宝
        $add_pay['pay_type'] = $type;//支付类型：1支付医院，2支付商城订单，3优惠券，4团队费用
        $add_pay['order_id'] = $type_id;
        $add_pay['out_trade_no'] = $order;
        $add_pay['order_name'] = $title;
        $add_pay['money'] = $money;
        $add_pay['user_id'] = $user_info['id'];
        $add_pay['create_time'] = time();
        $query = $pay->add($add_pay);

        if ( $user_info['id']==1){
            $money = 0.01;
        }

        if($query){
            return  array(
                'code' => 1,
                'res' => '数据生成成功！',
                'money'=>$money,
                'order'=>$order,
                'body'=>$body,
                'title'=>$title,
            );
        }else{
            return  array(
                'code' => 0,
                'res' => '当前价格无法支付！'
            );
        }
    }

    return  array(
        'code' => 0,
        'res' => '支付超时！'
    );


}

//支付回调
function payNotify($out_trade_no){
    $user = M('user');
    $pay_info = M('pay')->where(array('out_trade_no'=>$out_trade_no))->find();
    $user_info = $user->where(array('id'=>$pay_info['user_id']))->find();

    if($pay_info['pay_type']=='1'){
        $order_info = M('hospital_order')->where(array('id'=>$pay_info['order_id']))->find();
        if ($pay_info['money_type']=='2'){
            $save_order['money'] = $pay_info['money'];
            $save_order['need_money'] = $pay_info['need_money'];
            $save_order['status'] = 1;
            $save_order['money_type'] = 1;
        }else if ($pay_info['money_type']=='3'){
            $save_order['money'] = $order_info['money']+$pay_info['money'];
            $save_order['need_money'] = 0;
            $save_order['status'] = 2;
            $save_order['money_type'] = 2;
        }else{
            $save_order['money'] =  $pay_info['money'];
            $save_order['need_money'] = 0;
            $save_order['status'] = 2;
            $save_order['money_type'] = 2;
        }

        $save_order['user_id'] = $pay_info['user_id'];
        //$save_order['money'] = $pay_info['money'];
        //$save_order['status'] = 1;
        $save_order['update_time'] = time();
        $save_order['pay_time'] = time();
        $save_order['ticket_id'] = $pay_info['ticket_id'];
        $save_order['ticket_money'] = $pay_info['ticket_money'];
        $save_order['score'] = $pay_info['score'];
        $save_order['score_money'] = $pay_info['score_money'];

        M('hospital')->where(array('id'=>$order_info['parent']))->setInc('subscribe',1);
        M('doctor')->where(array('id'=>$order_info['doctor_id']))->setInc('subscribe',1);
        //平台抽水
        //$chou_odds = M('set')->where(array('set_type'=>'hos_order_chou'))->find();
        /*$shop_type = M('shop')->where(array('hospital_id'=>$order_info['parent']))->field('odds_type,odds')->find();

        if($shop_type['odds']!=NULL){
            $chou_odds['set_value']  = $shop_type['odds'];
        }else{
            if(!$shop_type['odds_type']){
                $odds_type_id = $shop_type['odds_type'];
            }else{
                $odds_type_id = 1;
            }
            $odds_type_odds = M('odds_type')->where(array('id'=>$odds_type_id))->field('odds')->find();
            $chou_odds['set_value']   = $odds_type_odds['odds'];
        }

        if($chou_odds['set_value']!='0'){
            $chou_money = $order_info['total_money'] - $order_info['cost'] - $order_info['loans'];
            $odds = $chou_odds['set_value']/100;
            $chou = $chou_money * $odds;
            $save_order['chou'] = $chou;
            $save_order['odds'] = $chou_odds['set_value'];
        }*/
        $query = M('hospital_order')->where(array('id'=>$pay_info['order_id']))->save($save_order);
        if($query){
            $a = SendSocket($order_info['parent'],$order_info['id']);
            if ($pay_info['money_type']!='3'){
                if($pay_info['score']>0){
                    $user->where(array('id'=>$order_info['user_id']))->setDec('score',$pay_info['score']);
                    //减去积分
                    $add_log['user_id'] = $order_info['user_id'];
                    $add_log['add_or_del'] = 2;
                    $add_log['type'] = 2;
                    $add_log['num'] = $pay_info['score'];
                    $add_log['order_id'] = $order_info['id'];
                    $add_log['create_time'] = time();
                    M('shop_score_log')->add($add_log);
                }

                if($pay_info['ticket_id']){
                    M('ticket_user')->where(array('id'=>$pay_info['ticket_id']))->save(array('status'=>'1','update_time'=>time()));
                }
            }


            //代理抽水
            //HosOrderAgentChou($order_info['id']);

            if ($pay_info['money_type']=='1'){
                if($user_info['is_agent']=='1'){
                    $add_fan = HosOrderAgentChou($order_info['id'],'3');
                }
            }
        }
    }


    if ($pay_info['pay_type']=='2'){
        $order_info = M('shop_order')->where(array('id'=>$pay_info['order_id']))->find();
        $shop_location  = M('shop_location');
        $location_info = $shop_location->where(array('user_id'=>$pay_info['user_id']))->find();

        //$save_order['location_id'] = $location_info['id'];
        //$save_order['location'] = $location_info['province'].$location_info['city'].$location_info['district'].$location_info['street'];
        //$save_order['mobile'] = $location_info['mobile'];
        //$save_order['addressee'] = $location_info['addressee'];
        //$save_order['particular'] = $location_info['particular'];
        //$save_order['remark'] = $pay_info['remark'];
        $save_order['pay_time'] = time();
        $save_order['ticket_id'] = $pay_info['ticket_id'];
        $save_order['ticket_money'] = $pay_info['ticket_money'];
        $save_order['pay_type'] = $pay_info['type'];//1微信，2支付宝
        if ($pay_info['money_type']=='2'){
            $save_order['money'] = $pay_info['money'];
            $save_order['need_money'] = $order_info['total_money'] - $order_info['prepay_money']+$order_info['postage_money'];
            $save_order['status'] = 1;
            $save_order['money_type'] = 1;
            $save_order['total_money'] = $order_info['total_money']+$order_info['postage_money'];
        }else if ($pay_info['money_type']=='3'){
            $save_order['money'] = $order_info['total_money'];
            $save_order['need_money'] = 0;
            $save_order['status'] = 2;
            $save_order['money_type'] = 2;
        }else{
            $save_order['money'] = $pay_info['money'];
            $save_order['need_money'] = 0;
            $save_order['status'] = 2;
            $save_order['money_type'] = 2;
            $save_order['total_money'] = $order_info['total_money']+$order_info['postage_money'];
        }




        $query = M('shop_order')->where(array('id'=>$pay_info['order_id']))->save($save_order);
        if ($query){

            if($pay_info['ticket_id']){
                M('ticket_user')->where(array('id'=>$pay_info['ticket_id']))->save(array('status'=>'1','update_time'=>time()));
            }
            $shop_info = M('shop')->where(array('id'=>$order_info['shop_id']))->find();
            if ($pay_info['money_type']=='1'){
                $add_system['type'] = 1;//1新的订单，2订单交易成功，3待处理订单
                $tis = '新订单';
            }
            if ($pay_info['money_type']=='2'){
                $add_system['type'] = 1;//1新的订单，2订单交易成功，3待处理订单
                $tis = '新订单-已支付预约金';
            }
            if ($pay_info['money_type']=='3'){
                $add_system['type'] = 3;//1新的订单，2订单交易成功，3待处理订单
                $tis = '支付完成';
            }
            //添加消息通知
            //新版
            $add_system['user_id'] = $shop_info['user_id'];
            $add_system['send_user_id'] = $pay_info['user_id'];
            $add_system['content'] = $tis;
            $add_system['type_id'] = $order_info['id'];
            $add_system['status'] = 3;
            $add_system['create_time'] = time();
            M('system')->add($add_system);
            $rong_user[] = $shop_info['user_id'];
            SendRongSystem($rong_user,$tis);

            if ($pay_info['money_type']=='1' || $pay_info['money_type']=='3'){
                $goods_list = M('shop_order_goods')->where(array('order_id'=>$order_info['id']))->field('goods_id')->select();
                foreach($goods_list as $item){
                    $goods_info = M('shop_goods')->where(array('id'=>$item['goods_id']))->find();
                    //增加销量
                    if($goods_info['project_id']){
                        M('project_apply')->where(['id'=>$goods_info['project_id']])->setInc('sales',1);
                    }
                    M('shop_goods')->where(array('id'=>$item['goods_id']))->setInc('sales',$order_info['total_num']);
                    M('shop_goods')->where(array('id'=>$item['goods_id']))->setDec('stock',$order_info['total_num']);
                }
            }
            $add_status = AddScore($pay_info['user_id'],$user_info['score'],$user_info['level'],'0',$order_info['id']);

            if ($pay_info['money_type']=='1'){
                if($user_info['is_agent']=='1'){
                    $add_fan = ShopOrderAgentChou($order_info['id'],'3');
                }
            }

        }
    }



    if($pay_info['pay_type']=='3'){
        $ticket_info = M('ticket')->where(array('id'=>$pay_info['order_id']))->find();

        $add_tic['user_id'] = $user_info['id'];
        $add_tic['parent'] = $ticket_info['id'];
        $add_tic['buy_money'] = $ticket_info['price'];
        $add_tic['rebate_money'] = $ticket_info['rebate'];
        $add_tic['discount'] = $ticket_info['discount'];
        $add_tic['create_time'] = time();
        $query = M('ticket_user')->add($add_tic);
        M('ticket')->where(array('id'=>$pay_info['order_id']))->setInc('sales','1');
        if($ticket_info['is_rebate']=='1'){
            //城市合伙人抽水
            cityRebateMoney(4,$pay_info['order_id']);

            //赠送优惠券
            AgentTaskSend($user_info['id']);
        }
    }
        //开通团队
    if ($pay_info['pay_type']=='4'){
        $team_city_money = M('set')->where(array('set_type'=>'team_city_money'))->find();
        $parent_id = 0;
        $agent_money_arr  = M('set')->where(array('set_type'=>'team_agent_money'))->find();
        $agent_money = $agent_money_arr['set_value'];
        $qr_info = array();
        if($pay_info['qr_id']){
            $qr_info =  M('team_qr')->where(array('id'=>$pay_info['qr_id']))->find();
        }
        if($user_info['code_user']){
            $parent_id = $user_info['code_user'];
        }

        $team_money = $team_city_money['set_value'];

        if($qr_info){
            if($qr_info['user_id']!=0 && $qr_info['user_id'] != $user_info['id']){
                $parent_id = $qr_info['user_id'];
            }
            $agent_money = $qr_info['agent_money'];

            //更新二维码使用数量
            $save_qr['use_num'] = $qr_info['use_num']+1;
            $save_qr['update_time'] = time();
            if($qr_info['type']==2){
                if($save_qr['use_num']>= $qr_info['num'] ){
                    $save_qr['is_use'] = 0;
                }
            }
            $team_money = $qr_info['city_money'];
            M('team_qr')->where(array('id'=>$qr_info['id']))->save($save_qr);
        }else{
            $qr_info['id'] = 0;
        }
        //更新团队数据表
        $team_info = M('team')->where(array('user_id'=>$user_info['id']))->find();
        if($team_info){
            $save_team['status'] = 1;
            $save_team['update_time'] = time();
            $query = M('team')->where(array('id'=>$team_info['id']))->save($save_team);
            $team_id = $team_info['id'];
        }else{
            $add_team['status']=1;
            $add_team['type']=1;
            $add_team['user_id']=$user_info['id'];
            $add_team['qr_id']=$qr_info['id'];
            $add_team['create_time']=time();
            $query = M('team')->add($add_team);
            $team_id = $query;
        }

        //赠送礼物
        $set_info = M('set')->where(array('set_type'=>'agent_send'))->find();

        $agent_send = json_decode($set_info['remark'],true);
        $save_team_new['is_send'] = 1;
        $save_team_new['send_type'] = $agent_money['type'];
        $save_team_new['send_ticket'] = $agent_money['ticket_id'];
        //赠送优惠券
        if($agent_send['ticket_id']){
            $give_list =  M('ticket')->where(array('id'=>$agent_send['ticket_id']))->find();
            $add_ticket['user_id'] = $user_info['id'];
            $add_ticket['buy_money'] = 0;
            $add_ticket['rebate_money'] = 0;
            $add_ticket['create_time'] = time();
            $add_ticket['parent'] = $give_list['id'];
            $add_ticket['discount'] = $give_list['discount'];
            M('ticket_user')->add($add_ticket);
        }
        if($agent_send['type']==2){
            $save_team_new['is_send'] = 0;
        }
        $save_team_new['update_time'] = time();
        M('team')->where(array('id'=>$team_id))->save($save_team_new);

        if($parent_id){
            $shang = $user->where(array('id'=>$parent_id))->find();
            if($shang){
                $team_id = $shang['team_id'];
                $save_user['parent'] = $shang['id'];
                $save_user['one'] = $shang['parent'];
                $save_user['rebate_city'] = $shang['rebate_city'];
            }
        }
        $save_user['team_id'] = $team_id;
        $save_user['is_agent'] = 1;
        $save_user['update_time'] = time();
        //更新用户数据
        $user->where(array('id'=>$user_info['id']))
            ->save($save_user);
        if ($query){

            //城市合伙人抽水
            cityRebateMoney(3,$user_info['id'],$team_money);

            //添加上级返利数据
            if($agent_money>0 && $parent_id){
                $add_tui_agent['user_id'] = $parent_id;
                $add_tui_agent['type_id'] = $user_info['id'];
                $add_tui_agent['money'] = $agent_money;
                $add_tui_agent['status'] = 0;
                $add_tui_agent['create_time'] = time();
                M('tui_agent')->add($add_tui_agent);
            }

            //发送星级会员公告
            SendNoticeUser($user_info['id'],2);
        }


    }
    //开通团队
    if ($pay_info['pay_type']=='7'){
        //赠送礼物
        $set_info = M('set')->where(array('set_type'=>'agent_send'))->find();

        $agent_send = json_decode($set_info['remark'],true);

        //赠送优惠券
        if($agent_send['ticket_id']){
            $give_list =  M('ticket')->where(array('id'=>$agent_send['ticket_id']))->find();
            $add_ticket['user_id'] = $user_info['id'];
            $add_ticket['buy_money'] = 0;
            $add_ticket['rebate_money'] = 0;
            $add_ticket['create_time'] = time();
            $add_ticket['parent'] = $give_list['id'];
            $add_ticket['discount'] = $give_list['discount'];
            M('ticket_user')->add($add_ticket);
        }
        $save_user['is_agent'] = 1;
        $save_user['update_time'] = time();
        //更新用户数据
        $user->where(array('id'=>$user_info['id']))
            ->save($save_user);
    }
    if ($pay_info['pay_type']=='6'){
        $save_team['status'] = 1;
        $save_team['update_time'] = time();
        $query = M('gudong')->where(array('id'=>$pay_info['order_id']))->save($save_team);
        if ($query){
            //设置用户未股东
            UserIsGudong($user_info['id']);
        }
    }

    if ($pay_info['pay_type']=='5'){
        $save_team['is_pay'] = 1;
        $save_team['update_time'] = time();
        $query = M('task')->where(array('id'=>$pay_info['order_id']))->save($save_team);
        if ($query){
            $task_info = M('task')->where(array('id'=>$pay_info['order_id']))->find();
            $send_con['type'] = '1';
            $send_con['user_id'] = $task_info['user_id'];
            SendSocket($task_info['user_id'],json_encode($send_con));
        }
    }

    return true;

}

function Pay0Money($type,$order_id,$user_id,$pay_type){
    //type:1支付商品，2购买优惠券
    if($type=='1'){
        $user_info = M('user')->where(array('id'=>$user_id))->find();
        $order_info = M('shop_order')->where(array('id'=>$order_id))->find();
        $shop_location  = M('shop_location');

        $save_order['pay_time'] = time();
        $save_order['pay_type'] = $pay_type;//1微信，2支付宝

        $save_order['money'] = 0;
        $save_order['need_money'] = 0;
        $save_order['status'] = 2;
        $save_order['money_type'] = 2;
        $save_order['total_money'] = 0;

        $query = M('shop_order')->where(array('id'=>$order_info['id']))->save($save_order);
        if ($query){
            $shop_info = M('shop')->where(array('id'=>$order_info['shop_id']))->find();

            $add_system['type'] = 1;//1新的订单，2订单交易成功，3待处理订单
            $tis = '新订单';

            //添加消息通知
            //新版
            $add_system['user_id'] = $shop_info['user_id'];
            $add_system['send_user_id'] = $user_id;
            $add_system['content'] = $tis;
            $add_system['type_id'] = $order_info['id'];
            $add_system['status'] = 3;
            $add_system['create_time'] = time();
            M('system')->add($add_system);
            $rong_user[] = $shop_info['user_id'];
            SendRongSystem($rong_user,$tis);


            $goods_list = M('shop_order_goods')->where(array('order_id'=>$order_info['id']))->field('goods_id')->select();
            foreach($goods_list as $item){
                M('shop_goods')->where(array('id'=>$item['goods_id']))->setInc('sales',$order_info['total_num']);
                M('shop_goods')->where(array('id'=>$item['goods_id']))->setDec('stock',$order_info['total_num']);
            }
            $add_status = AddScore($user_id,$user_info['score'],$user_info['level'],'0',$order_info['id']);

            return true;
        }else{
            return false;
        }
    }

    if($type=='2'){
        $ticket_info = M('ticket')->where(array('id'=>$order_id))->find();
        $user_info = M('user')->where(array('id'=>$user_id))->find();

        $add_tic['user_id'] = $user_info['id'];
        $add_tic['parent'] = $ticket_info['id'];
        $add_tic['buy_money'] = $ticket_info['price'];
        $add_tic['rebate_money'] = $ticket_info['rebate'];
        $add_tic['discount'] = $ticket_info['discount'];
        $add_tic['create_time'] = time();
        $query = M('ticket_user')->add($add_tic);
        if($query){
            M('ticket')->where(array('id'=>$order_id))->setInc('sales','1');
            if($ticket_info['is_rebate']=='1'){
                //赠送优惠券
                AgentTaskSend($user_info['id']);
            }
            return true;
        }else{
            return false;
        }

    }
    return true;
}
/**
 * 设置用户为股东
 * @param $user_id
 * @return bool
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function UserIsGudong($user_id){
    $gudong = M('gudong');
    $user = M('user');
    $user_info = $user->where(array('id'=>$user_id))->field('id,is_agent,is_gudong')->find();
    if($user_info['is_gudong']!=1){
        if($user_info['is_agent']!=1){
            $team = M('team');
            $team_info = $team->where(array('user_id'=>$user_info['id']))->find();
            if ($team_info){
                $save_team['status'] = 1;
                $save_team['type'] = 3;
                $save_team['update_time'] = time();
                $query = M('team')->where(array('id'=>$team_info['id']))->save($save_team);
            }else{
                $add['user_id'] = $user_info['id'];
                $add['status'] = 1;
                $add['type'] = 3;
                $add['phone'] = $user_info['phone'];
                $add['sex'] = $user_info['sex'];
                $add['name'] = $user_info['name'];
                $add['age'] = $user_info['age'];
                $add['create_time'] = time();
                $add['update_time'] = time();
                $query = $team->add($add);
                $team_info['id'] = $query;
            }
            if($query){
                SendNoticeUser($user_info['id'],2);
                $user->where(array('id' => $user_info['id']))->save(array(
                    'is_agent' => '1',
                    'team_id' => $team_info['id'],
                    'update_time' => time()
                ));
            }
        }
        $save_gudong['is_gudong'] = 1;
        $save_gudong['update_time'] = time();
        $query2 = $user->where(array('id'=>$user_info['id']))->save($save_gudong);
        if($query2){
            $is_have  = $gudong->where(array('user_id'=>$user_info['id']))->find();
            if($is_have){
                $save1['status'] = 1;
                $save1['update_time'] = time();
                $gudong->where(array('user_id'=>$user_info['id']))->save($save1);
            }
        }
    }

    return true;
}


/**
 * 计算购买商品时的邮费
 * @param $select_id
 * @param $num
 * @param $location_id
 * @return int
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function JsPostageMoney($select_id,$num,$location_id,$type='1'){
    $money = 0;
    $postage = M('postage');
    $shop_select = M('shop_select');
    $shop_goods = M('shop_goods');
    $shop_location = M('shop_location');
    $select_info = $shop_select->where(array('id'=>$select_id))->find();
    $goods_info = $shop_goods->where(array('id'=>$select_info['goods_id']))->field('id,postage_id')->find();
    if($goods_info['postage_id']){
        if($type=='2'){
            $location_info['province'] = $location_id;
        }else{
            $location_info = $shop_location->where(array('id'=>$location_id))->field('id,province')->find();


        }

        $post_info = $postage->where(array('id'=>$goods_info['postage_id']))->find();
        $total_money = $select_info['add_price'] *$num;
        if($post_info['status']==1){
            if($post_info['is_bao']==1){
                if($post_info['bao_type']==1){
                    //总购买金额小于包邮条件金额
                    if($total_money<$post_info['bao_num']){
                        $money = provinceque($location_info['province'],$post_info['data'],$post_info['one_num'],$post_info['one_money'],$post_info['more_num'],$post_info['more_money'],$num);
                    }
                }else{
                    //总购买件数小于包邮件数
                    if($num<$post_info['bao_num']){
                        $money = provinceque($location_info['province'],$post_info['data'],$post_info['one_num'],$post_info['one_money'],$post_info['more_num'],$post_info['more_money'],$num);
                    }
                }
            }else{
                $money = provinceque($location_info['province'],$post_info['data'],$post_info['one_num'],$post_info['one_money'],$post_info['more_num'],$post_info['more_money'],$num);
            }
        }
    }
    return $money;
}

/**
 * 根据省份计算邮费
 * @param $str
 * @param $data
 * @param $one_num
 * @param $one_money
 * @param $more_num
 * @param $more_money
 * @param $num
 * @return float|int
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function provinceque($str,$data,$one_num,$one_money,$more_num,$more_money,$num){
    $postage_area = M('postage_area');
    $where['name'] = array("like","%".$str."%");
    $where['level'] = 2;
    $pro_info = $postage_area->where($where)->find();
    $pro = $pro_info['name'];
    $data_arr = json_decode($data,true);

    $list['one_num'] = $one_num;
    $list['one_money'] = $one_money;
    $list['more_num'] = $more_num;
    $list['more_money'] = $more_money;
    foreach ($data_arr as $item){
        $loca_arr = array();
        $loca_arr = explode('+',$item['location']);
        if(in_array($pro,$loca_arr)){
            $list['one_num'] = $item['one_num'];
            $list['one_money'] = $item['one_money'];
            $list['more_num'] = $item['more_num'];
            $list['more_money'] = $item['more_money'];
        }
    }
    //不到需要收费的数量
    if($num<$list['one_num']){
        return 0;
    }else{
        if($num==$list['one_num']){
           //到达首件数
            return $list['one_money'];
        }else{
            //大于首件数
            $other_num = $num - $list['one_num'];
            $z_num = $other_num/$list['more_num'];
            $z_num = floor($z_num);
            $money = $z_num * $list['more_money'];

            return $money+$list['one_money'];
        }
    }
    return 0;
}

function GameBind($game_id,$user_id,$friend_user_id){

    $game_share = M('game_share');
    if($user_id!=$friend_user_id){
        $where_check['friend_user_id'] =$friend_user_id;
        $is_have = $game_share->where($where_check)->find();
        if(!$is_have){
            $add['user_id'] = $user_id;
            $add['friend_user_id'] = $friend_user_id;
            $add['friend_user_id'] = $friend_user_id;
            $add['create_time'] = time();
            $game_share->add($add);
        }
    }
    if($game_id!='no'){
        $game = M('game');
        $game_log = M('game_log');
        $game_info = $game->where(array('id'=>$game_id))->find();
        if($game_info['type']==2){
            if($game_info['status']!=1){
                $where_log['user_id'] = $friend_user_id;
                $where_log['game_id'] = $game_info['id'];
                $is_have_log = $game_log->where($where_log)->find();
                if(!$is_have_log){
                    $add_log['user_id'] = $friend_user_id;
                    $add_log['game_id'] = $game_info['id'];
                    $add_log['create_time'] = time();
                    $game_log->add($add_log);
                }
            }
        }
    }
    return true;

}

function check_verify($code, $id = ""){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}


//绑定邀请人(分享商品案例，绑定邀请人)
function BindCodeUser($share_user_id,$user_id){
    $user = M('user');
    if($share_user_id){
        $user_info = $user->where(array('id'=>$user_id))->field('id,code,code_user')->find();
        if($user_info){
            if(!$user_info['code_user']){
                $is_code = $user->where(array('id'=>$share_user_id))->field('id,score,level,phone')->find();
                if($is_code){
                    if($is_code['id']!=$user_id){
                        $save_user['code_user'] = $is_code['id'];
                        $save_user['code'] = $is_code['phone'];
                        $save_user['is_code'] = 1;
                        $save_user['update_time'] = time();
                        $query = $user->where(array('id'=>$user_id))->save($save_user);
                        if ($query){
                            $add_status = AddScore($is_code['id'],$is_code['score'],$is_code['level'],'6',$user_id);
                        }
                    }
                }
            }
        }

    }

    return true;
}

//成为星级会员发送红包
function AgentTaskSend($user_id){
    $task = M('task');
    $user = M('user');
    $task_prize = M('task_prize');
    $task_reg = M('task_reg');
    $is_send_socket = false;
    $user_info = $user->where(array('id'=>$user_id))->field('is_agent')->find();
    if($user_info['is_agent']!='1'){
        return true;
    }
    $task_list = $task_reg->where(array('type'=>'1'))->select();
    //$give_list =  M('ticket')->where(array('type'=>'2','is_give'=>'1'))->select();
    if($task_list){
        $is_send = $task->where(array('user_id'=>$user_id,'type'=>'4'))->find();
        if(!$is_send){
            $is_send_socket = true;
            foreach ($task_list as $item){
                $add['user_id'] = $user_id;
                $add['type'] = 4;
                $add['name'] = $item['name'];
                $add['is_pay'] = 1;
                $add['classes'] = $item['classes'];
                $add['prize'] = $item['prize'];
                $add['need_num'] = $item['need_num'];
                if($item['prize']=='2' || $item['prize']=='3'){
                    $add['prize_num'] = $item['prize_num'];
                }
                $add['parent'] = 0;
                $add['create_time'] = time();
                $query = $task->add($add);
                if($query){
                    if($item['prize']=='1'){
                        $ticket_info = M('ticket')->where(array('id'=>$item['prize_id']))->find();
                        $add_prize['user_id'] = 0;
                        $add_prize['task_id'] = $query;
                        $add_prize['parent'] = $item['prize_id'];
                        $add_prize['buy_money'] = 0;
                        $add_prize['rebate_money'] = 0;
                        $add_prize['discount'] = $ticket_info['discount'];
                        $add_prize['dis_title'] = $ticket_info['dis_title'];
                        $add_prize['create_time'] = time();
                        $prize_id = $task_prize->add($add_prize);
                        $task->where(array('id'=>$query))->save(array('prize_id'=>$prize_id));
                    }
                }
            }
        }

    }
    if($is_send_socket){
        $send_con['type'] = '1';
        $send_con['user_id'] = $user_id;
        SendSocket($user_id,json_encode($send_con));
    }

    return true;

}

//结算任务邀请人数
function TaskSex($ali_id,$code_user){
        $ali = M('ali');
        $task = M('task');
        $ali_info = $ali->where(array('id'=>$ali_id))->find();

        if($ali_info['sex']=='2' && $ali_info['task_id']=='0'){
            $where_task['user_id'] =  $code_user;
            $where_task['classes'] =  1;
            $where_task['status'] =  0;
            $where_task['is_pay'] =  1;
            $is_have = $task->where($where_task)->order('create_time')->find();
            if($is_have){
                $add['task_id'] = $is_have['id'];
                $new_num = $is_have['now_num'] + 1;
                if($new_num==$is_have['need_num']){
                    $save_task['status'] = 1;
                }
                $save_task['now_num'] = $new_num;
                $save_task['update_time'] = time();
                $task->where(array('id'=>$is_have['id']))->save($save_task);
                $ali->where(array('id'=>$ali_id))->save(array('task_id'=>$is_have['id']));
            }
        }
        return true;

}

//注册后可获得的礼物
function RegGetTask($user_id){
    $set = M('set');
    $task = M('task');
    $task_reg = M('task_reg');
    $user = M('user');
    $user_info = $user->where(array('id'=>$user_id))->find();
    if($user_info['code']){
        $reg_score = $set->where(array('set_type'=>'register_score_code'))->find();
    }else{
        $reg_score = $set->where(array('set_type'=>'register_score'))->find();
    }
    $is_send_scoket = false;

    if($reg_score['set_value']>0){
        $is_send = $task->where(array('user_id'=>$user_id,'type'=>'1','classes'=>'2'))->find();
        if(!$is_send){
            $add['user_id'] = $user_id;
            $add['type'] = 1;
            $add['name'] = '注册赠送积分';
            $add['is_pay'] = 1;
            $add['classes'] = 2;
            $add['prize'] = 2;
            $add['prize_num'] = $reg_score['set_value'];
            $add['parent'] = 0;
            $add['create_time'] = time();
            $query = $task->add($add);
            if($query){
                $add_status = AddScore($user_info['id'],$user_info['score'],$user_info['level'],'8',$query,'0',$reg_score['set_value']);
                if($add_status){
                    $save_task['status'] = 2;
                    $save_task['is_get'] = 1;
                    $save_task['get_time'] = time();
                    $save_task['update_time'] = time();
                    $task->where(array('id'=>$query))->save($save_task);
                }
               $is_send_scoket = true;
            }
        }
    }
    $task_list = $task_reg->where(array('type'=>'1'))->select();
    if($task_list){
        $is_send_scoket = true;
            foreach ($task_list as $item){
                $add['user_id'] = $user_id;
                $add['type'] = 1;
                $add['name'] = $item['name'];
                $add['is_pay'] = 1;
                $add['classes'] = $item['classes'];
                $add['prize'] = $item['prize'];
                $add['need_num'] = $item['need_num'];
                if($item['prize']=='2' || $item['prize']=='3'){
                    $add['prize_num'] = $item['prize_num'];
                }


                $add['parent'] = 0;
                $add['create_time'] = time();
                $query = $task->add($add);
                if($query){
                    if($item['prize']=='1'){
                        $ticket_info = M('ticket')->where(array('id'=>$item['prize_id']))->find();
                        $add_prize['user_id'] = 0;
                        $add_prize['task_id'] = $query;
                        $add_prize['parent'] = $item['prize_id'];
                        $add_prize['buy_money'] = 0;
                        $add_prize['rebate_money'] = 0;
                        $add_prize['discount'] = $ticket_info['discount'];
                        $add_prize['dis_title'] = $ticket_info['dis_title'];
                        $add_prize['create_time'] = time();
                        $prize_id = M('task_prize')->add($add_prize);
                        $task->where(array('id'=>$query))->save(array('prize_id'=>$prize_id));
                    }

                }

            }
    }
    if($is_send_scoket){
        $send_con['type'] = '1';
        $send_con['user_id'] = $user_id;
        SendSocket($user_id,json_encode($send_con));
    }
    return;
}

//使用抵消券返利给医院
function TicketUseGetMoney($id,$order_id){
    $ticket_hospital  =  M('ticket_hospital');
    $tui  =  M('tui');
    $hospital_order  =  M('hospital_order');
    $ticket  =  M('ticket');
    $ticket_user  =  M('ticket_user');

    $list = $ticket_hospital->where(array('id'=>$id))->find();
    if($list['money']<=0){
        return true;
    }
    $is_have = $tui->where(array('type'=>'3','type_id'=>$order_id))->field('id')->find();
    if($is_have){
        return true;
    }

    $order_info = $hospital_order->where(array('id'=>$order_id))->field('ticket_id')->find();
    $have_info = $ticket_user->where(array('id'=>$order_info['ticket_id']))->field('parent')->find();
    if($list['ticket_id']!=$have_info['parent']){
        return true;
    }
    $ticket_info = $ticket->where(array('id'=>$list['ticket_id']))->find();
    if($ticket_info['type']==2){
        return true;
    }
    if($ticket_info['use_type']!=2){
        return true;
    }
    $add['status'] = 0;
    $add['user_id'] = 0;
    $add['type'] = 3;
    $add['type_id'] = $order_id;
    $add['money'] = $list['money'];
    $add['create_time'] = time();
    $query = $tui->add($add);
    return true;

}

//使用抵消券返利给医院
function TicketUseGetMoneyProject($order_id){
    $project_apply  =  M('project_apply');
    $tui  =  M('tui');
    $hospital_order  =  M('hospital_order');


    $is_have = $tui->where(array('type'=>'3','type_id'=>$order_id))->field('id')->find();
    if($is_have){
        return true;
    }

    $order_info = $hospital_order->where(array('id'=>$order_id))->field('project_apply_id')->find();
    if($order_info['project_apply_id']==0){
        return true;
    }

    $apply = $project_apply->where(['id'=>$order_info['project_apply_id']])->find();
    if($apply['rebate']==0){
        return true;
    }

    $add['status'] = 0;
    $add['user_id'] = 0;
    $add['type'] = 3;
    $add['type_id'] = $order_id;
    $add['money'] = $apply['rebate'];
    $add['create_time'] = time();
    $query = $tui->add($add);
    return true;

}


//生成图片缩略图
function ImgGetThumb($img,$type){
    $cache = M('cache');
    if($type){
        if (strpos($img,XUANIMG) !== false) {
            $is_add = $cache->where(array('url'=>$img))->field('id')->find();
            if(!$is_add){

                $img_url = str_replace(XUANIMG,".",$img);
                $is_file = file_exists($img_url);
                if($is_file){
                    $img_arr = explode('.',$img_url);
                    $thumb_url = $img_arr[1].'_thumb.'.$img_arr[2];
                    $image = new \Think\Image();
                    $image->open($img_url);
                    // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
                    $image->thumb(150,150)->save('.'.$thumb_url);
                    $add['url'] = $img;
                    $add['thumb'] = XUANIMG.$thumb_url;
                    $add['type'] = $type;
                    $add['create_time'] = time();
                    $query = $cache->add($add);
                    if($query){
                        return true;
                    }
                }

            }
        }
    }
    return false;
}



//星级评分
function gradeset($set_id,$type){
    //type:1医院订单，2店铺订单
    if($type=='1'){
        $hospital = M('hospital');
        $shop = M('shop');
        $order_comment = M('order_comment');

        $grade_num = $order_comment->where(array('parent'=>$set_id,'order_type'=>'2'))->sum('grade');
        $user_num = $order_comment->where(array('parent'=>$set_id,'order_type'=>'2'))->count();

        $is_bind = $shop->where(array('hospital_id'=>$set_id))->field('id')->find();
        if ($is_bind){
            $grade_num+=$order_comment->where(array('parent'=>$is_bind['id'],'order_type'=>'1'))->sum('grade');
            $user_num+=$order_comment->where(array('parent'=>$is_bind['id'],'order_type'=>'1'))->count();
        }
        $fen = $grade_num/$user_num;
        $res_fen = round($fen,2);

        $hospital->where(array('id'=>$set_id))->save(array('grade'=>$res_fen,'update_time'=>time()));
        if ($is_bind){
            $shop->where(array('id'=>$is_bind['id']))->save(array('grade'=>$res_fen,'update_time'=>time()));
        }
        return true;
    }
    if ($type=='2'){
        $hospital = M('hospital');
        $shop = M('shop');
        $order_comment = M('order_comment');
        $grade_num = $order_comment->where(array('parent'=>$set_id,'order_type'=>'1'))->sum('grade');
        $user_num = $order_comment->where(array('parent'=>$set_id,'order_type'=>'1'))->count();

        $is_bind = $shop->where(array('id'=>$set_id))->field('id,hospital_id')->find();
        if ($is_bind['hospital_id']){
            $grade_num+=$order_comment->where(array('parent'=>$is_bind['hospital_id'],'order_type'=>'2'))->sum('grade');
            $user_num+=$order_comment->where(array('parent'=>$is_bind['hospital_id'],'order_type'=>'2'))->count();
        }
        $fen = $grade_num/$user_num;
        $res_fen = round($fen,2);
        $shop->where(array('id'=>$set_id))->save(array('grade'=>$res_fen,'update_time'=>time()));
        if ($is_bind['hospital_id']){
            $hospital->where(array('id'=>$is_bind['hospital_id']))->save(array('grade'=>$res_fen,'update_time'=>time()));
        }
        return true;
    }
    return;
}

//注册发送公告给用户
function SendNoticeUser($user_id,$type=0){
    $notice = M('notice');
    if ($type==2){
        $is_have = $notice->where(array('is_agent'=>'1'))->find();
    }else{
        $is_have = $notice->where(array('is_reg'=>'1'))->find();
    }
    $user_info = M('user')->where(array('id'=>$user_id))->field('id,create_time')->find();
    if ($is_have){
        $is_send = M('system')->where(array('status'=>5,'type_id'=>$is_have['id'],'user_id'=>$user_id))->find();
        if (!$is_send){
            $is_all_have = M('system')->where(
                array('user_id'=>0,'create_time'=>array('EGT',$user_info['create_time']),'status'=>5,
                    'type_id'=>$is_have['id']
                ))->field('id')->find();
            if (!$is_all_have){
                $add_system['user_id'] = $user_id;
                $add_system['status'] = 5;
                $add_system['type'] = $type;
                $add_system['type_id'] = $is_have['id'];
                $add_system['content'] = $is_have['title'];
                $add_system['create_time'] = time();
                M('system')->add($add_system);
                $rong_user[] = $user_id;
                $tis = '《'.$is_have['title'].'》';
                SendRongSystem($rong_user,$tis);
            }

        }
    }
}



/**
 * 医院订单代理抽水
 * @param $order_id
 * @param string $type  1正常，2用户支付前先减去返利金额，3用户支付后添加返利金额
 * @return bool
 * @throws \think\Exception
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function HosOrderAgentChouCheck($money1,$cost2,$shop_id,$have_id,$user_id,$type='2'){

    $order_info['money'] = $money1 - $cost2;



    $is_shop = M('shop')->where(array('id'=>$shop_id))->field('id,odds_type')->find();
    $odds_type = $is_shop['odds_type'];

    $user = M('user');
    $set = M('set');
    $odds = M('odds');
    $user_odds_new = M('user_odds_new');
    //默认抽水配置
    $set_list= $set->where(array('type'=>'1'))->select();
    foreach ($set_list as $item){
        $new_set[$item['set_type']] = $item['set_value']/100;
    }
    $is_agent_ticket = false;
    if ($have_id){
        $have_info = M('ticket_user')->where(array('id'=>$have_id))->field('parent')->find();
        $ticket_info = M('ticket')->where(array('id'=>$have_info['parent']))->field('type,is_rebate')->find();
        if ($ticket_info['type']=='3'){
            $is_agent_ticket = true;
            if($ticket_info['is_rebate']!='1'){
                return ['money'=>0,'odds'=>0];
            }
            $ss_set = $set->where(array('set_type'=>'use_agent_ticket'))->find();
            $use_a_t = $ss_set['set_value']/100;
        }
    }


    $odds_value = $odds->select();
    $shop_myself = $user_odds_new->where(array('type'=>'4','user_id'=>$is_shop['id']))->select();
    if($shop_myself){
        foreach ($shop_myself as $item){
            $new_shop_self[$item['name']]=$item['odds'];
        }
    }
    foreach ($odds_value as $key=>$item){
        if(array_key_exists($item['name'],$new_shop_self)){
            $odds_value[$key]['odds'] = $new_shop_self[$item['name']];
        }else{
            $admin_odds = $user_odds_new->where(array('type'=>'2','name'=>$item['name'].'_'.$odds_type))->find();
            $odds_value[$key]['odds'] = $admin_odds['odds'];
        }
    }
    $odds_info = array();
    foreach ($odds_value as $item){
        $odds_info[$item['name']] = $item['odds']/100;
    }

    $user_info = $user->where(array('id'=>$user_id))->field('id,parent,one,team_id,is_agent,code_user')->find();
    if($user_info['is_agent']=='1' && !$is_agent_ticket ){
        $user_info['parent'] = $user_info['id'];
    }else{
        if($type=='2' || $type=='3'){
            return ['money'=>0,'odds'=>0];
        }
    }
    if($user_info['parent'] || $user_info['code_user']){
        if($user_info['parent']){
            $parent_info =  $user->where(array('id'=>$user_info['parent']))->field('id,parent,is_agent,team_id,code_user')->find();
        }else{
            $parent_info =  $user->where(array('id'=>$user_info['code_user']))->field('id,parent,is_agent,team_id,code_user')->find();
        }

        if($parent_info['is_agent']=='1'){
            $parent_set  = 'agent_rebate';
            $parent_odds = $user_odds_new->where(array('user_id'=>$parent_info['id'],'type'=>'1','name'=>$parent_set.'_'.$odds_type))->find();
            //下属用户消费返利
            if($parent_odds){
                $add_t1['money'] = $order_info['money'] * ($parent_odds['odds']/100);
                $add_t1['odds']  = $parent_odds['odds'];
                $res_odds =  $parent_odds['odds']/10;
            }else{
                $add_t1['money'] = $order_info['money'] * $odds_info[$parent_set];
                $add_t1['odds']  = $odds_info[$parent_set]*100;
                $res_odds = $odds_info[$parent_set]*10;
            }
            if ($is_agent_ticket){
                $add_t1['money'] = $order_info['money'] * $use_a_t;
                $add_t1['odds']  = $use_a_t*100;
                $res_odds = $use_a_t*10;
            }


            return ['money'=>round($add_t1['money'],2),'odds'=>(10-round($res_odds,2))];

        }

        return ['money'=>0,'odds'=>0];
    }
}



/**
 * 医院订单代理抽水
 * @param $order_id
 * @param string $type  1正常，2用户支付前先减去返利金额，3用户支付后添加返利金额
 * @return bool
 * @throws \think\Exception
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function HosOrderAgentChou($order_id,$type='1'){
    $hospital_order = M('hospital_order');

    $order_info = $hospital_order->where(array('id'=>$order_id))->find();
    if($order_info['status']==4){
        $order_info['money'] = $order_info['total_money'] - $order_info['cost'];//代理结算金额为 总金额 - 成本
    }else{
        $order_info['money'] = $order_info['money'] - $order_info['refund_money'];//代理结算金额为 总金额 - 成本
    }
    if($type=='1'){
        if(!$order_info || $order_info['status']!='4'){
            return;
        }
        if($order_info['money']<=0){
            return;
        }
    }else{
        $order_info['money'] = $order_info['pay_money'];
    }
    $team_money = M('team_money');
    /*$is_chou = $team_money->where(array('order_id'=>$order_id,'type'=>'1'))->find();
    if($is_chou){
        return;
    }*/
    $is_shop = M('shop')->where(array('hospital_id'=>$order_info['parent']))->field('id,odds_type')->find();
    if($is_shop){
        $odds_type = $is_shop['odds_type'];
        //$shop_odds = M('shop_odds')->where(array('shop_id'=>$is_shop['id'],'type'=>'1'))->find();
    }else{
        $odds_type  = 1;
    }

    $user = M('user');
    $set = M('set');
    $team = M('team');
    $user_odds = M('user_odds');
    $odds = M('odds');
    $user_odds_new = M('user_odds_new');
    //默认抽水配置
    $set_list= $set->where(array('type'=>'1'))->select();
    foreach ($set_list as $item){
        $new_set[$item['set_type']] = $item['set_value']/100;
    }
    $is_agent_ticket = false;
    if ($order_info['ticket_id']!=0){
        $have_info = M('ticket_user')->where(array('id'=>$order_info['ticket_id']))->field('parent')->find();
        $ticket_info = M('ticket')->where(array('id'=>$have_info['parent']))->field('type,is_rebate')->find();
        if ($ticket_info['type']=='3'){
            $is_agent_ticket = true;
            if($ticket_info['is_rebate']!='1'){
                return true;
            }
            $ss_set = $set->where(array('set_type'=>'use_agent_ticket'))->find();
            $use_a_t = $ss_set['set_value']/100;
        }
    }
    //dump($ticket_info);die;

    $odds_value = $odds->select();
    $shop_myself = $user_odds_new->where(array('type'=>'4','user_id'=>$is_shop['id']))->select();
    if($shop_myself){
        foreach ($shop_myself as $item){
            $new_shop_self[$item['name']]=$item['odds'];
        }
    }
    foreach ($odds_value as $key=>$item){
        if(array_key_exists($item['name'],$new_shop_self)){
            $odds_value[$key]['odds'] = $new_shop_self[$item['name']];
        }else{
            $admin_odds = $user_odds_new->where(array('type'=>'2','name'=>$item['name'].'_'.$odds_type))->find();
            $odds_value[$key]['odds'] = $admin_odds['odds'];
        }
    }
    $odds_info = array();
    foreach ($odds_value as $item){
        $odds_info[$item['name']] = $item['odds']/100;
    }



    $user_info = $user->where(array('id'=>$order_info['user_id']))->field('id,parent,one,team_id,is_agent,code_user')->find();
    if($user_info['is_agent']=='1' && !$is_agent_ticket ){
        $user_info['parent'] = $user_info['id'];
    }else{
        if($type=='2' || $type=='3'){
            return 0;
        }
    }
    if($user_info['parent'] || $user_info['code_user']){
        if($user_info['parent']){
            $parent_info =  $user->where(array('id'=>$user_info['parent']))->field('id,parent,is_agent,team_id,code_user')->find();
        }else{
            $parent_info =  $user->where(array('id'=>$user_info['code_user']))->field('id,parent,is_agent,team_id,code_user')->find();
        }



        //$parent_odds = $user_odds->where(array('user_id'=>$parent_info['id']))->find();
        if($parent_info['is_agent']=='1'){
            $shang = $user->where(array('id'=>$parent_info['parent']))->field('id,parent,is_agent,team_id')->find();
            //$shang_odds = $user_odds->where(array('user_id'=>$shang['id']))->find();

            $shang_shang = $user->where(array('id'=>$shang['parent']))->field('id,parent,is_agent,team_id')->find();
            //$shang_shang_odds = $user_odds->where(array('user_id'=>$shang_shang['id']))->find();

        /*if ($user_info['is_agent']=='1'){
                $parent_set  = 'xia_rebate';
            }else{
                $parent_set  = 'agent_rebate';
            }*/

            $parent_set  = 'agent_rebate';
            $parent_odds = $user_odds_new->where(array('user_id'=>$parent_info['id'],'type'=>'1','name'=>$parent_set.'_'.$odds_type))->find();
            //下属用户消费返利
            if($parent_odds){
                $add_t1['money'] = $order_info['money'] * ($parent_odds['odds']/100);
                $add_t1['odds']  = $parent_odds['odds'];
            }else{
                $add_t1['money'] = $order_info['money'] * $odds_info[$parent_set];
                $add_t1['odds']  = $odds_info[$parent_set]*100;
            }
            if ($is_agent_ticket){
                $add_t1['money'] = $order_info['money'] * $use_a_t;
                $add_t1['odds']  = $use_a_t*100;

            }

            $add_t1['user_id'] = $parent_info['id'];
            $add_t1['type'] = 1;//1支付给医院，2商城
            $add_t1['order_id'] = $order_id;
            $add_t1['buy_user_id'] = $order_info['user_id'];
            $add_t1['team_id'] = $parent_info['team_id'];
            $add_t1['create_time'] = time();

            if($add_t1['money']==0){
                $add_t1['is_get'] = 1;
                $add_t1['get_time'] = time();
            }

            if($type=='1' || $type=='3'){
                $where_add['user_id'] = $parent_info['id'];
                $where_add['type'] = 1;
                $where_add['order_id'] = $order_id;
                $is_add = $team_money->where($where_add)->field('id')->find();
            }
            if($type=='3'){
                $add_t1['is_da'] = 1;
                $add_t1['is_get'] = 1;
                $add_t1['get_time'] = time();
                if($is_add){
                    return 0;
                }else{
                    $team_money->add($add_t1);
                }
            }else if($type=='2'){
                return $add_t1['money'];
            }else{
                if(!$is_add){
                    $team_money->add($add_t1);
                }
            }

            if(!$is_add){
                //增加个人反水
                $user->where(array('id'=> $parent_info['id']))->setInc('rebate_money',$add_t1['money']);

                //增加团队总反水
                $team->where(array('id'=> $parent_info['team_id']))->setInc('total_money',$add_t1['money']);
                $gudong_list = checkgd($parent_info['id'],$parent_info['team_id']);
                if($gudong_list){
                    $gudong_num = count($gudong_list);
                    foreach ($gudong_list as $item_gd){
                        if($gudong_num>1){
                            $gd_set = 'shareholde_2';
                        }else{
                            $gd_set = 'shareholde_1';
                        }
                        $add_gd['money'] = $order_info['money'] * $new_set[$gd_set];
                        $add_gd['odds']  = $new_set[$gd_set]*100;
                        $add_gd['user_id'] = $item_gd;
                        $add_gd['type'] = 1;//1支付给医院，2商城
                        $add_gd['order_id'] = $order_id;
                        $add_gd['buy_user_id'] = $order_info['user_id'];
                        $add_gd['team_id'] = $parent_info['team_id'];
                        $add_gd['create_time'] = time();
                        if($add_gd['money']==0){
                            $add_gd['is_get'] = 1;
                            $add_gd['get_time'] = time();
                        }
                        $team_money->add($add_gd);
                        //增加个人反水
                        $user->where(array('id'=> $item_gd))->setInc('rebate_money',$add_gd['money']);
                        //增加团队总反水
                        $team->where(array('id'=> $parent_info['team_id']))->setInc('total_money',$add_gd['money']);
                    }
                }

            }


            if($type=='2' || $type=='3'){
                return 0;
            }
            //上级代理抽水
            if($parent_info['parent']){
                if($shang['is_agent']=='1' && $parent_info['team_id'] == $shang['team_id']){
                    /*if ($user_info['is_agent']=='1'){
                        $one_set  = 'xiaixa_rebate';
                    }else{
                        $one_set  = 'xia_rebate';
                    }*/
                    $one_set  = 'xia_rebate';
                    $shang_odds = $user_odds_new->where(array('user_id'=>$shang['id'],'type'=>'1','name'=>$one_set.'_'.$odds_type))->find();
                    if($shang_odds){
                        $add_t2['money'] = $order_info['money'] * ($shang_odds['odds']/100);
                        $add_t2['odds']  = $shang_odds['odds'];
                    }else{
                        $add_t2['money'] = $order_info['money'] * $odds_info[$one_set];
                        $add_t2['odds']  = $odds_info[$one_set]*100;
                    }

                    if ($is_agent_ticket){
                        $add_t2['money'] = $order_info['money'] * $use_a_t;
                        $add_t2['odds']  = $use_a_t*100;
                    }

                    /*if($shop_odds && $shop_odds[$one_set]!=NULL){
                        $add_t2['money'] = $order_info['money'] * ($shop_odds[$one_set]/100);
                        $add_t2['odds']  = $shop_odds[$one_set];
                    }*/

             /*       if ($shang['parent']){
                        if($shang_shang['is_agent']=='1' && $shang['team_id'] == $shang_shang['team_id']){
                            if ($shang_shang_odds && $shang_shang_odds['next_gd']!=NULL){
                                $add_t2['money'] = $order_info['money'] * ($shang_shang_odds['next_gd']/100);
                                $add_t2['odds']  = $shang_shang_odds['next_gd'];
                            }
                        }
                    }*/

                    $add_t2['user_id'] = $shang['id'];
                    $add_t2['type'] = 1;//1支付给医院，2商城
                    $add_t2['order_id'] = $order_id;
                    $add_t2['buy_user_id'] = $order_info['user_id'];
                    $add_t2['team_id'] = $shang['team_id'];
                    $add_t2['create_time'] = time();
                    if($add_t2['money']==0){
                        $add_t2['is_get'] = 1;
                        $add_t2['get_time'] = time();
                    }
                    $where_add2['user_id'] = $shang['id'];
                    $where_add2['type'] = 1;//1支付给医院，2商城
                    $where_add2['order_id'] = $order_id;
                    $is_add2 = $team_money->where($where_add2)->field('id')->find();
                    if(!$is_add2){
                        $team_money->add($add_t2);
                        //增加个人反水
                        $user->where(array('id'=> $shang['id']))->setInc('rebate_money',$add_t2['money']);
                        //增加团队总反水
                        $team->where(array('id'=> $parent_info['team_id']))->setInc('total_money',$add_t2['money']);
                    }

                    //上上级代理抽水
                    if ($shang['parent'] && $is_agent_ticket==false){
                        if($shang_shang['is_agent']=='1' && $shang['team_id'] == $shang_shang['team_id']){

                            $shang_shang_odds = $user_odds_new->where(array('user_id'=>$shang_shang['id'],'type'=>'1','name'=>'xiaixa_rebate_'.$odds_type))->find();
                            if($shang_shang_odds){
                                $add_t3['money'] = $order_info['money'] * ($shang_shang_odds['odds']/100);
                                $add_t3['odds']  = $shang_shang_odds['odds'];
                            }else{
                                $add_t3['money'] = $order_info['money'] * $odds_info['xiaixa_rebate'];
                                $add_t3['odds']  = $odds_info['xiaixa_rebate']*100;
                            }

                            if ($is_agent_ticket){
                                $add_t3['money'] = $order_info['money'] * $use_a_t;
                                $add_t3['odds']  = $use_a_t*100;
                            }
                           /* if($shop_odds && $shop_odds['xiaixa_rebate']!=NULL){
                                $add_t3['money'] = $order_info['money'] * ($shop_odds['xiaixa_rebate']/100);
                                $add_t3['odds']  = $shop_odds['xiaixa_rebate'];
                            }*/
                            $add_t3['user_id'] = $shang_shang['id'];
                            $add_t3['type'] = 1;//1支付给医院，2商城
                            $add_t3['order_id'] = $order_id;
                            $add_t3['buy_user_id'] = $order_info['user_id'];
                            $add_t3['team_id'] = $shang_shang['team_id'];
                            $add_t3['create_time'] = time();
                            if($add_t3['money']==0){
                                $add_t3['is_get'] = 1;
                                $add_t3['get_time'] = time();
                            }
                            $where_add3['user_id'] = $shang_shang['id'];
                            $where_add3['type'] = 1;//1支付给医院，2商城
                            $where_add3['order_id'] = $order_id;
                            $is_add3 = $team_money->where($where_add3)->field('id')->find();
                            if(!$is_add3){
                                $team_money->add($add_t3);
                                //增加个人反水
                                $user->where(array('id'=> $shang_shang['id']))->setInc('rebate_money',$add_t3['money']);
                                //增加团队总反水
                                $team->where(array('id'=> $parent_info['team_id']))->setInc('total_money',$add_t3['money']);
                            }
                        }
                    }
                }
            }

        }else{
            if($type=='2' || $type=='3'){
                return 0;
            }
                if ($parent_info){
                    //$parent_info =  $user->where(array('id'=>$user_info['code_user']))->field('id,parent,is_agent,team_id')->find();
                    //$parent_odds = $user_odds->where(array('user_id'=>$parent_info['id']))->find();
                    //邀请好友消费返利

                    $parent_odds = $user_odds_new->where(array('user_id'=>$parent_info['id'],'type'=>'1','name'=>'friend_rebate_'.$odds_type))->find();
                    if($parent_odds){
                        $add_t1['money'] = $order_info['money'] * ($parent_odds['odds']/100);
                        $add_t1['odds']  = $parent_odds['odds'];
                    }else{
                        $add_t1['money'] = $order_info['money'] * $odds_info['friend_rebate'];
                        $add_t1['odds']  = $odds_info['friend_rebate']*100;
                    }
                    if ($is_agent_ticket){
                        $add_t1['money'] = $order_info['money'] * $use_a_t;
                        $add_t1['odds']  = $use_a_t*100;
                    }


                   /* if($shop_odds && $shop_odds['friend_rebate']!=NULL){
                        $add_t1['money'] = $order_info['money'] * ($shop_odds['friend_rebate']/100);
                        $add_t1['odds']  = $shop_odds['friend_rebate'];
                    }*/

                    $add_t1['user_id'] = $parent_info['id'];
                    $add_t1['type'] = 1;//1支付给医院，2商城
                    $add_t1['order_id'] = $order_id;
                    $add_t1['buy_user_id'] = $order_info['user_id'];
                    $add_t1['create_time'] = time();
                    if($add_t1['money']==0){
                        $add_t1['is_get'] = 1;
                        $add_t1['get_time'] = time();
                    }
                    $where_add1['user_id'] = $parent_info['id'];
                    $where_add1['type'] = 1;//1支付给医院，2商城
                    $where_add1['order_id'] = $order_id;
                    $is_add1 = $team_money->where($where_add1)->field('id')->find();
                    if(!$is_add1){
                        $team_money->add($add_t1);
                        //增加个人反水
                        $user->where(array('id'=> $parent_info['id']))->setInc('rebate_money',$add_t1['money']);
                    }


                    if($parent_info['code_user']){
                        $code_user_info  = $user->where(array('id'=>$parent_info['code_user']))->field('id,is_agent,team_id')->find();
                        if($code_user_info['is_agent']=='1'){
                            //$code_user_odds = $user_odds->where(array('user_id'=>$code_user_info['id']))->find();

                            $code_user_odds = $user_odds_new->where(array('user_id'=>$code_user_info['id'],'type'=>'1','name'=>'hos_xia_friend_'.$odds_type))->find();

                            if($code_user_odds ){
                                $add_t2['money'] = $order_info['money'] * ($code_user_odds['odds']/100);
                                $add_t2['odds']  = $code_user_odds['odds'];
                            }else{
                                $add_t2['money'] = $order_info['money'] * $odds_info['hos_xia_friend'];
                                $add_t2['odds']  = $odds_info['hos_xia_friend']*100;
                            }
                           /* if($shop_odds && $shop_odds['hos_xia_friend']!=NULL){
                                $add_t2['money'] = $order_info['money'] * ($shop_odds['hos_xia_friend']/100);
                                $add_t2['odds']  = $shop_odds['hos_xia_friend'];
                            }*/
                            $add_t2['user_id'] = $code_user_info['id'];
                            $add_t2['type'] = 1;//1支付给医院，2商城
                            $add_t2['order_id'] = $order_id;
                            $add_t2['buy_user_id'] = $order_info['user_id'];
                            $add_t2['create_time'] = time();
                            if($add_t2['money']==0){
                                $add_t2['is_get'] = 1;
                                $add_t2['get_time'] = time();
                            }
                            $where_add2['user_id'] = $code_user_info['id'];
                            $where_add2['type'] = 1;//1支付给医院，2商城
                            $where_add2['order_id'] = $order_id;
                            $is_add2 = $team_money->where($where_add2)->field('id')->find();
                            if(!$is_add2){
                                $team_money->add($add_t2);
                                //增加个人反水
                                $user->where(array('id'=> $code_user_info['id']))->setInc('rebate_money',$add_t2['money']);
                                //增加团队总反水
                                $team->where(array('id'=> $code_user_info['team_id']))->setInc('total_money',$add_t2['money']);

                            }
                        }
                    }
                }
        }

        return true;
    }else{


        return true;
    }
}




/**
 * 商城订单代理抽水
 * @param $order_id
 * @param string $type   1正常，2用户支付前先减去返利金额，3用户支付后添加返利金额
 * @return bool
 * @throws \think\Exception
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function ShopOrderAgentChou($order_id,$type='1'){
    $shop_order = M('shop_order');
    $shop_goods = M('shop_goods');
    $order_info = $shop_order->where(array('id'=>$order_id))->find();
    $goods_info = $shop_goods->where(array('id'=>$order_info['goods_id']))->field('is_fan,is_fan_my')->find();
    if($goods_info['is_fan']!=1){
        return;
    }
    if($type=='1'){
        if(!$order_info || $order_info['status']!='5'){
            return;
        }
    }else{
        $order_info['money'] = $order_info['total_money'];
    }

    $team_money = M('team_money');
    /*$is_chou = $team_money->where(array('order_id'=>$order_id,'type'=>'2'))->find();
    if($is_chou){
        return;
    }*/

    $shop_info = M('shop')->where(array('id'=>$order_info['shop_id']))->field('id,odds_type')->find();
    if($shop_info['odds_type']){
        $odds_type = $shop_info['odds_type'];
    }else{
        $odds_type = 1;
    }

 /*   $shop_odds = M('shop_odds')->where(array('shop_id'=>$order_info['goods_id'],'type'=>'2'))->find();
    if(!$shop_odds){
        $shop_odds = M('shop_odds')->where(array('shop_id'=>$order_info['shop_id'],'type'=>'1'))->find();
    }*/

    $user = M('user');
    $set = M('set');
    $team = M('team');
    $odds = M('odds');
    $user_odds = M('user_odds');
    $user_odds_new = M('user_odds_new');
    //默认抽水配置
    $set_list= $set->where(array('type'=>'1'))->select();
    foreach ($set_list as $item){
        $new_set[$item['set_type']] = $item['set_value']/100;
    }

    $odds_value = $odds->where(array('id'=>array('IN','1,2,3,4')))->select();
    $odds_goods_shop = array();
    $odds_myself = $user_odds_new->where(array('type'=>'3','user_id'=>$order_info['goods_id']))->select();
    if($odds_myself){
        foreach ($odds_myself as $item){
            $new_self[$item['name']]=$item['odds'];
            $odds_goods_shop[$item['name']] = $item['odds']/100;
        }
    }

    $shop_myself = $user_odds_new->where(array('type'=>'4','user_id'=>$order_info['shop_id']))->select();
    if($shop_myself){
        foreach ($shop_myself as $item){
            $new_shop_self[$item['name']]=$item['odds'];
        }
    }

    foreach ($odds_value as $key=>$item){
        if(array_key_exists($item['name'],$new_self)){
            $odds_value[$key]['odds'] = $new_self[$item['name']];
        }else{
            if(array_key_exists($item['name'],$new_shop_self)){
                $odds_value[$key]['odds'] = $new_shop_self[$item['name']];
            }else{
                $admin_odds = $user_odds_new->where(array('type'=>'2','name'=>$item['name'].'_'.$odds_type))->find();
                $odds_value[$key]['odds'] = $admin_odds['odds'];
            }
        }
    }

    $odds_info = array();
    foreach ($odds_value as $item){
        $odds_info[$item['name']] = $item['odds']/100;
    }

    $user_info = $user->where(array('id'=>$order_info['user_id']))->field('id,parent,one,team_id,is_agent,code_user')->find();
    if($user_info['is_agent']=='1'){
        $user_info['parent'] = $user_info['id'];
    }else{

        if($type=='2' || $type=='3'){
            return 0;
        }
    }

    if($user_info['parent'] || $user_info['code_user']){

        if($user_info['parent']){
            $parent_info =  $user->where(array('id'=>$user_info['parent']))->field('id,parent,is_agent,team_id')->find();
        }else{
            $parent_info =  $user->where(array('id'=>$user_info['code_user']))->field('id,parent,is_agent,team_id')->find();
        }

        //$parent_odds = $user_odds->where(array('user_id'=>$parent_info['id']))->find();
        if($parent_info['is_agent']=='1'){

            $shang = $user->where(array('id'=>$parent_info['parent']))->field('id,parent,is_agent,team_id')->find();
            //$shang_odds = $user_odds->where(array('user_id'=>$shang['id']))->find();

            $shang_shang = $user->where(array('id'=>$shang['parent']))->field('id,parent,is_agent,team_id')->find();
            //$shang_shang_odds = $user_odds->where(array('user_id'=>$shang_shang['id']))->find();
            //下属用户消费返利
            /*if ($user_info['is_agent']=='1'){
                $parent_set  = 'shop_xia_rebate';
            }else{
                $parent_set  = 'shop_agent_rebate';
            }*/
            $parent_set  = 'agent_rebate';

            if($odds_goods_shop[$parent_set]){
                $add_t1['money'] = $order_info['money'] * $odds_goods_shop[$parent_set];
                $add_t1['odds']  = $odds_goods_shop[$parent_set]*100;
                $return_odds =  $odds_goods_shop[$parent_set]*10;
            }else{
                $parent_odds = $user_odds_new->where(array('user_id'=>$parent_info['id'],'type'=>'1','name'=>$parent_set.'_'.$odds_type))->find();
                if($parent_odds){
                    $add_t1['money'] = $order_info['money'] * ($parent_odds['odds']/100);
                    $add_t1['odds']  = $parent_odds['odds'];
                    $return_odds =  $parent_odds['odds']/10;
                }else{
                    $add_t1['money'] = $order_info['money'] * $odds_info[$parent_set];
                    $add_t1['odds']  = $odds_info[$parent_set]*100;
                    $return_odds = $odds_info[$parent_set]*10;
                }
            }

            /*if($shop_odds && $shop_odds[$parent_set]!=NULL){
                $add_t1['money'] = $order_info['money'] * ($shop_odds[$parent_set]/100);
                $add_t1['odds']  = $shop_odds[$parent_set];
            }*/


     /*       if ($parent_info['parent']){
                if($shang['is_agent']=='1' && $parent_info['team_id'] == $shang['team_id']){
                    if ($shang_odds && $shang_odds['shop_next']!=NULL){
                        $add_t1['money'] = $order_info['money'] * ($shang_odds['shop_next']/100);
                        $add_t1['odds']  = $shang_odds['shop_next'];
                    }
                }
            }

            if ($shang['parent']){
                if($shang_shang['is_agent']=='1' && $shang['team_id'] == $shang_shang['team_id']){
                    if ($shang_shang_odds && $shang_shang_odds['shop_nextnext']!=NULL){
                        $add_t1['money'] = $order_info['money'] * ($shang_shang_odds['shop_nextnext']/100);
                        $add_t1['odds']  = $shang_shang_odds['shop_nextnext'];
                    }
                }
            }*/

            $add_t1['money'] = round($add_t1['money'],2);
            $add_t1['user_id'] = $parent_info['id'];
            $add_t1['type'] = 2;//1支付给医院，2商城
            $add_t1['order_id'] = $order_id;
            $add_t1['buy_user_id'] = $order_info['user_id'];
            $add_t1['team_id'] = $parent_info['team_id'];
            $add_t1['create_time'] = time();

            if($add_t1['money']==0){
                $add_t1['is_get'] = 1;
                $add_t1['get_time'] = time();
            }
            if($type=='1' || $type=='3'){
                $where_add['user_id'] = $parent_info['id'];
                $where_add['type'] = 2;
                $where_add['order_id'] = $order_id;
                $is_add = $team_money->where($where_add)->field('id')->find();
                if($goods_info['is_fan_my']=='1'){
                    if($user_info['parent']==$user_info['id']){
                        $is_add = true;
                    }
                }
            }
            if($type=='3'){
                $add_t1['is_da'] = 1;
                $add_t1['is_get'] = 1;
                $add_t1['get_time'] = time();
                if($is_add){
                    return ['money'=>0,'odds'=>0];
                }else{
                    $team_money->add($add_t1);
                }
            }else if($type=='2'){
                return ['money'=>$add_t1['money'],'odds'=>round($return_odds,2)];
            }else{
                if(!$is_add){
                    $team_money->add($add_t1);
                }
            }
            if(!$is_add){
                //增加个人反水
                $user->where(array('id'=> $parent_info['id']))->setInc('rebate_money',$add_t1['money']);
                //增加团队总反水
                $team->where(array('id'=> $parent_info['team_id']))->setInc('total_money',$add_t1['money']);

                $gudong_list = checkgd($parent_info['id'],$parent_info['team_id']);
                if($gudong_list){
                    $gudong_num = count($gudong_list);
                    foreach ($gudong_list as $item_gd){
                        if($gudong_num>1){
                            $gd_set = 'shareholde_2';
                        }else{
                            $gd_set = 'shareholde_1';
                        }
                        $add_gd['money'] = $order_info['money'] * $new_set[$gd_set];
                        $add_gd['odds']  = $new_set[$gd_set]*100;
                        $add_gd['user_id'] = $item_gd;
                        $add_gd['type'] = 2;//1支付给医院，2商城
                        $add_gd['order_id'] = $order_id;
                        $add_gd['buy_user_id'] = $order_info['user_id'];
                        $add_gd['team_id'] = $parent_info['team_id'];
                        $add_gd['create_time'] = time();
                        if($add_gd['money']==0){
                            $add_gd['is_get'] = 1;
                            $add_gd['get_time'] = time();
                        }
                        $team_money->add($add_gd);
                        //增加个人反水
                        $user->where(array('id'=> $item_gd))->setInc('rebate_money',$add_gd['money']);
                        //增加团队总反水
                        $team->where(array('id'=> $parent_info['team_id']))->setInc('total_money',$add_gd['money']);
                    }
                }
            }


            if($type=='2' || $type=='3'){
                return 0;
            }

            //上级代理抽水
            if($parent_info['parent']){
                if($shang['is_agent']=='1' && $parent_info['team_id'] == $shang['team_id']){
                   /* if ($user_info['is_agent']=='1'){
                        $one_set  = 'shop_xiaixa_rebate';
                    }else{
                        $one_set  = 'shop_xia_rebate';
                    }*/
                    $one_set  = 'xia_rebate';

                    if($odds_goods_shop[$one_set]){
                        $add_t2['money'] = $order_info['money'] * $odds_goods_shop[$one_set];
                        $add_t2['odds']  = $odds_goods_shop[$one_set]*100;
                    }else{
                        $shang_odds = $user_odds_new->where(array('user_id'=>$shang['id'],'type'=>'1','name'=>$one_set.'_'.$odds_type))->find();
                        if($shang_odds){
                            $add_t2['money'] = $order_info['money'] * ($shang_odds['odds']/100);
                            $add_t2['odds']  = $shang_odds['odds'];
                        }else{
                            $add_t2['money'] = $order_info['money'] * $odds_info[$one_set];
                            $add_t2['odds']  = $odds_info[$one_set]*100;
                        }
                    }


                    /*if($shop_odds && $shop_odds[$one_set]!=NULL){
                        $add_t2['money'] = $order_info['money'] * ($shop_odds[$one_set]/100);
                        $add_t2['odds']  = $shop_odds['shop_xia_rebate'];
                    }*/

                 /*   if ($shang['parent']){
                        if($shang_shang['is_agent']=='1' && $shang['team_id'] == $shang_shang['team_id']){
                            if ($shang_shang_odds && $shang_shang_odds['shop_next_gd']!=NULL){
                                $add_t2['money'] = $order_info['money'] * ($shang_shang_odds['shop_next_gd']/100);
                                $add_t2['odds']  = $shang_shang_odds['shop_next_gd'];
                            }
                        }
                    }*/

                    $add_t2['user_id'] = $shang['id'];
                    $add_t2['type'] = 2;//1支付给医院，2商城
                    $add_t2['order_id'] = $order_id;
                    $add_t2['buy_user_id'] = $order_info['user_id'];
                    $add_t2['team_id'] = $shang['team_id'];
                    $add_t2['create_time'] = time();
                    if($add_t2['money']==0){
                        $add_t2['is_get'] = 1;
                        $add_t2['get_time'] = time();
                    }
                    $where_add2['user_id'] = $shang['id'];
                    $where_add2['type'] = 2;//1支付给医院，2商城
                    $where_add2['order_id'] = $order_id;
                    $is_add2 = $team_money->where($where_add2)->field('id')->find();
                    if(!$is_add2){
                        $team_money->add($add_t2);
                        //增加个人反水
                        $user->where(array('id'=> $shang['id']))->setInc('rebate_money',$add_t2['money']);
                        //增加团队总反水
                        $team->where(array('id'=> $parent_info['team_id']))->setInc('total_money',$add_t2['money']);
                    }

                    //上上级代理抽水
                    if ($shang['parent']){

                        if($shang_shang['is_agent']=='1' && $shang['team_id'] == $shang_shang['team_id']){

                            if($odds_goods_shop['xiaixa_rebate']){
                                $add_t3['money'] = $order_info['money'] * $odds_goods_shop['xiaixa_rebate'];
                                $add_t3['odds']  = $odds_goods_shop['xiaixa_rebate']*100;
                            }else{
                                $shang_shang_odds = $user_odds_new->where(array('user_id'=>$shang_shang['id'],'type'=>'1','name'=>'xiaixa_rebate_'.$odds_type))->find();
                                if($shang_shang_odds){
                                    $add_t3['money'] = $order_info['money'] * ($shang_shang_odds['odds']/100);
                                    $add_t3['odds']  = $shang_shang_odds['odds'];
                                }else{
                                    $add_t3['money'] = $order_info['money'] * $odds_info['xiaixa_rebate'];
                                    $add_t3['odds']  = $odds_info['xiaixa_rebate']*100;
                                }
                            }

                           /* if($shop_odds && $shop_odds['shop_xiaixa_rebate']!=NULL){
                                $add_t3['money'] = $order_info['money'] * ($shop_odds['shop_xiaixa_rebate']/100);
                                $add_t3['odds']  = $shop_odds['shop_xiaixa_rebate'];
                            }*/
                            $add_t3['user_id'] = $shang_shang['id'];
                            $add_t3['type'] = 2;//1支付给医院，2商城
                            $add_t3['order_id'] = $order_id;
                            $add_t3['buy_user_id'] = $order_info['user_id'];
                            $add_t3['team_id'] = $shang_shang['team_id'];
                            $add_t3['create_time'] = time();
                            if($add_t3['money']==0){
                                $add_t3['is_get'] = 1;
                                $add_t3['get_time'] = time();
                            }
                            $where_add3['user_id'] = $shang_shang['id'];
                            $where_add3['type'] = 2;//1支付给医院，2商城
                            $where_add3['order_id'] = $order_id;
                            $is_add3 = $team_money->where($where_add3)->field('id')->find();
                            if(!$is_add3){
                                $team_money->add($add_t3);
                                //增加个人反水
                                $user->where(array('id'=> $shang_shang['id']))->setInc('rebate_money',$add_t3['money']);
                                //增加团队总反水
                                $team->where(array('id'=> $parent_info['team_id']))->setInc('total_money',$add_t3['money']);
                            }
                        }
                    }
                }
            }

        }else{
            if($type=='2' || $type=='3'){
                return 0;
            }
                if ($parent_info) {
                    //$parent_info = $user->where(array('id' => $user_info['code_user']))->field('id,parent,is_agent,team_id')->find();
                    //$parent_odds = $user_odds->where(array('user_id' => $parent_info['id']))->find();
                    //邀请好友消费返利
                    if($odds_goods_shop['friend_rebate']){
                        $add_t1['money'] = $order_info['money'] * $odds_goods_shop['friend_rebate'];
                        $add_t1['odds']  = $odds_goods_shop['friend_rebate']*100;
                    }else{
                        $parent_odds = $user_odds_new->where(array('user_id'=>$parent_info['id'],'type'=>'1','name'=>'friend_rebate_'.$odds_type))->find();

                        if($parent_odds){
                            $add_t1['money'] = $order_info['money'] * ($parent_odds['odds']/100);
                            $add_t1['odds']  = $parent_odds['odds'];
                        }else{
                            $add_t1['money'] = $order_info['money'] * $odds_info['friend_rebate'];
                            $add_t1['odds']  = $odds_info['friend_rebate']*100;
                        }
                    }

                    /*if($shop_odds && $shop_odds['shop_friend_rebate']!=NULL){
                        $add_t1['money'] = $order_info['money'] * ($shop_odds['shop_friend_rebate']/100);
                        $add_t1['odds']  = $shop_odds['shop_friend_rebate'];
                    }*/
                    $add_t1['user_id'] = $parent_info['id'];
                    $add_t1['type'] = 2;//1支付给医院，2商城
                    $add_t1['order_id'] = $order_id;
                    $add_t1['buy_user_id'] = $order_info['user_id'];
                    $add_t1['create_time'] = time();
                    if($add_t1['money']==0){
                        $add_t1['is_get'] = 1;
                        $add_t1['get_time'] = time();
                    }
                    $where_add1['user_id'] = $parent_info['id'];
                    $where_add1['type'] = 2;//1支付给医院，2商城
                    $where_add1['order_id'] = $order_id;
                    $is_add1 = $team_money->where($where_add1)->field('id')->find();
                    if(!$is_add1){
                        $team_money->add($add_t1);
                        //增加个人反水
                        $user->where(array('id'=> $parent_info['id']))->setInc('rebate_money',$add_t1['money']);
                    }
            }

        }

        return true;
    }else{



        return true;
    }
}

function checkgd($user_id,$team_id,$arr=array()){
    $user = M('user');
    $user_info = $user->where(array('id'=>$user_id,'team_id'=>$team_id))->field('id,parent,is_gudong,team_id')->find();
    if($user_info['is_gudong']=='1'){
        $arr[] = $user_info['id'];
    }
    if(!$user_info){
        return $arr;
    }

    if($user_info['parent']!='0'){
        return checkgd($user_info['parent'],$team_id,$arr);
    }else{
        return $arr;
    }
}

/**
 *生成二维码
 * 参数详情：
 *      $qrcode_path:logo地址
 *      $content:需要生成二维码的内容
 *      $matrixPointSize:二维码尺寸大小
 *      $matrixMarginSize:生成二维码的边距
 *      $errorCorrectionLevel:容错级别
 *      $url:生成的带logo的二维码地址
 * */
function makecode($content,$qrcode_path){

    $is_img = getImagetype($qrcode_path);

    $matrixPointSize = 10;
    $matrixMarginSize = 1;
    $errorCorrectionLevel = 'H';



    $mk = date('Y-m-d');
    $path="Public/Uploads/qrcode/".$mk."/";
    //判断目录存在否，存在给出提示，不存在则创建目录
    if (is_dir($path)){
        //echo "对不起！目录 " . $path . " 已经存在！";
    }else{
        //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
        $res=mkdir(iconv("UTF-8", "GBK", $path),0777,true);
        if ($res){
            //echo "目录 $path 创建成功";
        }else{
            //echo "目录 $path 创建失败";
        }
    }

    $file_name = $path.'qr_'.date("Ymdhis").rand(100000,999999).'.png';
    $file_name_logo = $path.'qr_logo_'.date("Ymdhis").rand(100000,999999).'.png';


    ob_clean ();
    Vendor('phpqrcode.phpqrcode');
    $object = new \QRcode();
    $qrcode_path_new = $file_name;//定义生成二维码的路径及名称
    $object::png($content,$qrcode_path_new, $errorCorrectionLevel, $matrixPointSize, $matrixMarginSize);
    if ($is_img){
        $QR = imagecreatefromstring(file_get_contents($qrcode_path_new));//imagecreatefromstring:创建一个图像资源从字符串中的图像流
        $logo = imagecreatefromstring(file_get_contents($qrcode_path));
        //imagetruecolortopalette($logo, false, 65535);//添加这行代码来解决颜色失真问题


        $QR_width = imagesx($QR);// 获取图像宽度函数
        $QR_height = imagesy($QR);//获取图像高度函数
        $logo_width = imagesx($logo);// 获取图像宽度函数
        $logo_height = imagesy($logo);//获取图像高度函数
        $logo_qr_width = $QR_width / 4;//logo的宽度
        $scale = $logo_width / $logo_qr_width;//计算比例
        $logo_qr_height = $logo_height / $scale;//计算logo高度
        $from_width = ($QR_width - $logo_qr_width) / 2;//规定logo的坐标位置
        imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        /**     imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
         *      参数详情：
         *      $dst_image:目标图象连接资源。
         *      $src_image:源图象连接资源。
         *      $dst_x:目标 X 坐标点。
         *      $dst_y:目标 Y 坐标点。
         *      $src_x:源的 X 坐标点。
         *      $src_y:源的 Y 坐标点。
         *      $dst_w:目标宽度。
         *      $dst_h:目标高度。
         *      $src_w:源图象的宽度。
         *      $src_h:源图象的高度。
         * */
        //Header("Content-type: image/png");

        //$url:定义生成带logo的二维码的地址及名称
        $a = imagepng($QR,$file_name_logo);
        return XUANIMG.'/'.$file_name_logo;

    }else{
        return XUANIMG.'/'.$file_name;
    }
}


//*判断图片上传格式是否为图片 return返回文件后缀
function getImagetype($filename)
{
    $file = fopen($filename, 'rb');
    $bin  = fread($file, 2); //只读2字节
    fclose($file);
    $strInfo  = @unpack('C2chars', $bin);
    $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
    // dd($typeCode);
    $fileType = false;
    switch ($typeCode) {
        case 255216:
            //$fileType = 'jpg';
            $fileType = true;
            break;
        case 7173:
            //$fileType = 'gif';
            $fileType = false;
            break;
        case 6677:
            //$fileType = 'bmp';
            $fileType = false;
            break;
        case 13780:
            //$fileType = 'png';
            $fileType = true;
            break;
        default:
            $fileType = false;
            break;
    }
    // if ($strInfo['chars1']=='-1' AND $strInfo['chars2']=='-40' ) return 'jpg';
    // if ($strInfo['chars1']=='-119' AND $strInfo['chars2']=='80' ) return 'png';
    return $fileType;
}


function getRandomString($len, $chars=null)
{
    if (is_null($chars)) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    }
    mt_srand(10000000*(double)microtime());
    for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++) {
        $str .= $chars[mt_rand(0, $lc)];
    }
    $str2 = 'smq'.$str;
    $is_have = M('app')->where(array('key'=>$str2))->find();
    if ($is_have){
        getRandomString($len);
    }else{
        return $str2;
    }
}

/**
 * 判断身份证有效性
 * @param $number
 * @return bool
 */
function isIdCard($number) {
    //加权因子
    $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
    //校验码串
    $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    //按顺序循环处理前17位
    for ($i = 0;$i < 17;$i++) {
        //提取前17位的其中一位，并将变量类型转为实数
        $b = (int) $number{$i};

        //提取相应的加权因子
        $w = $wi[$i];

        //把从身份证号码中提取的一位数字和加权因子相乘，并累加
        $sigma += $b * $w;
    }
    //计算序号
    $snumber = $sigma % 11;

    //按照序号从校验码串中提取相应的字符。
    $check_number = $ai[$snumber];

    if ($number{17} == $check_number) {
        return true;
    } else {
        return false;
    }
}

/**
 * 判断中文姓名(UTF-8,2-4位)
 * @param $name
 * @return bool
 */
function isChineseName($name){
	if (preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/', $name)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 返回IM号
 * @return bool|int
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function GetImId(){
    $imid = rand(10000000,99999999);
    $is_have = M('user')->where(array('imid'=>$imid))->field('id')->find();
    if ($is_have){
        GetImId();
        return false;
    }
    return $imid;
}

/**
 * 返回邀请码
 * @param $user_id
 * @return bool|int
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function GetAddCode($user_id){
    $code = M('code');
    $rand = rand(100000,999999);

    $is_have = $code->where(array('number'=>$rand,'is_open'=>1))->find();
    if ($is_have){
        GetAddCode($user_id);
        return false;
    }else{
        $is_user_have = $code->where(array('number'=>$rand,'user_id'=>$user_id))->find();
        if ($is_user_have){
            GetAddCode($user_id);
            return false;
        }
    }
    return $rand;
}

function OrderSendSms($phone,$type,$money_type,$order_number,$goods_name){

    if ($type=='1'){
        $content = "您已预约成功，订单编号为：".$order_number."【小时光】";
    }else{
        //$content = "您的店铺有新的预约，订单编号为：".$order_number."【小时光】";
        $content = "尊敬的用户，您好:您已有新的订单（套餐名:".$goods_name."，订单ID:".$order_number."），请您打开手机登陆小时光进行查收确认，祝您生活愉快，谢谢【小时光】";
    }

    $uid = "2255";
    $passwd = "7fdf6096659ff66beaba43046368a020";

    $url = "http://sms.bamikeji.com:8890/mtPort/mt/normal/send?uid=".$uid."&passwd=".$passwd."&phonelist=".$phone."&content=".$content;
    $html = file_get_contents($url);
    $result = json_decode($html,true);
    //$con= substr( file_get_contents($url), 0, 1 );  //获取信息发送后的状态
    return true;
}
/**
 * 新建获取群id
 * @return int
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function GetGroupID(){
    $group = M('group');
    $check_id = rand(10000,99999999);
    $is_have = $group->where(array('id'=>$check_id))->field('id')->find();
    if ($is_have){
        GetGroupID();
    }else{
        return $check_id;
    }
}


/**
 * 新建客服获取用户10000以下id
 * @return int
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function GetUserID(){
    $group = M('user');
    $check_id = rand(1,9999);
    $is_have = $group->where(array('id'=>$check_id))->field('id')->find();
    if ($is_have){
        GetUserID();
    }else{
        return $check_id;
    }
}



/**
 * 结算订单
 * @return bool
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function AccountOrder(){
    $user = M('user');
    $order = M('order');
    $shop = M('shop');
    $set_info = M('set')->where(array('set_type'=>'gathering'))->find();
    $where['pay_status'] = 1;
    $where['is_ti'] = 0;
    $where['status'] = array('IN','4,5');
    if ($set_info['set_value']>0){
        $a_time = strtotime("-".$set_info['set_value']." day");
        $where['ok_time'] = array('ELT',$a_time);
    }
    $order_list = $order->order('create_time')->limit(10)->where($where)->field('id,shop_id,money')->select();

    foreach ($order_list as $item){
        $shop_info = $shop->where(array('id'=>$item['shop_id']))->field('user_id')->find();
        if ($shop_info){
            $shop_user = $user->where(array('id'=>$shop_info['user_id']))->field('id,money')->find();
            if ($shop_user){

                //计算可得金额
                if ($set_info['remark']>0){
                    $jian_moeny = $item['money']*($set_info['remark']/100);
                    $jian_moeny2 = sprintf("%.2f",$jian_moeny);
                    $add_moeny = $item['money']-$jian_moeny2;;
                }else{
                    $add_moeny = $item['money'];
                }
                $is_save = $order->where(array('id'=>$item['id']))
                    ->save(array('is_ti'=>'1','ti_time'=>time(),'ti_odds'=>$set_info['remark'],'ti_money'=>$jian_moeny2));

                if ($is_save){


                    $new_money = $shop_user['money']+$add_moeny;

                    $is_save_user = $user->where(array('id'=>$shop_info['user_id']))->save(array('money'=>$new_money,'update_time'=>time()));

                    if($is_save_user){
                        //金额交易记录
                        $add_money['user_id'] = $shop_info['user_id'];
                        $add_money['money'] = $add_moeny;

                        $add_money['type'] = 8;

                        $add_money['add_or_del'] = 1;//1增加，2减去
                        $add_money['pay_type'] = 4;//1微信，2支付宝，3银联，4余额
                        $add_money['create_time'] = time();
                        $add_money['order_id'] = $item['id'];
                        $add_money['create_type'] = 2;//1管理员操作，2系统操作，3用户操作，4业务员操作
                        M('money_log')->add($add_money);
                        //金额交易记录
                    }else{
                        $order->where(array('id'=>$item['id']))->save(array('is_ti'=>'0','ti_time'=>'0','ti_odds'=>'0'));
                    }
                }

            }

        }

    }
    return true;
}
function addvtorandp($array,$position,$value){
    $tmp=array();
    for($i=0;$i<=count($array);$i++){
        if($i==$position){
            $tmp[$position]=$value;
        }elseif($i<$position){
            $tmp[$i]=$array[$i];
        }else{
            $tmp[$i]=$array[$i-1];
        }
    }
    return $tmp;
}


//增加积分,用户id，用户积分，用户等级,增分类型，对应typeid（帖子id或评论id）
function AddScore($user_id,$user_score,$user_lv,$type,$type_id = 0,$zx_user=0,$get_num = 0){
    $level = M('level');
    $level_log = M('level_log');
    $level_type = M('level_type');
    $user = M('user');


    $today = strtotime(date('Y-m-d'));

    $lv_count = $level_log->where(array('create_time'=>array('EGT',$today),'user_id'=>$user_id,'type'=>$type))->count();
    $lv_type = $level_type->where(array('tid'=>$type))->find();
    if($lv_count<$lv_type['max'] || $lv_type['max']==0 || $type=='0'){

        if ($type=='0'){
            //购买商品获得积分
            $set_info = M('set')->where(array('set_type'=>'buy_gooods_score'))->find();
            $odds = $set_info['set_value']/100;

            $order_info = M('shop_order')->where(array('id'=>$type_id))->field('total_money')->find();
            $get_score1 = $odds*$order_info['total_money'];

            $get_score = floor($get_score1);
            if ($get_score<=0){
                $get_score = 1;
            }
            $new_score = $user_score + $get_score;
            $add_lv_log['score'] = $get_score;
            $add_log['num'] = $get_score;
            $add_log['type'] = 3;

            $add_log['order_id'] = $type_id;
        }else if ($type=='5'){
            $is_add = M('shop_score_log')->where(array('user_id'=>$user_id,'type'=>'5'))->find();
            if ($is_add){
                return false;
                die;
            }
            //注册获得积分
            $set_info = M('set')->where(array('set_type'=>'register_score'))->find();

            $add_lv_log['score'] = $set_info['set_value'];
            $add_log['num'] = $set_info['set_value'];
            $add_log['type'] = 5;

            $add_log['order_id'] = $type_id;


            $new_score = $user_score + $set_info['set_value'];
        }else if ($type=='6'){
            //邀请好友获得积分
            $set_info = M('set')->where(array('set_type'=>'friend_score'))->find();

            $add_lv_log['score'] = $set_info['set_value'];
            $add_log['num'] = $set_info['set_value'];
            $add_log['type'] = 6;

            $add_log['order_id'] = $type_id;
            $new_score = $user_score + $set_info['set_value'];
        }else if ($type=='8'){
            //红包获得

            $add_lv_log['score'] = $get_num;
            $add_log['num'] = $get_num;
            $add_log['type'] = 8;

            $add_log['order_id'] = $type_id;
            $new_score = bcadd($user_score , $get_num);
        }else{
            //判断是否重复
            if($lv_type['status']=='1'){
                $is_have = $level_log->where(array('user_id'=>$user_id,'type'=>$type,'type_id'=>$type_id))->find();
                if($is_have){
                    return false;
                }
            }
            $new_score = $user_score + $lv_type['score'];



            $add_lv_log['score'] = $lv_type['score'];
            $add_log['num'] = $lv_type['score'];
            $add_log['type'] = 4;
        }
        $save['score'] = $new_score;
        $save['update_time'] = time();

            //判断是否升级
            $next_lv = $user_lv+1;
            $next_lv_info = $level->where(array('lv'=>$next_lv))->find();
            if($next_lv_info){
                if($new_score>=$next_lv_info['score']){
                    $save['level'] = $next_lv;
                }
            }

        //修改用户分数

        $query = $user->where(array('id'=>$user_id))->save($save);

        if($query){
            //增加积分明细
            $add_lv_log['user_id'] = $user_id;
            $add_lv_log['zx_user_id'] = $zx_user;
            $add_lv_log['type'] = $type;
            $add_lv_log['type_id'] = $type_id;

            $add_lv_log['create_time'] = time();
            $query_lv = $level_log->add($add_lv_log);

            //如果是任务，order_id为level_log的id
            if ($type!='0'){
                $add_log['order_id'] = $query_lv;
            }

            //积分记录
            $add_log['user_id'] = $user_id;
            $add_log['add_or_del'] = 1;
            $add_log['type_id'] = $type;
            $add_log['create_time'] = time();
            M('shop_score_log')->add($add_log);


            return true;
        }else{
            return false;
        }

    }else{
        return;
    }
}

//发送融云系统消息
function SendRongSystem($user_id,$content){
    vendor('Rong.rongcloud');
    $RongCloud = new \RongCloud(C('RongCloudConf.key'),C('RongCloudConf.secret'));
    //$result = $RongCloud->user()->getToken('system', '系统助手','http://120.78.161.104/pingche/logo.png');//更新系统助手信息
    //$user = json_encode($user_id,true);
    //dump($user);die;
    $content_val = "{\"content\":\"".$content."\",\"extra\":\"helloExtra\"}";
    $result = $RongCloud->message()->PublishSystem('system',$user_id, 'RC:TxtMsg', $content_val, $content, '{\"pushData\":\"111\"}', '1', '1', '0');
    return $result;
}


//模拟用户发送信息
function SendRongUserMessage($send_user,$user_id,$content){
    $RongCloud = new \RongCloud(C('RongCloudConf.key'),C('RongCloudConf.secret'));
    //$result = $RongCloud->user()->getToken('system', '系统助手','http://120.78.161.104/pingche/logo.png');//更新系统助手信息
    //$user = json_encode($user_id,true);
    //dump($user);die;

    $content_val = "{\"content\":\"".$content."\",\"extra\":\"helloExtra\"}";
    $result = $RongCloud->message()->publishPrivate($send_user,$user_id, 'RC:TxtMsg', $content_val, $content, '{\"pushData\":\"111\"}', '1', '0', '1','1','1');
    return $result;
}


function strReplace(&$array,$sen_arr) {

    $array = str_replace($sen_arr, '**', $array);
    if (is_array($array)) {
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                strReplace($array[$key],$sen_arr);
            }
        }
    }
    return $array;
}

/**
 * 计算店铺星级
 * @param $shop_id
 * @return bool
 */
function ShopStarCheck($shop_id){
    $shop = M('shop');
    $order = M('order');
    $where['shop_id'] = $shop_id;
    $where['status'] = 5;
    $where['pay_status'] = 1;
    $star = $order->where($where)->sum('star');
    $zong = 0;
    $zong += $order->where($where)->count();
    if($zong>0){
        $fen = $star/$zong;
        $fen2 = ceil($fen);
        $shop->where(array('id'=>$shop_id))->save(array('star'=>$fen2,'update_time'=>time()));
    }
    return true;
}

/**
 * DUOCAICREDIT 获取数据
 * @param $url 请求地址
 * @param array $params 请求的数据
 * @param $appCode 您的APPCODE
 * @param $method
 * @return array|mixed
 */
function DUOCAICREDIT($url, $params = array(), $appCode, $method = "GET")
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $method == "POST" ? $url : $url . '?' . http_build_query($params));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Authorization:APPCODE ' . $appCode
    ));
    //如果是https协议
    if (stripos($url, "https://") !== FALSE) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        //CURL_SSLVERSION_TLSv1
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
    }
    //超时时间
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //通过POST方式提交
    if ($method == "POST") {
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    }
    //返回内容
    $callbcak = curl_exec($curl);
    //http status
    $CURLINFO_HTTP_CODE = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //关闭,释放资源
    curl_close($curl);
    //如果返回的不是200,请参阅错误码 https://help.aliyun.com/document_detail/43906.html
    if ($CURLINFO_HTTP_CODE == 200)
        return json_decode($callbcak, true);
    else if ($CURLINFO_HTTP_CODE == 403)
        return array("error_code" => $CURLINFO_HTTP_CODE, "reason" => "剩余次数不足");
    else if ($CURLINFO_HTTP_CODE == 400)
        return array("error_code" => $CURLINFO_HTTP_CODE, "reason" => "APPCODE错误");
    else
        return array("error_code" => $CURLINFO_HTTP_CODE, "reason" => "APPCODE错误");
}


/**
 * post请求
 * @param $url
 * @param string $post_data
 * @param int $timeout
 * @return mixed
 */
function SendPost($url, $post_data = '', $timeout = 5){

    $headers = array(
        "Content-type: application/json;charset='utf-8'",


);
    $ch = curl_init();


    curl_setopt ($ch, CURLOPT_URL, $url);

    curl_setopt ($ch, CURLOPT_POST, 1);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if($post_data != ''){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }

    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HEADER, false);

    $file_contents = curl_exec($ch);
    curl_close($ch);

    return $file_contents;

}

//图片添加水印
function ImageAddLogo($file_url){
    $img_info = getimagesize($file_url);
    $width = $img_info[0];
    $image = new \Think\Image();
    //$BinImg = './xiao.jpg'; // 获得原图绝对路径
    //$BinImg2 = './Public/test.jpg'; // 获得原图绝对路径
    $image->open($file_url); // 打开原图
    // 添加水印
    $sy_img = './Public/shuiyin/sy_img_100.png';
    if($width<=200){$sy_img = './Public/shuiyin/sy_img_40.png';}
    if($width>200 && $width<=300){$sy_img = './Public/shuiyin/sy_img_60.png';}
    if($width>300 && $width<=400){$sy_img = './Public/shuiyin/sy_img_80.png';}
    if($width>400 && $width<=500){$sy_img = './Public/shuiyin/sy_img_100.png';}
    if($width>500 && $width<=600){$sy_img = './Public/shuiyin/sy_img_120.png';}
    if($width>600 && $width<=700){$sy_img = './Public/shuiyin/sy_img_140.png';}
    if($width>700 && $width<=800){$sy_img = './Public/shuiyin/sy_img_160.png';}
    if($width>800 && $width<=900){$sy_img = './Public/shuiyin/sy_img_180.png';}
    if($width>900 && $width<=1000){$sy_img = './Public/shuiyin/sy_img_200.png';}
    if($width>1000 && $width<=1100){$sy_img = './Public/shuiyin/sy_img_220.png';}
    if($width>1100 && $width<=1200){$sy_img = './Public/shuiyin/sy_img_240.png';}
    if($width>1200 && $width<=1300){$sy_img = './Public/shuiyin/sy_img_260.png';}
    if($width>1300 && $width<=1400){$sy_img = './Public/shuiyin/sy_img_280.png';}
    if($width>1400 && $width<=1500){$sy_img = './Public/shuiyin/sy_img_300.png';}
    if($width>1500 && $width<=1600){$sy_img = './Public/shuiyin/sy_img_320.png';}
    if($width>1600 && $width<=1700){$sy_img = './Public/shuiyin/sy_img_340.png';}
    if($width>1700 && $width<=1800){$sy_img = './Public/shuiyin/sy_img_360.png';}
    if($width>1800){$sy_img = './Public/shuiyin/sy_img_380.png';}
    //$status = $image->open($BinImg)->water('./Public/shuiyin/sy_img_60.png',\Think\Image::IMAGE_WATER_SOUTHEAST)-> save($BinImg2);
    $status = $image->open($file_url)->water($sy_img,\Think\Image::IMAGE_WATER_SOUTHEAST)-> save($file_url);
    return true;
}

//视频插入水印
function VideoAddLogo($file,$width) {
    $j_file = str_replace('.mp4','_sy.mp4',$file);
    $start_file = '/www/wwwroot/default/yimei'.$file;
    $over_file = '/www/wwwroot/default/yimei'.$j_file;
    $sy_img = '/www/wwwroot/default/yimei/Public/shuiyin/sy_video_105.png';
    if($width<=600){
        $sy_img = '/www/wwwroot/default/yimei/Public/shuiyin/sy_video_75.png';
    }
    if($width>600 && $width<=720){
        $sy_img = '/www/wwwroot/default/yimei/Public/shuiyin/sy_video_105.png';
    }
    if($width>720){
        $sy_img = '/www/wwwroot/default/yimei/Public/shuiyin/sy_video_140.png';
    }

    $str = '/usr/local/bin/ffmpeg -i '.$start_file.' -vf "movie=\''.$sy_img.'\' [logo]; [in][logo] overlay=main_w-overlay_w-10 : main_h-overlay_h-10 [out]" -q:v 2 -y '.$over_file;
    $result = system($str,$retval);
    $arr = array();
    $arr['code'] = $retval;
    $arr['url'] = $j_file;
    return $arr;
}



//获取视频封面
function getVideoCover($file,$time,$name) {

    if(empty($time))$time = '0.001';//默认截取第一秒第一帧
    //$strlen = strlen($file);
    $width = 480;
    $height = 360;
    $wh = getVideoWidthHeight($file);
    if($wh[0]){
        $width = $wh[0];
    }
    if($wh[1]){
        $height = $wh[1];
    }

    // $videoCover = substr($file,0,$strlen-4);
    // $videoCoverName = $videoCover.'.jpg';//缩略图命名
    //exec("ffmpeg -i ".$file." -y -f mjpeg -ss ".$time." -t 0.001 -s 320x240 ".$name."",$out,$status);
    $str = "/usr/local/bin/ffmpeg -i ".$file." -y -f mjpeg -ss 0.001 -t 0.001 -s ".$width."x".$height." ".$name;
    //echo $str."</br>";
    //dump($str);die;
    $result = system($str,$retval);


    return $wh;
}



/**
 * 使用ffmpeg获取视频宽高
 * @param String $file 视频文件
 * @return Array
 */
function getVideoWidthHeight($file){

    ob_start();
    passthru(sprintf( '/usr/local/bin/ffmpeg -i "%s" 2>&1', $file));
    $video_info = ob_get_contents();
    ob_end_clean();

    // 使用输出缓冲，获取ffmpeg所有输出内容
    $ret = array();

    if(preg_match("/Video: (.*?), (.*?), (.*?), (.*?), (.*?), (.*?)[,\s]/", $video_info, $matches)){
        foreach($matches as $item){
            if(strpos($item,'x') !== false){
                $new_a = explode('x',$item);
                //dump($new_a[0]);
                if(is_numeric($new_a[0]) && $new_a[0]>0){
                    $new_wh1 = $item;

                }
            }
        }
        $trim_wh = explode(' ',$new_wh1);
        $wah = $trim_wh[0];  // 编码格式
        /*dump($wah);
        die;
        if(strpos($matches[4],'x') !== false){
            $wah = $matches[4];  // 编码格式
        }else{
            $new_a = explode(' ',$matches[3]);
            $wah = $new_a[0];  // 编码格式
        }*/

        $new_wh = explode('x',$wah);

    }
    if(preg_match("/displaymatrix: (.*?) (.*?) (.*?) (.*?)[,\s]/", $video_info, $matches)){
        if($matches[3]){
            $new_wh2[0] =  $new_wh[1];
            $new_wh2[1] =  $new_wh[0];

            return $new_wh2;
        }
    }

    return $new_wh;

}

/**
 * @desc 根据两点间的经纬度计算距离
 * @param float $lat 纬度值
 * @param float $lng 经度值
 */
function getDistance($lat1, $lng1, $lat2, $lng2){
    $earthRadius = 6367000; //approximate radius of earth in meters
    $lat1 = ($lat1 * pi() ) / 180;
    $lng1 = ($lng1 * pi() ) / 180;
    $lat2 = ($lat2 * pi() ) / 180;
    $lng2 = ($lng2 * pi() ) / 180;
    $calcLongitude = $lng2 - $lng1;
    $calcLatitude = $lat2 - $lat1;
    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
    $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
    $calculatedDistance = $earthRadius * $stepTwo;
    $mi =  round($calculatedDistance);
    //return DistanceInfo($mi);
    if($mi<1000){
        return $mi.'m';
    }else{
        $mi2 = $mi/1000;
        return sprintf("%.2f",$mi2).'km';
    }

}

function MiZhuanhuan($mi){
    if($mi<1){
        return ceil($mi*1000).'m';
    }else{

        return sprintf("%.2f",$mi).'KM';
    }
}


/**
 * 计算年龄
 * @param $birthday
 * @return bool|int
 */
function birthday($birthday){
    $age = strtotime($birthday);
    if($age === false){
        return false;
    }
    list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age));
    $now = strtotime("now");
    list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now));
    $age = $y2 - $y1;
    if((int)($m2.$d2) < (int)($m1.$d1))
        $age -= 1;
    if ($age<0){
        return 0;
    }else{
        return $age;
    }

}

/**
 * 解析response报文
 * $content  收到的response报文
 * $pkey     爱贝平台公钥，用于验签
 * $respJson 返回解析后的json报文
 * return    解析成功TRUE，失败FALSE
 */
function parseResp($content, $pkey, &$respJson) {
    $arr=array_map(create_function('$v', 'return explode("=", $v);'), explode('&', $content));
    foreach($arr as $value) {
        $resp[($value[0])] = $value[1];
    }

    //解析transdata
    if(array_key_exists("transdata", $resp)) {
        $respJson = json_decode($resp["transdata"]);
    } else {
        return FALSE;
    }

    //验证签名，失败应答报文没有sign，跳过验签
    if(array_key_exists("sign", $resp)) {
        //校验签名
        $pkey = formatPubKey($pkey);
        return verify($resp["transdata"], $resp["sign"], $pkey);
    } else if(array_key_exists("errmsg", $respJson)) {
        return FALSE;
    }

    return TRUE;
}

/**
 * 清除验证码缓存
 */
function DeleteOldCode(){
    $verify = M('verify');
    $del_code_time = time()-900;
    $where_del_code['create_time'] = array('ELT',$del_code_time);
    $need_del = $verify->where($where_del_code)->count();
    if($need_del>1){
        $verify->where($where_del_code)->delete();
    }
}


/**格式化公钥
 * $pubKey PKCS#1格式的公钥串
 * return pem格式公钥， 可以保存为.pem文件
 */
function formatPubKey($pubKey) {
    $fKey = "-----BEGIN PUBLIC KEY-----\n";
    $len = strlen($pubKey);
    for($i = 0; $i < $len; ) {
        $fKey = $fKey . substr($pubKey, $i, 64) . "\n";
        $i += 64;
    }
    $fKey .= "-----END PUBLIC KEY-----";
    return $fKey;
}


/**RSA验签
 * $data待签名数据
 * $sign需要验签的签名
 * $pubKey爱贝公钥
 * 验签用爱贝公钥，摘要算法为MD5
 * return 验签是否通过 bool值
 */
function verify($data, $sign, $pubKey)  {
    //转换为openssl格式密钥
    $res = openssl_get_publickey($pubKey);

    //调用openssl内置方法验签，返回bool值
    $result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_MD5);

    //释放资源
    openssl_free_key($res);

    //返回资源是否成功
    return $result;
}

//计算发布时间
function mdate($time = NULL) {
    $text = '';
    $time = $time === NULL || $time > time() ? time() : intval($time);
    $t = time() - $time; //时间差 （秒）
    $y = date('Y', $time)-date('Y', time());//是否跨年
    switch($t){
        case $t == 0:
            $text = '刚刚';
            break;
        case $t < 60:
            $text = $t . '秒前'; // 一分钟内
            break;
        case $t < 60 * 60:
            $text = floor($t / 60) . '分钟前'; //一小时内
            break;
        case $t < 60 * 60 * 24:
            $text = floor($t / (60 * 60)) . '小时前'; // 一天内
            break;
        case $t < 60 * 60 * 24 * 3:
            $text = floor($time/(60*60*24)) ==1 ?'昨天 ' . date('H:i', $time) : '前天 ' . date('H:i', $time) ; //昨天和前天
            break;
        case $t < 60 * 60 * 24 * 30:
            $text = date('m-d H:i', $time); //一个月内
            break;
        case $t < 60 * 60 * 24 * 365&&$y==0:
            $text = date('m-d', $time); //一年内
            break;
        default:
            $text = date('Y-m-d', $time); //一年以前
            break;
    }

    return $text;
}



//推荐有礼判断
function TuiCheck($wx_id,$order_id){
    $status = M('status');
    $order = M('order');
    $recommend_log = M('recommend_log');
    $recommend = M('recommend');
    $is_open_tuijian =0;

        $is_tj_open = $status->where(array('type'=>'3'))->find();
        if($is_tj_open['status']!='0'){
            if($is_tj_open['status']=='1'){
                $is_open_tuijian =1;
            }
            if($is_tj_open['status']=='2'){
                if(time() >= $is_tj_open['start_time']  && time()<= $is_tj_open['end_time'] ) {
                    $is_open_tuijian =1;
                }
            }

        }
    if($is_open_tuijian==1){
        $is_tj = $recommend->where(array('friend_wx_id'=>$wx_id,'is_have'=>'0'))->find();
        if($is_tj){
            $order_info = $order->where(array('id'=>$order_id))->field('card_money')->find();
            if($order_info>=$is_tj_open['money']){
               $add['user_id'] = $is_tj['user_id'];
               $add['rid'] = $is_tj['id'];
               $add['num'] = $is_tj_open['num'];
               $add['order_id'] = $order_id;
               $add['create_time'] = time();
                $recommend_log->add($add);
            }

            $recommend->where(array('id'=>$is_tj['id']))->save(array('is_have'=>'1','update_time'=>time()));
        }
    }
    return true;
}

//根据ip获取地址
function GetIpLookup($ip = ''){

    $res = file_get_contents('http://api.ip138.com/query/?ip='.$ip.'&datatype=jsonp&callback=&token=aa071109f6e7e33619d969fa4478ea32');
    $data = json_decode($res,true);
    return $data;
}

//处理地址
function useriplocation(){
    $user_ip = M('user_ip');
    $list = $user_ip->where(array('is_check'=>'0'))->select();
    foreach($list as $item){
        $save = array();
        $is_check = $user_ip->where(array('ip'=>$item['ip'],'is_check'=>'1'))->find();
        if($is_check){
            $save['country'] = $is_check['country'];
            $save['province'] =  $is_check['province'];
            $save['city'] =  $is_check['city'];
            $save['operator'] =  $is_check['operator'];
            $save['is_check'] = 1;
            $save['update_time'] = time();
            $user_ip->where(array('id'=>$item['id']))->save($save);
        }else{
            $a = GetIpLookup($item['ip']);
            if($a['ret']=='ok'){
                $save['country'] = $a['data'][0];
                $save['province'] = $a['data'][1];
                $save['city'] = $a['data'][2];
                $save['operator'] = $a['data'][3];
                $save['is_check'] = 1;
                $save['update_time'] = time();
                $user_ip->where(array('id'=>$item['id']))->save($save);
            }
        }

    }
    return true;
}


function show_num(){
    $hospital_order = M('hospital_order');
    $order = M('shop_order');
    $shop_prize_order = M('shop_prize_order');
    $ticket_user = M('ticket_user');
    $tix_message = M('tix_message');
    $user_blogs = M('user_blogs');
    $team_money = M('team_money');
    $blogs_comment = M('blogs_comment');
    $opinion = M('opinion');
    $tui = M('tui');
    $report = M('report');
    $shop = M('shop');
    $team = M('team');
    $gudong = M('gudong');
    $project_apply = M('project_apply');
    $made = M('made');
    $leave = M('leave');
    $shop_order_num = 0;
    $hos_order_num = 0;
    $prize_order_num = 0;

    $where_shop['lyx_shop_order.status'] = array('IN','1,2,3');
    $where_shop['lyx_shop_order.refund_status'] = array('NOT IN','4,5');
    $where_shop['lyx_shop.service_type'] = 1;
    $shop_order_num += $order
        ->join('lyx_shop ON lyx_shop_order.shop_id = lyx_shop.id')
        ->where($where_shop)
        ->count();

//    $where_shop2['lyx_shop_order.status'] = 3;
//    $where_shop2['lyx_shop_order.refund_status'] = array('IN','1,3');
//    $where_shop2['lyx_shop.service_type'] = 1;
//    $shop_order_num += $order
//        ->join('lyx_shop ON lyx_shop_order.shop_id = lyx_shop.id')
//        ->where($where_shop2)
//        ->count();


    $hos_order_num+=$hospital_order->where(array('status'=>array('IN','2,3'),'refund_status'=>array('NOT IN','2,4,5,6')))->count();

//    $hos_order_num+= $hospital_order->where(array('status'=>'2'))->count();


    $prize_order_num +=$shop_prize_order->where(array('status'=>'1'))->count();
    $list['shop_order_num'] = $shop_order_num;
    $list['hos_order_num'] = $hos_order_num;
    $list['prize_order_num'] = $prize_order_num;



    $list['order_num'] = $shop_order_num+$hos_order_num+$prize_order_num;


    $report_num = 0;
    $report_num += $report->where(array('status'=>'0'))->count();
    $list['report_num'] = $report_num;

    $shop_num = 0;
    $shop_num += $shop->where(array('status'=>'0'))->count();
    $list['shop_num'] = $shop_num;


    $opinion_num = 0;
    $opinion_num += $opinion->where(array('is_read'=>'0'))->count();
    $list['opinion_num'] = $opinion_num;


    $tix_num = 0;
    $tix_num += $tix_message->where(array('status'=>'0'))->count();
    $list['tix_num'] = $tix_num;

    $blogs_num = 0;
    $blogs_num += $user_blogs->where(array('is_show'=>'0','is_tis'=>'1'))->count();
    $list['blogs_num'] = $blogs_num;


    $team_num = 0;
    $team_num += $team->where(array('is_send'=>'0'))->count();
    $list['team_num'] = $team_num;

    $gudong_num = 0;
    $gudong_num += $gudong->where(array('status'=>'0'))->count();
    $list['gudong_num'] = $gudong_num;

    $made_num = 0;
    $made_num += $made->where(array('is_dispose'=>'0'))->count();
    $list['made_num'] = $made_num;


    $leave_num = 0;
    $leave_num += $leave->where(array('is_read'=>'0','type'=>'1'))->count();
    $list['leave_num'] = $leave_num;

    $yuyue_num = 0;
    $yuyue_num += M('yuyue')->where(array('status'=>'0'))->count();
    $list['yuyue_num'] = $yuyue_num;

    $sh_num =$shop_num+$made_num+$leave_num+$gudong_num+$yuyue_num;
    $list['sh_num']+=$sh_num;


    $agent_tui = 0;
    $agent_tui += $team_money->where(array('is_da'=>'1','is_get'=>'0'))->count();
    $list['agent_tui'] = $agent_tui;

    $hos_tui = 0;
    $hos_tui += $hospital_order->where(array('is_da'=>'1','is_get'=>'0'))->count();
    $list['hos_tui'] = $hos_tui;


    $shop_tui = 0;
    $shop_tui += $order->where(array('is_da'=>'1','is_get'=>'0'))->count();
    $list['shop_tui'] = $shop_tui;

    $tic_rebate = 0;
    $tic_rebate += $ticket_user->where(array('is_da'=>'1','is_rebate'=>'0'))->count();
    $list['tic_rebate'] = $tic_rebate;

    $tui_num = 0;
    $tui_num += $tui->where(array('is_da'=>'1','status'=>'0','type'=>array('IN','1,2,4')))->count();
    $list['tui_num'] = $tui_num;


    $comment_num = 0;
    $comment_num += $blogs_comment->where(array('is_read'=>'0'))->count();
    $list['comment_num'] = $comment_num;


    $da_num = $tui_num+$tic_rebate+$shop_tui+$hos_tui+$agent_tui;
    $list['da_num'] = $da_num;

    $project_num = 0;
    $project_num+=$project_apply->where(array('status'=>0))->count();
    $list['project_num'] = $project_num;
    return $list;

}


function limits_status(){
    $limits_log = M('limits_log');
    $log_list = $limits_log->where(array('admin_id'=>session(XUANDB.'id')))->select();
    foreach($log_list as $item){
        $new_log[$item['lid']] = '1';
    }
    $new_log['type'] = session(XUANDB.'user_type');
    return $new_log;
}


//计算vip
function VipDispose($wx_id){
    $order = M('order');
    $vip = M('vip');
    $user = M('user');
    $buy = M('buy');
    $is_user = $user->where(array('wx_id'=>$wx_id))->find();
    if($is_user){
        $zong = 0;
        $zong += $order->where(array('wx_id'=>$wx_id,'pay_status'=>'1'))->sum('card_num');
        $zong += $buy->where(array('wx_id'=>$wx_id))->sum('num');

        $vip_list = $vip->order('target')->field('id,target')->select();
        $vip_type = '0';
        foreach($vip_list as $item){
            if($zong>=$item['target']){
                $vip_type = $item['id'];
            }
        }
        if($vip_type!='0'){
            if($vip_type!=$is_user['vip']){
                $user->where(array('id'=>$is_user['id']))->save(array('vip'=>$vip_type,'update_time'=>time()));

                /*事件记录*/
                $add_event_log['event_number'] =  $is_user['event_number'];
                $add_event_log['type'] =  12;
                $add_event_log['remark'] =  $vip_type;
                $add_event_log['create_time'] =  time();
                M('event_log')->add($add_event_log);
                /*事件记录*/
            }
        }
        return true;
    }else{
        return true;
    }

}


/**
 * 获取图片的Base64编码(不支持url)
 * @date 2017-02-20 19:41:22
 *
 * @param $img_file 传入本地图片地址
 *
 * @return string
 */
function imgToBase64($img_file) {

    $img_base64 = '';
    if (file_exists($img_file)) {
        $app_img_file = $img_file; // 图片路径
        $img_info = getimagesize($app_img_file); // 取得图片的大小，类型等

        //echo '<pre>' . print_r($img_info, true) . '</pre><br>';
        $fp = fopen($app_img_file, "r"); // 图片是否可读权限

        if ($fp) {
            $filesize = filesize($app_img_file);
            $content = fread($fp, $filesize);
            $file_content = chunk_split(base64_encode($content)); // base64编码
            switch ($img_info[2]) {           //判读图片类型
                case 1: $img_type = "gif";
                    break;
                case 2: $img_type = "jpg";
                    break;
                case 3: $img_type = "png";
                    break;
            }

            $img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content;//合成图片的base64编码

        }
        fclose($fp);
    }

    return $img_base64; //返回图片的base64
}


function SendSocket($to_uid,$content){
// 推送的url地址，使用自己的服务器地址

    $push_api_url = NOWIP.":2121/";
    $post_data = array(
        "type" => "publish",
        "content" => $content,
        "to" => $to_uid,
    );
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
    curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Expect:"));
    $return = curl_exec ( $ch );
    curl_close ( $ch );
    //var_export($return);
}

/**
 * 出去二维数组重复数据
 * @param $arr
 * @param $key
 * @return array
 */
function array_unset_tt($arr,$key){
    //建立一个目标数组
    $res = array();
    foreach ($arr as $value) {
        //查看有没有重复项

        if(isset($res[$value[$key]])){
            //有：销毁

            unset($value[$key]);

        }
        else{

            $res[$value[$key]] = $value;
        }
    }
    return $res;
}

/**
 * 判断是否有未处理订单
 * @return mixed
 */
function is_order(){
    $shop_order = M('shop_order');
    $shop_prize_order = M('shop_prize_order');

    $list['order_num'] = $shop_order->where(array('status'=>'1'))->count();
    $list['prize_order_num'] = $shop_prize_order->where(array('status'=>'1'))->count();
    $list['zong_num'] = $list['order_num']+$list['prize_order_num'];
    return $list;
}


//月结算
function MonthBill($type,$courier_id=""){
    if($type) {
        $courier = M('courier');
        $order = M('order');
        $bill = M('bill');

        $now = date('Y-m-d');
        $shang_month = date('Ym', strtotime("$now -1 month"));

        $start_time = date('Y-m-01', strtotime("$now -1 month"));//上一个月第一天
        $end_time = date('Y-m-d', strtotime("$start_time +1 month -1 day"));//上一个月最后一天


        $add['bill_date'] = date('Y/m', strtotime("$now -1 month")) . "月账单";
        $add['sort'] = $shang_month;
        $add['bill_time'] = strtotime("$now -1 month");
        $add['create_time'] = time();




        //配送员端查询
        if($type="courier"){
            if($courier_id){
                $where_s1['sort'] = $shang_month;
                $where_s1['courier_id'] = $courier_id;
                $is_jie = $bill->where($where_s1)->field('id')->find();
                if(!$is_jie){
                    $add['courier_id'] = $courier_id;
                    $query = $bill->add($add);
                    if ($query) {
                        $where_ord1['create_time'] = array(array('EGT',strtotime($start_time)),array('ELT',strtotime($end_time)+86399),'AND');
                        $where_ord1['courier_id'] = $courier_id;
                        $order->where($where_ord1)->save(array('bill_id' => $query, 'update_time' => time()));
                    }
                }else{
                    return false;
                }
            }
        }

        //总后台端
        if($type="admin"){
            $courier_list = $courier->select();
            foreach ($courier_list as $item) {
                $is_jie2 ='';
                $where_s2['courier_id'] = $item['id'];
                $where_s2['sort'] = $shang_month;
                $is_jie2 = $bill->where($where_s2)->field('id')->find();
                if(!$is_jie2){
                    $add['courier_id'] = $item['id'];
                    $query = $bill->add($add);
                    if ($query) {
                        $where_ord2['create_time'] = array(array('EGT',strtotime($start_time)),array('ELT',strtotime($end_time)+86399),'AND');
                        $where_ord2['courier_id'] = $item['id'];
                        $order->where($where_ord2)->save(array('bill_id' => $query, 'update_time' => time()));
                        $order_list = array();
                        $zong_money = 0;
                        $order_list = $order->where(array('bill_id' => $query))->field('price,courier')->select();
                        foreach($order_list as $val){
                            $zong_money += $val['price'] * $val['courier'];
                        }
                        PushUser($item['push_id'],$add['bill_date'].'已生成,总金额:'.$zong_money);
                    }
                }

            }
        }

    }else{
        return false;
    }
    return false;
}



    //结算
    function OpenBill(){
        $courier = M('courier');
        $order = M('order');
        $bill = M('bill');


        $now = date('Y-m-d');
        $start_time = date('Y-m-01', strtotime("$now -1 month"));//上一个月第一天
        $end_time = date('Y-m-d', strtotime("$start_time +1 month -1 day"));//上一个月最后一天


        $add['bill_date'] = date('Y/m', strtotime("$now -1 month"))."月账单";
        $add['sort'] = date('Ym',strtotime("$now -1 month"));
        $add['bill_time'] = strtotime("$now -1 month");
        $add['create_time'] = time();
        $courier_list = $courier->select();
        foreach($courier_list as $item){
            $add['courier_id'] = $item['id'];

            $query = $bill->add($add);
            if($query){
                $order->where(array('courier_id'=>$item['id']))->save(array('bill_id'=>$query,'update_time'=>time()));
            }
        }
    }


//超过万的处理
function WanChu($zan_num = 0){
    if($zan_num>=10000){
        $zan_num = round($zan_num/10000,1) .'万';
    }
    return (string)$zan_num;
}

//AES解密
function decrypt1($encryptStr) {
    $localIV = "5695158172956734";
    $encryptKey = "YimeingXuanDbabc";

    //Open module
    $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, $localIV);

    //print "module = $module <br/>" ;

    mcrypt_generic_init($module, $encryptKey, $localIV);

    $encryptedData = base64_decode($encryptStr);
    $encryptedData = mdecrypt_generic($module, $encryptedData);
    $encryptedData= str_replace(array("","","","","","","","","","","","","",""),"",$encryptedData);
    $encryptedData= trim($encryptedData);
    return $encryptedData;
}



//加密
function encrypt1($encryptStr) {
    $localIV = '5695158172956734';
    $encryptKey = 'YimeingXuanDbabc';

    //Open module
    $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, $localIV);

    //print "module = $module <br/>" ;

    mcrypt_generic_init($module, $encryptKey, $localIV);

    //Padding
    $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $pad = $block - (strlen($encryptStr) % $block); //Compute how many characters need to pad
    $encryptStr .= str_repeat(chr($pad), $pad); // After pad, the str length must be equal to block or its integer multiples

    //encrypt
    $encrypted = mcrypt_generic($module, $encryptStr);

    //Close
    mcrypt_generic_deinit($module);
    mcrypt_module_close($module);

    return base64_encode($encrypted);

}



    function get_user_code(){

            $num = rand(100000,999999);


        $is_hao = M('hao')->where(array('number'=>$num))->find();

        if($is_hao){

            return get_user_code();
        }
        $is_have = M('agent')->where(array('agent_code'=>$num))->find();
        if($is_have){

            return get_user_code();

        }
        return $num;
    }

    function get_user_login($num){
        if($num==''){
            $num = rand(100000,999999);
        }
        $is_have = M('agent')->where(array('agent_user'=>$num))->find();
        if($is_have){
            return get_user_login('');
        }

        return $num;
    }


    function agent_num(){
        $agent = M('agent');
        $query =$agent->where(array('agent_user'=>session(XUANDB.'user_agent_id')))->find();
        return $query['agent_card'];
    }
	
	
	function online_num(){
        $fh= file_get_contents(XUANIP.'/gaojia/test.php');
        return strstr($fh,":");
    }




 function PushUser($audience,$content){
    $url = 'https://api.jpush.cn/v3/push';
    $base64=base64_encode("f0cdb81ef96564be25023058:2a5251e9c3c164d4468090a4");
    $header=array("Authorization:Basic $base64","Content-Type:application/json");
    // print_r($header);
    $param='{
                "platform":"all",
                "audience":{
                    "registration_id" :["'.$audience.'"]
                    },
                "notification" : {
                   "android": {
                        "alert": "'.$content.'",
                        "title": "优送APP",
                        "builder_id": 1,
                        "extras":{
                            "newsid": 321
                        }
                    },
                    "ios": {
                        "alert": "'.$content.'",
                        "sound": "default",
                        "badge": "+1",
                        "extras": {
                            "newsid": 321
                        }
                    }
                },
                "options": {
					"time_to_live": 86400 ,
					"apns_production": false
				}
                }';
     $res = request_post($url,$param,$header);
     $res_arr = json_decode($res, true);
     return $res_arr;
}


function send_jg($data)
{
    $_appkeys = 'eb1158b7dabe9a66ed84cfd2';
    $_masterSecret = 'c6c4710dddb45b6bed1530ed';
    $url = 'https://api.jpush.cn/v3/push';
    $base64=base64_encode("$_appkeys:$_masterSecret");
    $header=array("Authorization:Basic $base64","Content-Type:application/json");
    // print_r($header);
    $param='{
                "platform": "all",
                "audience":"all",
                "notification" : {
                         '.$data.'
                },
                "options": {
					"time_to_live": 60,
					"apns_production": false
				}
                }';

    $res = request_post($url,$param,$header);
    $res_arr = json_decode($res, true);
    return $res_arr;
}
function request_post($url="",$param="",$header="") {
    if (empty($url) || empty($param)) {
        return false;
    }
    $postUrl = $url;
    $curlPost = $param;
    $ch = curl_init();//��ʼ��curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//ץȡָ����ҳ
    curl_setopt($ch, CURLOPT_HEADER, 0);//����header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//Ҫ����Ϊ�ַ����������Ļ��
    curl_setopt($ch, CURLOPT_POST, 1);//post�ύ��ʽ
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
    // ���� HTTP Header��ͷ������ֶ�
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    // ��ֹ�ӷ���˽�����֤
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $data = curl_exec($ch);//����curl

    curl_close($ch);
    return $data;
}