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

    <link href="/taxihailing_cms/Public/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <link href="/taxihailing_cms/Public/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

    <link href="/taxihailing_cms/Public/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

    <link href="/taxihailing_cms/Public/css/style-metro.css" rel="stylesheet" type="text/css"/>

    <link href="/taxihailing_cms/Public/css/style.css" rel="stylesheet" type="text/css"/>

    <link href="/taxihailing_cms/Public/css/style-responsive.css" rel="stylesheet" type="text/css"/>

    <link href="/taxihailing_cms/Public/css/default.css" rel="stylesheet" type="text/css" id="style_color"/>

    <link href="/taxihailing_cms/Public/css/uniform.default.css" rel="stylesheet" type="text/css"/>

    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->

    <link href="/taxihailing_cms/Public/css/jquery.gritter.css" rel="stylesheet" type="text/css"/>

    <link href="/taxihailing_cms/Public/css/daterangepicker.css" rel="stylesheet" type="text/css" />

    <link href="/taxihailing_cms/Public/css/fullcalendar.css" rel="stylesheet" type="text/css"/>

    <link href="/taxihailing_cms/Public/css/jqvmap.css" rel="stylesheet" type="text/css" media="screen"/>

    <link href="/taxihailing_cms/Public/css/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>

    <!-- END PAGE LEVEL STYLES -->
    <link href="/taxihailing_cms/Public/css/jquery.fancybox.css" rel="stylesheet" />


    <link href="/taxihailing_cms/Public/css/search.css" rel="stylesheet" type="text/css"/>






    <link rel="stylesheet" href="/taxihailing_cms/Public/css/kalendae.css" type="text/css" charset="utf-8">

    <link href="/taxihailing_cms/Public/css/xuan.css" rel="stylesheet" type="text/css"/>

    <link rel="shortcut icon" href="/taxihailing_cms/Public/image/logo180.ico" />

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

                <img src="/taxihailing_cms/Public/image/logo.png" alt="logo"/>

            </a>

            <!-- END LOGO -->

            <!-- BEGIN RESPONSIVE MENU TOGGLER -->

            <a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">

                <img src="/taxihailing_cms/Public/image/menu-toggler.png" alt="" />

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

                                 <span class="photo"><img src="/taxihailing_cms/Public/image/avatar2.jpg" alt="" /></span>

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

                                 <span class="photo"><img src=".//taxihailing_cms/Public/image/avatar3.jpg" alt="" /></span>

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

                                 <span class="photo"><img src=".//taxihailing_cms/Public/image/avatar1.jpg" alt="" /></span>

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


