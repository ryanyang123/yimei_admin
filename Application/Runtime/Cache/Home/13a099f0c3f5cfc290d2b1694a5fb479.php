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


<!-- 上传图片 -->

<link rel="stylesheet" type="text/css" href="/sellcard/Public/css/bootstrap-fileupload.css" />

<link rel="stylesheet" type="text/css" href="/sellcard/Public/css/jquery.gritter.css" />

<link rel="stylesheet" type="text/css" href="/sellcard/Public/css/chosen.css" />

<link rel="stylesheet" type="text/css" href="/sellcard/Public/css/select2_metro.css" />

<link rel="stylesheet" type="text/css" href="/sellcard/Public/css/jquery.tagsinput.css" />

<link rel="stylesheet" type="text/css" href="/sellcard/Public/css/clockface.css" />

<link rel="stylesheet" type="text/css" href="/sellcard/Public/css/bootstrap-wysihtml5.css" />

<link rel="stylesheet" type="text/css" href="/sellcard/Public/css/datepicker.css" />

<link rel="stylesheet" type="text/css" href="/sellcard/Public/css/timepicker.css" />

<link rel="stylesheet" type="text/css" href="/sellcard/Public/css/colorpicker.css" />

<link rel="stylesheet" type="text/css" href="/sellcard/Public/css/bootstrap-toggle-buttons.css" />

<link rel="stylesheet" type="text/css" href="/sellcard/Public/css/daterangepicker.css" />

<link rel="stylesheet" type="text/css" href="/sellcard/Public/css/datetimepicker.css" />

<link rel="stylesheet" type="text/css" href="/sellcard/Public/css/multi-select-metro.css" />

<link href="/sellcard/Public/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>

<!-- 上传图片-->

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

        <div class="container-fluid">

            <!-- BEGIN PAGE HEADER-->

            <div class="row-fluid">

                <div class="span12">



                    <!--颜色选择-->
                    <!--
<div class="color-panel hidden-phone">

    <div class="color-mode-icons icon-color"></div>

    <div class="color-mode-icons icon-color-close"></div>

    <div class="color-mode">

        <p>主题颜色</p>

        <ul class="inline">

            <li class="color-black current color-default" data-style="default"></li>

            <li class="color-blue" data-style="blue"></li>

            <li class="color-brown" data-style="brown"></li>

            <li class="color-purple" data-style="purple"></li>

            <li class="color-grey" data-style="grey"></li>

            <li class="color-white color-light" data-style="light"></li>

        </ul>

        <label>

            <span>布局</span>

            <select class="layout-option m-wrap small">

                <option value="fluid" selected>全屏</option>

                <option value="boxed">盒装</option>

            </select>

        </label>

        <label>

            <span>头部</span>

            <select class="header-option m-wrap small">

                <option value="fixed" selected>固定</option>

                <option value="default">默认</option>

            </select>

        </label>


    </div>

