<?php
namespace Home\Controller;
use Think\Controller;
class GameshowController extends Controller {
    public function index(){
        $game = M('game');
        $game_id = I('game_id');

        $list = $game->where(array('id'=>$game_id))->field('content')->find();

        $data['list'] = $list;
        $this->assign($data);
        $this->display();
    }
}