<link rel="stylesheet" href="/taxihailing_cms/Public/time/css/borain-timeChoice.css">
<link rel="stylesheet" href="/taxihailing_cms/Public/font-awesome/css/font-awesome.min.css">
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






        <li class="last <?php echo $at == 'vehicletype'?'active':'';?>">
            <a href="<?php echo U('home/vehicletype/index');?>">
                <i class="icon-credit-card"></i>
                <span class="title">车辆类型</span>

            </a>

        </li>


        <li class="last <?php echo $at == 'vehicle'?'active':'';?>">
            <a href="<?php echo U('home/vehicle/index');?>">
                <i class="icon-credit-card"></i>
                <span class="title">车辆列表</span>

            </a>

        </li>

        <li class="last <?php echo $at == 'driver'?'active':'';?>">
            <a href="<?php echo U('home/driver/index');?>">
                <i class="icon-credit-card"></i>
                <span class="title">司机列表</span>

            </a>

        </li>

        <li class="last <?php echo $at == 'shang'?'active':'';?>">
            <a href="<?php echo U('home/shang/index');?>">
                <i class="icon-credit-card"></i>
                <span class="title">上/下车地点分类</span>

            </a>

        </li>

        <li class="last <?php echo $at == 'location'?'active':'';?>">
            <a href="<?php echo U('home/location/index');?>">
                <i class="icon-credit-card"></i>
                <span class="title">上车地点列表</span>

            </a>

        </li>
        <li class="last <?php echo $at == 'location2'?'active':'';?>">
            <a href="<?php echo U('home/location2/index');?>">
                <i class="icon-credit-card"></i>
                <span class="title">下车地点列表</span>

            </a>

        </li>

        <li class="last <?php echo $at == 'location2'?'active':'';?>">
            <a href="<?php echo U('home/location2/index');?>">
                <i class="icon-credit-card"></i>
                <span class="title">活动列表</span>

            </a>

        </li>
























        <li class="last <?php echo $at == 'editpass'?'active':'';?>">
            <a href="<?php echo U('home/editpass/index');?>">
                <i class="icon-key"></i>
                <span class="title">修改密码</span>

            </a>

        </li>
        <li class="last <?php echo $at == 'set'?'active':'';?>">
            <a href="<?php echo U('home/set/index');?>">
                <i class="icon-cog"></i>
                <span class="title">设置</span>

            </a>

        </li>


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

                        设置 <small>配置信息</small>

                    </h3>

                    <ul class="breadcrumb">

                        <li>

                            <i class="icon-home"></i>

                            <a href="<?php echo U('home/home/index');?>">主页</a>

                            <i class="icon-angle-right"></i>

                        </li>

                        <li><a href="#">设置</a></li>



                    </ul>

                    <!-- END PAGE TITLE & BREADCRUMB-->

                </div>

            </div>

            <!-- END PAGE HEADER-->

            <div id="dashboard">



                <div class="row-fluid">

                    <div class="tabbable tabbable-custom tabbable-full-width">

                        <ul class="nav nav-tabs">

                            <li class="active" ><a data-toggle="tab" href="#tab_2_2">企业信息</a></li>

                            <li><a data-toggle="tab" href="#tab_2_3">客服信息</a></li>

                            <li><a data-toggle="tab" href="#tab_2_6">包车价格</a></li>


                        </ul>

                        <div class="tab-content">

                            <!--end tab-pane-->

                            <div id="tab_2_3" class="tab-pane">

                                <div class="row-fluid">
                                    <style>
                                        .toolbar {
                                            border: 1px solid #ccc;
                                        }
                                        .text {
                                            border: 1px solid #ccc;
                                            height: 500px;
                                        }
                                    </style>

                                    <div id="div3" class="toolbar">
                                    </div>


                                    <div id="div4" class="text"> <!--可使用 min-height 实现编辑区域自动增加高度-->
                                        <?php echo ($list["service"]["remark"]); ?>
                                    </div>

                                    <!--<div id="editor" style="min-height: 600px;">
                                        <?php echo ($list["content"]); ?>
                                    </div>-->


                                    <!--end tabbable-->
                                    <div style="margin-top: 50px;text-align: center;">


                                        <button type="button" id="btn2"  class="btn blue">
                                            <i class="icon-ok"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> 更新</font></font></button>
                                    </div>

                                </div>


                            </div>



                            <div id="tab_2_6" class="tab-pane">

                                <div class="row-fluid">
                                    <div class="span12">
                                        <!-- BEGIN PORTLET-->
                                        <div class="portlet box">
                                            <div class="portlet-body form">
                                                <!-- BEGIN FORM-->
                                                <form action="/yfm/home/GameAdd/upload_thumb.html" class="form-horizontal">
                                                    <div class="control-group">
                                                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">2小时价格</font></font></label>
                                                        <div class="controls">
                                                            <input type="text" placeholder="请输入包车两小时价格" name="bao_2_value" value="<?php echo ($list["bao_2"]["set_value"]); ?>" class="m-wrap medium">
                                                            <button type="button" onclick="UploadSet('bao_2')" class="btn blue"><i class="icon-ok"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> 更新</font></font></button>

                                                        </div>
                                                    </div>

                                                    <div class="control-group">
                                                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">4小时价格</font></font></label>
                                                        <div class="controls">
                                                            <input type="text" placeholder="请输入包车4小时价格" name="bao_4_value" value="<?php echo ($list["bao_4"]["set_value"]); ?>" class="m-wrap medium">
                                                            <button type="button" onclick="UploadSet('bao_4')" class="btn blue"><i class="icon-ok"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> 更新</font></font></button>

                                                        </div>
                                                    </div>


                                                </form>

                                                <!-- END FORM-->

                                            </div>

                                        </div>

                                        <!-- END PORTLET-->

                                    </div>

                                </div>

                            </div>

                            <!--end tab-pane-->


                            <div id="tab_2_2" class="tab-pane  active ">
                            <div class="row-fluid">
                                <style>
                                    .toolbar {
                                        border: 1px solid #ccc;
                                    }
                                    .text {
                                        border: 1px solid #ccc;
                                        height: 500px;
                                    }
                                </style>

                                <div id="div1" class="toolbar">
                                </div>


                                <div id="div2" class="text"> <!--可使用 min-height 实现编辑区域自动增加高度-->
                                    <?php echo ($list["company"]["remark"]); ?>
                                </div>

                                <!--<div id="editor" style="min-height: 600px;">
                                    <?php echo ($list["content"]); ?>
                                </div>-->


                                <!--end tabbable-->
                                <div style="margin-top: 50px;text-align: center;">
                                    <button type="button" id="btn1"  class="btn blue">
                                        <i class="icon-ok"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> 更新</font></font></button>
                                </div>

                            </div>
                            </div>






                            <!--end tab-pane-->






                    <!--end tab-pane-->







                        </div>

                    </div>

                    <!--end tabbable-->




                </div>


                <div class="clearfix"></div>





            </div>

        </div>

        <!-- END PAGE CONTAINER-->

    </div>

    <!-- END PAGE -->