</div>-->




                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->

                    <h3 class="page-title">

                        购卡平台 <small>详细</small>

                    </h3>

                    <ul class="breadcrumb">

                        <li>

                            <i class="icon-home"></i>

                            <a href="<?php echo U('home/home/index');?>">主页</a>

                            <i class="icon-angle-right"></i>

                        </li>

                        <li>

                            <i class="icon-long-arrow-right"></i>

                            <a href="<?php echo U('home/'.$at.'/index');?>"><?php echo ($title); ?></a>

                            <i class="icon-angle-right"></i>

                        </li>

                        <li><a href="#"><?php echo ($list["card_name"]); ?></a></li>



                    </ul>

                    <!-- END PAGE TITLE & BREADCRUMB-->

                </div>

            </div>

            <!-- END PAGE HEADER-->

            <div id="dashboard">


                <div class="row-fluid">

                    <div class="span6">

                        <!-- BEGIN SAMPLE TABLE PORTLET-->

                        <div class="portlet">



                            <div class="portlet-body">

                                <table class="table table-striped table-bordered table-advance table-hover">

                                    <thead>

                                    <tr>

                                        <th><i class="icon-briefcase"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> 选项</font></font></th>

                                        <th ><i class="icon-flag"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> 数值</font></font></th>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    <tr>
                                        <td class="highlight">
                                            <div class="success"></div>
                                            <a href="#"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">平台名称</font></font></a>
                                        </td>
                                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo ($list["card_name"]); ?></font></font></td>
                                    </tr>
                                    <tr>
                                        <td class="highlight">
                                            <div class="info"></div>
                                            <a href="#"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">成本价</font></font></a>
                                        </td>
                                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo ($list["cost"]); ?></font></font></td>
                                    </tr>
                                    <tr>
                                        <td class="highlight">
                                            <div class="important"></div>
                                            <a href="#"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">套餐数量</font></font></a>
                                        </td>
                                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo ($tc_num); ?></font></font></td>
                                    </tr>
                                    <tr>
                                        <td class="highlight">
                                            <div class="warning"></div>
                                            <a href="#"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">出售数量</font></font></a>
                                        </td>
                                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo ($mai); ?></font></font></td>
                                    </tr>

                                    <tr>
                                        <td class="highlight">
                                            <div class="success"></div>
                                            <a href="#"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">出售金额</font></font></a>
                                        </td>
                                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo ($money); ?></font></font></td>
                                    </tr>




                                    </tbody>

                                </table>

                            </div>

                        </div>

                        <!-- END SAMPLE TABLE PORTLET-->

                    </div>



                </div>

                <div class="clearfix"></div>

                <div class="portlet-title">


                    <a href="<?php echo U('home/'.$at.'/plus2/edit/'.$is_edit);?>" id="sample_editable_1_new" class="btn green">

                        新增套餐 <i class="icon-plus"></i>

                    </a>



                </div>



                    <!-- BEGIN CONDENSED TABLE PORTLET-->


                    <div class="portlet-title">

                        <div class="caption">
                            <i class="icon-coffee"></i>套餐列表
                        </div>

                    </div>
                <style>
                    .tc_table th,.tc_table td{
                        text-align: center;
                    }
                </style>

                    <div class="portlet-body" style="width: 70%;">

                        <table class="table table-bordered table-hover tc_table" >

                            <thead>

                            <tr>

                                <th>序号</th>

                                <th>用户属性</th>

                                <th>销售价格</th>

                                <th>销售数量</th>
                                <th>状态</th>

                                <th style="width: 80px;">是否推荐</th>
                                <th>赠卡</th>
                                <th>操作</th>

                            </tr>

                            </thead>

                            <tbody>
                            <?php if(is_array($detail_list)): foreach($detail_list as $key=>$v): ?><tr>

                                    <td><?php echo ($v["sort"]); ?></td>

                                    <td>
                                        <?php if($v['user_type'] == 1): ?>普通客户<?php endif; ?>
                                        <?php if($v['user_type'] == 2): ?>大客户<?php endif; ?>
                                    </td>

                                    <td>￥<?php echo ($v["money"]); ?></td>

                                    <td><?php echo ($v["num"]); ?></td>
                                    <td>

                                        <?php if($v["is_show"] == 1): ?><a href="javascript:EditStatus('<?php echo ($v["id"]); ?>','0','套餐','card_detail')">
                                                <span class="label label-success"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">显示</font></font></span>
                                            </a><?php endif; ?>

                                        <?php if($v["is_show"] == 0): ?><a href="javascript:EditStatus('<?php echo ($v["id"]); ?>','1','套餐','card_detail')">
                                                <span class="label label-important"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">隐藏</font></font></span>
                                            </a><?php endif; ?>
                                    </td>

                                    <td>
                                        <label class="checkbox">
                                           <input type="checkbox" class="tuijian<?php echo ($v["id"]); ?>" <?php if($v['is_jian'] == 1): ?>checked<?php endif; ?>  onchange="EditCheckbox('<?php echo ($v["id"]); ?>')" value="1">
                                        </label>
                                    </td>
                                    <td><?php echo ($v["give_num"]); ?></td>
                                    <td>
                                        <a href="javascript:fahuo('<?php echo ($v["id"]); ?>')" class="btn blue"><i class="icon-edit"></i> 修改赠卡</a>
                                        <a href="JavaScript:_deleteUser('<?php echo ($v["id"]); ?>','套餐','card_detail');" class="btn red"><i class="icon-trash"></i> 删除</a>
                                    </td>

                                </tr><?php endforeach; endif; ?>


                            </tbody>

                        </table>

                    </div>





        </div>

        <!-- END PAGE CONTAINER-->

    </div>

    <!-- END PAGE -->

</div>

    <!-- modal -->
    <div class="modal fade" id = 'modal_fahuo' style="display: none;">
        <div class="modal-dialog" >
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                    </button>
                    <i class="fa fa-question" style='margin-top:5px; width:30px; float:left;'></i>
                    <h4 class="modal-title" id='modal_fahuo_title'>修改赠送房卡数量</h4>
                </div>
                <div class="modal-body">


                    <form action="#" class="form-horizontal">


                        <div class="control-group">
                            <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">修改数量</font></font></label>
                            <div class="controls">
                                <input type="number" placeholder="请输入要修改的赠送数量" name="fh_num"  value="" class="m-wrap medium">
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



    <!-- END CONTAINER -->
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

            if(msg){
                OverAllNav(msg);
            }

        });
        // 后端推送来在线数据时
        socket.on('update_online_count', function(online_stat){
            /*$('#online_box').html(online_stat);
             console.log(online_stat);*/
        });
    });


</script>
<!-- END JAVASCRIPTS -->

</body>


<!-- END BODY -->

