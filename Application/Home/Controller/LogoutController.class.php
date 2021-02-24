<?php
namespace Home\Controller;
use Think\Controller;
class LogoutController extends Controller
{
    public function index(){
        
            session(XUANDB.'user_id',null);
            session(XUANDB.'user_name',null);
            session(XUANDB.'user_type',null);
            session(XUANDB.'id',null);
            $this->redirect('/home/login/index');
    }

}

