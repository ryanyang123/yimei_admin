<?php
namespace Home\Controller;
use Think\Controller;
class PublicController extends CommonController {


    function overallnav($msg){
        $limits_log = M('limits_log');
        $aa = show_num();
        $is_sy = 0;
        $sy_url = '';

     /*   if($msg=='service_1'){
            $is_limits = $limits_log->where(array('admin_id'=>session(XUANDB.'id')))->field('id')->find();
            if($is_limits){
                $is_sy = 1;
                $sy_url = '/mp3/service.mp3';
            }
        }*/


        $arr = array(
            'code'=> 1,
            'list'=> $aa,
            'is_sy'=> $is_sy,
            'sy_url'=> $sy_url,
        );
        $this->ajaxReturn($arr,"JSON");
    }

    function editshow($id,$str,$db){
        $article = M($db);

        $query = $article->where(array('id'=>$id))->save(array('is_show'=>$str));
        $res = "修改";
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

    function cleartis($type){
        $user_blogs = M('user_blogs');

        $save['is_tis'] = '0';
        $save['update_time'] = '0';
        $query = $user_blogs->where(array('is_show'=>'0'))->save($save);
        $res = "修改";
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

    function editall($id,$str,$db,$value){
        $article = M($db);

        if ($db=='user_blogs'){
            if ($value=='is_top'){
                $article->where(array('id'=>array('NEQ',$id)))->save(array('is_top'=>'0'));
            }
        }

       /* if ($db=='shop_goods'){
            if ($value=='is_top'){
                $article->where(array('id'=>array('NEQ',$id)))->save(array('is_top'=>'0'));
            }
        }*/

        if ($db=='notice'){
            if ($value=='is_reg'){
                $article->where(array('id'=>array('NEQ',$id)))->save(array('is_reg'=>'0'));
            }
            if ($value=='is_agent'){
                $article->where(array('id'=>array('NEQ',$id)))->save(array('is_agent'=>'0'));
            }
        }

        if ($value=='is_get'){
            $save_a['get_time'] = time();
        }

        if ($db=='ticket' && $value=='is_reg_send'){
           M('ticket')->where(array('id'=>array('NEQ',$id)))->save(array('is_reg_send'=>0,'update_time'=>time()));
            $save_a['update_time'] = time();
        }

        if ($db=='tui'){
            $save_a['tui_time'] = time();
        }
        if ($db=='ticket_user'){
            if ($value=='is_rebate'){
                $save_a['rebate_time'] = time();
            }
        }
        if ($db=='shop_order'){
            $save_a['update_time'] = time();
        }

        if ($db=='project_apply'){
            if($value=='status'){
                $save_a['ok_time'] = time();
            }

        }
        $save_a[$value] = $str;
        $query = $article->where(array('id'=>$id))->save($save_a);
        $res = "修改";
        if($query){

            if($db=='shop'){
                if ($value=='is_show'){
                    if($str=='1'){
                        M('shop_goods')->where(array('shop_id'=>$id))->save(array('is_freeze'=>'1','update_time'=>time()));
                    }else{
                        M('shop_goods')->where(array('shop_id'=>$id))->save(array('is_freeze'=>'0','update_time'=>time()));
                        $goods_list = M('shop_goods')->where(array('shop_id'=>$id))->field('id')->select();

                        foreach ($goods_list as $item){
                            $is_have = M('ad')->where(['goods_id'=>$item['id']])->find();
                            if($is_have){

                                M('ad')->where(['goods_id'=>$item['id']])->save(['is_freeze'=>0,'update_time'=>0]);
                            }
                        }
                    }
                }
            }
            if($db=='shop_goods'){
                if ($value=='is_freeze'){
                    M('ad')->where(array('goods_id'=>$id))->save(array('is_freeze'=>$str,'update_time'=>time()));
                }
            }
            if($db=='tix_message'){
                M('set')->where(array('set_type'=>'tix_status'))->save(array('set_value'=>'1','update_time'=>time()));
            }


            if ($db=='shop_order'){
                if ($value=='refund_status'){
                    $order_info = M('shop_order')->where(array('id'=>$id))->find();
                    if ($str=='5'){
                        $add_tui['user_id'] = $order_info['user_id'];
                        $add_tui['type'] = 1;
                        $add_tui['type_id'] = $order_info['id'];
                        $add_tui['money'] = $order_info['money'];
                        $add_tui['create_time'] = time();
                        M('tui')->add($add_tui);

                        $tis = '退款中';
                        //新版
                           $add_system['user_id'] = $order_info['user_id'];
                           $add_system['send_user_id'] = '';
                           $add_system['content'] = $tis;
                           $add_system['type'] = 3;//1预约成功，2交易成功，3退款成功，4退款失败
                           $add_system['type_id'] = $order_info['id'];
                           $add_system['status'] = 2;
                           $add_system['create_time'] = time();
                           M('system')->add($add_system);

                        $rong_user[] = $order_info['user_id'];

                        SendRongSystem($rong_user,$tis);
                        $arr = array(
                            'code'=> 1,
                            'res'=> $res.'成功'
                        );
                        $this->ajaxReturn($arr,"JSON");
                    }
                    if ($str=='2'){
                        $tis = '拒绝退款';
                        //新版
                        $add_system['user_id'] = $order_info['user_id'];
                        $add_system['send_user_id'] = '';
                        $add_system['content'] = $tis;
                        $add_system['type'] = 4;//1预约成功，2交易成功，3退款成功，4退款失败
                        $add_system['type_id'] = $order_info['id'];
                        $add_system['status'] = 2;
                        $add_system['create_time'] = time();
                        M('system')->add($add_system);

                        $rong_user[] = $order_info['user_id'];

                        SendRongSystem($rong_user,$tis);

                        $query2 = M('shop_order')->where(array('id'=>$order_info['id']))->save(array('status'=>$order_info['front_status'],'update_time'=>time()));

                        $arr = array(
                            'code'=> 1,
                            'res'=> $res.'成功'
                        );
                        $this->ajaxReturn($arr,"JSON");
                    }
                }
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


    function delete($id,$db){

        if(session(XUANDB.'user_type')!='1'){
            if ($db=="team"){
                $db_check = "teamsh";
            }else if ($db=="shop"){
                $db_check = "shopsh";
            }else if ($db=="tix_message"){
                $db_check = "tixmessage";
            }else if ($db=="shop_search"){
                $db_check = "shopsearch";
            }else if ($db=="shop_goods"){
                $db_check = "shopgoods";
            }else if ($db=="shop_goods_banner"){
                $db_check = "shopgoods";
            }else if ($db=="shop_prize"){
                $db_check = "shopprize";
            }else if ($db=="hospital_order"){
                $db_check = "hospitalorder";
            }else if ($db=="hospital_papers"){
                $db_check = "hospital";
            }else if ($db=="comment_admin"){
                $db_check = "comadmin";
            }else if ($db=="hospital_hj"){
                $db_check = "hospital";
            }else if ($db=="order"){
                $db_check = "shop_order";
            }else if ($db=="shop_prize_order"){
                $db_check = "shopprizeorder";
            }else if ($db=="diary_classify"){
                $db_check = "diaryclassify";
            }else if ($db=="user_blogs_photo"){
                $db_check = "blogs";
            }else if ($db=="store_classify"){
                $db_check = "storeclassify";
            }else if ($db=="shop_classify"){
                $db_check = "shopclassify";
            }else if ($db=="made_classify"){
                $db_check = "madeclassify";
            }else if ($db=="comment_admin"){
                $db_check = "comadmin";
            }else if ($db=="opinion"){
                $db_check = "opinion";
            }else if ($db=="activity_group"){
                $db_check = "activitygroup";
            }else if ($db=="com_classify"){
                $db_check = "comclassify";
            }else if ($db=="web_img"){
                $db_check = "webimg";
            }else if ($db=="web_news"){
                $db_check = "webnews";
            }else if ($db=="ticket_hospital"){
                $db_check = "ticket";
            }else if ($db=="blogs_comment"){
                $db_check = "blogscomment";
            }else if ($db=="city_admin_city"){
                $db_check = "rebatecity";
            }else if ($db=="team_qr"){
                $db_check = "teamqr";
            }else if ($db=="project"){
                $db_check = "project";
            }else if ($db=="project_class"){
                $db_check = "projectclass";
            }else if ($db=="project_apply"){
                $db_check = "projectapply";
            }else{
                $db_check = $db;
            }
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>$db_check))->find();
            if(!$is_tiao){
                $arr = array(
                    'code'=> 0,
                    'res'=> '权限不足'
                );
                $this->ajaxReturn($arr,"JSON");
            }
        }
        $article = M($db);
        if($db=='task'){
            $info  = $article->where(array('id'=>$id))->find();
            if($info['status']=='2'){
                $arr = array(
                    'code'=> 0,
                    'res'=> '已领取的红包无法删除'
                );
                $this->ajaxReturn($arr,"JSON");
            }
        }


        $query = $article->where(array('id'=>$id))->delete();
        $res = "删除";
        if($query){
            if ($db=='game'){
                M('game_banner')->where(array('parent'=>$id))->delete();
            }
            if ($db=='notice'){
                M('system')->where(array('status'=>5,'type_id'=>$id))->delete();
            }
            if ($db=='com_classify'){
                M('comment_admin')->where(array('classify'=>$id))->delete();
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



}