</html>
<script src="/sellcard/Public/js/ajaxfileupload.js"></script>
<script>
    var is_Edit = '<?php echo $is_edit;?>';
    $("#uploadImage").on("change", function(){
        // Get a reference to the fileList
        var files = !!this.files ? this.files : [];

        // If no files were selected, or no FileReader support, return
        if (!files.length || !window.FileReader) return;

        // Only proceed if the selected file is an image
        if (/^image/.test( files[0].type)){

            // Create a new instance of the FileReader
            var reader = new FileReader();

            // Read the local file as a DataURL
            reader.readAsDataURL(files[0]);

            // When loaded, set image data as background of div
            reader.onloadend = function(){

                $("#uploadPreview").html("<img style='max-height: 150px;border:1px solid #ddd;' src='"+this.result+"'/>");
                $('.on_img_btn').hide();
                $('.change_img_btn').show().css('display','inline-block');
                $('.del_img_btn').show().css('display','inline-block');
                $("#upload_kuang").removeClass("dan_upload_img_div1");
            }
        }
    });

    function fahuo(id){
        $('#modal_fahuo').modal('show');
        $('#modal_fahuo_yes')[0].onclick=function(){
            //$('#modal_confirm').modal('hide');
            SubmitFaHuo(id);
        };
    }

    function SubmitFaHuo(id){
        var fh_num = $.trim($("*[name='fh_num']").val());
        if(fh_num==''){
            showAlert("请输入赠送数量");
            return false;
        }
        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/'.$at.'/editgive'); ?>",
            data:{id:id,fh_num:fh_num},
            success: function (result) {
                if(result.code == 1){
                    showAlert(result.res);
                    window.location.reload();
                }else{
                    showAlert(result.res);
                }
            }
        });

    }



    var parent_url = "<?php echo U('home/user/index');?>";

    function to_gen(){
        window.location.href = parent_url;
    }

    function EditCheckbox(id){
        var str ;
        if($('.tuijian'+id).is(':checked')) {
            str =1;
        }else{
            str=0;
        }
        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/'.$at.'/edittuijian'); ?>",
            data:{id:id,str:str},
            success: function (result) {
                if(result.code == 1){
                    showAlert(result.res);
                    window.location.reload();
                }else{
                    showAlert(result.res);
                }
            }
        });
    }

    function EditStatus(id,str,title,db){
        var name;
        if(str=='1'){
            name = '显示';
        }else{
            name = '隐藏';
        }
        showConfirm('<i class="icon-exclamation-sign"></i> '+name+title,'确定要'+name+title+' 吗?',function(){
            $.ajax({
                type: 'POST',
                url: "<?php echo U('home/public/editshow'); ?>",
                data:{id:id,str:str,db:db},
                success: function (result) {
                    if(result.code == 1){
                        showAlert(result.res);
                        window.location.reload();
                    }else{
                        showAlert(result.res);
                    }
                }
            });
        });
    }

    function _deleteUser(id,name,db){
        showConfirm('<i class="icon-exclamation-sign"></i> 删除'+name,'确定要删除'+name+' 吗?',function(){
            $.ajax({
                type: 'POST',
                url: "<?php echo U('home/public/delete'); ?>",
                data:{id:id,db:db},
                success: function (result) {
                    if(result.code == 1){
                        showAlert(result.res);
                        window.location.reload();
                    }else{
                        showAlert(result.res);
                    }
                }
            });
        });
    }

    function ClearImg(){
        $("#upload_kuang").addClass("dan_upload_img_div1");
        $("#uploadImage").val('');
        $('.on_img_btn').show();
        $('.change_img_btn').hide();
        $('.del_img_btn').hide();
        $('#uploadPreview').html('');
    }


    function UploadImg(){
        var img = "";
        var file = $("#uploadImage").val();
        if(file==""){
            SubmitData("");
        }


        else{
            //判断上传的文件的格式是否正确
            var fileType = file.substring(file.lastIndexOf(".")+1);
            if(fileType!="png"&&fileType!="jpg" &&fileType!="jpeg"){
                showAlert("上传文件格式错误");
                return false;
            }
            else{
                var url = "<?php echo U('home/Upload/thumb');?>";
                $.ajaxFileUpload({
                    url:url,
                    fileElementId:"uploadImage",        //file的id
                    dataType:"json",                  //返回数据类型为文本
                    success:function(res){
                        if(res){
                            img = res;

                            SubmitData(img);
                        }else{
                            showAlert("图片上传失败");
                        }
                    }
                })
            }
        }
    }


    function SubmitData(){

        var name = $.trim($("*[name='name']").val());
        var phone = $.trim($("*[name='phone']").val());
        var location = $.trim($("*[name='location']").val());
        var parent = $.trim($("*[name='parent']").val());
        var price = $.trim($("*[name='price']").val());
        var ku_num = $.trim($("*[name='ku_num']").val());

        if(!name){
            showAlert("请输入明细账单名称");
            return false;
        }

        if(!phone){
            showAlert("请输入手机号");
            return false;
        }
        if(!location){
            showAlert("请输入地址");
            return false;
        }



        $('#js_btn').attr('disabled',true);
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?php echo U('home/user/add'); ?>",
            data:{name:name,phone:phone,location:location,parent:parent,price:price,ku_num:ku_num,is_edit:is_Edit},
            success: function (result) {
                if(result.code == 1){
                    showAlert(result.res);
                    window.location.href= parent_url;
                }else if(result.code==0){
                    showAlert(result.res);
                    $('#js_btn').attr('disabled',false);
                }else{
                    showAlert("提交失败，请检查您的输入是否合法");
                    $('#js_btn').attr('disabled',false);
                }
            }
        });

    }

</script>