</div>

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

<script src="/taxihailing_cms/Public/js/jquery-1.10.1.min.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

<script src="/taxihailing_cms/Public/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/bootstrap.min.js" type="text/javascript"></script>

<!--[if lt IE 9]>

<script src="/taxihailing_cms/Public/js/excanvas.min.js"></script>

<script src="/taxihailing_cms/Public/js/respond.min.js"></script>

<![endif]-->

<script src="/taxihailing_cms/Public/js/jquery.slimscroll.min.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.blockui.min.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.cookie.min.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.uniform.min.js" type="text/javascript" ></script>

<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->

<script src="/taxihailing_cms/Public/js/jquery.vmap.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.vmap.russia.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.vmap.world.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.vmap.europe.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.vmap.germany.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.vmap.usa.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.vmap.sampledata.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.flot.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.flot.resize.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.pulsate.min.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/date.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/daterangepicker.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.gritter.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/fullcalendar.min.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.easy-pie-chart.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/jquery.sparkline.min.js" type="text/javascript"></script>



<!--上传图片添加-->
<script type="text/javascript" src="/taxihailing_cms/Public/js/ckeditor.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/bootstrap-fileupload.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/chosen.jquery.min.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/select2.min.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/wysihtml5-0.3.0.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/bootstrap-wysihtml5.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/jquery.tagsinput.min.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/jquery.toggle.buttons.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/bootstrap-datepicker.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/bootstrap-datetimepicker.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/clockface.js"></script>




<script type="text/javascript" src="/taxihailing_cms/Public/js/bootstrap-colorpicker.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/bootstrap-timepicker.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/jquery.inputmask.bundle.min.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/jquery.input-ip-address-control-1.0.min.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/jquery.multi-select.js"></script>




<!--上传图片添加-->
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/taxihailing_cms/Public/js/jquery.fancybox.pack.js"></script>

<script src="/taxihailing_cms/Public/js/app.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/index.js" type="text/javascript"></script>

<script src="/taxihailing_cms/Public/js/search.js"></script>

<script src="/taxihailing_cms/Public/js/form-components.js"></script>

<script type="text/javascript" src="/taxihailing_cms/Public/js/jquery.dataTables.min.js"></script>

<script src="/taxihailing_cms/Public/js/table-advanced.js"></script>

<script src="/taxihailing_cms/Public/js/kalendae.standalone.js" type="text/javascript" charset="utf-8"></script>

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
                        $('#newMessageDIV2').html('<audio autoplay="autoplay"><source src="/taxihailing_cms/Public'+result.sy_url+'"'
                        + 'type="audio/wav"/><source src="/taxihailing_cms/Public'+result.sy_url+'" type="audio/mpeg"/></audio>');
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
<script src="/taxihailing_cms/Public/time/js/borain-timeChoice.js"></script>
<script type="text/javascript" src="/taxihailing_cms/Public/wangEditor/release/wangEditor.min.js"></script>


