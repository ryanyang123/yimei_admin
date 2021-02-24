<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends CommonController {
    public function _initialize(){
        vendor('Rong.rongcloud');
    }
    public function index(){
        $article = M('user_blogs');
        $article_phone = M('user_blogs_photo');
        $article_comment = M('blogs_comment');
        $article_zan = M('blogs_zan');
        $user = M('user');

        $aid = I('get.aid');
        if($aid){
            $where['id'] =  $aid;
        }

        $search = I('get.search');
        if($search){
            $where['content'] =  array("like","%".$search."%");
        }

        $place_id = I('get.place_id');
        if($place_id){
            $where['place_id'] =  $place_id;
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
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  条帖子</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $article->where($where)->order('update_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();


        foreach($list as $key=>$item){

            $user_name  = $user->where(array('id'=>$item['user_id']))->field('name')->find();
            $list[$key]['user_name'] = $user_name['name'];
            //图片
            $phone_list = array();
            $phone_list = $article_phone->where(array('article_id'=>$item['id']))->select();
            $list[$key]['phone_list'] = $phone_list;

            //评论数
            $comment_count = $article_comment->where(array('article_id'=>$item['id']))->count();
            $list[$key]['comment_count'] = $comment_count;
        }

        $data = array(
            'search' =>$search,
            'type_id' =>$type_id,
            'search_date' =>$search_date,
            'search_date2' =>$search_date2,
            'user_id' =>$user_id,
            'status' =>$status,
            'place_id' =>$place_id,
            'list' =>$list,
            'page' =>$show,
            'at' =>'article',
            'count' =>count($list),
        );



        $this->assign($data);
        $this->display();
    }

    function plus(){
        $is_edit  =  I('get.edit');
        $user = M('user');
        $place = M('place');



        $place_list  =  $place->field('id,name')->select();

        $user_lsit  =  $user->where(array('is_robot'=>'1'))->field('id,name,head,sex')->select();
        foreach($user_lsit as $key=>$item){
            if($item['sex']=='1'){
                $user_lsit[$key]['sex'] = '男';
            }else{
                $user_lsit[$key]['sex'] = '女';
            }
        }
        $data = array(
            'at'=>'article',
            'place_list'=>$place_list,
            'user_list'=>$user_lsit,
        );

        $this->assign($data);
        $this->display();
    }

    function photo(){
        $is_edit  =  I('get.edit');
        $user = M('user');
        $place = M('place');

        $place_list  =  $place->field('id,name')->select();
        $user_lsit  =  $user->where(array('is_robot'=>'1'))->field('id,name,head,sex')->select();
        foreach($user_lsit as $key=>$item){
            if($item['sex']=='1'){
                $user_lsit[$key]['sex'] = '男';
            }else{
                $user_lsit[$key]['sex'] = '女';
            }
        }
        $data = array(
            'at'=>'article',
            'place_list'=>$place_list,
            'user_list'=>$user_lsit,
        );

        $this->assign($data);
        $this->display();
    }
    function video(){
        $is_edit  =  I('get.edit');
        $user = M('user');
        $place = M('place');

        $place_list  =  $place->field('id,name')->select();
        $user_lsit  =  $user->where(array('is_robot'=>'1'))->field('id,name,head,sex')->select();
        foreach($user_lsit as $key=>$item){
            if($item['sex']=='1'){
                $user_lsit[$key]['sex'] = '男';
            }else{
                $user_lsit[$key]['sex'] = '女';
            }
        }
        $data = array(
            'at'=>'article',
            'place_list'=>$place_list,
            'user_list'=>$user_lsit,
        );

        $this->assign($data);
        $this->display();
    }

    function addcomment($article_id,$user_id,$content){
        $content = trim($content);
        $user = M('user');
        $article = M('article');
        $article_comment = M('article_comment');
        $add['content'] = $content;
        $add['user_id'] = $user_id;
        $add['article_id'] = $article_id;
        $add['create_time'] = time();
        $query = $article_comment->add($add);
        $res = "添加";

        if($query){
            $user_info = $user->where(array('id'=>$user_id))->find();

            $article_info = $article->where(array('id'=>$article_id))->field('user_id,content')->find();

            $article->where(array('id'=>$article_id))->save(array('update_time'=>time()));
            //添加消息通知
            if($article_info['user_id']!=$user_info['id']){
                //旧版
                $add_message['user_id'] = $article_info['user_id'];
                $add_message['send_user_id'] = $user_info['id'];
                $add_message['content'] = $content;
                $add_message['type'] = 1;//1回复帖子，2回复评论，3@用户
                $add_message['type_id'] = $article_id;
                $add_message['create_time'] = time();
                M('message')->add($add_message);
                //新版
                $add_system['user_id'] = $article_info['user_id'];
                $add_system['send_user_id'] = $user_info['id'];
                $add_system['content'] = $content;
                $add_system['type'] = 1;//1回复帖子，2回复评论，3@用户
                $add_system['type_id'] = $article_id;
                $add_system['status'] = 1;
                $add_system['create_time'] = time();
                M('system')->add($add_system);

                $rong_user[] = $article_info['user_id'];

                $tis = $user_info['name'].'回复了你的帖子《'.$article_info['content'].'》';
                SendRongSystem($rong_user,$tis);

            }
            //评论增加积分
            $add_status = AddScore($user_info['id'],$user_info['score'],$user_info['level'],'5',$article_id);
            $user_other = $user->where(array('id'=>$article_info['user_id']))->find();
            //评论增加积分
            $add_status = AddScore($user_other['id'],$user_other['score'],$user_other['level'],'9',$article_id);

            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功'
            );
        }else{
            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功'
            );

        }
        $this->ajaxReturn($arr,"JSON");
    }


    function addreply($article_id,$user_id,$content,$comment_id,$appoint_user_id){
        $content = trim($content);
        $user = M('user');
        $article = M('article');
        $article_comment = M('article_comment');
        $article_reply = M('article_reply');


        $add['comment_id'] =  $comment_id;
        $add['reply_content'] = $content;
        $add['user_id'] = $user_id;
        if($appoint_user_id){
            if($appoint_user_id == $user_id){
                $arr = array('code'=> 0, 'res'=>'不能@自己');
                $this->ajaxReturn($arr,"JSON");;
            }
            $add['appoint_user_id'] = $appoint_user_id;
        }
        $add['create_time'] = time();
        $query = $article_reply->add($add);

        $res = "添加";


        if($query){
            //添加消息通知
            $comment_info = $article_comment->where(array('id'=>$comment_id))->find();
            $user_info = $user->where(array('id'=>$user_id))->find();
            if($appoint_user_id){
                $add_message['user_id'] = $appoint_user_id;
                $add_message['type'] = 3;//1回复帖子，2回复评论，3@用户
                $add_message['send_user_id'] = $user_id;
                $add_message['content'] = $content;
                $add_message['type_id'] = $comment_id;
                $add_message['create_time'] = time();
                M('message')->add($add_message);

                //新版
                $add_system['user_id'] = $appoint_user_id;
                $add_system['send_user_id'] = $user_id;
                $add_system['content'] = $content;
                $add_system['type'] = 3;//1回复帖子，2回复评论，3@用户
                $add_system['type_id'] = $comment_id;
                $add_system['status'] = 1;
                $add_system['create_time'] = time();
                M('system')->add($add_system);

                $rong_user[] = $appoint_user_id;

                $tis = $user_info['name'].'@了你';
                SendRongSystem($rong_user,$tis);
            }
            if($comment_info['user_id']!=$user_info['id']){
                $add_message['user_id'] = $comment_info['user_id'];
                $add_message['type'] = 2;//1回复帖子，2回复评论，3@用户
                $add_message['send_user_id'] = $user_info['id'];
                $add_message['content'] = $content;
                $add_message['type_id'] = $comment_id;
                $add_message['create_time'] = time();
                M('message')->add($add_message);


                //新版
                $add_system['user_id'] = $comment_info['user_id'];
                $add_system['send_user_id'] = $user_info['id'];
                $add_system['content'] = $content;
                $add_system['type'] = 2;//1回复帖子，2回复评论，3@用户
                $add_system['type_id'] = $comment_id;
                $add_system['status'] = 1;
                $add_system['create_time'] = time();
                M('system')->add($add_system);

                $rong_user[] = $comment_info['user_id'];

                $tis = $user_info['name'].'回复了你的评论:'.$comment_info['content'];;
                SendRongSystem($rong_user,$tis);
            }



            $new_count = $comment_info['reply_num']+1;
            $article_comment->where(array('id'=>$comment_id))->save(array('reply_num'=>$new_count,'update_time'=>time()));

            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功'
            );
        }else{
            $arr = array(
                'code'=> 1,
                'res'=> $res.'成功'
            );

        }
        $this->ajaxReturn($arr,"JSON");
    }

    function check(){
        $is_edit  =  I('get.edit');
        $article = M('article');
        $user = M('user');
        $article_phone = M('article_phone');
        $article_comment = M('article_comment');
        $article_zan = M('article_zan');
        $article_reply = M('article_reply');

        if($is_edit){
            $list = $article->where(array('id'=>$is_edit))->find();
            $user_name  = $user->where(array('id'=>$list['user_id']))->field('name,head')->find();
            $list['user_name'] = $user_name['name'];
            $list['user_head'] = $user_name['head'];
            //图片
            $phone_list = $article_phone->where(array('article_id'=>$list['id']))->select();
            $list['phone_list'] = $phone_list;

            //评论数
            $comment_count = $article_comment->where(array('article_id'=>$list['id']))->count();
            $list['comment_count'] = $comment_count;

            //点赞数
            $zan_count = $article_zan->where(array('article_id'=>$list['id']))->count();
            $list['zan_count'] = $zan_count;
            $list['create_time'] = mdate($list['create_time']);



            $comment_list = $article_comment
                ->order('lyx_article_comment.create_time desc')
                ->join('lyx_user ON lyx_article_comment.user_id = lyx_user.id')
                ->field('lyx_article_comment.id as comment_id,lyx_article_comment.content,
                lyx_article_comment.user_id,lyx_article_comment.create_time,lyx_user.name,lyx_user.head,lyx_article_comment.reply_num,lyx_article_comment.zan')
                ->where(array('article_id'=>$list['id']))
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
                    ->join('lyx_user ON lyx_article_reply.user_id = lyx_user.id')
                    ->field('lyx_article_reply.id as reply_id,lyx_article_reply.user_id,lyx_article_reply.appoint_user_id,lyx_article_reply.reply_content,lyx_user.name')
                    ->where(array('lyx_article_reply.comment_id'=>$item['comment_id']))
                    ->order('lyx_article_reply.create_time')
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
            'at'=>'article',
            'list'=>$list,
            'user_list'=>$user_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }



    function add($content,$user_id,$zan,$browse,$place_id,$type,$img1,$img2,$img3){
        $content = trim($content);
        $article = M('article');
        $article_phone = M('article_phone');

        if($type=='1'){
            if(!$img1 && !$img2 && !$img3){
                $arr = array(
                    'code'=> 0,
                    'res'=> '至少上传一张图片'
                );
                $this->ajaxReturn($arr,"JSON");
            }
        }

        if($type=='2'){
            if(!$img1){
                $arr = array(
                    'code'=> 0,
                    'res'=> '请上传视频'
                );
                $this->ajaxReturn($arr,"JSON");
            }else{
                $cache_info = M('cache')->where(array('url'=>$img1,'type'=>'2'))->field('thumb,first,width,height')->find();
                $add['video_url'] = $img1;
                $add['video_icon'] = $cache_info['thumb'];
                $add['video_thumb'] = $cache_info['first'];
                $add['video_width'] = $cache_info['width'];
                $add['video_height'] = $cache_info['height'];
            }
        }

            $add['content'] = $content;
            $add['user_id'] = $user_id;
            $add['zan'] = $zan;
            $add['browse'] = $browse;
            $add['place_id'] = $place_id;
            $add['type'] = $type;
            $add['create_time'] = time();
            $add['update_time'] = time();
            $query = $article->add($add);

            if($type=='1'){
                $new_img[] =  $img1;
                $new_img[] =  $img2;
                $new_img[] =  $img3;
                foreach($new_img as $item){
                    if($item){
                        $cache_info_img = M('cache')->where(array('url'=>$item,'type'=>'1'))->field('thumb')->find();
                        $add_phone['thumb'] = $cache_info_img['thumb'];
                        $add_phone['article_id'] = $query;
                        $add_phone['img_url'] = $item;
                        $add_phone['create_time'] = time();
                        $article_phone->add($add_phone);
                    }
                }
            }

            $res = "添加";
        $user_info = M('user')->where(array('id'=>$user_id))->field('id,score,level')->find();

        if($query){
            //发帖增加积分
            $add_status = AddScore($user_info['id'],$user_info['score'],$user_info['level'],'1',$query);
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

    function delete($id){
        $article = M('article');
        $article_comment = M('article_comment');
        $article_comment_zan = M('article_comment_zan');
        $article_reply = M('article_reply');
        $article_phone = M('article_phone');
        $article_zan = M('article_zan');

        $message = M('message');

        $query = $article->where(array('id'=>$id))->delete();
        $res = "删除";
        if($query){
            $message->where(array('type'=>'1','type_id'=>$id))->delete();
            M('system')->where(array('type'=>'1','status'=>'1','type_id'=>$id))->delete();
            $article_phone->where(array('article_id'=>$id))->delete();
            $article_zan->where(array('article_id'=>$id))->delete();
            $comment_list  = $article_comment->where(array('article_id'=>$id))->field('id')->select();
            foreach($comment_list as $item){
                $message->where(array('type'=>array('IN','2,3'),'type_id'=>$item['id']))->delete();
                M('system')->where(array('type'=>array('IN','2,3'),'status'=>'1','type_id'=>$item['id']))->delete();
                $article_reply->where(array('comment_id'=>$item['id']))->delete();
                $article_comment_zan->where(array('comment_id'=>$item['id']))->delete();
            }
            $article_comment->where(array('article_id'=>$id))->delete();

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
        $article = M('article');


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
        $article_comment = M('article_comment');
        $article_comment_zan = M('article_comment_zan');
        $article_reply = M('article_reply');
        $message = M('message');
        $query = $article_comment->where(array('id'=>$id))->delete();
        $res = "删除";
        if($query){
            $message->where(array('type'=>array('IN','2,3'),'type_id'=>$id))->delete();
            M('system')->where(array('type'=>array('IN','2,3'),'status'=>'1','type_id'=>$id))->delete();
            $article_reply->where(array('comment_id'=>$id))->delete();
            $article_comment_zan->where(array('comment_id'=>$id))->delete();
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


    //修改点赞数
    function editzan($id,$num,$type){
        $db_name = M($type);

        $query = $db_name->where(array('id'=>$id))->save(array('zan'=>$num,'update_time'=>time()));
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

    function deletereply($id){
        $article_comment = M('article_comment');
        $article_reply = M('article_reply');
        $message = M('message');


        $reply_info = $article_reply->where(array('id'=>$id))->find();

        $query = $article_reply->where(array('id'=>$id))->delete();
        $res = "删除";
        if($query){
            $comment_info = $article_comment->where(array('id'=>$reply_info['comment_id']))->field('reply_num')->find();
            $new_count = $comment_info['reply_num']-1;
            $article_comment->where(array('id'=>$reply_info['comment_id']))->save(array('reply_num'=>$new_count,'update_time'=>time()));

            $message->where(array('type'=>array('IN','2,3'),'type_id'=>$reply_info['comment_id'],'content'=>$reply_info['content']))->delete();
            M('system')->where(array('type'=>array('IN','2,3'),'type_id'=>$reply_info['comment_id'],'content'=>$reply_info['content'],'status'=>'1'))->delete();
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
        $article_phone = M('article_phone');
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