<?php if (!defined('THINK_PATH')) exit();?><!--引用文件head-->
<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->

<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

    <meta charset="utf-8" />

    <title>售卡系统 | 后台管理</title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <meta content="" name="description" />

    <meta content="" name="author" />

    <!-- BEGIN GLOBAL MANDATORY STYLES -->

    <link href="/sellcard/Public/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <link href="/sellcard/Public/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

    <link href="/sellcard/Public/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

    <link href="/sellcard/Public/css/style-metro.css" rel="stylesheet" type="text/css"/>

    <link href="/sellcard/Public/css/style.css" rel="stylesheet" type="text/css"/>

    <link href="/sellcard/Public/css/style-responsive.css" rel="stylesheet" type="text/css"/>

    <link href="/sellcard/Public/css/default.css" rel="stylesheet" type="text/css" id="style_color"/>

    <link href="/sellcard/Public/css/uniform.default.css" rel="stylesheet" type="text/css"/>

    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->

    <link href="/sellcard/Public/css/jquery.gritter.css" rel="stylesheet" type="text/css"/>

    <link href="/sellcard/Public/css/daterangepicker.css" rel="stylesheet" type="text/css" />

    <link href="/sellcard/Public/css/fullcalendar.css" rel="stylesheet" type="text/css"/>

    <link href="/sellcard/Public/css/jqvmap.css" rel="stylesheet" type="text/css" media="screen"/>

    <link href="/sellcard/Public/css/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>

    <!-- END PAGE LEVEL STYLES -->
    <link href="/sellcard/Public/css/jquery.fancybox.css" rel="stylesheet" />


    <link href="/sellcard/Public/css/search.css" rel="stylesheet" type="text/css"/>






    <link rel="stylesheet" href="/sellcard/Public/css/kalendae.css" type="text/css" charset="utf-8">

    <link href="/sellcard/Public/css/xuan.css" rel="stylesheet" type="text/css"/>

    <link rel="shortcut icon" href="/sellcard/Public/image/logo180.ico" />

</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class="page-header-fixed">

<!-- BEGIN HEADER -->

<!--头部导航栏-->
<div class="header navbar navbar-inverse navbar-fixed-top">

    <!-- BEGIN TOP NAVIGATION BAR -->

    <div class="navbar-inner">

        <div class="container-fluid">

            <!-- BEGIN LOGO -->

            <a class="brand" href="index.html">

                <img src="/sellcard/Public/image/logo.png" alt="logo"/>

            </a>

            <!-- END LOGO -->

            <!-- BEGIN RESPONSIVE MENU TOGGLER -->

            <a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">

                <img src="/sellcard/Public/image/menu-toggler.png" alt="" />

            </a>

            <!-- END RESPONSIVE MENU TOGGLER -->

            <!-- BEGIN TOP NAVIGATION MENU -->

            <ul class="nav pull-right">

                <!-- BEGIN NOTIFICATION DROPDOWN -->

                <!--<li class="dropdown" id="header_notification_bar">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <i class="icon-warning-sign"></i>

                        <span class="badge">6</span>

                    </a>

                    <ul class="dropdown-menu extended notification">

                        <li>

                            <p>You have 14 new notifications</p>

                        </li>

                        <li>

                            <a href="#">

                                <span class="label label-success"><i class="icon-plus"></i></span>

                                New user registered.

                                <span class="time">Just now</span>

                            </a>

                        </li>

                        <li>

                            <a href="#">

                                <span class="label label-important"><i class="icon-bolt"></i></span>

                                Server #12 overloaded.

                                <span class="time">15 mins</span>

                            </a>

                        </li>

                        <li>

                            <a href="#">

                                <span class="label label-warning"><i class="icon-bell"></i></span>

                                Server #2 not respoding.

                                <span class="time">22 mins</span>

                            </a>

                        </li>

                        <li>

                            <a href="#">

                                <span class="label label-info"><i class="icon-bullhorn"></i></span>

                                Application error.

                                <span class="time">40 mins</span>

                            </a>

                        </li>

                        <li>

                            <a href="#">

                                <span class="label label-important"><i class="icon-bolt"></i></span>

                                Database overloaded 68%.

                                <span class="time">2 hrs</span>

                            </a>

                        </li>

                        <li>

                            <a href="#">

                                <span class="label label-important"><i class="icon-bolt"></i></span>

                                2 user IP blocked.

                                <span class="time">5 hrs</span>

                            </a>

                        </li>

                        <li class="external">

                            <a href="#">See all notifications <i class="m-icon-swapright"></i></a>

                        </li>

                    </ul>

                </li>
-->

                <!--锁屏-->
                <!--<li class="dropdown" >

                    <a href="extra_lock.html" title="锁屏" class="dropdown-toggle" >

                        <i class="icon-lock"></i>

                        &lt;!&ndash;<span class="badge">6</span>&ndash;&gt;

                    </a>
                    <ul class="dropdown-menu extended notification">
                    </ul>
                </li>-->

                <li class="dropdown" >

                    <a href="<?php echo U('home/logout/index');?>" title="退出" class="dropdown-toggle" >

                        <i class="icon-off"></i>

                        <!--<span class="badge">6</span>-->

                    </a>
                    <ul class="dropdown-menu extended notification">
                    </ul>
                </li>





                <!-- END NOTIFICATION DROPDOWN -->

                <!-- BEGIN INBOX DROPDOWN -->

                <!-- <li class="dropdown" id="header_inbox_bar">

                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                         <i class="icon-envelope"></i>

                         <span class="badge">5</span>

                     </a>

                     <ul class="dropdown-menu extended inbox">

                         <li>

                             <p>You have 12 new messages</p>

                         </li>

                         <li>

                             <a href="inbox.html?a=view">

                                 <span class="photo"><img src="/sellcard/Public/image/avatar2.jpg" alt="" /></span>

                                 <span class="subject">

                                 <span class="from">Lisa Wong</span>

                                 <span class="time">Just Now</span>

                                 </span>

                                 <span class="message">

                                 Vivamus sed auctor nibh congue nibh. auctor nibh

                                 auctor nibh...

                                 </span>

                             </a>

                         </li>

                         <li>

                             <a href="inbox.html?a=view">

                                 <span class="photo"><img src=".//sellcard/Public/image/avatar3.jpg" alt="" /></span>

                                 <span class="subject">

                                 <span class="from">Richard Doe</span>

                                 <span class="time">16 mins</span>

                                 </span>

                                 <span class="message">

                                 Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh

                                 auctor nibh...

                                 </span>

                             </a>

                         </li>

                         <li>

                             <a href="inbox.html?a=view">

                                 <span class="photo"><img src=".//sellcard/Public/image/avatar1.jpg" alt="" /></span>

                                 <span class="subject">

                                 <span class="from">Bob Nilson</span>

                                 <span class="time">2 hrs</span>

                                 </span>

                                 <span class="message">

                                 Vivamus sed nibh auctor nibh congue nibh. auctor nibh

                                 auctor nibh...

                                 </span>

                             </a>

                         </li>

                         <li class="external">

                             <a href="inbox.html">See all messages <i class="m-icon-swapright"></i></a>

                         </li>

                     </ul>

                 </li>-->

                <!-- END INBOX DROPDOWN -->

                <!-- BEGIN TODO DROPDOWN -->

                <!--<li class="dropdown" id="header_task_bar">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <i class="icon-tasks"></i>

                        <span class="badge">5</span>

                    </a>

                    <ul class="dropdown-menu extended tasks">

                        <li>

                            <p>You have 12 pending tasks</p>

                        </li>

                        <li>

                            <a href="#">

								<span class="task">

								<span class="desc">New release v1.2</span>

								<span class="percent">30%</span>

								</span>

								<span class="progress progress-success ">

								<span style="width: 30%;" class="bar"></span>

								</span>

                            </a>

                        </li>

                        <li>

                            <a href="#">

								<span class="task">

								<span class="desc">Application deployment</span>

								<span class="percent">65%</span>

								</span>

								<span class="progress progress-danger progress-striped active">

								<span style="width: 65%;" class="bar"></span>

								</span>

                            </a>

                        </li>

                        <li>

                            <a href="#">

								<span class="task">

								<span class="desc">Mobile app release</span>

								<span class="percent">98%</span>

								</span>

								<span class="progress progress-success">

								<span style="width: 98%;" class="bar"></span>

								</span>

                            </a>

                        </li>

                        <li>

                            <a href="#">

								<span class="task">

								<span class="desc">Database migration</span>

								<span class="percent">10%</span>

								</span>

								<span class="progress progress-warning progress-striped">

								<span style="width: 10%;" class="bar"></span>

								</span>

                            </a>

                        </li>

                        <li>

                            <a href="#">

								<span class="task">

								<span class="desc">Web server upgrade</span>

								<span class="percent">58%</span>

								</span>

								<span class="progress progress-info">

								<span style="width: 58%;" class="bar"></span>

								</span>

                            </a>

                        </li>

                        <li>

                            <a href="#">

								<span class="task">

								<span class="desc">Mobile development</span>

								<span class="percent">85%</span>

								</span>

								<span class="progress progress-success">

								<span style="width: 85%;" class="bar"></span>

								</span>

                            </a>

                        </li>

                        <li class="external">

                            <a href="#">See all tasks <i class="m-icon-swapright"></i></a>

                        </li>

                    </ul>

                </li>-->

                <!-- END TODO DROPDOWN -->

                <!-- BEGIN USER LOGIN DROPDOWN -->

                <li class="dropdown user">

                    <a href="#" class="dropdown-toggle" style="line-height: 33px;" data-toggle="">

                        <img alt="" src="" />

                        <span class="username"><?php echo session(XUANDB.'user_name');?></span>


                    </a>

                    <ul class="dropdown-menu">

             <!--           <li><a href="extra_profile.html"><i class="icon-user"></i> My Profile</a></li>

                        <li><a href="page_calendar.html"><i class="icon-calendar"></i> My Calendar</a></li>

                        <li><a href="inbox.html"><i class="icon-envelope"></i> My Inbox(3)</a></li>

                        <li><a href="#"><i class="icon-tasks"></i> My Tasks</a></li>

                        <li class="divider"></li>

                        <li><a href="extra_lock.html"><i class="icon-lock"></i> 锁屏</a></li>

                        <li><a href="login.html"><i class="icon-key"></i> 退出</a></li>-->

                    </ul>

                </li>

                <!-- END USER LOGIN DROPDOWN -->

            </ul>

            <!-- END TOP NAVIGATION MENU -->

        </div>

    </div>

    <!-- END TOP NAVIGATION BAR -->
