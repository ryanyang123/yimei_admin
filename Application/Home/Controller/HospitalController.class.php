<?php
namespace Home\Controller;
use Think\Controller;
class HospitalController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'hospital'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $hospital = M('hospital');
        $doctor = M('doctor');
        $shop = M('shop');


        $search = I('get.search');
        if($search){
            $where['name|classify'] =  array("like","%".$search."%");;
            $search_list['search'] = $search;
        }

        $is_show = I('get.is_show');
        if($is_show){
            $where['is_show'] =  $is_show-1;
            $search_list['is_show'] = $is_show;
        }

        $status = I('get.status');
        if($status){
            $where['type'] =  $status-1;
            $search_list['status'] = $status;
        }

        $hos_id = I('get.hos_id');
        if($hos_id){
            $where['id'] =  $hos_id;
            $search_list['hos_id'] = $hos_id;
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


        //$where['is_show'] = 1;
        $count = $hospital->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个医院</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $hospital->where($where)->order('sort,create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $list[$key]['administrative'] = explode('+',$item['administrative']);
            $list[$key]['doctor_num'] = 0;
            $list[$key]['doctor_num'] += $doctor->where(array('parent'=>$item['id']))->count();

            $shop_info = $shop->where(array('hospital_id'=>$item['id']))->field('id,name')->find();
            $list[$key]['shop_info'] = $shop_info;

        }

        $data = array(
            'list' =>$list,
            'search_list' =>$search_list,
            'page' =>$show,
            'is_show' =>$is_show,
            'status' =>$status,
            'at' =>'hospital',
            'count' =>count($list),

        );
        $this->assign($data);
        $this->display();
    }


    function check(){
        $is_edit  =  I('get.edit');

        $hospital = M('hospital');
        $shop = M('shop');
        $hospital_hj = M('hospital_hj');
        $hospital_papers = M('hospital_papers');
        $user = M('user');
        $doctor = M('doctor');




        if($is_edit){
            $list = $hospital->where(array('id'=>$is_edit))->find();
            $list['administrative'] = explode('+',$list['administrative']);
            $list['doctor_num'] = 0;
            $doctor_list = $doctor->where(array('hospital_id'=>$list['id']))->select();
            foreach ($doctor_list as $key=>$item){
                if($item['user_id']){
                    $user_name = $user->where(array('id'=>$item['user_id']))->field('name')->find();
                    $doctor_list[$key]['user_name'] = $user_name['name'];
                }
            }

            $list['doctor_num'] += count($doctor_list);
            $list['doctor_list'] = $doctor_list;
            $list['hj_list'] = $hospital_hj->where(array('parent'=>$list['id']))->order('id')->select();
            $list['zy_list'] = $hospital_papers->where(array('parent'=>$list['id'],'type'=>'1'))->order('id')->select();
            $list['ry_list'] = $hospital_papers->where(array('parent'=>$list['id'],'type'=>'2'))->order('id')->select();

            $shop_info = $shop->where(array('hospital_id'=>$list['id']))->field('id,name')->find();
            $list['shop_info'] = $shop_info;
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
        }

        $data = array(
            'at'=>'hospital',
            'title'=>'医院',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    function other_img($id,$title,$type,$img){
        if ($type=='1'){
            $add_db = M('hospital_hj');
        }

        if ($type=='2'){
            $add_db = M('hospital_papers');
            $add['title'] = $title;
            $add['type'] = 1;
        }
        if ($type=='3'){
            $add_db = M('hospital_papers');
            $add['title'] = $title;
            $add['type'] = 2;
        }

        $add['parent'] = $id;
        $add['img_url'] = $img;
        $add['create_time'] = time();
        $res = '新增';
        $query = $add_db->add($add);
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

    public function editgrade($id,$grade,$is_edit){
        $hospital = M('hospital');

        if(!$grade){$grade = 0;}



        $save['grade'] = $grade;
        $save['update_time'] = time();
        $query = $hospital->where(array('id'=>$id))->save($save);

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




    function BindUser($doc_id,$user_id){
        if(!$user_id){$arr = array('code'=> 0, 'res'=>'请输入要绑定的ID');$this->ajaxReturn($arr,"JSON");}

        $user = M('user');
        $doctor = M('doctor');
        $doc_info = $doctor->where(array('id'=>$doc_id))->field('user_id')->find();
        if($doc_info['user_id']==$user_id){$arr = array('code'=> 0, 'res'=>'当前绑定用户相同，请勿重复操作');$this->ajaxReturn($arr,"JSON");}

        $is_user=  $user->where(array('id'=>$user_id))->field('id')->find();
        if(!$is_user){$arr = array('code'=> 0, 'res'=>'用户不存在');$this->ajaxReturn($arr,"JSON");}


        $where['id'] = array('NEQ',$doc_id);
        $where['user_id'] = $user_id;
        $is_bind = $doctor->where($where)->find();
        if($is_bind){$arr = array('code'=> 0, 'res'=>'此ID已被绑定其他医生');$this->ajaxReturn($arr,"JSON");}



        $save['user_id'] = $user_id;
        $save['update_time'] = time();
        $query = $doctor->where(array('id'=>$doc_id))->save($save);

        $res = '绑定';
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


    function plus(){
        $is_edit  =  I('get.edit');

        $hospital = M('hospital');


        if($is_edit){
            $list = $hospital->where(array('id'=>$is_edit))->find();
        }else{
            $list = array();

        }


        $data = array(
            'at'=>'hospital',
            'title'=>'医院',
            'list'=>$list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($img1, $img2, $name, $username,$password,$ali_number,$ali_name,$classify, $location, $lng, $lat, $proportion,
                $treatment_room, $operating_room, $administrative, $open_time, $intro, $sort, $is_edit){


        $hospital = M('hospital');

        if(!$img1 && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传医院logo');$this->ajaxReturn($arr,"JSON");}
        if(!$img2 && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传医院背景图');$this->ajaxReturn($arr,"JSON");}
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入医院名称');$this->ajaxReturn($arr,"JSON");}
        if(!$location){$arr = array('code'=> 0, 'res'=>'请输入医院地址');$this->ajaxReturn($arr,"JSON");}
        if(!$lng){$arr = array('code'=> 0, 'res'=>'请输入医院地址经度');$this->ajaxReturn($arr,"JSON");}
        if(!$lat){$arr = array('code'=> 0, 'res'=>'请输入医院地址纬度');$this->ajaxReturn($arr,"JSON");}
        if(!$classify){$arr = array('code'=> 0, 'res'=>'请输入医院类型');$this->ajaxReturn($arr,"JSON");}
        if(!$proportion){$arr = array('code'=> 0, 'res'=>'请输入医院面积');$this->ajaxReturn($arr,"JSON");}
        if(!$treatment_room){$arr = array('code'=> 0, 'res'=>'请输入医院治疗室数量');$this->ajaxReturn($arr,"JSON");}
        if(!$operating_room){$arr = array('code'=> 0, 'res'=>'请输入医院手术室数量');$this->ajaxReturn($arr,"JSON");}
        if(!$administrative){$arr = array('code'=> 0, 'res'=>'请输入医院科室');$this->ajaxReturn($arr,"JSON");}
        if(!$open_time){$arr = array('code'=> 0, 'res'=>'请选择医院成立时间');$this->ajaxReturn($arr,"JSON");}
        if(!$intro){$arr = array('code'=> 0, 'res'=>'请输入医院简介');$this->ajaxReturn($arr,"JSON");}


        if(!$username){$arr = array('code'=> 0, 'res'=>'请输入登录账号');$this->ajaxReturn($arr,"JSON");}
        if(!$ali_number){$arr = array('code'=> 0, 'res'=>'请输入支付宝账号');$this->ajaxReturn($arr,"JSON");}
        if(!$ali_name){$arr = array('code'=> 0, 'res'=>'请输入支付宝真实姓名');$this->ajaxReturn($arr,"JSON");}

        if(strlen($username)<5 || strlen($username)>12){$arr = array('code'=> 0, 'res'=>'登陆账号长度为5-12字符');$this->ajaxReturn($arr,"JSON");}

        if (!$is_edit){
            if(!$password){$arr = array('code'=> 0, 'res'=>'请输入登录密码');$this->ajaxReturn($arr,"JSON");}

        }

        if ($password){
            if(strlen($password)<6 || strlen($password)>12){$arr = array('code'=> 0, 'res'=>'密码长度为6-12字符');$this->ajaxReturn($arr,"JSON");}
        }

        if ($is_edit){
            $where['id'] = array('NEQ',$is_edit);
        }
        $where['username'] = $username;
        $is_have = $hospital->where($where)->field('id')->find();
        if($is_have){$arr = array('code'=> 0, 'res'=>'登录账号已被占用');$this->ajaxReturn($arr,"JSON");}



        if($is_edit){
            if($img1){
                $save['logo'] = $img1;
            }
            if($img2){
                $save['bg'] = $img2;
            }
            $save['username'] = $username;
            $save['ali_number'] = $ali_number;
            $save['ali_name'] = $ali_name;
            if ($password){
                $save['password'] = md5($password);
            }

            $save['sort'] = $sort;
            $save['name'] = $name;
            $save['location'] = $location;
            $save['lng'] = $lng;
            $save['lat'] = $lat;
            $save['classify'] = $classify;
            $save['proportion'] = $proportion;
            $save['treatment_room'] = $treatment_room;
            $save['operating_room'] = $operating_room;
            $save['administrative'] = $administrative;
            $save['open_time'] = strtotime($open_time);
            $save['intro'] = $intro;
            $save['update_time'] = time();
            $query = $hospital->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{

            $add['username'] = $username;
            $add['ali_number'] = $ali_number;
            $add['ali_name'] = $ali_name;
            $add['password'] = md5($password);
            $add['logo'] = $img1;
            $add['bg'] = $img2;
            $add['sort'] = $sort;
            $add['name'] = $name;
            $add['location'] = $location;
            $add['lng'] = $lng;
            $add['lat'] = $lat;
            $add['classify'] = $classify;
            $add['proportion'] = $proportion;
            $add['treatment_room'] = $treatment_room;
            $add['operating_room'] = $operating_room;
            $add['administrative'] = $administrative;
            $add['open_time'] = strtotime($open_time);
            $add['intro'] = $intro;
            $add['create_time'] = time();
            $query = $hospital->add($add);
            $res = '新增';
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


}












