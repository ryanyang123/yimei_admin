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


<!-- 上传图片 -->

<link rel="stylesheet" type="text/css" href="/taxihailing_cms/Public/css/bootstrap-fileupload.css" />

<link rel="stylesheet" type="text/css" href="/taxihailing_cms/Public/css/jquery.gritter.css" />

<link rel="stylesheet" type="text/css" href="/taxihailing_cms/Public/css/chosen.css" />

<link rel="stylesheet" type="text/css" href="/taxihailing_cms/Public/css/select2_metro.css" />

<link rel="stylesheet" type="text/css" href="/taxihailing_cms/Public/css/jquery.tagsinput.css" />

<link rel="stylesheet" type="text/css" href="/taxihailing_cms/Public/css/clockface.css" />

<link rel="stylesheet" type="text/css" href="/taxihailing_cms/Public/css/bootstrap-wysihtml5.css" />

<link rel="stylesheet" type="text/css" href="/taxihailing_cms/Public/css/datepicker.css" />

<link rel="stylesheet" type="text/css" href="/taxihailing_cms/Public/css/timepicker.css" />

<link rel="stylesheet" type="text/css" href="/taxihailing_cms/Public/css/colorpicker.css" />

<link rel="stylesheet" type="text/css" href="/taxihailing_cms/Public/css/bootstrap-toggle-buttons.css" />

<link rel="stylesheet" type="text/css" href="/taxihailing_cms/Public/css/daterangepicker.css" />

<link rel="stylesheet" type="text/css" href="/taxihailing_cms/Public/css/datetimepicker.css" />

<link rel="stylesheet" type="text/css" href="/taxihailing_cms/Public/css/multi-select-metro.css" />

<link href="/taxihailing_cms/Public/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>

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

        <li class="last <?php echo $at == 'activity'?'active':'';?>">
            <a href="<?php echo U('home/activity/index');?>">
                <i class="icon-credit-card"></i>
                <span class="title">活动列表</span>

            </a>

        </li>
        <li class="last <?php echo $at == 'banner'?'active':'';?>">
            <a href="<?php echo U('home/banner/index');?>">
                <i class="icon-credit-card"></i>
                <span class="title">广告列表</span>

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

                        <?php echo ($title); ?> <small>列表</small>

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

                        <li><a href="#"><?php echo $is_edit?'修改'.$title:'新增'.$title;?></a></li>



                    </ul>

                    <!-- END PAGE TITLE & BREADCRUMB-->

                </div>

            </div>

            <!-- END PAGE HEADER-->

            <div id="dashboard">



                <div class="row-fluid">

                    <div class="span12">

                        <!-- BEGIN PORTLET-->

                        <div class="portlet box">



                            <div class="portlet-body form">

                                <!-- BEGIN FORM-->

                                <form action="<?php echo U('home/GameAdd/upload_thumb');?>" class="form-horizontal">




                                    <?php if($is_edit){?>
                                    <div class="control-group">

                                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">广告图片</font></font></label>

                                        <div class="controls">

                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="dan_upload_img_div1" style="border: none;;">
                                                    <img src="<?php echo $list['img_url'];?>" style="max-height: 150px;border:1px solid #ddd;" alt=""/>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <?php }?>

                                    <div class="control-group">

                                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $is_edit?'修改广告图片':'上传广告图片';?></font></font></label>


                                        <div class="controls">
                                            <div id="upload_kuang"  class="dan_upload_img_div1">
                                                <div id="uploadPreview">

                                                </div>
                                            </div>

                                            <label class="on_img_btn" for="uploadImage" style="display: inline;"> <span  class="btn btn-default" style="">选择图片</span></label>
                                            <label class="change_img_btn" for="uploadImage" style="display: none;"> <span  class="btn btn-default" style="">更改</span></label>
                                            <label class="del_img_btn" style="display: none;" onclick="ClearImg()"> <span  class="btn btn-default" style="">删除</span></label>
                                            <input id="uploadImage" style="" type="file"  name="photoimage" class="fimg1"  />
                                        </div>
                                    </div>

                                    <div class="control-group">

                                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">广告类型</font></font></label>

                                        <div class="controls">
                                            <select name="type_id" id="type_id" style="line-height: 34px;height: 34px;">
                                                <option <?php if($list['vehicle_type'] == 1): ?>selected<?php endif; ?> value="1">首页轮播图</option>
                                                <option <?php if($list['vehicle_type'] == 2): ?>selected<?php endif; ?> value="2">弹出广告</option>
                                            </select>
                                        </div>

                                    </div>



                                    <div class="control-group">

                                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">跳转活动</font></font></label>

                                        <div class="controls">
                                            <select name="aid" id="aid" style="line-height: 34px;height: 34px;">
                                                <option <?php if($list['vehicle_type'] == 0): ?>selected<?php endif; ?> value="0">不跳转</option>
                                                <?php if(is_array($activity_list)): foreach($activity_list as $key=>$v): ?><option <?php if($list['vehicle_type'] == $v['id']): ?>selected<?php endif; ?> value="<?php echo ($v["id"]); ?>" ><?php echo ($v["title"]); ?></option><?php endforeach; endif; ?>
                                            </select>
                                        </div>

                                    </div>


                                    <div class="control-group">

                                        <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">排序</font></font></label>

                                        <div class="controls">
                                            <input type="text" placeholder="请输入排序" name="sort"  value="<?php echo ($list["sort"]); ?>" class="m-wrap medium">
                                        </div>

                                    </div>












                                    <div class="form-actions">

                                        <button type="button" id="js_btn" onclick="UploadImg()" class="btn blue"><i class="icon-ok"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> 保存</font></font></button>

                                        <button type="button" onclick="to_gen()" class="btn"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">取消</font></font></button>



                                    </div>

                                </form>

                                <!-- END FORM-->

                            </div>

                        </div>

                        <!-- END PORTLET-->

                    </div>

                </div>


                <div class="clearfix"></div>





            </div>

        </div>

        <!-- END PAGE CONTAINER-->

    </div>

    <!-- END PAGE -->

</div>

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
<script src='/taxihailing_cms/Public/push/socket.io.js'></script>
<script src='/taxihailing_cms/Public/push/notify.js'></script>
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
<script src="/taxihailing_cms/Public/js/ajaxfileupload.js"></script>
<script>

    var is_Edit = '<?php echo $is_edit;?>';
    var parent_url = "<?php echo U('home/'.$at.'/index');?>";

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
    function ClearImg(){
        $("#upload_kuang").addClass("dan_upload_img_div1");
        $("#uploadImage").val('');
        $('.on_img_btn').show();
        $('.change_img_btn').hide();
        $('.del_img_btn').hide();
        $('#uploadPreview').html('');
    }




    function to_gen(){
        window.location.href = parent_url;
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


    function SubmitData(img){
        var aid = $("#aid").val();
        var type = $("#type_id").val();
        var sort = $.trim($("*[name='sort']").val());
        if(!img && !is_Edit){
            showAlert("请上传广告图片");
            return false;
        }


        $('#js_btn').attr('disabled',true);
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?php echo U('home/'.$at.'/add'); ?>",
            data:{aid:aid,type:type,sort:sort,img:img,is_edit:is_Edit},
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