</div>

<style>
    .four_div{
        margin: 0;width: 50%;
    }
    .portlet{
        margin-bottom: 0;
    }
    .portlet-body{
        height: 370px;;
        overflow-y: auto;
    }
    .btn_z{
        background: #fff;;
        border: 1px solid #000;;
        border-radius: 5px!important;;
    }
    .zdy_table{
        width: 100%;;
        margin:0 5px;;
    }
    .zdy_table th{
        text-align: left;
    }
    .zdy_table tr{
        line-height: 40px;;
    }
    .gd_span{
        margin-left: 20px!important;;
        display: inline-block!important;;
        float: none;
    }
    .bitian{
        line-height: 30px;color: red;font-weight: bolder;font-size: 16px;
    }
    .hide_text_inp{
        width: 0.1px!important;
        height: 0!important;
        padding: 0!important;
        margin: 0!important;
        float: right!important;
        border: none!important;
    }

    .red_bg{
        background: rgba(255,0,0,0.6)!important;
    }
</style>

<div class="page-container">


    <!--导航栏-->
    <div class="page-sidebar nav-collapse collapse">

    <!-- BEGIN SIDEBAR MENU -->
    <style>
        .tubiao{
            border-radius: 50%!important;
            float: right;
            padding:0;
            display: inline-block;;
            width: 25px;
            height: 25px;;
            text-align: center;
            line-height: 25px;;
        }

    </style>
    <ul class="page-sidebar-menu">

        <li>

            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->

            <div class="sidebar-toggler hidden-phone"></div>

            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->

        </li>

        <li>

            <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->

           <!-- <form class="sidebar-search">

                <div class="input-box">

                    <a href="javascript:;" class="remove"></a>

                    <input type="text" placeholder="Search..." />

                    <input type="button" class="submit" value=" " />

                </div>

            </form>-->

            <!-- END RESPONSIVE QUICK SEARCH FORM -->

        </li>

        <li class="start <?php echo $at== 'home'?'active':'';?>">

            <a href="<?php echo U('home/home/index');?>">

                <i class="icon-home"></i>

                <span class="title">首页</span>

                <span class="selected"></span>

            </a>

        </li>


        <?php $limit_list = limits_status();?>
        <?php $show_num = show_num();?>

        <?php if($limit_list["1"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at== 'service'?'active':'';?>">
                <a href="<?php echo U('home/service/index');?>" class="overall_a overall_a_service">
                    <i class="icon-headphones"></i>
                    <span class="title">客服值班页面
                      <?php if($show_num["service_num"] != 0): ?><span class="badge badge-important tubiao"><?php echo ($show_num["service_num"]); ?></span><?php endif; ?>
                    </span>

                </a>

            </li><?php endif; ?>

        <?php if($limit_list["2"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at== 'manager'?'active':'';?>">
            <a href="<?php echo U('home/manager/index');?>"  class="overall_a overall_a_manager">
                <i class="icon-road"></i>
                <span class="title">客户经理值班页面
                <?php if($show_num["manager_num"] != 0): ?><span class="badge badge-important tubiao"><?php echo ($show_num["manager_num"]); ?></span><?php endif; ?>
                </span>

            </a>

        </li><?php endif; ?>
        <?php if($limit_list["3"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at== 'order'?'active':'';?>">
            <a href="<?php echo U('home/order/index');?>"  class="overall_a overall_a_order">
                <i class="icon-paste"></i>
                <span class="title">订单处理系统
                  <?php if($show_num["order_num"] != 0): ?><span class="badge badge-important tubiao"><?php echo ($show_num["order_num"]); ?></span><?php endif; ?>
                </span>

            </a>

        </li><?php endif; ?>

        <?php if($limit_list["4"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at == 'card'?'active':'';?>">
            <a href="<?php echo U('home/card/index');?>">
                <i class="icon-credit-card"></i>
                <span class="title">购卡平台</span>

            </a>

        </li><?php endif; ?>

      <!--  <?php if($limit_list["5"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at == 'vip'?'active':'';?>">
            <a href="<?php echo U('home/vip/index');?>">
                <i class="icon-trophy"></i>
                <span class="title">VIP列表</span>

            </a>

        </li><?php endif; ?>-->

        <?php if($limit_list["6"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at == 'stock'?'active':'';?>">
            <a href="<?php echo U('home/stock/index');?>">
                <i class="icon-folder-open"></i>
                <span class="title">卡包库</span>

            </a>

        </li><?php endif; ?>

        <?php if($limit_list["7"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at == 'cost'?'active':'';?>">
                <a href="<?php echo U('home/cost/index');?>">
                    <i class="icon-jpy"></i>
                    <span class="title">其他成本录入</span>

                </a>

            </li><?php endif; ?>

        <?php if($limit_list["8"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at == 'system'?'active':'';?>">
                <a href="<?php echo U('home/system/index');?>">
                    <i class="icon-envelope"></i>
                    <span class="title">系统公告</span>

                </a>

            </li><?php endif; ?>

        <!--<?php if($limit_list["9"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at == 'month'?'active':'';?>">
                <a href="<?php echo U('home/month/index');?>">
                    <i class="icon-calendar"></i>
                    <span class="title">月卡列表</span>
                </a>
            </li><?php endif; ?>-->

        <?php if($limit_list["10"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at == 'orderlist'?'active':'';?>">
                <a href="<?php echo U('home/orderlist/index');?>">
                    <i class="icon-print"></i>
                    <span class="title">订单列表</span>
                </a>
            </li><?php endif; ?>
        <?php if($limit_list["13"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at == 'huan'?'active':'';?>">
                <a href="<?php echo U('home/huan/index');?>">
                    <i class="icon-random"></i>
                    <span class="title">置换列表</span>
                </a>
            </li><?php endif; ?>

        <?php if($limit_list["12"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at == 'event'?'active':'';?>">
                <a href="<?php echo U('home/event/index');?>">
                    <i class="icon-fire"></i>
                    <span class="title">事件列表</span>
                </a>
            </li><?php endif; ?>


        <?php if($limit_list["11"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at == 'user'?'active':'';?>">
                <a href="<?php echo U('home/user/index');?>">
                    <i class="icon-group"></i>
                    <span class="title">用户列表</span>
                </a>
            </li><?php endif; ?>



        <?php if($limit_list["5"] == 1 or $limit_list["type"] == 1 or $limit_list["9"] == 1 ): ?><li class="<?php echo$at == 'vip' || $at == 'month' ?'active':'';?>">

            <a href="javascript:;">

                <i class="icon-gift"></i>

                <span class="title">活动列表</span>

                <span class="arrow <?php echo $at == 'user' || $at == 'user2' ?'open':'';?>"></span>

            </a>

            <ul class="sub-menu">

                <?php if($limit_list["5"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at == 'vip'?'active':'';?>">
                        <a href="<?php echo U('home/vip/index');?>">
                            <span class="title">VIP列表</span>

                        </a>

                    </li><?php endif; ?>

                <?php if($limit_list["9"] == 1 or $limit_list["type"] == 1): ?><li class="last <?php echo $at == 'month'?'active':'';?>">
                        <a href="<?php echo U('home/month/index');?>">
                            <span class="title">月卡列表</span>
                        </a>
                    </li><?php endif; ?>


            </ul>

        </li><?php endif; ?>

        <li class="<?php echo$at == '1' || $at == '2' ?'active':'';?>">

            <a href="javascript:;">

                <i class="icon-bar-chart"></i>

                <span class="title">数据统计</span>

                <span class="arrow <?php echo $at == 'user' || $at == 'user2' ?'open':'';?>"></span>

            </a>

            <ul class="sub-menu">


                    <li class="last <?php echo $at == '1'?'active':'';?>">
                        <a href="">
                            <span class="title">流水和利润</span>

                        </a>

                    </li>



                    <li class="last <?php echo $at == '2'?'active':'';?>">
                        <a href="">
                            <span class="title">用户留存</span>
                        </a>
                    </li>

                <li class="last <?php echo $at == '2'?'active':'';?>">
                    <a href="">
                        <span class="title">用户转化</span>
                    </a>
                </li>
                <li class="last <?php echo $at == '2'?'active':'';?>">
                    <a href="">
                        <span class="title">用户支付渗透率</span>
                    </a>
                </li>
                <li class="last <?php echo $at == '2'?'active':'';?>">
                    <a href="">
                        <span class="title">流失用户</span>
                    </a>
                </li>
                <li class="last <?php echo $at == '2'?'active':'';?>">
                    <a href="">
                        <span class="title">房卡销售统计</span>
                    </a>
                </li>


            </ul>

        </li>









        <?php if($limit_list["type"] == 1): ?><li class="last <?php echo $at == 'admin'?'active':'';?>">
            <a href="<?php echo U('home/admin/index');?>">
                <i class="icon-briefcase"></i>
                <span class="title">账号管理</span>

            </a>

        </li><?php endif; ?>


       <!-- <li class="last <?php echo $at == 'reguser'?'active':'';?>">
            <a href="<?php echo U('home/reguser/index');?>">
                <i class="icon-user"></i>
                <span class="title">注册客户列表</span>

            </a>

        </li>-->

       <!--<li class="last <?php echo $at == 'vehicle'?'active':'';?>">
            <a href="<?php echo U('home/vehicle/index');?>">
                <i class="icon-truck"></i>
                <span class="title">车辆配送信息</span>

            </a>

        </li>-->













        <li class="last <?php echo $at == 'editpass'?'active':'';?>">
            <a href="<?php echo U('home/editpass/index');?>">
                <i class="icon-key"></i>
                <span class="title">修改密码</span>

            </a>

        </li>
        <?php if($limit_list["type"] == 1): ?><li class="last <?php echo $at == 'set'?'active':'';?>">
            <a href="<?php echo U('home/set/index');?>">
                <i class="icon-cog"></i>
                <span class="title">设置</span>

            </a>

        </li><?php endif; ?>


    </ul>

    <!-- END SIDEBAR MENU -->

</div>



    <div class="page-content">
        <!-- BEGIN PAGE CONTAINER-->

        <div class="span6 four_div">
        <!-- BEGIN CONDENSED TABLE PORTLET-->
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="icon-plus"></i>待加用户</div>
            </div>

            <div class="portlet-body">
                <table class="table event_user_table">

                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>用户联系方式</th>
                        <th>咨询关键字</th>
                        <th>备注</th>
                        <th>操作</th>
                    </tr>

                    </thead>
                    <tbody id="tbody_1">
                    <?php if(is_array($event_list)): foreach($event_list as $key=>$v): ?><tr class="event_tr tr_red<?php echo ($v["id"]); ?> <?php if($v['is_red'] == 0): ?>red_bg<?php endif; ?>" >
                            <td><?php echo $key+1;?></td>

                            <td><?php echo ($v["user_contact"]); ?></td>
                            <td><?php echo ($v["user_keyword"]); ?></td>
                            <td><?php echo ($v["user_remark"]); ?></td>
                            <td>
                                <button type="button" onclick="OnApply('<?php echo ($v["id"]); ?>')"  class="btn btn_z apply_btn<?php echo ($v["id"]); ?>"  <?php if($v['status'] != 1): ?>disabled<?php endif; ?>>已申请</button>
                                <button type="button" onclick="IsAdd('<?php echo ($v["id"]); ?>')"  class="btn btn_z add_btn<?php echo ($v["id"]); ?>" <?php if($v['status'] != 2): ?>disabled<?php endif; ?> >已添加</button>
                                <button type="button" onclick="UserInexistence('<?php echo ($v["id"]); ?>','4')" class="btn btn_z" >用户不存在</button>
                                <button type="button" onclick="UserInexistence('<?php echo ($v["id"]); ?>','5')"  class="btn btn_z">协助跟进</button>
                                <button type="button" onclick="CloseEvent('<?php echo ($v["id"]); ?>')" class="btn btn_z">关单</button>
                            </td>
                        <?php if($v['is_red'] == 1): ?><input type="hidden" value="<?php echo ($v["update_time"]); ?>" class="red_time<?php echo ($v["id"]); ?>"/>
                            <input type="hidden" value="<?php echo ($v["id"]); ?>" class="red_d"/><?php endif; ?>
                        </tr><?php endforeach; endif; ?>


                    </tbody>

                </table>


            </div>

        </div>

        <!-- END CONDENSED TABLE PORTLET-->

    </div>
        <div class="span6 four_div">
            <!-- BEGIN CONDENSED TABLE PORTLET-->
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-hdd"></i>快捷操作工作台</div>
                </div>

                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-advance table-hover">

                        <thead>

                        <tr>

                            <th  style="border-right: 1px solid #ccc;text-align: center;"><i class="icon-briefcase"></i> 值班快捷入口</th>

                            <th  style="text-align: center;" ><i class="icon-question-sign"></i> 工作台快捷入口</th>

                        </tr>

                        </thead>

                        <tbody>

                        <tr>

                            <td style="border-right: 1px solid #ccc;text-align: center;">
                                <div style="margin: 20px 0;">
                                    <p>
                                        当前状态:
                                        <?php if($login_type == 1): ?><span class="label label-success">值班中</span><?php endif; ?>
                                        <?php if($login_type == 0): ?><span class="label label-important">登出状态</span><?php endif; ?>
                                    </p>
                                    <br/>
                                    <p><button type="button" onclick="LoginType('1','登入值班')" <?php if($login_type == 1): ?>disabled<?php endif; ?> class="btn green"><i class="icon-signin"></i> 登入值班坐席</button></p>
                                    <br/>
                                    <p><button type="button" onclick="LoginType('0','退出值班')" <?php if($login_type == 0): ?>disabled<?php endif; ?> class="btn red"><i class="icon-off"></i> 签退值班坐席</a></button>
                                </div>

                            </td>

                            <td style="text-align: center;">
                                <div style="margin: 20px 0;">
                                    <p><a href="JavaScript:UserGuid2();" class="btn blue"><i class="icon-plus"></i> 新用户手动创档</a></p>
                                    <br/>
                                    <p><a href="JavaScript:AddOrder();" class="btn blue"><i class="icon-signout"></i> 低消用户请卡入口</a></p>
                                </div>
                            </td>
                        </tr>

                        </tbody>

                    </table>


                </div>

            </div>

            <!-- END CONDENSED TABLE PORTLET-->

        </div>

        <div class="span6 four_div">
            <!-- BEGIN CONDENSED TABLE PORTLET-->
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-user"></i>待归档用户</div>
                </div>

                <div class="portlet-body">
                    <table class="table table-striped table-hover">

                        <thead>

                        <tr>

                            <th>序号</th>

                            <th>事件单号</th>

                            <th>用户微信号</th>

                            <th>操作</th>

                        </tr>

                        </thead>

                        <tbody id="tbody_2">
                        <?php if(is_array($event_list2)): foreach($event_list2 as $key=>$v): ?><tr class="tr_guid_add<?php echo ($v["id"]); ?>">
                            <td><?php echo $key+1;?></td>
                            <td><?php echo ($v["event_number"]); ?></td>
                            <td><?php echo ($v["user_contact"]); ?></td>
                            <td>
                                <button type="button" id="guid_btn<?php echo ($v["id"]); ?>" <?php if($v['is_guid'] == 1): ?>disabled<?php endif; ?>  onclick="UserGuid('<?php echo ($v["id"]); ?>','<?php echo ($v["event_number"]); ?>','<?php echo ($v["user_contact"]); ?>')" class="btn btn_z">信息归档</button>
                                <button type="button" id="addun_btn<?php echo ($v["id"]); ?>"  <?php if($v['is_add'] == 1): ?>disabled<?php endif; ?> onclick="UserAdd('<?php echo ($v["id"]); ?>')" class="btn btn_z">创建用户账号</button>
                                <button type="button" id="asd<?php echo ($v["id"]); ?>"  onclick="Okaddguid('<?php echo ($v["id"]); ?>')" class="btn btn_z">完成</button>
                            </td>
                        </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- END CONDENSED TABLE PORTLET-->

        </div>

        <div class="span6 four_div">
            <!-- BEGIN CONDENSED TABLE PORTLET-->
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-hdd"></i>订单处理工作台</div>
                </div>

                <div class="portlet-body">

                    <table class="zdy_table">
                        <thead>
                        <tr  style="border-bottom: 1px solid #ccc;line-height: 30px;">
                            <th>序号</th>
                            <th>订单编号</th>
                            <th>用户微信号</th>
                            <th>购卡平台名</th>
                            <th>购卡数量</th>
                            <th>购卡金额</th>
                            <th>订单状态</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="tbody_3">
                        <?php if(is_array($order_list)): foreach($order_list as $key=>$v): ?><tr class="tr_order_cl<?php echo ($v["id"]); ?>">
                                <td colspan="8" style="padding: 5px 0;">

                                </td>
                            </tr>
                            <tr  class="tr_order_cl<?php echo ($v["id"]); ?>">
                                <td rowspan="2"><?php echo $key+1;?></td>
                                <td><?php echo ($v["order_number"]); ?></td>
                                <td><?php echo ($v["wx_id"]); ?></td>
                                <td><?php echo ($v["card_name"]); ?></td>
                                <td><?php echo ($v["card_num"]); ?></td>
                                <td>￥<?php echo ($v["card_money"]); ?></td>
                                <td>
                                    <?php if($v['status'] == 0): ?><span class="label">处理中</span><?php endif; ?>
                                    <?php if($v['status'] == 1): ?><span class="label label-info">待领卡</span><?php endif; ?>
                                    <?php if($v['status'] == 3): ?><span class="label label-warning">领卡异常处理中</span><?php endif; ?>
                                </td>
                                <td style="width: 150px;"> <button onclick="LingCard('<?php echo ($v["id"]); ?>')" <?php if($v['status'] != 1): ?>disabled<?php endif; ?> type="button" class="btn btn_z" style="width: 100%;">点此领卡</button></td>
                            </tr>
                            <tr class="tr_order_cl<?php echo ($v["id"]); ?>">

                                <td colspan="6">
                                    <span onclick="myCopy('<?php echo ($v["id"]); ?>')"><?php echo ($v["card_url"]); ?></span>
                                    <input type="text" class="hide_text_inp" value="<?php echo ($v["card_url"]); ?>" id="inp_url<?php echo ($v["id"]); ?>"/>
                                </td>

                               <!-- <td>
                                    <button type="button" onclick="myCopy('<?php echo ($v["id"]); ?>')"  class="btn btn_z">复制链接</button>

                                </td>-->
                                <td><button type="button" <?php if($v['status'] != 1): ?>disabled<?php endif; ?> onclick="LingError('<?php echo ($v["id"]); ?>')" class="btn btn_z">无法领卡</button>
                                    <button type="button"  <?php if($v['status'] != 0): ?>disabled<?php endif; ?> onclick="CuiOrder('<?php echo ($v["id"]); ?>')" class="btn btn_z">催单</button></td>
                            </tr>
                            <tr  class="tr_order_cl<?php echo ($v["id"]); ?>" style="border-bottom: 1px solid #ccc;">
                                <td colspan="8" style="padding: 5px 0;">

                                </td>
                            </tr><?php endforeach; endif; ?>
                        </tbody>

                    </table>

                </div>

            </div>

            <!-- END CONDENSED TABLE PORTLET-->

        </div>




        <!-- END PAGE CONTAINER-->

    </div>
    <div id="newMessageDIV">

    </div>
    <!-- END PAGE -->

</div>





<!-- modal -->
<div class="modal fade" id = 'modal_guid' style="display: none;">
    <div class="modal-dialog" >
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                </button>
                <i class="fa fa-question" style='margin-top:5px; width:30px; float:left;'></i>
                <h4 class="modal-title" id='modal_guid_title'>用户归档</h4>
            </div>
            <div class="modal-body" style="min-height: 520px;">

                <form action="#" class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">事件编号</font></font></label>
                        <div class="controls">
                            <p id="guid_number" style="padding-top: 7px;margin: 0;">12321321</p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户微信号</font></font></label>
                        <div class="controls">
                            <p id="guid_contact" style="padding-top: 7px;margin: 0;">1111</p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户微信名称</font></font></label>
                        <div class="controls">
                            <input type="text" placeholder="请输入用户微信名称" name="guid_wx_name"  value="" class="m-wrap medium">
                            <span class="bitian">*</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户联系电话</font></font></label>
                        <div class="controls">
                            <input type="text" placeholder="请输入用户联系电话" name="guid_mobile"  value="" class="m-wrap medium">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户初次购卡平台</font></font></label>
                        <div class="controls">
                            <select name="guid_card_id" data-placeholder="Your Favorite Type of Bear" class="chosen-with-diselect large" tabindex="-1" id="guid_card_id">
                                <option value="0" selected >请选择</option>
                                <?php if(is_array($card_list)): foreach($card_list as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["card_name"]); ?></option><?php endforeach; endif; ?>

                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户初次购卡数量</font></font></label>
                        <div class="controls">
                            <input type="number" placeholder="请输入用户初次购卡数量" name="guid_card_num"  value="" class="m-wrap medium">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户初次购卡金额</font></font></label>
                        <div class="controls">
                            <input type="text" placeholder="请输入用户初次购卡金额" name="guid_card_money"  value="" class="m-wrap medium">
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户支付方式</font></font></label>
                        <div class="controls">
                            <select name="guid_pay_type" data-placeholder="Your Favorite Type of Bear" class="chosen-with-diselect large" tabindex="-1" id="guid_pay_type">
                                <option value="1" selected>微信</option>
                                <option value="2">支付宝</option>
                                <option value="3">银行转账</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">信息归档人</font></font></label>
                        <div class="controls">
                            <p style="padding-top: 7px;margin: 0;"><?php echo session(XUANDB.'user_name');?></p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">归档备注</font></font></label>
                        <div class="controls">
                            <textarea class="m-wrap" rows="3" id="guid_remark" placeholder="归档备注"></textarea>

                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal" >取 消</button>
                <button type="button" class="btn green" id='modal_guid_yes'  style='margin-left:9px'>确 定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- ./modal -->

<!-- modal -->
<div class="modal fade" id = 'modal_guid2' style="display: none;">
    <div class="modal-dialog" >
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                </button>
                <i class="fa fa-question" style='margin-top:5px; width:30px; float:left;'></i>
                <h4 class="modal-title" id='modal_guid2_title'><i class="icon-plus"></i> 新用户手动创档</h4>
            </div>
            <div class="modal-body" style="min-height: 520px;">

                <form action="#" class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">事件编号</font></font></label>
                        <div class="controls">
                            <p id="guid2_number" style="padding-top: 7px;margin: 0;">确认后生成</p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户微信号</font></font></label>
                        <div class="controls">
                            <input type="text" placeholder="请输入用户微信号" name="guid2_wx_id"  value="" class="m-wrap medium">
                            <span class="bitian">*</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户微信名称</font></font></label>
                        <div class="controls">
                            <input type="text" placeholder="请输入用户微信名称" name="guid2_wx_name"  value="" class="m-wrap medium">
                            <span class="bitian">*</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户联系电话</font></font></label>
                        <div class="controls">
                            <input type="text" placeholder="请输入用户联系电话" name="guid2_mobile"  value="" class="m-wrap medium">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户初次购卡平台</font></font></label>
                        <div class="controls">
                            <select name="guid2_card_id" data-placeholder="Your Favorite Type of Bear" class="chosen-with-diselect large" tabindex="-1" id="guid2_card_id">
                                <option value="0" selected >请选择</option>
                                <?php if(is_array($card_list)): foreach($card_list as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["card_name"]); ?></option><?php endforeach; endif; ?>

                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户初次购卡数量</font></font></label>
                        <div class="controls">
                            <input type="number" placeholder="请输入用户初次购卡数量" name="guid2_card_num"  value="" class="m-wrap medium">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户初次购卡金额</font></font></label>
                        <div class="controls">
                            <input type="text" placeholder="请输入用户初次购卡金额" name="guid2_card_money"  value="" class="m-wrap medium">
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户支付方式</font></font></label>
                        <div class="controls">
                            <select name="guid2_pay_type" data-placeholder="Your Favorite Type of Bear" class="chosen-with-diselect large" tabindex="-1" id="guid2_pay_type">
                                <option value="1" selected>微信</option>
                                <option value="2">支付宝</option>
                                <option value="3">银行转账</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">信息归档人</font></font></label>
                        <div class="controls">
                            <p style="padding-top: 7px;margin: 0;"><?php echo session(XUANDB.'user_name');?></p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">归档备注</font></font></label>
                        <div class="controls">
                            <textarea class="m-wrap" rows="3" id="guid2_remark" placeholder="归档备注"></textarea>

                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal" >取 消</button>
                <button type="button" class="btn green" id='modal_guid2_yes'  style='margin-left:9px'>确 定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- ./modal -->

<!-- modal -->
<div class="modal fade" id = 'modal_add' style="display: none;">
    <div class="modal-dialog" >
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                </button>
                <i class="fa fa-question" style='margin-top:5px; width:30px; float:left;'></i>
                <h4 class="modal-title" id='modal_add_title'>创建用户账号</h4>
            </div>
            <div class="modal-body">

                <form action="#" class="form-horizontal">
                    <p style="text-align: center;margin-bottom: 15px;">*快速为用户创建购卡后台账号和密码</p>
                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">登陆账号</font></font></label>
                        <div class="controls">
                            <input type="text" placeholder="请输入登陆账号" name="add_username"  value="" class="m-wrap medium">
                            <span class="bitian">*</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">账号密码</font></font></label>
                        <div class="controls">
                            <input type="text" placeholder="不填默认设置密码为：123456" name="add_pass"  value="" class="m-wrap medium">
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal" >取 消</button>
                <button type="button" class="btn green" id='modal_add_yes'  style='margin-left:9px'>确 定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- ./modal -->

<!-- modal -->
<div class="modal fade" id = 'modal_fahuo' style="display: none;">
    <div class="modal-dialog" >
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                </button>
                <i class="fa fa-question" style='margin-top:5px; width:30px; float:left;'></i>
                <h4 class="modal-title" id='modal_fahuo_title'>关闭事件单</h4>
            </div>
            <div class="modal-body">

                <form action="#" class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">关单原因</font></font></label>
                        <div class="controls">
                            <input type="text" placeholder="请输入关单原因" name="fail_val"  value="" class="m-wrap medium">
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal" >取 消</button>
                <button type="button" class="btn green" id='modal_fahuo_yes'  style='margin-left:9px'>确 定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- ./modal -->

<!-- modal -->
<div class="modal fade" id = 'modal_order' style="display: none;">
    <div class="modal-dialog" >
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                </button>
                <i class="fa fa-question" style='margin-top:5px; width:30px; float:left;'></i>
                <h4 class="modal-title" id='modal_order_title'>创建用户账号</h4>
            </div>
            <div class="modal-body">

                <form action="#" class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">订单编号</font></font></label>
                        <div class="controls">
                            <p id="order_number" style="padding-top: 7px;margin: 0;">确认后生成</p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户微信号</font></font></label>
                        <div class="controls">
                            <input type="text" placeholder="请输入用户微信号" name="order_wx_id"  value="" class="m-wrap medium">
                            <span class="bitian">*</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户购卡平台名</font></font></label>
                        <div class="controls">
                            <select name="order_card_id" data-placeholder="Your Favorite Type of Bear" class="chosen-with-diselect large" tabindex="-1" id="order_card_id">
                                <option value="0" selected >请选择</option>
                                <?php if(is_array($card_list)): foreach($card_list as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["card_name"]); ?></option><?php endforeach; endif; ?>

                            </select>
                            <span class="bitian">*</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户购卡数量</font></font></label>
                        <div class="controls">
                            <input type="number" placeholder="请输入用户购卡数量" name="order_card_num"  value="" class="m-wrap medium">
                            <span class="bitian">*</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户支付金额</font></font></label>
                        <div class="controls">
                            <input type="number" placeholder="请输入用户支付金额" name="order_card_money"  value="" class="m-wrap medium">
                            <span class="bitian">*</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">用户支付方式</font></font></label>
                        <div class="controls">
                            <select name="pay_type" data-placeholder="Your Favorite Type of Bear" class="chosen-with-diselect large" tabindex="-1" id="pay_type">
                                <option value="1" selected>微信</option>
                                <option value="2">支付宝</option>
                                <option value="3">银行转账</option>
                            </select>
                            <span class="bitian">*</span>
                        </div>
                    </div>



                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal" >取 消</button>
                <button type="button" class="btn green" id='modal_order_yes'  style='margin-left:9px'>确 定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- ./modal -->



<div class="footer">

    <div class="footer-inner">

        2017 &copy; <a href="#" title="一方盟" target="_blank">网站模板</a> - 一方盟 <a href="#" target="_blank" title="模板之家">一方盟</a>

    </div>

    <div class="footer-tools">

			<span class="go-top">

			<i class="icon-angle-up"></i>

			</span>

    </div>

</div>






<!-- modal -->
<div class="modal fade" id = 'modal_confirm' style="display: none;">
    <div class="modal-dialog" >
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                </button>
                <i class="fa fa-question" style='margin-top:5px; width:30px; float:left;'></i>
                <h4 class="modal-title" id='modal_confirm_title'>Default Modal</h4>
            </div>
            <div class="modal-body">
                <p id='modal_confirm_body'>One fine body…</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal" >取 消</button>
                <button type="button" class="btn green" id='modal_confirm_yes' style='margin-left:9px'>确 定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- ./modal -->


<div class="modal fade" id = 'modal_alert'  style="display: none;">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                </button>
                <h4 class="modal-title" id='modal_alert_title'><i class="icon-exclamation-sign"></i> 消息</h4>
            </div>
            <div class="modal-body">
                <p id='modal_alert_body'>One fine body…</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id='modal_alert_yes' style='margin-left:9px'>确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div id="newMessageDIV2">

</div>



<!-- END FOOTER -->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

<!-- BEGIN CORE PLUGINS -->

<script src="/sellcard/Public/js/jquery-1.10.1.min.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

<script src="/sellcard/Public/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/bootstrap.min.js" type="text/javascript"></script>

<!--[if lt IE 9]>

<script src="/sellcard/Public/js/excanvas.min.js"></script>

<script src="/sellcard/Public/js/respond.min.js"></script>

<![endif]-->

<script src="/sellcard/Public/js/jquery.slimscroll.min.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.blockui.min.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.cookie.min.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.uniform.min.js" type="text/javascript" ></script>

<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->

<script src="/sellcard/Public/js/jquery.vmap.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.vmap.russia.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.vmap.world.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.vmap.europe.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.vmap.germany.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.vmap.usa.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.vmap.sampledata.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.flot.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.flot.resize.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.pulsate.min.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/date.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/daterangepicker.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.gritter.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/fullcalendar.min.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.easy-pie-chart.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/jquery.sparkline.min.js" type="text/javascript"></script>



<!--上传图片添加-->
<script type="text/javascript" src="/sellcard/Public/js/ckeditor.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/bootstrap-fileupload.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/chosen.jquery.min.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/select2.min.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/wysihtml5-0.3.0.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/bootstrap-wysihtml5.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/jquery.tagsinput.min.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/jquery.toggle.buttons.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/bootstrap-datepicker.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/bootstrap-datetimepicker.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/clockface.js"></script>




<script type="text/javascript" src="/sellcard/Public/js/bootstrap-colorpicker.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/bootstrap-timepicker.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/jquery.inputmask.bundle.min.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/jquery.input-ip-address-control-1.0.min.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/jquery.multi-select.js"></script>




<!--上传图片添加-->
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/sellcard/Public/js/jquery.fancybox.pack.js"></script>

<script src="/sellcard/Public/js/app.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/index.js" type="text/javascript"></script>

<script src="/sellcard/Public/js/search.js"></script>

<script src="/sellcard/Public/js/form-components.js"></script>

<script type="text/javascript" src="/sellcard/Public/js/jquery.dataTables.min.js"></script>

<script src="/sellcard/Public/js/table-advanced.js"></script>

<script src="/sellcard/Public/js/kalendae.standalone.js" type="text/javascript" charset="utf-8"></script>

<!-- END PAGE LEVEL SCRIPTS -->

<script>
    function OverAllNav(msg){
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?php echo U('home/public/overallnav'); ?>",
            data:{msg:msg},
            success: function (result) {
                if(result.code == 1){
                    $('.overall_a .tubiao').remove();
                    if(result.list.order_num>0){
                        $('.overall_a_order').append('<span class="badge badge-important tubiao">'+result.list.order_num+'</span>');
                    }
                    if(result.list.manager_num>0){
                        $('.overall_a_manager').append('<span class="badge badge-important tubiao">'+result.list.manager_num+'</span>');
                    }
                    if(result.list.service_num>0){
                        $('.overall_a_service').append('<span class="badge badge-important tubiao">'+result.list.service_num+'</span>');
                    }
                    if(result.is_sy==1){
                        $('#newMessageDIV2').html('<audio autoplay="autoplay"><source src="/sellcard/Public'+result.sy_url+'"'
                        + 'type="audio/wav"/><source src="/sellcard/Public'+result.sy_url+'" type="audio/mpeg"/></audio>');
                    }
                }
            }
        });
    }

    function showConfirm(title,body,callback){
        $('#modal_confirm_title')[0].innerHTML=title;
        $('#modal_confirm_body')[0].innerHTML=body;
        $('#modal_confirm_yes')[0].onclick=function(){
            $('#modal_confirm').modal('hide');
            if(callback){
                callback();
            }
        };
        $('#modal_confirm').modal('show');
        $('#modal_confirm_yes')[0].focus();
    }

    function showAlert(body,callback){
        //$('#modal_alert_title')[0].innerHTML=title;
        $('#modal_alert_body')[0].innerHTML=body;
        $('#modal_alert_yes')[0].onclick=function(){
            $('#modal_alert').modal('hide');
            if(callback){
                callback();
            }
        };
        $('#modal_alert').modal('show');
        $('#modal_alert_yes')[0].focus();
    }

    jQuery(document).ready(function() {

        App.init(); // initlayout and core plugins

        Index.init();

        Index.initJQVMAP(); // init index page's custom scripts

        Index.initCalendar(); // init index page's custom scripts

        Index.initCharts(); // init index page's custom scripts

        Index.initChat();

        Index.initMiniCharts();

        Index.initDashboardDaterange();
        Search.init();//图片插件
        //Index.initIntro();//登录弹出
        FormComponents.init();
        TableAdvanced.init();

    });

</script>

<!-- END JAVASCRIPTS -->

</body>


<!-- END BODY -->

</html>
<script src='/sellcard/Public/push/socket.io.js'></script>
<script src='/sellcard/Public/push/notify.js'></script>

<script>
    var uid = "<?php echo session(XUANDB.'id');?>";
    $(document).ready(function () {
        // 连接服务端
        var socket = io('http://'+document.domain+':2120');
        // 连接后登录
        socket.on('connect', function(){
            socket.emit('login', uid);
        });
        // 后端推送来消息时
        socket.on('new_msg', function(msg){
            //IE9+,Firefox,Chrome均支持<audio/>

            if(msg=='manager_1'){
                $('#newMessageDIV').html('<audio autoplay="autoplay"><source src="/sellcard/Public/mp3/manger.mp3"'
                + 'type="audio/wav"/><source src="/sellcard/Public/mp3/manger.mp3" type="audio/mpeg"/></audio>');
                checkupdate1();
                OverAllNav('no');
            }

            if(msg=='manager_2'){
                $('#newMessageDIV').html('<audio autoplay="autoplay"><source src="/sellcard/Public/mp3/manger.mp3"'
                + 'type="audio/wav"/><source src="/sellcard/Public/mp3/manger.mp3" type="audio/mpeg"/></audio>');
                checkupdate2();
                OverAllNav('no');
            }

            if(msg=='manager_3'){
                $('#newMessageDIV').html('<audio autoplay="autoplay"><source src="/sellcard/Public/mp3/manger.mp3"'
                + 'type="audio/wav"/><source src="/sellcard/Public/mp3/manger.mp3" type="audio/mpeg"/></audio>');
                checkupdate3();
            }

        });
        // 后端推送来在线数据时
        socket.on('update_online_count', function(online_stat){
           /* $('#online_box').html(online_stat);
            console.log(online_stat);*/
        });
    });
</script>

<script>
    $(function(){
        /*alert(parseInt(timestamp/1000));*/
        time();
    });

    function myCopy(id){
        var ele = document.getElementById("inp_url"+id);
        ele.select();
        document.execCommand("Copy");
        showAlert("复制成功");
    }


    var time2;
    function UserAdd(id){
        var timestamp = (new Date()).getTime();
        var time1 = parseInt(timestamp/1000);
        var time2 = random(100,999);
        $("*[name='add_username']").val(String(time1)+String(time2));
        $('#modal_add').modal('show');
        $('#modal_add_yes')[0].onclick=function(){
            //$('#modal_confirm').modal('hide');
            Addusername(id);
        };
    }

    function checkupdate1(){
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?php echo U('home/'.$at.'/checkupdate'); ?>",
            data:{type:'1'},
            success: function (result){
                if(result.code == 1){
                    $('#tbody_1').html('');
                    $.each(result.list, function (index, item) {
                        var backg ='';
                        var disab = '';
                        var disab2 = '';
                        var hide_inp = '';
                        if(item.is_red==0){
                            backg = 'red_bg';
                        }
                        if(item.is_red==1){
                            hide_inp = '<input type="hidden" value="'+item.update_time+'" class="red_time'+item.id+'"/> <input type="hidden" value="'+item.id+'" class="red_d"/>';
                        }
                        if(item.status !='1'){
                            disab = 'disabled';
                        }
                        if(item.status !='2'){
                            disab2 = 'disabled';
                        }
                        $('#tbody_1').append('<tr class="event_tr tr_red'+item.id+' '+backg+'" >' +
                        '<td>'+(parseInt(index)+1)+'</td>' +
                        ' <td>'+item.user_contact+'</td> ' +
                        '<td>'+item.user_keyword+'</td> ' +
                        '<td>'+item.user_remark+'</td>' +
                        '<td> ' +
                        '<button type="button" onclick="OnApply(\''+item.id+'\')"  class="btn btn_z apply_btn'+item.id+'" '+disab+' >已申请</button> ' +
                        '<button type="button" onclick="IsAdd(\''+item.id+'\')"  class="btn btn_z add_btn'+item.id+'" '+disab2+' >已添加</button> ' +
                        '<button type="button" onclick="UserInexistence(\''+item.id+'\',\'4\')" class="btn btn_z" >用户不存在</button> ' +
                        '<button type="button" onclick="UserInexistence(\''+item.id+'\',\'5\')" class="btn btn_z" >协助跟进</button> ' +
                        '<button type="button" onclick="CloseEvent(\''+item.id+'\')" class="btn btn_z">关单</button> ' +
                        '</td>' +hide_inp +
                        '</tr>');
                    });
                }else if(result.code==0){
                    showAlert(result.res);
                }else{
                    showAlert("提交失败，请检查您的输入是否合法");
                }
            }
        });
    }

    function checkupdate2(){
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?php echo U('home/'.$at.'/checkupdate'); ?>",
            data:{type:'2'},
            success: function (result){
                if(result.code == 1){
                    $('#tbody_2').html('');
                    $.each(result.list, function (index, item) {
                        var backg ='';
                        var disab = '';
                        var disab2 = '';

                        if(item.is_red==0){
                            backg = 'style="background: rgba(255,0,0,0.6)"';
                        }

                        if(item.is_guid =='1'){
                            disab = 'disabled';
                        }
                        if(item.is_add =='1'){
                            disab2 = 'disabled';
                        }
                        $('#tbody_2').append('<tr class="event_tr tr_red'+item.id+'" '+backg+'>' +
                        '<td>'+(parseInt(index)+1)+'</td>' +
                        ' <td>'+item.event_number+'</td> ' +
                        ' <td>'+item.user_contact+'</td> ' +
                        '<td> ' +
                        '<button type="button" id="guid_btn'+item.id+'" '+disab+' onclick="UserGuid(\''+item.id+'\',\''+item.event_number+'\',\''+item.user_contact+'\')" class="btn btn_z">信息归档</button> ' +
                        '<button type="button" id="addun_btn'+item.id+'"  '+disab2+' onclick="UserAdd(\''+item.id+'\')" class="btn btn_z">创建用户账号</button> ' +
                        '<button type="button" id="asd'+item.id+'"  onclick="Okaddguid(\''+item.id+'\')" class="btn btn_z">完成</button>' +
                        '</td>' +
                        '</tr>');
                    });
                }else if(result.code==0){
                    showAlert(result.res);
                }else{
                    showAlert("提交失败，请检查您的输入是否合法");
                }
            }
        });
    }



    function checkupdate3(){
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?php echo U('home/'.$at.'/checkupdate'); ?>",
            data:{type:'3'},
            success: function (result){
                if(result.code == 1){
                    $('#tbody_3').html('');
                    $.each(result.list, function (index, item) {
                        var disab ='';
                        var disab2 = '';
                        var span = '';


                        if(item.status =='0'){span = '<span class="label">处理中</span>';}else{disab2 = 'disabled';}
                        if(item.status =='1'){span = '<span class="label label-info">待领卡</span>';}else{disab = 'disabled';}
                        if(item.status =='3'){span = '<span class="label label-warning">领卡异常处理中</span>';}

                        if(item.is_add =='1'){
                            disab2 = 'disabled';
                        }
                        $('#tbody_3').append('<tr class="tr_order_cl'+item.id+'"> <td colspan="8" style="padding: 5px 0;"> </td> </tr>' +

                        '<tr  class="tr_order_cl'+item.id+'">' +
                        '<td rowspan="2">'+(parseInt(index)+1)+'</td>' +
                        '<td>'+item.order_number+'</td> ' +
                        '<td>'+item.wx_id+'</td> ' +
                        '<td>'+item.card_name+'</td> ' +
                        '<td>'+item.card_num+'</td> ' +
                        '<td>￥'+item.card_money+'</td> ' +
                        '<td>'+span+'</td> ' +
                        '<td style="width: 150px;"><button onclick="LingCard(\''+item.id+'\')" '+disab+' type="button" class="btn btn_z" style="width: 100%;">点此领卡</button></td> ' +
                        '</tr>' +

                        '<tr class="tr_order_cl'+item.id+'">' +
                        '<td colspan="6">' +
                        '<span onclick="myCopy(\''+item.id+'\')">'+item.card_url+'</span>' +
                        '<input type="text" class="hide_text_inp" value="'+item.card_url+'" id="inp_url'+item.id+'"/>' +
                        '</td>' +
                        '<td>' +
                        '<button type="button" '+disab+' onclick="LingError(\''+item.id+'\')" class="btn btn_z">无法领卡</button> ' +
                        '<button type="button"  '+disab2+' onclick="CuiOrder(\''+item.id+'\')" class="btn btn_z">催单</button>' +
                        '</td>' +
                        '</tr>' +

                        '<tr  class="tr_order_cl'+item.id+'" style="border-bottom: 1px solid #ccc;"> ' +
                        '<td colspan="8" style="padding: 5px 0;"></td>' +
                        '</tr>');
                    });

                }else if(result.code==0){
                    showAlert(result.res);
                }else{
                    showAlert("提交失败，请检查您的输入是否合法");
                }
            }
        });
    }




    function LoginType(status,str){
        showConfirm('<i class="icon-exclamation-sign"></i> '+str,'确定要'+str+'吗?',function(){
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: "<?php echo U('home/'.$at.'/logintype'); ?>",
                data:{status:status,str:str},
                success: function (result){
                    if(result.code == 1){
                        showAlert(result.res);
                        window.location.reload();
                    }else if(result.code==0){
                        showAlert(result.res);
                    }else{
                        showAlert("提交失败，请检查您的输入是否合法");
                    }
                }
            });
        });
    }

    function LingCard(id){
        showConfirm('<i class="icon-exclamation-sign"></i> 确认已领卡','确定已领取房卡包中的房卡吗?',function(){
            $.ajax({
                type: 'POST',
                url: "<?php echo U('home/'.$at.'/lingsuccess'); ?>",
                data:{id:id},
                success: function (result) {
                    if (result.code == 1){
                        showAlert(result.res);
                        $('.tr_order_cl'+id).remove();
                    } else {
                        showAlert(result.res);

                    }
                }
            });
        });
    }

    function LingError(id){
        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/'.$at.'/lingerror'); ?>",
            data:{id:id},
            success: function (result) {
                if (result.code == 1){
                    showAlert(result.res);
                    checkupdate3();
                } else {
                    showAlert(result.res);

                }
            }
        });
    }

    function CuiOrder(id){
        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/'.$at.'/cuiorder'); ?>",
            data:{id:id},
            success: function (result) {
                if (result.code == 1){
                    showAlert(result.res);
                } else {
                    showAlert(result.res);

                }
            }
        });

    }


    function Okaddguid(id){
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?php echo U('home/'.$at.'/okaddguid'); ?>",
            data:{id:id},
            success: function (result) {
                if(result.code == 1){
                    showAlert(result.res);
                    $('.tr_guid_add'+id).remove();
                    OverAllNav('no');
                }else if(result.code==0){
                    showAlert(result.res);
                }else{
                    showAlert("提交失败，请检查您的输入是否合法");
                }
            }
        });
    }

    function Addusername(id){
        var username = $.trim($("*[name='add_username']").val());
        var pass = $.trim($("*[name='add_pass']").val());

        if(!username){
            showAlert('登陆账号不能为空');
            return false;
        }
        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/'.$at.'/addusername'); ?>",
            data:{id:id,username:username,pass:pass},
            success: function (result) {
                if(result.code == 1) {
                    showAlert(result.res);
                    $('#modal_add').modal('hide');
                    $('#addun_btn'+id).attr('disabled',true);
                }else{
                    showAlert(result.res);

                }
            }
        });

    }

    function random(min,max)
    {
        return Math.floor(min + Math.random() * (max - min));
    }

    function UserGuid2(){
        $('#modal_guid2').modal('show');
        $('#modal_guid2_yes')[0].onclick=function(){
            //$('#modal_confirm').modal('hide');
            Guid2();
        };
    }

    function Guid2(){
        var wx_id = $.trim($("*[name='guid2_wx_id']").val());
        var wx_name = $.trim($("*[name='guid2_wx_name']").val());
        var mobile = $.trim($("*[name='guid2_mobile']").val());
        var card_id = $.trim($("*[name='guid2_card_id']").val());
        var card_num = $.trim($("*[name='guid2_card_num']").val());
        var card_money = $.trim($("*[name='guid2_card_money']").val());
        var remark = $.trim($("#guid2_remark").val());
        var pay_type = $("#guid2_pay_type").val();

        if(!wx_id){
            showAlert('请输入微信ID');
            return false;
        }
        if(!wx_name){
            showAlert('请输入微信名称');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/'.$at.'/newguidadd'); ?>",
            data:{wx_id:wx_id,
                wx_name:wx_name,
                mobile:mobile,
                card_id:card_id,
                card_num:card_num,
                card_money:card_money,
                remark:remark,
                pay_type:pay_type
            },
            success: function (result) {
                if (result.code == 1) {
                    showAlert(result.res);
                    checkupdate1();
                    checkupdate2();
                    $("*[name='guid2_wx_id']").val('');
                    $("*[name='guid2_wx_name']").val('');
                    $("*[name='guid2_mobile']").val('');
                    $("*[name='guid2_card_id']").val('0');
                    $("*[name='guid2_card_num']").val('');
                    $("*[name='guid2_card_money']").val('');
                    $("#guid2_remark").val('');
                    $('#modal_guid2').modal('hide');
                } else {
                    showAlert(result.res);

                }
            }
        });
    }

    function AddOrder(){
        $('#modal_order').modal('show');
        $('#modal_order_yes')[0].onclick=function(){
            //$('#modal_confirm').modal('hide');
            SubmitOrder();
        };
    }

    function SubmitOrder(){
        var wx_id = $.trim($("*[name='order_wx_id']").val());
        var card_num = $.trim($("*[name='order_card_num']").val());
        var card_money = $.trim($("*[name='order_card_money']").val());
        var card_id = $("#order_card_id").val();
        var pay_type = $("#pay_type").val();

        if(!wx_id){showAlert('请输入微信ID');return false;}
        if(card_id=='0'){showAlert('请选择购卡平台名');return false;}
        if(!card_num){showAlert('请输入购卡数量');return false;}
        if(!card_money){showAlert('请输入购卡金额');return false;}

        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/'.$at.'/addorder'); ?>",
            data:{
                wx_id:wx_id,
                card_id:card_id,
                card_num:card_num,
                card_money:card_money,
                pay_type:pay_type
            },
            success: function (result) {
                if (result.code == 1){
                    showAlert(result.res);
                    $('#modal_order').modal('hide');
                    checkupdate3();
                } else {
                    showAlert(result.res);

                }
            }
        });

    }


    function UserGuid(id,number,wx_id){
        $('#guid_number').html(number);
        $('#guid_contact').html(wx_id);
        $('#modal_guid').modal('show');
        $('#modal_guid_yes')[0].onclick=function(){
            //$('#modal_confirm').modal('hide');
            Guid(id);
        };
    }




    function Guid(id){
        var guid_wx_name = $.trim($("*[name='guid_wx_name']").val());
        var guid_mobile = $.trim($("*[name='guid_mobile']").val());
        var guid_card_id = $.trim($("*[name='guid_card_id']").val());
        var guid_card_num = $.trim($("*[name='guid_card_num']").val());
        var guid_card_money = $.trim($("*[name='guid_card_money']").val());
        var guid_remark = $.trim($("#guid_remark").val());
        var guid_pay_type = $("#guid_pay_type").val();

        if(!guid_wx_name){
            showAlert('请输入微信名称');
            return false;
        }
        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/'.$at.'/guidadd'); ?>",
            data:{id:id,guid_wx_name:guid_wx_name,guid_mobile:guid_mobile,guid_card_id:guid_card_id,guid_card_num:guid_card_num,guid_card_money:guid_card_money,guid_remark:guid_remark,guid_pay_type:guid_pay_type},
            success: function (result) {
                if (result.code == 1){
                    showAlert(result.res);
                    $('#modal_guid').modal('hide');
                    $('#guid_btn'+id).attr('disabled',true);

                } else {
                    showAlert(result.res);

                }
            }
        });
    }


    function CloseEvent(id){
        $('#modal_fahuo').modal('show');
        $('#modal_fahuo_yes')[0].onclick=function(){
            //$('#modal_confirm').modal('hide');
            SubmitFaHuo(id);
        };
    }

    function SubmitFaHuo(id){
        var fail_val = $.trim($("*[name='fail_val']").val());
        if(!fail_val){
            showAlert('请输入关单原因');
            return false;
        }
        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/'.$at.'/closeevent'); ?>",
            data:{id:id,fail_val:fail_val},
            success: function (result) {
                if (result.code == 1) {
                    showAlert(result.res);
                    $('#modal_fahuo').modal('hide');
                    $('.tr_red'+id).remove();
                } else {
                    showAlert(result.res);

                }
            }
        });
    }


    function UserInexistence(id,status){
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?php echo U('home/'.$at.'/editstatus'); ?>",
            data:{id:id,status:status},
            success: function (result) {
                if(result.code == 1){
                    showAlert(result.res);
                    $('.tr_red'+id).remove();
                    OverAllNav('no');
                }else if(result.code==0){
                    showAlert(result.res);
                }else{
                    showAlert("提交失败，请检查您的输入是否合法");
                }
            }
        });
    }

    function StartEvent(id){
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?php echo U('home/'.$at.'/startevent'); ?>",
            data:{id:id,status:status},
            success: function (result) {
                if(result.code == 1){
                    showAlert(result.res);
                    window.location.reload();
                }else if(result.code==0){
                    showAlert(result.res);
                }else{
                    showAlert("提交失败，请检查您的输入是否合法");
                }
            }
        });
    }


    function IsAdd(id){
        showConfirm('<i class="icon-exclamation-sign"></i> 添加确认','确定已添加该用户吗?',function(){
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: "<?php echo U('home/'.$at.'/isadd'); ?>",
                data:{id:id},
                success: function (result){
                    if(result.code == 1){
                        showAlert(result.res);
                        $('.tr_red'+id).remove();
                        checkupdate2();
                        OverAllNav('no');

                    }else if(result.code==0){
                        showAlert(result.res);
                    }else{
                        showAlert("提交失败，请检查您的输入是否合法");
                    }
                }
            });
        });

    }

    function OnApply(id){

        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?php echo U('home/'.$at.'/editstatus'); ?>",
            data:{id:id,status:'2'},
            success: function (result) {
                if(result.code == 1){
                    var tr_len = $(".event_user_table tr").length;

                    showAlert(result.res);
                    $('.apply_btn'+id).attr('disabled',true);
                    $('.add_btn'+id).attr('disabled',false);
                    $('.tr_red'+id).css({'background':''});

                    $('.tr_red'+id+' .red_d').remove();

                    var $tr =  $('.tr_red'+id);
                    if ($tr.index() != tr_len - 1) {
                        $tr.fadeOut().fadeIn();
                        $(".event_user_table").append($tr);
                    }
                }else if(result.code==0){
                    showAlert(result.res);
                }else{
                    showAlert("提交失败，请检查您的输入是否合法");
                }
            }
        });
    }


    var wait=parseInt(10);
    function time(){
        var create_time;
        var cha_time;
        if (wait == 0) {
            //window.location.reload();
            wait = 10;
           time();
        } else {
            wait--;
            var timestamp = (new Date()).getTime();
            $(".red_d").each(function(){
                create_time = $('.red_time'+$(this).val()).val();
                cha_time =  parseInt(timestamp/1000) - parseInt(create_time);
                if(cha_time>180){
                    $('.tr_red'+$(this).val()).css({'background':'rgba(255,0,0,0.6)'});

                    var $tr = $(this).parents("tr");
                    $tr.fadeOut().fadeIn();
                    $(".event_user_table").prepend($tr);
                    $(this).remove();

                    //IE9+,Firefox,Chrome均支持<audio/>
                    $('#newMessageDIV').html('<audio autoplay="autoplay"><source src="/sellcard/Public/mp3/warn.mp3"'
                    + 'type="audio/wav"/><source src="/sellcard/Public/mp3/warn.mp3" type="audio/mpeg"/></audio>');
                }


            });
            setTimeout(function() {time();}, 1000)
        }
    }

</script>