<?php
namespace Home\Controller;
use Think\Controller;
class UserinfoController extends Controller
{

    public function check(){
        $key = trim(I('post.key'));
        $user_id = trim(I('post.user_id'));
        $app = M('app');
        $user = M('user');
        $app_info  = $app->where(array('key'=>$key))->find();
        if (!$app_info){
            $arr = array(
                'code'=> 100,
                'res'=> 'Key错误'
            );
            $this->ajaxReturn($arr,"JSON");
        }
        $user_info =  $user->where(array('id'=>$user_id))->find();
        if ($user_info){

            $arr = array(
                'code'=> 0,
                'res'=> '查询成功',
                'user_id'=> $user_info['id'],
                'name'=> $user_info['name'],
                'head'=> $user_info['head'],
                'sex'=> $user_info['sex'],
            );
            $this->ajaxReturn($arr,"JSON");
        }else{
            $arr = array(
                'code'=> 101,
                'res'=> '用户不存在'
            );
            $this->ajaxReturn($arr,"JSON");
        }

    }










}


























