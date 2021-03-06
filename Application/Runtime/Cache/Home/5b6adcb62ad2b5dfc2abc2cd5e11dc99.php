<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->

<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

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

    <link href="/taxihailing_cms/Public/css/login-soft.css" rel="stylesheet" type="text/css"/>

    <!-- END PAGE LEVEL STYLES -->

    <link rel="shortcut icon" href="/taxihailing_cms/Public/image/logo180.ico" />

</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class="login">

<!-- BEGIN LOGO -->

<div class="logo">

    <img src="/taxihailing_cms/Public/image/logo-big.png" alt="" />

</div>

<!-- END LOGO -->

<!-- BEGIN LOGIN -->

<div class="content">

    <!-- BEGIN LOGIN FORM -->

    <form class="form-vertical login-form" action="index.html">

        <h3 class="form-title">登录</h3>

        <div class="alert alert-error hide">

            <button class="close" data-dismiss="alert"></button>

            <span>用户名或密码错误，请重新输入.</span>

        </div>

        <div class="control-group">

            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->

            <label class="control-label visible-ie8 visible-ie9">Username</label>

            <div class="controls">

                <div class="input-icon left">

                    <i class="icon-user"></i>

                    <input class="m-wrap placeholder-no-fix" type="text" id="Login_Username" value="<?php echo ($username); ?>" placeholder="Username" name="username"/>

                </div>

            </div>

        </div>

        <div class="control-group">

            <label class="control-label visible-ie8 visible-ie9">Password</label>

            <div class="controls">

                <div class="input-icon left">

                    <i class="icon-lock"></i>

                    <input class="m-wrap placeholder-no-fix" type="password"  value="<?php echo ($password); ?>" placeholder="Password" id="Login_Password" name="password"/>

                </div>

            </div>

        </div>

        <div class="form-actions" style="text-align: center;">

           <!-- <label class="checkbox">

                <input type="checkbox" name="remember" value="1"/> Remember me

            </label>-->

            <button type="submit" id="sub_btn" class="btn blue">

                Login <i class="m-icon-swapright m-icon-white"></i>

            </button>

        </div>

      <!--  <div class="forget-password">

            <h4>Forgot your password ?</h4>

            <p>

                no worries, click <a href="javascript:;" class="" id="forget-password">here</a>

                to reset your password.

            </p>

        </div>

        <div class="create-account">

            <p>

                Don't have an account yet ?&nbsp;

                <a href="javascript:;" id="register-btn" class="">Create an account</a>

            </p>

        </div>-->

    </form>

    <!-- END LOGIN FORM -->

    <!-- BEGIN FORGOT PASSWORD FORM -->

    <form class="form-vertical forget-form" action="index.html">

        <h3 class="">Forget Password ?</h3>

        <p>Enter your e-mail address below to reset your password.</p>

        <div class="control-group">

            <div class="controls">

                <div class="input-icon left">

                    <i class="icon-envelope"></i>

                    <input class="m-wrap placeholder-no-fix" type="text" placeholder="Email" name="email" />

                </div>

            </div>

        </div>

        <div class="form-actions">

            <button type="button" id="back-btn" class="btn">

                <i class="m-icon-swapleft"></i> Back

            </button>

            <button type="submit" class="btn blue pull-right">

                Submit <i class="m-icon-swapright m-icon-white"></i>

            </button>

        </div>

    </form>

    <!-- END FORGOT PASSWORD FORM -->

    <!-- BEGIN REGISTRATION FORM -->

    <form class="form-vertical register-form" action="index.html">

        <h3 class="">Sign Up</h3>

        <p>Enter your account details below:</p>

        <div class="control-group">

            <label class="control-label visible-ie8 visible-ie9">Username</label>

            <div class="controls">

                <div class="input-icon left">

                    <i class="icon-user"></i>

                    <input class="m-wrap placeholder-no-fix" type="text" placeholder="Username" name="username"/>

                </div>

            </div>

        </div>

        <div class="control-group">

            <label class="control-label visible-ie8 visible-ie9">Password</label>

            <div class="controls">

                <div class="input-icon left">

                    <i class="icon-lock"></i>

                    <input class="m-wrap placeholder-no-fix" type="password" id="register_password" placeholder="Password" name="password"/>

                </div>

            </div>

        </div>

        <div class="control-group">

            <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>

            <div class="controls">

                <div class="input-icon left">

                    <i class="icon-ok"></i>

                    <input class="m-wrap placeholder-no-fix" type="password" placeholder="Re-type Your Password" name="rpassword"/>

                </div>

            </div>

        </div>

        <div class="control-group">

            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->

            <label class="control-label visible-ie8 visible-ie9">Email</label>

            <div class="controls">

                <div class="input-icon left">

                    <i class="icon-envelope"></i>

                    <input class="m-wrap placeholder-no-fix" type="text" placeholder="Email" name="email"/>

                </div>

            </div>

        </div>

        <div class="control-group">

            <div class="controls">

                <label class="checkbox">

                    <input type="checkbox" name="tnc"/> I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>

                </label>

                <div id="register_tnc_error"></div>

            </div>

        </div>

        <div class="form-actions">

            <button id="register-back-btn" type="button" class="btn">

                <i class="m-icon-swapleft"></i>  Back

            </button>

            <button type="submit" id="register-submit-btn" class="btn blue pull-right">

                Sign Up <i class="m-icon-swapright m-icon-white"></i>

            </button>

        </div>

    </form>

    <!-- END REGISTRATION FORM -->

</div>

<!-- END LOGIN -->

<!-- BEGIN COPYRIGHT -->

<div class="copyright">

    2017 &copy; 一方盟 - 后台管理系统.

</div>

<!-- END COPYRIGHT -->

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

<script src="/taxihailing_cms/Public/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/taxihailing_cms/Public/js/jquery.backstretch.min.js" type="text/javascript"></script>

<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/taxihailing_cms/Public/js/app.js" type="text/javascript"></script>
<script src="/taxihailing_cms/Public/js/login-soft.js" type="text/javascript"></script>
<script src="/taxihailing_cms/Public/js/xuan.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    jQuery(document).ready(function() {
        App.init();
        Login.init();
    });
    var submit_url =  "<?php echo U('home/login/verify'); ?>";
    var to_url =  "<?php echo U('home/home/index'); ?>";
</script>

<!-- END JAVASCRIPTS -->

</body>

<!-- END BODY -->

</html>