<?php
namespace Home\Controller;
use Think\Controller;
class ShopblogsController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'5'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
        vendor('Rong.rongcloud');
    }

    public function index(){
        $article = M('shop_blogs');
        $article_photo = M('shop_blogs_photo');
        $article_comment = M('shop_comment');
        $article_zan = M('shop_zan');
        $user = M('user');

        $aid = I('get.aid');
        if($aid){
            $where['id'] =  $aid;
        }

        $search = I('get.search');
        if($search){
            $where['content'] =  array("like","%".$search."%");
        }



        $user_id = I('get.user_id');
        if($user_id){
            $where['user_id'] =  $user_id;
        }



        $status = I('get.status');
        if($status){
            $where['is_show'] =  $status-1;
        }
        $type_id = I('get.type_id');
        if($type_id){
            $where['type'] =  $type_id;
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
        $count = $article->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条动态</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $article->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();



        foreach($list as $key=>$item){
            $phone_list = array();
            $user_name  = $user->where(array('id'=>$item['user_id']))->field('name')->find();
            $list[$key]['user_name'] = $user_name['name'];
            //图片
            $phone_list = $article_photo->where(array('shop_blogs_id'=>$item['id']))->select();
            $list[$key]['phone_list'] = $phone_list;

            //评论数
            $comment_count = $article_comment->where(array('shop_blogs_id'=>$item['id']))->count();
            $list[$key]['comment_count'] = $comment_count;
        }

        $data = array(
            'search' =>$search,
            'type_id' =>$type_id,
            'search_date' =>$search_date,
            'search_date2' =>$search_date2,
            'user_id' =>$user_id,
            'status' =>$status,
            'list' =>$list,
            'page' =>$show,
            'at' =>'shopblogs',
            'count' =>count($list),
        );



        $this->assign($data);
        $this->display();
    }





    function check(){
        $is_edit  =  I('get.edit');
        $article = M('shop_blogs');
        $user = M('user');
        $article_photo = M('shop_photo');
        $article_comment = M('shop_comment');
        $article_zan = M('shop_zan');
        $article_reply = M('shop_reply');

        if($is_edit){
            $list = $article->where(array('id'=>$is_edit))->find();
            $user_name  = $user->where(array('id'=>$list['user_id']))->field('name,head')->find();
            $list['user_name'] = $user_name['name'];
            $list['user_head'] = $user_name['head'];
            //图片
            $phone_list = $article_photo->where(array('shop_blogs_id'=>$list['id']))->select();
            $list['phone_list'] = $phone_list;

            //评论数
            $comment_count = $article_comment->where(array('shop_blogs_id'=>$list['id']))->count();
            $list['comment_count'] = $comment_count;

            //点赞数
            $zan_count = $article_zan->where(array('shop_blogs_id'=>$list['id']))->count();
            $list['zan_count'] = $zan_count;
            $list['create_time'] = mdate($list['create_time']);



            $comment_list = $article_comment
                ->order('lyx_shop_comment.create_time desc')
                ->join('lyx_user ON lyx_shop_comment.user_id = lyx_user.id')
                ->field('lyx_shop_comment.id as comment_id,lyx_shop_comment.content,
                lyx_shop_comment.user_id,lyx_shop_comment.create_time,lyx_user.name,lyx_user.head,lyx_shop_comment.reply_num,lyx_shop_comment.zan')
                ->where(array('shop_blogs_id'=>$list['id']))
                ->select();


            foreach($comment_list as $key=>$item){
                $comment_list[$key]['create_time'] = mdate($item['create_time']);
                if(!$item['name']){
                    $comment_list[$key]['name'] = "";
                }
                if(!$item['head']){
                    $comment_list[$key]['head'] = "";
                }

                if($item['user_id']==$list['user_id']){
                    $comment_list[$key]['is_self'] = '1';
                }else{
                    $comment_list[$key]['is_self'] = '0';
                }

                $reply_list = $article_reply
                    ->join('lyx_user ON lyx_shop_reply.user_id = lyx_user.id')
                    ->field('lyx_shop_reply.id as reply_id,lyx_shop_reply.user_id,lyx_shop_reply.appoint_user_id,lyx_shop_reply.reply_content,lyx_user.name')
                    ->where(array('lyx_shop_reply.comment_id'=>$item['comment_id']))
                    ->order('lyx_shop_reply.create_time')
                    ->select();

                if($reply_list){
                    foreach($reply_list as $key1=>$val){
                        if(!$val['name']){
                            $reply_list[$key1]['name'] = "";
                        }

                        if($val['appoint_user_id']){
                            $appoint_name = $user->where(array('id'=>$val['appoint_user_id']))->field('name')->find();
                            if(!$appoint_name['name']){
                                $appoint_name['name'] = '';
                            }
                            $reply_list[$key1]['appoint_user_name'] = $appoint_name['name'];
                        }else{
                            $reply_list[$key1]['appoint_user_name'] = '';
                        }
                    }
                }


                $comment_list[$key]['reply_list'] = $reply_list;

            }

            $list['comment_list'] =  $comment_list;
        }else{
            $list = array();
        }

        $user_list = $user->where(array('is_robot'=>'1'))->field('id,name,sex')->select();
        foreach($user_list as $key=>$item){
            if($item['sex']=='1'){
                $user_list[$key]['sex'] = '男';
            }else{
                $user_list[$key]['sex'] = '女';
            }
        }

        $data = array(
            'at'=>'shopblogs',
            'list'=>$list,
            'user_list'=>$user_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }




    function delete($id){
        $article = M('shop_blogs');
        $article_comment = M('shop_comment');
        $article_reply = M('shop_reply');
        $article_phone = M('shop_photo');
        $article_zan = M('shop_zan');


        $query = $article->where(array('id'=>$id))->delete();
        $res = "删除";
        if($query){
            M('system')->where(array('type'=>'1','status'=>'2','type_id'=>$id))->delete();
            $article_phone->where(array('shop_blogs_id'=>$id))->delete();
            $article_zan->where(array('shop_blogs_id'=>$id))->delete();
            $comment_list  = $article_comment->where(array('shop_blogs_id'=>$id))->field('id')->select();
            foreach($comment_list as $item){
                M('system')->where(array('type'=>array('IN','2,3'),'status'=>'2','type_id'=>$item['id']))->delete();
                $article_reply->where(array('comment_id'=>$item['id']))->delete();
            }
            $article_comment->where(array('shop_blogs_id'=>$id))->delete();

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
    function editstatus($id,$str){
        $article = M('shop_blogs');


        $query = $article->where(array('id'=>$id))->save(array('is_show'=>$str,'update_time'=>time()));
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
    function deletecomment($id){
        $article_comment = M('shop_comment');
        $article_reply = M('shop_reply');
        $query = $article_comment->where(array('id'=>$id))->delete();
        $res = "删除";
        if($query){
            M('system')->where(array('type'=>array('IN','2,3'),'status'=>'2','type_id'=>$id))->delete();
            $article_reply->where(array('comment_id'=>$id))->delete();
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




    function deletereply($id){
        $article_comment = M('shop_comment');
        $article_reply = M('shop_reply');


        $reply_info = $article_reply->where(array('id'=>$id))->find();

        $query = $article_reply->where(array('id'=>$id))->delete();
        $res = "删除";
        if($query){
            $comment_info = $article_comment->where(array('id'=>$reply_info['comment_id']))->field('reply_num')->find();
            $new_count = $comment_info['reply_num']-1;
            $article_comment->where(array('id'=>$reply_info['comment_id']))->save(array('reply_num'=>$new_count,'update_time'=>time()));

            M('system')->where(array('type'=>array('IN','2,3'),'type_id'=>$reply_info['comment_id'],'content'=>$reply_info['content'],'status'=>'2'))->delete();
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
    function deletephone($id){
        $article_phone = M('shop_photo');
        $query = $article_phone->where(array('id'=>$id))->delete();
        $res = "删除";
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