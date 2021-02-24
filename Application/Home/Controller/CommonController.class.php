<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller
{
    public function _initialize(){
        if(session(XUANDB."user_id") == false && session(XUANDB."user_agent_type") != '1'){
            $this->redirect('/home/login/index');
        }
    }
}

