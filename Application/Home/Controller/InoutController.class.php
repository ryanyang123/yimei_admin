<?php
namespace Home\Controller;
use Think\Controller;
class InoutController extends Controller {

    public function index() {
        $this->assign('current',1);
        $this->display();
    }
    public function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $expTitle.date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        $footNum = $dataNum+2;
        vendor("PHPExcel.PHPExcel");
            
        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  导出时间:'.date('Y-m-d H:i:s'));
        // 加粗
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle('A2:Q2')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$footNum.':Q'.$footNum)->getFont()->setBold(true)->setSize(12);

        // 设置行高
        $objPHPExcel->getActiveSheet()->getRowDimension($footNum)->setRowHeight(25);

        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
        }

        //设置默认行高
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(22);
        //设置列宽
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('18');
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('14');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('21');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('10');
        //设置头部
        $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);

        // 设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // 设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        // 设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A2:Q2')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


        // 设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A2:Q2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // 合计-设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A'.$footNum.':Q'.$footNum)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // 合计-设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A'.$footNum.':Q'.$footNum)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


        //边框样式
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,//细边框
                ),
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A2:Q2')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$footNum.':Q'.$footNum)->applyFromArray($styleArray);
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            // 设置行高
            $objPHPExcel->getActiveSheet()->getRowDimension($i+3)->setRowHeight(20);
            // 设置水平居中
            $objPHPExcel->getActiveSheet()->getStyle($i+3)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+3).':Q'.($i+3))->applyFromArray($styleArray);
            for($j=0;$j<$cellNum;$j++){

                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }



        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xlsx");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }


    public function exportExcel2($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $expTitle.date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        $footNum = $dataNum+2;
        vendor("PHPExcel.PHPExcel");

        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  导出时间:'.date('Y-m-d H:i:s'));
        // 加粗
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$footNum.':H'.$footNum)->getFont()->setBold(true)->setSize(12);

        // 设置行高
        $objPHPExcel->getActiveSheet()->getRowDimension($footNum)->setRowHeight(25);

        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
        }

        //设置默认行高
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(22);
        //设置列宽
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('18');
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('14');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('10');
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('21');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('10');
        //设置头部
        $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);

        // 设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // 设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        // 设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


        // 设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // 合计-设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A'.$footNum.':H'.$footNum)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // 合计-设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A'.$footNum.':H'.$footNum)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


        //边框样式
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,//细边框
                ),
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$footNum.':H'.$footNum)->applyFromArray($styleArray);
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            // 设置行高
            $objPHPExcel->getActiveSheet()->getRowDimension($i+3)->setRowHeight(20);
            // 设置水平居中
            $objPHPExcel->getActiveSheet()->getStyle($i+3)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+3).':H'.($i+3))->applyFromArray($styleArray);
            for($j=0;$j<$cellNum;$j++){

                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }



        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }






    /**
     *
     * 导出Excel-商城订单信息
     */
    function expOrderInfo(){//导出Excel
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'order'))->find();
            if(!$is_tiao){
                $this->error('权限不足！',U('order/index'),2);
            }
        }

        $xlsName = '商城订单信息';


        $shop_order = M('shop_order');
        $user = M('user');
        $shop = M('shop');
        $shop_goods = M('shop_goods');

        $search = I('get.search');
        if($search){
            $where['lyx_shop_order.order_number'] =  array("like","%".$search."%");;
            $search_list['search'] = $search;
        }

        $search2 = I('get.search2');
        if($search2){
            $where['lyx_shop_order.mobile|lyx_shop_order.addressee|lyx_shop_order.location|lyx_shop_order.particular|lyx_shop_goods.name'] =  array("like","%".$search2."%");;
            $search_list['search2'] = $search2;
        }

        $order_number = I('get.order_number');
        if($order_number){
            $where['lyx_shop_order.order_number'] = $order_number;
        }

        $user_id = I('get.user_id');
        if($user_id){
            $where['lyx_shop_order.user_id'] =  $user_id;
            $search_list['user_id'] = $user_id;
        }

        $money_type = I('get.money_type');
        if($money_type){
            $where['lyx_shop_order.money_type'] =  $money_type;
            $search_list['money_type'] = $money_type;
        }

        $pay_type = I('get.pay_type');
        if($pay_type){
            $where['lyx_shop_order.pay_type'] =  $pay_type;
            $search_list['pay_type'] = $pay_type;
        }

        $status = I('get.status');
        if($status){
            $where['lyx_shop_order.status'] =  $status;
            $search_list['status'] = $status;
        }else{
            $where['status'] =  array("NEQ",'0');
        }


        $refund_status = I('get.refund_status');
        if($refund_status){
            $where['lyx_shop_order.refund_status'] =  $refund_status-1;
            $search_list['refund_status'] = $refund_status;
        }

        $is_ti = I('get.is_ti');
        if($is_ti){
            $where['lyx_shop_order.is_ti'] =  $is_ti-1;
            $search_list['is_ti'] = $is_ti;
        }

        //时间搜索
        $search_date  = I('get.search_date');
        $search_date2  = I('get.search_date2');
        //$data['search_date'] = $search_date;
        //$data['search_date2'] = $search_date2;
        $newtime = strtotime($search_date);
        $newtime1 = strtotime($search_date2)+86399;
        if($search_date && $search_date2){
            $where['lyx_shop_order.pay_time'] = array(array('EGT',$newtime),array('ELT',$newtime1),'AND');
        }else if($search_date && !$search_date2){
            $where['lyx_shop_order.pay_time'] = array('EGT',$newtime);
        }else if(!$search_date && $search_date2){
            $where['lyx_shop_order.pay_time'] = array('ELT',$newtime1);
        }
        $search_list['search_date'] = $search_date;
        $search_list['search_date2'] = $search_date2;
        //时间搜索
        $list = $shop_order->where($where)
            ->join('lyx_shop_goods ON lyx_shop_order.goods_id = lyx_shop_goods.id')
            ->field('lyx_shop_order.*,lyx_shop_goods.name as goods_name,lyx_shop_goods.price as goods_price')
            ->order('lyx_shop_order.create_time desc')
            ->select();

        $money =0;
        $total_money =0;
        $total_num =0;
        foreach($list as $key=>$item){
            $user_name = $user->where(array('id'=>$item['user_id']))->field('id,name')->find();
            $list[$key]['user_name'] = $user_name['name'];
            $list[$key]['order_number'] = $item['order_number'].' ';
            $list[$key]['mobile'] = $item['mobile'].' ';
            //店铺名称
            $shop_name = $shop->where(array('id'=>$item['shop_id']))->field('name')->find();
            $list[$key]['shop_name'] = $shop_name['name'];
            $list[$key]['dizhi'] = $item['location'].$item['particular'];
            if($item['pay_time']){
                $list[$key]['pay_time'] = date('Y-m-d H:i:s',$item['pay_time']);
            }else{
                $list[$key]['pay_time'] = '-';
            }

            $list[$key]['create_time'] = date('Y-m-d H:i:s',$item['create_time']);

            if($item['money_type']=='1'){
                $list[$key]['money_type']= '支付预约金';
            }else{
                $list[$key]['money_type']= '支付全额';
            }
            if($item['pay_type']=='1'){$list[$key]['pay_type']= '微信';}
            if($item['pay_type']=='2'){$list[$key]['pay_type']= '支付宝';}

            if($item['status']=='1'){$list[$key]['status']= '支付预约金额';}
            if($item['status']=='2'){$list[$key]['status']= '支付全款,待发货';}
            if($item['status']=='3'){
                $list[$key]['status'] = '退款';
                if($item['refund_status']=='1'){$list[$key]['status'] .= '-买家申请中，待卖家同意';}
                if($item['refund_status']=='2'){$list[$key]['status'] .= '-卖家拒绝退款';}
                if($item['refund_status']=='3'){$list[$key]['status'] .= '-买家申请客服介入';}
                if($item['refund_status']=='4'){$list[$key]['status'] .= '-退款成功';}
                if($item['refund_status']=='5'){$list[$key]['status'] .= '-退款中';}
                $list[$key]['status'] .= '  申请退款前状态：';
                if($item['front_status']=='1'){$list[$key]['status'] .= '支付预约金额';}
                if($item['front_status']=='2'){$list[$key]['status'] .= '支付全款,待发货';}
                if($item['front_status']=='4'){$list[$key]['status'] .= '已发货，待收货';}
                if($item['front_status']=='5'){$list[$key]['status'] .= '交易完成(未评价)';}
                if($item['front_status']=='6'){$list[$key]['status'] .= '交易完成(已评价)';}

            }
            if($item['status']=='4'){$list[$key]['status']= '已发货，待收货';}
            if($item['status']=='5'){$list[$key]['status']= '交易完成(未评价)';}
            if($item['status']=='6'){$list[$key]['status']= '交易完成(已评价)';}
            $total_num += $item['total_num'];
            $total_money += $item['total_money'];
            $money += $item['money'];
        }


        foreach($list as $item){
            $xlsData[] = $item;
        }

        $foot['order_number'] = '合计';
        $foot['user_name'] = '-';
        $foot['addressee'] = '-';
        $foot['mobile'] = '-';
        $foot['dizhi'] = '-';
        $foot['goods_name'] = '-';
        $foot['goods_price'] = '-';
        $foot['shop_name'] = '-';
        $foot['total_num'] = $total_num;
        $foot['money_type'] = '-';
        $foot['money'] = $money;
        $foot['total_money'] = $total_money;
        $foot['pay_type'] = '-';
        $foot['status'] = '-';
        $foot['pay_time'] = '-';
        $foot['create_time'] = '-';
        $foot['remark'] = '-';

        $xlsData[] = $foot;
        $xlsCell  = array(
            array('order_number','订单编号'),
            array('user_name','购买用户'),
            array('addressee','收件人'),
            array('mobile','电话'),
            array('dizhi','收货地址'),
            array('goods_name','购买商品'),
            array('goods_price','商品价格'),
            array('shop_name','购买店铺'),
            array('total_num','购买数量'),
            array('money_type','支付类型'),
            array('money','支付金额'),
            array('total_money','订单总金额'),
            array('pay_type','支付方式'),
            array('status','订单状态'),
            array('pay_time','支付时间'),
            array('create_time','创建时间'),
            array('remark','买家备注'),
        );


        $this->exportExcel($xlsName,$xlsCell,$xlsData);
    }

    /**
     *
     * 导出Excel-积分商城订单信息
     */
    function expPrizeOrderInfo(){//导出Excel
        if(session(XUANDB.'user_type')!='1'){
            $is_tiao = M('limits_log')->where(array('admin_id'=>session(XUANDB.'id'),'lid'=>'shopprizeorder'))->find();
            if(!$is_tiao){
                $this->error('权限不足！',U('shopprizeorder/index'),2);
            }
        }

        $xlsName = '积分商城订单信息';

        $user = M('user');
        $shop_prize_order = M('shop_prize_order');
        $shop_prize = M('shop_prize');

        $shop_prize_select = M('shop_prize_select');


        $user_id = I('get.user_id');
        if($user_id){
            $where['user_id'] = $user_id;
        }

        $order_number = I('get.order_number');
        if($order_number){
            $where['order_number'] = $order_number;
        }

        $status = I('get.status');
        if($status){
            $where['status'] = $status;
        }else{
            $where['status'] = array('NEQ','0');
        }

        $search = I('get.search');
        if($search){
            $where['order_number|addressee|mobile'] =  array("like","%".$search."%");
        }

        //时间搜索
        $search_date  = I('get.search_date');
        $search_date2  = I('get.search_date2');
        //$data['search_date'] = $search_date;
        //$data['search_date2'] = $search_date2;
        $newtime = strtotime($search_date);
        $newtime1 = strtotime($search_date2)+86399;
        if($search_date && $search_date2){
            $where['pay_time'] = array(array('EGT',$newtime),array('ELT',$newtime1),'AND');
        }else if($search_date && !$search_date2){
            $where['pay_time'] = array('EGT',$newtime);
        }else if(!$search_date && $search_date2){
            $where['pay_time'] = array('ELT',$newtime1);
        }
        //时间搜索


        $total_num = 0;
        $total_score = 0;
        $list = $shop_prize_order->where($where)->order('status,fh_time desc')->select();
        foreach($list as $key=>$item){
            $user_name = $user->where(array('id'=>$item['user_id']))->field('name,phone')->find();
            $list[$key]['user_name'] = $user_name['name'];

            /*  if($item['ex_id']){
                  $list[$key]['ex_name'] = $new_expresses[$item['ex_id']];
              }*/
            $list[$key]['order_number'] = $item['order_number'].' ';
            $list[$key]['mobile'] = $item['mobile'].' ';
            $prize_info = $shop_prize->where(array('id'=>$item['prize_id']))->field('name,score')->find();
            $prize_info['select_val'] = $item['select_val'];
            $list[$key]['prize_info'] = $prize_info;
            $list[$key]['prize_name'] = $prize_info['name'];
            $list[$key]['prize_score'] = $prize_info['score'];
            $list[$key]['prize_select'] = $prize_info['select_val'];
            if($item['pay_time']){
                $list[$key]['pay_time'] = date('Y-m-d H:i:s',$item['pay_time']);
            }else{
                $list[$key]['pay_time'] = '-';
            }
            $list[$key]['dizhi'] = $item['location'].$item['particular'];
            if($item['status']=='1'){$list[$key]['status']= '已支付-发货中';}
            if($item['status']=='2'){$list[$key]['status']= '已发货-待收货';}
            if($item['status']=='3'){$list[$key]['status']= '交易完成';}
            $total_num+=$item['num'];
            $total_score+=$item['total_score'];
        }
        foreach($list as $item){
            $xlsData[] = $item;
        }

        $foot['order_number'] = '合计';
        $foot['total_score'] = $total_score;
        $foot['user_name'] = '-';
        $foot['addressee'] = '-';
        $foot['mobile'] = '-';
        $foot['dizhi'] = '-';
        $foot['prize_name'] = '-';
        $foot['prize_score'] = '-';
        $foot['prize_select'] = '-';
        $foot['num'] = $total_num;
        $foot['goods_name'] = '-';
        $foot['pay_time'] = '-';
        $foot['remark'] = '-';

        $xlsData[] = $foot;
        $xlsCell  = array(
            array('order_number','订单编号'),
            array('total_score','购买积分'),
            array('user_name','用户'),
            array('addressee','收件人'),
            array('mobile','电话'),
            array('dizhi','收货地址'),
            array('prize_name','兑换商品'),
            array('prize_score','商品积分'),
            array('prize_select','兑换规格'),
            array('num','兑换数量'),
            array('goods_name','当前状态'),
            array('pay_time','支付时间'),
            array('remark','买家备注'),
        );


        $this->exportExcel($xlsName,$xlsCell,$xlsData);
    }

















    /**
     *
     * 显示导入页面 ...
     */


    /**
     *
     * 导出Excel
     */


    /**实现导入excel
     **/
    function impUser(){
        if (!empty($_FILES)) {
            $item = M('item');
            $product = M('product');
            $repertory = M('repertory');
            $shift = M('shift');
            $user_type = M('user_type');

            $upload = new \Think\Upload();// 实例化上传类
            $filepath='./Public/Excel/';
            $upload->exts = array('xlsx','xls');// 设置附件上传类型
            $upload->rootPath  =  $filepath; // 设置附件上传根目录
            $upload->saveName  =     'time';
            $upload->autoSub   =     false;
            if (!$info=$upload->upload()) {
                $this->error($upload->getError());
            }

            foreach ($info as $key => $value) {
                unset($info);
                $info[0]=$value;
                $info[0]['savepath']=$filepath;
            }
            vendor("PHPExcel.PHPExcel");
            $file_name = $info[0]['savepath'].$info[0]['savename'];
            $extension = strtolower( pathinfo($file_name, PATHINFO_EXTENSION) );
            if($extension =='xlsx'){
                $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            }else if($extension =='xls'){
                $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            }else if ($extension=='csv') {
                $objReader = \PHPExcel_IOFactory::createReader('CSV');
            }

            $objPHPExcel = $objReader->load($file_name,$encode='utf-8');
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumn = $sheet->getHighestColumn(); // 取得总列数
            $j=0;
            $type = I('get.type');
            //判断导入类型
            if($type == 'classify'){
                for($i=2;$i<=$highestRow;$i++)
                {
                    $data['part_no']= $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();

                    // if(M('Contacts')->where("name='".$data['name']."' and phone=$data['phone']")->find()){
                    if(!$item->where($data)->find()){
                        $data['name']= $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
                        $data['create_time']= time();
                        $item->add($data);
                        $j++;
                    }
                    $data=array();
                }
            }else if($type == 'product'){
                $where_ck['name'] = array('IN','倉庫,仓库');
                $ck = $user_type->where($where_ck)->find();
                for($i=2;$i<=$highestRow;$i++)
                {
                    $data['sn']= $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();

                    $data['num']= $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
                    if(!$data['num']){
                        $data['num'] = 1;
                    }
                    $data['location']= $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
                    $data['create_time']= time();

                    $data['part_no']= $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
                    $where_part['part_no']=$data['part_no'];
                    $query_item = $item->where($where_part)->find();
                    if($query_item){

                        $where_locat['name'] = $data['location'];
                        $newlocat = $user_type->where($where_locat)->find();
                        if($newlocat){
                            $locat_id = $newlocat['id'];
                            $data['location'] = $locat_id;
                        }else{
                            $add_locat['type'] = 0;
                            $add_locat['name'] = $data['location'];
                            $add_locat['user_id'] = 0;
                            $add_locat['user_type'] = 6;
                            $add_locat['create_time'] = time();
                            $locat_id = $user_type->add($add_locat);
                            $data['location'] = $locat_id;
                        }

                        $data['item']= $query_item['name'];
                        if($data['sn']){
                            $data['num'] = 1;
                            $where_sn['sn'] = $data['sn'];
                            $where_sn['part_no'] = $data['part_no'];
                            if(!$product->where($where_sn)->find()){
                                $query = $product->add($data);
                                if($query){

                                    $add_repe['in'] = $ck['id'];
                                    $add_repe['product'] = $query;
                                    $add_repe['num'] = $data['num'];
                                    $add_repe['location'] = $locat_id;
                                    $add_repe['create_time'] = time();
                                    $repertory->add($add_repe);

                                    $add_shift['shift_time'] = time();
                                    $add_shift['submit'] = 1;
                                    $add_shift['time'] ="00:00";
                                    $add_shift['part_no'] =$query;
                                    $add_shift['num'] =$data['num'];
                                    $add_shift['status'] =0;
                                    $add_shift['to'] = $ck['id'];
                                    $add_shift['in'] = $locat_id;
                                    $add_shift['create_time'] = time();
                                    $shift->add($add_shift);
                                }
                                $j++;
                            }
                        }else{
                            $data['sn'] = '';
                            $where_part['part_no'] = $data['part_no'];
                            $where_part['sn'] = array('EXP','IS NULL');
                            if($query_name = $product->where($where_part)->find()) {
                                $save['num'] = $query_name['num'] + $data['num'];
                                $product->where('id='.$query_name['id'])->save($save);
                            }else{
                                $query = $product->add($data);
                                if($query){
                                    $add_repe['in'] = $ck['id'];
                                    $add_repe['product'] = $query;
                                    $add_repe['num'] = $data['num'];
                                    $add_repe['location'] = $locat_id;
                                    $add_repe['create_time'] = time();
                                    $repertory->add($add_repe);
                                }
                            }
                            $j++;
                        }
                    }

                    $data=array();
                }
            }

            unlink($file_name);
            //User_log('批量导入联系人，数量：'.$j);
            $this->success('導入成功！本次導入産品數據：'.$j.'條');
        }else
        {
            $this->error("請選擇上傳的文件");
        }
    }
}

?>