<?php
namespace Home\Controller;
use Think\Controller;
class ShopshowController extends Controller {
    function pingan(){
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $is_smq = strpos($useragent,'YIMEI');
        $is_smq2 = strpos($useragent,'miniProgram');
        if(!$is_smq && !$is_smq2){
            echo "非法访问";die;
        }
        $user = M('user');
        $shop_order = M('shop_order');
        $hospital_order = M('hospital_order');
        $order_bx = M('order_bx');
        $noun = M('noun');

        $order_id = I('get.order_id');
        $token = I('get.token');
        $type = I('get.type');//1商城订单，2医院订单,3无订单购买，4查看成功订单
        $noun_id = I('get.noun_id');//保险ID
        $check_id = I('get.check_id');//查询成功ID
        if(!$type){
            echo "参数非法-1001";die;
        }

      /*  if(!$user_id){
            echo "参数非法";die;
        }*/
        if($type=='1' || $type=='2'){
            if(!$order_id){
                echo "参数非法-1002";die;
            }
            if($type=='1'){
                $order_info = $shop_order->where(array('id'=>$order_id))->find();
            }else{
                $order_info = $hospital_order->where(array('id'=>$order_id))->find();
            }
            if(!$order_info){
                echo "订单不存在";die;
            }
        }
        $user_info = $user->where(array('token'=>$token))->find();
        if(!$user_info){
            echo "用户信息错误";die;
        }
        if($type!='4'){
            if(!$noun_id){
                echo "参数非法-1003";die;
            }
            $noun_info = $noun->where(array('id'=>$noun_id))->find();
            if(!$noun_info){
                echo "保险信息错误";die;
            }
            if($noun_info['is_show']!=1){
                echo "保险已下架";die;
            }
            if($noun_info['type']=='1'){
                $where_ling['user_id'] = $user_info['id'];
                $where_ling['show_type'] = 1;

                $is_ling = $order_bx->where($where_ling)->find();
                if($is_ling){
                    echo "您已领取过一份免费保险，无法重复领取！";die;
                }
            }
            $url = $noun_info['url'];
        }else{
            if(!$check_id){
                echo "参数非法-1004";die;
            }
            $bx_info = $order_bx->where(array('id'=>$check_id))->find();
            if(!$bx_info){
                echo "参数非法-1005";die;
            }
            if($bx_info['user_id']!=$user_info['id']){
                echo "参数非法-1006";die;
            }
            $url = $bx_info['url'];
        }

        $data['url'] = $url;
        $data['order_id'] = $order_id;
        $data['type'] = $type;
        $data['at'] = 'shopshow';
        $data['user_id'] = $user_info['id'];
        $this->assign($data);
        $this->display();
    }