<script>
    var E = window.wangEditor
    var editor = new E('#div1', '#div2')
    // 配置服务器端地址
    editor.customConfig.uploadImgServer = "<?php echo U('home/upload/upload');?>"
    editor.customConfig.uploadFileName = 'fileData'


    editor.customConfig.linkImgCheck = function (src) {
        console.log(src) // 图片的链接

        return true // 返回 true 表示校验成功
        // return '验证失败' // 返回字符串，即校验失败的提示信息
    }
    editor.customConfig.linkCheck = function (text, link) {
        console.log(text) // 插入的文字
        console.log(link) // 插入的链接

        return true // 返回 true 表示校验成功
        // return '验证失败' // 返回字符串，即校验失败的提示信息
    }
    editor.customConfig.linkImgCallback = function (url) {
        console.log(url) // url 即插入图片的地址
    }

    document.getElementById('btn1').addEventListener('click', function () {
        // 读取 html
        //alert(editor.txt.html())
        SubmitContent(editor.txt.html(),'company')
    }, false)

    // 或者 var editor = new E( document.getElementById('editor') )
    editor.create();




    var E2 = window.wangEditor
    var editor2 = new E2('#div3', '#div4')
    // 配置服务器端地址
    editor2.customConfig.uploadImgServer = "<?php echo U('home/upload/upload');?>"
    editor2.customConfig.uploadFileName = 'fileData'


    editor2.customConfig.linkImgCheck = function (src) {
        console.log(src) // 图片的链接

        return true // 返回 true 表示校验成功
        // return '验证失败' // 返回字符串，即校验失败的提示信息
    }
    editor2.customConfig.linkCheck = function (text, link) {
        console.log(text) // 插入的文字
        console.log(link) // 插入的链接

        return true // 返回 true 表示校验成功
        // return '验证失败' // 返回字符串，即校验失败的提示信息
    }
    editor2.customConfig.linkImgCallback = function (url) {
        console.log(url) // url 即插入图片的地址
    }

    document.getElementById('btn2').addEventListener('click', function () {
        // 读取 html
        //alert(editor.txt.html())
        SubmitContent(editor2.txt.html(),'service')
    }, false)

    // 或者 var editor = new E( document.getElementById('editor') )
    editor2.create();







    function SubmitContent(str,value){
        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/set/shili'); ?>",
            data:{str:str,value:value},
            success: function (result) {
                if(result.code == 1){
                    showAlert(result.res);
                }else{
                    showAlert(result.res);
                }
            }
        });
    }


    function SetPayType(){
        var set_type = $("input[name='set_pay_type']:checked").val();
        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/'.$at.'/updatepay'); ?>",
            data:{set_type:set_type},
            success: function (result) {
                if(result.code == 1){
                    showAlert(result.res);
                }else{
                    showAlert(result.res);
                }
            }
        });
    }


    function UpdateVip(){
        var status_type = $("input[name='optionsRadios1']:checked").val();
        var start_time = '';
        var end_time = '';
        var vip_content='';
        if(status_type==1){
            vip_content = $.trim($("#vip_content").val());
            if(!vip_content){
                showAlert("请输入活动规则");
                return false;
            }
        }
        if(status_type==2){
            start_time = $.trim($("*[name='vip_start_time']").val());
            end_time = $.trim($("*[name='vip_end_time']").val());
            vip_content = $.trim($("#vip_content").val());
            if(!start_time){
                showAlert("请选择活动开始时间");
                return false;
            }
            if(!end_time){
                showAlert("请选择活动结束时间");
                return false;
            }
            if(!vip_content){
                showAlert("请输入活动规则");
                return false;
            }
        }

        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/'.$at.'/updatevip'); ?>",
            data:{status_type:status_type,start_time:start_time,end_time:end_time,vip_content:vip_content},
            success: function (result) {
                if(result.code == 1){
                    showAlert(result.res);
                }else{
                    showAlert(result.res);
                }
            }
        });

    }


    function Updatexs(){
        var status_type = $("input[name='optionsRadios4']:checked").val();
        var start_time = '';
        var end_time = '';
        var vip_content='';
        if(status_type==1){
            vip_content = $.trim($("#xs_content").val());
            if(!vip_content){
                showAlert("请输入活动规则");
                return false;
            }
        }
        if(status_type==2){
            start_time = $.trim($("*[name='xs_start_time']").val());
            end_time = $.trim($("*[name='xs_end_time']").val());
            vip_content = $.trim($("#xs_content").val());
            if(!start_time){
                showAlert("请选择活动开始时间");
                return false;
            }
            if(!end_time){
                showAlert("请选择活动结束时间");
                return false;
            }
            if(!vip_content){
                showAlert("请输入活动规则");
                return false;
            }
        }

        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/'.$at.'/updatexs'); ?>",
            data:{status_type:status_type,start_time:start_time,end_time:end_time,vip_content:vip_content},
            success: function (result) {
                if(result.code == 1){
                    showAlert(result.res);
                }else{
                    showAlert(result.res);
                }
            }
        });

    }

    function Updatetj(){
        var status_type = $("input[name='optionsRadios3']:checked").val();
        var tj_money = $.trim($("*[name='tj_money']").val());
        var tj_num = $.trim($("*[name='tj_num']").val());
        var start_time = '';
        var end_time = '';
        var vip_content='';


        if(status_type==1){
            vip_content = $.trim($("#tj_content").val());
            if(!vip_content){
                showAlert("请输入活动规则");
                return false;
            }
        }else{
            if(!tj_money){
                showAlert("请输入消费金额");
                return false;
            }
            if(!tj_num){
                showAlert("请输入赠送数量");
                return false;
            }
        }
        if(status_type==2){
            start_time = $.trim($("*[name='tj_start_time']").val());
            end_time = $.trim($("*[name='tj_end_time']").val());
            vip_content = $.trim($("#tj_content").val());
            if(!start_time){
                showAlert("请选择活动开始时间");
                return false;
            }
            if(!end_time){
                showAlert("请选择活动结束时间");
                return false;
            }
            if(!vip_content){
                showAlert("请输入活动规则");
                return false;
            }
        }

        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/'.$at.'/updatetj'); ?>",
            data:{status_type:status_type,start_time:start_time,end_time:end_time,vip_content:vip_content,tj_money:tj_money,tj_num:tj_num},
            success: function (result) {
                if(result.code == 1){
                    showAlert(result.res);
                }else{
                    showAlert(result.res);
                }
            }
        });

    }



    function Updatemonth(){
        var status_type = $("input[name='optionsRadios2']:checked").val();
        var start_time = '';
        var end_time = '';
        var vip_content='';
        if(status_type==1){
            vip_content = $.trim($("#month_content").val());
            if(!vip_content){
                showAlert("请输入活动规则");
                return false;
            }
        }
        if(status_type==2){
            start_time = $.trim($("*[name='month_start_time']").val());
            end_time = $.trim($("*[name='month_end_time']").val());
            vip_content = $.trim($("#month_content").val());
            if(!start_time){
                showAlert("请选择活动开始时间");
                return false;
            }
            if(!end_time){
                showAlert("请选择活动结束时间");
                return false;
            }
            if(!vip_content){
                showAlert("请输入活动规则");
                return false;
            }
        }

        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/'.$at.'/updatemonth'); ?>",
            data:{status_type:status_type,start_time:start_time,end_time:end_time,vip_content:vip_content},
            success: function (result) {
                if(result.code == 1){
                    showAlert(result.res);
                }else{
                    showAlert(result.res);
                }
            }
        });

    }


    function UploadSets(str){
        var set_value = $.trim($("*[name='"+str+"_value']").val());
        var remark = $.trim($("*[name='"+str+"_remark']").val());

        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/set/UploadSets'); ?>",
            data:{str:str,set_value:set_value,remark:remark},
            success: function (result) {
                if(result.code == 1){
                    showAlert(result.res);
                }else{
                    showAlert(result.res);
                }
            }
        });
    }

    function UploadSet(str){
        var set_value = $.trim($("*[name='"+str+"_value']").val());

        $.ajax({
            type: 'POST',
            url: "<?php echo U('home/set/UploadSet'); ?>",
            data:{str:str,set_value:set_value},
            success: function (result) {
                if(result.code == 1){
                    showAlert(result.res);
                }else{
                    showAlert(result.res);
                }
            }
        });
    }   

    function _deleteUser(id,name){
        showConfirm('<i class="icon-exclamation-sign"></i> 删除客户','确定要删除客户 '+name+' 吗?',function(){
            $.ajax({
                type: 'POST',
                url: "<?php echo U('home/user/delete'); ?>",
                data:{id:id},
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


    function EditData(id)
    {
        location.href = "<?php echo U('home/GameAdd/index/edit_id/"+id+"');?>";
    }
</script>