<?php
namespace Home\Controller;
use Think\Controller;
class DoctorController extends CommonController {
    public function _initialize(){
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'doctor'))->find();
            if(!$is_tiao){
                $this->redirect('/home/home/index');
            }
        }
    }
    public function index(){

        $doctor = M('doctor');
        $user = M('user');
        $doctor_photo = M('doctor_photo');


        $search = I('get.search');
        if($search){
            $where['lyx_doctor.name|lyx_doctor.job|lyx_hospital.name'] =  array("like","%".$search."%");;
            $search_list['search'] = $search;
        }

        $is_show = I('get.is_show');
        if($is_show){
            $where['lyx_doctor.is_show'] =  $is_show-1;
            $search_list['is_show'] = $is_show;
        }

        $status = I('get.status');
        if($status){
            $where['lyx_doctor.type'] =  $status-1;
            $search_list['status'] = $status;
        }

        $user_id = I('get.user_id');
        if($user_id){
            $where['lyx_doctor.user_id'] =  $user_id;
            $search_list['user_id'] = $user_id;
        }

        $doctor_id = I('get.doctor_id');
        if($doctor_id){
            $where['lyx_doctor.id'] =  $doctor_id;
            $search_list['doctor_id'] = $doctor_id;
        }

        //时间搜索
        $search_date  = I('get.search_date');
        $search_date2  = I('get.search_date2');
        //$data['search_date'] = $search_date;
        //$data['search_date2'] = $search_date2;
        $newtime = strtotime($search_date);
        $newtime1 = strtotime($search_date2)+86399;
        if($search_date && $search_date2){
            $where['lyx_doctor.create_time'] = array(array('EGT',$newtime),array('ELT',$newtime1),'AND');
        }else if($search_date && !$search_date2){
            $where['lyx_doctor.create_time'] = array('EGT',$newtime);
        }else if(!$search_date && $search_date2){
            $where['lyx_doctor.create_time'] = array('ELT',$newtime1);
        }
        $search_list['search_date'] = $search_date;
        $search_list['search_date2'] = $search_date2;
        //时间搜索


        //$where['is_show'] = 1;
        $count = $doctor->where($where)
            ->join('lyx_hospital ON lyx_doctor.hospital_id = lyx_hospital.id')
            ->count();
        $Page       = new \Think\Page($count,10);
        $Page->rollPage = 5;
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
        $Page->setConfig('header','<p class="rows">共 %TOTAL_ROW%  个医生</p>');
        $show       = $Page->show();// 分页显示输出
        $list = $doctor
            ->where($where)
            ->order('sort,create_time desc')
            ->join('lyx_hospital ON lyx_doctor.hospital_id = lyx_hospital.id')
            ->field('lyx_doctor.*,lyx_hospital.name as hos_name')
            ->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$item){
            $list[$key]['skilled'] = explode('+',$item['skilled']);
            $user_name = $user->where(array('id'=>$item['user_id']))->field('name')->find();
            $list[$key]['user_name'] = $user_name['name'];
            $list[$key]['banner_list'] = $doctor_photo->where(array('parent'=>$item['id']))->order('id')->select();
        }

        $data = array(
            'list' =>$list,
            'search_list' =>$search_list,
            'page' =>$show,
            'is_show' =>$is_show,
            'status' =>$status,
            'at' =>'doctor',
            'count' =>count($list),

        );
        $this->assign($data);
        $this->display();
    }


    function plus(){
        $is_edit  =  I('get.edit');
        $hos_id  =  I('get.hos_id');

        $doctor = M('doctor');
        $doctor_photo = M('doctor_photo');
        $hospital = M('hospital');
        $hos_list = $hospital->field('id,name')->select();

        if($is_edit){
            $list = $doctor->where(array('id'=>$is_edit))->find();
            $banner_list = $doctor_photo->where(array('parent'=>$list['id']))->order('id')->select();
        }else{
            $list = array();
            $banner_list = array();
            if ($hos_id){
                $list['hospital_id'] = $hos_id;
            }
        }


        $data = array(
            'at'=>'doctor',
            'title'=>'医生',
            'list'=>$list,
            'hos_list'=>$hos_list,
            'banner_list'=>$banner_list,
            'is_edit'=>$is_edit
        );

        $this->assign($data);
        $this->display();
    }

    public function add($img, $name, $user_id, $job, $job_age, $skilled, $sort, $intro, $hospital_id, $img_arr,$is_edit){
        $doctor = M('doctor');
        $doctor_photo = M('doctor_photo');
        $hospital = M('hospital');

        if(!$img && !$is_edit){$arr = array('code'=> 0, 'res'=>'请上传图片');$this->ajaxReturn($arr,"JSON");}
        if(!$name){$arr = array('code'=> 0, 'res'=>'请输入医生名称');$this->ajaxReturn($arr,"JSON");}
        if(!$job){$arr = array('code'=> 0, 'res'=>'请输入担任职位');$this->ajaxReturn($arr,"JSON");}
        if(!$job_age){$arr = array('code'=> 0, 'res'=>'请输入与从业年限');$this->ajaxReturn($arr,"JSON");}
        if(!$skilled){$arr = array('code'=> 0, 'res'=>'请输入擅长项目');$this->ajaxReturn($arr,"JSON");}
        if(!$intro){$arr = array('code'=> 0, 'res'=>'请输入简介');$this->ajaxReturn($arr,"JSON");}
        if(!$hospital_id){$arr = array('code'=> 0, 'res'=>'请选择所属医院');$this->ajaxReturn($arr,"JSON");}


        if ($user_id){
            if ($is_edit){
                $where['id'] = array('NEQ',$is_edit);
            }
            $where['user_id'] = $user_id;
            $is_bing = $doctor->where($where)->field('id')->find();
            if ($is_bing){
                $arr = array('code'=> 0, 'res'=>'该用户已经绑定其他医生');$this->ajaxReturn($arr,"JSON");
            }
        }


        $is_hos = $hospital->where(array('id'=>$hospital_id))->field('location,lng,lat')->find();
        if(!$is_hos){$arr = array('code'=> 0, 'res'=>'医院不存在');$this->ajaxReturn($arr,"JSON");}

        if($is_edit){
            if($img){
                $save['head'] = $img;
            }
            $save['sort'] = $sort;
            $save['name'] = $name;
            $save['user_id'] = $user_id;
            $save['job'] = $job;
            $save['job_age'] = $job_age;
            $save['skilled'] = $skilled;
            $save['intro'] = $intro;
            $save['hospital_id'] = $hospital_id;
            $save['location'] = $is_hos['location'];
            $save['lng'] = $is_hos['lng'];
            $save['lat'] = $is_hos['lat'];
            $save['update_time'] = time();
            $query = $doctor->where(array('id'=>$is_edit))->save($save);
            $res = '修改';
        }else{
            $add['head'] = $img;
            $add['sort'] = $sort;
            $add['name'] = $name;
            $add['user_id'] = $user_id;
            $add['job'] = $job;
            $add['job_age'] = $job_age;
            $add['skilled'] = $skilled;
            $add['intro'] = $intro;
            $add['hospital_id'] = $hospital_id;
            $add['location'] = $is_hos['location'];
            $add['lng'] = $is_hos['lng'];
            $add['lat'] = $is_hos['lat'];
            $add['create_time'] = time();
            $query = $doctor->add($add);
            $res = '新增';
        }

        if($query){
            if ($img_arr){
                if($is_edit){
                    $query = $is_edit;
                }
                //$img_list = explode(',',$img_arr);
                $add_img['parent'] = $query;
                $add_img['create_time'] = time();
                foreach ($img_arr as $item){
                    $add_img['img_url'] = $item;
                    $doctor_photo->add($add_img);
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


}