    public function file_get_contents_by_curl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, 'http://www.baidu.com/');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; GTB6.6; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);   // ssl 访问核心参数
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // ssl 访问核心参数
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function index(){
        $shop_id = I('get.shop_id');
        $type = I('get.type');
        if(!$type){$type='1';}
        if($type=='1'){
            $shop_goods_photo = M('shop_goods_photo');
            $img_list = $shop_goods_photo->where(array('goods_id'=>$shop_id))->field('content')->find();
        }else{
            $shop_prize_photo = M('shop_prize_photo');
            $img_list = $shop_prize_photo->where(array('prize_id'=>$shop_id))->field('content')->find();
        }
        $data['list'] = $img_list;
        $this->assign($data);
        $this->display();
    }

    /**
     * 提交保险数据
     */
    function GetInsuranceInfo($data_val,$user_id,$order_id,$type){
    //function GetInsuranceInfo(){
        $user = M('user');
        $order_bx = M('order_bx');
        $noun = M('noun');

        $test_arr['data'] = $data_val;
        $test_arr['user_id'] = $user_id;
        $test_arr['order_id'] = $order_id;
        $test_arr['type'] = $type;
        $add_test['content'] = json_encode($test_arr);
        M('test')->add($add_test);
        //$data_val = '{"data":"https://emcs.pa18.com/product/p_freeInsurance/index.html?appType=01&key=12004180000000561385&mainApplyPolicyNo=1EC61C67B9ACDD7595831C7E79F11A8FCEBB94D1FC02D32BC26E30698ABD1B94F60045FC72EAA79B0E0EDBE62913C946F1B602243C2D2C5B8681DD21301D33FF8FEFBEF0FEEF6F96FF97368F65A38E9E0EACE8D9C891659747279047C4D9EB9C92A5BE4F7498A066BA4A16979D445ABFC08B2F0F2DA4AAB6F4D9E3C5AFD6D13FCE7E3CD0A1C383AE951EE82645FEB91830266822B0EE21193689A18345213DEEFA64B5FEFF6AC8C3991D4D7CFD3A6423#/applyResult?","query":"?appType=01&key=12004180000000561385&mainApplyPolicyNo=1EC61C67B9ACDD7595831C7E79F11A8FCEBB94D1FC02D32BC26E30698ABD1B94F60045FC72EAA79B0E0EDBE62913C946F1B602243C2D2C5B8681DD21301D33FF8FEFBEF0FEEF6F96FF97368F65A38E9E0EACE8D9C891659747279047C4D9EB9C92A5BE4F7498A066BA4A16979D445ABFC08B2F0F2DA4AAB6F4D9E3C5AFD6D13FCE7E3CD0A1C383AE951EE82645FEB91830266822B0EE21193689A18345213DEEFA64B5FEFF6AC8C3991D4D7CFD3A6423#/applyResult?"}';

        $user_info = $user->where(array('id' => $user_id))->find();

        $data_arr = json_decode($data_val,true);
        if($data_arr['data'] && $data_arr['query']){
            $query_arr = explode('&',$data_arr['query']);
            foreach ($query_arr as $item){
                $q_arr = array();
                $q_arr = explode('=',$item);
                $new_query[$q_arr[0]] = $q_arr[1];
                //dump($item);
                /*if($show_type=='1'){
                    if(strstr($item, 'cpcNoticeNo=')){
                        $cpc = explode('cpcNoticeNo=',$item);
                        if($cpc){
                            if(count($cpc)>1){
                                $new_query['cpcNoticeNo'] = $cpc[1];
                            }else{
                                $new_query['cpcNoticeNo'] = $cpc[0];
                            }
                        }else{
                            $new_query['cpcNoticeNo'] = '0';
                        }
                    }
                }*/
            }
            if($new_query['mainApplyPolicyNo']){
                $mainApplyPolicyNo=str_replace('#/applyResult?','',$new_query['mainApplyPolicyNo']);

                //$where['order_id'] = $data_arr['order_id'];
                //$where['type'] = $data_arr['type'];
                $where['cpc_no'] = $mainApplyPolicyNo;
                $is_have = $order_bx->where($where)->field('id')->find();
                if(!$is_have){

                    $where_noun['noun_key'] = $new_query['key'];
                    $noun_info = $noun->where($where_noun)->find();
                    if($noun_info){
                        $add['cpc_no'] = $mainApplyPolicyNo;
                        $add['data'] = $data_val;
                        $add['order_id'] = $order_id;
                        $add['noun_key'] = $new_query['key'];
                        $add['parent'] = $noun_info['id'];
                        $add['type'] = $type;
                        $add['url'] = $data_arr['data'];
                        $add['show_type'] = $noun_info['type'];
                        $add['user_id'] = $user_info['id'];
                        $add['create_time'] = time();
                        $order_bx->add($add);
                    }

                }
            }
        }
        $arr = array(
            'code'=> 1,
            'res'=>'提交成功',
        );
        $this->ajaxReturn($arr,"JSON");
    }





    function explain(){
        $type = I('get.type');
        switch ($type) {
            case "1":
                $where['set_type'] = 'shop_user_xy';
                break;
            case "2":
                $where['set_type'] = 'team_xy';
                break;
            case "3":
                $where['set_type'] = 'invite_friends_xy';
                break;
            case "4":
                $where['set_type'] = 'user_xy';
                break;
             case "5":
                $where['set_type'] = 'agent_user_xy';
                break;
            case "6":
                $where['set_type'] = 'shop_pay_con';
                break;
            case "7":
                $where['set_type'] = 'gudong_h5';
                break;
            default:
                $where['set_type'] = 'shop_user_xy';
        }
        $list = M('set')->where($where)->find();
        $data['list'] = $list;
        $this->assign($data);
        $this->display();
    }


    function notice(){
        $id = I('get.id');
        $user_id = I('get.user_id');
        $notice = M('notice');
        $list = $notice->where(array('id'=>$id))->find();
        if (!session(XUANDB.'id')){
            if ($list['user_id']!='0'){
                if ($list['user_id']!=$user_id){
                    die;
                }
            }
        }

        $data['list'] = $list;
        $this->assign($data);
        $this->display();
    }

    public function test(){
        $user_id = I('get.user_id');
        $hospital = M('hospital');
        $hospital_order = M('hospital_order');
        $doctor = M('doctor');
        $hos_info = $hospital->order('rand()')->find();
        $doc_info = $doctor->where(array('hospital_id'=>$hos_info['id']))->order('rand()')->find();

        $add_order['order_number'] = time().rand(100000,9999999);
        //$add_order['user_id'] = $user_id;
        $add_order['parent'] = $hos_info['id'];
        $add_order['create_time'] = time();
        $add_order['status'] = 0;

        $add_order['doctor_id'] = 8;

        $list = array(
            array('total'=>'10000','cost'=>'1000','loans'=>'4000','is_loans'=>'1','pay_money'=>'6000'),
            array('total'=>'5000','cost'=>'800','loans'=>'0','is_loans'=>'0','pay_money'=>'5000'),
            array('total'=>'20000','cost'=>'5000','loans'=>'5000','is_loans'=>'1','pay_money'=>'15000'),
        );
        $rand = rand(0,2);

        $info = $list[$rand];
        $add_order['total_money'] = $info['total'];
        $add_order['cost'] = $info['cost'];
        $add_order['loans'] = $info['loans'];
        $add_order['is_loans'] = $info['is_loans'];
        $add_order['pay_money'] = $info['pay_money'];

        $query = $hospital_order->add($add_order);

        $content = encrypt1($query);


        $a = makecode($content,$hos_info['logo']);

        $hospital_order->where(array('id'=>$query))->save(array('qr_url'=>$a));

        $data['list'] = $a;
        $this->assign($data);
        $this->display();
    }



    function makecode(){
        /**     参数详情：
         *      $qrcode_path:logo地址
         *      $content:需要生成二维码的内容
         *      $matrixPointSize:二维码尺寸大小
         *      $matrixMarginSize:生成二维码的边距
         *      $errorCorrectionLevel:容错级别
         *      $url:生成的带logo的二维码地址
         * */
        $qrcode_path= 'http://web.yifangmeng.com/yimei/Public/Uploads/2019-06-01/2019060115111669922.jpg';
        $a = getImagetype($qrcode_path);
        dump($a);die;
        $content= '1';
        $matrixPointSize = 10;
        $matrixMarginSize = 1;
        $errorCorrectionLevel = 'H';
        ob_clean ();
        Vendor('phpqrcode.phpqrcode');
        $object = new \QRcode();
        $qrcode_path_new = 'Public/code'.'_'.date("Ymdhis").'.png';//定义生成二维码的路径及名称
        $object::png($content,$qrcode_path_new, $errorCorrectionLevel, $matrixPointSize, $matrixMarginSize);
        $QR = imagecreatefromstring(file_get_contents($qrcode_path_new));//imagecreatefromstring:创建一个图像资源从字符串中的图像流
        $logo = imagecreatefromstring(file_get_contents($qrcode_path));
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
        Header("Content-type: image/png");
        $url = 'Public/test1.png';
        //$url:定义生成带logo的二维码的地址及名称
        $a = imagepng($QR,$url);
        dump($a);

    }
    function makecode_no_pic($content,$qrcode_path_new,$matrixPointSize,$matrixMarginSize,$errorCorrectionLevel){
        ob_clean ();
        Vendor('phpqrcode.phpqrcode');
        $object = new \QRcode();
        $object::png($content,$qrcode_path_new, $errorCorrectionLevel, $matrixPointSize, $matrixMarginSize);
    }



    public function showedit(){
        $goods_id = I('get.goods_id');
        if ($goods_id){
            $list = M('shop_goods_photo')->where(array('goods_id'=>$goods_id))->find();
        }else{
            $list = array();
        }
        $data['list'] = $list;
        $this->assign($data);
        $this->display();
    }

    public function showedita(){
        $goods_id = I('get.goods_id');
        if ($goods_id){
            $list = M('shop_goods_photo')->where(array('goods_id'=>$goods_id))->find();
        }else{
            $list = array();
        }
        $data['list'] = $list;
        $this->assign($data);
        $this->display();
    }
    /*上传图片*/
    public function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize  = 3145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->saveName = date('YmdHis').rand(10000,99999);
        $upload->savePath =  'Uploads/goods/';// 设置附件上传目录
        /*if(!$upload->upload()) {// 上传错误提示错误信息
            $msg['success'] =false;
            echo json_encode($msg);
        }else{// 上传成功
            $info =  $upload->getUploadFileInfo();
            $msg['success'] =true;
            $msg['file_path'] ='/'.$info[0]['savepath'].$info[0]['savename'];
            echo json_encode($msg);
        }*/

        $info   =   $upload->uploadOne($_FILES['fileData']);
        if(!$info) {// 上传错误提示错误信息
            $msg['success'] =false;
            echo json_encode($msg);
        }else{// 上传成功 获取上传文件信息

            $msg[] =XUANIMG.'/Public/'.$info['savepath'].$info['savename'];
            $arr  = array(
                'errno'=>0,
                'data'=>$msg
            );

            $this->ajaxReturn($arr,"JSON");
        }


    }


    /*上传图片*/
    public function upload3(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize  = 3145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->saveName = date('YmdHis').rand(10000,99999);
        $upload->savePath =  'Uploads/goods_mobile/';// 设置附件上传目录
        /*if(!$upload->upload()) {// 上传错误提示错误信息
            $msg['success'] =false;
            echo json_encode($msg);
        }else{// 上传成功
            $info =  $upload->getUploadFileInfo();
            $msg['success'] =true;
            $msg['file_path'] ='/'.$info[0]['savepath'].$info[0]['savename'];
            echo json_encode($msg);
        }*/

        $info   =   $upload->uploadOne($_FILES['wangEditorMobileFile']);
        if(!$info) {// 上传错误提示错误信息
            $msg['success'] =false;
            echo json_encode($msg);
        }else{// 上传成功 获取上传文件信息

            //$msg[] =XUANIMG.'/Public/'.$info['savepath'].$info['savename'].'jpg';
            $msg =XUANIMG.'/Public/'.$info['savepath'].$info['savename'].'jpg';
           /* $arr  = array(
                'errno'=>0,
                'data'=>$msg
            );*/
            echo $msg;
            //$this->ajaxReturn($msg,"JSON");
        }


    }


    /*上传图片*/
    public function upload2(){
        $file = $_FILES['wangEditorMobileFile'];
        if ($_FILES['wangEditorMobileFile']['error']== 0) {
            //这里在同目录下需要有pictures文件夹
            $fillname = $_FILES['wangEditorMobileFile']['type']; // 得到文件全名
            $dotArray = explode('/', $fillname); // 以.分割字符串，得到数组
            $type = end($dotArray); // 得到最后一个元素：文件后缀
            if($type!='jpg' && $type!='jpeg' && $type!='png' && $type!='gif'){
               echo "error|格式错误";die;
            }
        }


        if ($_FILES['wangEditorMobileFile']['error'] == 0) {
            //这里在同目录下需要有pictures文件夹
            $fillname = time().rand(100000,999999).$type; // 得到文件全名

            $mk = date('Y-m-d');
            $path="./Public/Uploads/goods_mobile/".$mk."/";
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
            $num = date('YmdHis',time()).rand(1000,9999);
            if(move_uploaded_file($_FILES['wangEditorMobileFile']['tmp_name'], './Public/Uploads/goods_mobile/'.$mk.'/'.$num.'.'.$type)) {

                chmod( './Public/Uploads/goods_mobile/'.$mk.'/'.$num.'.'.$type, 0755);


                $add['url'] = XUANIMG.'/Public/Uploads/goods_mobile/'.$mk.'/'.$num.'.'.$type;
                //$this->ajaxReturn($add['url'],"JSON");
                echo $add['url'];
            }
        }


    }
}