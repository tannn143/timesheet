<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>Quản lý Timesheet</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description"/>
    <meta content="" name="author"/>

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="<?php echo asset('global/plugins/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo asset('global/plugins/simple-line-icons/simple-line-icons.min.css'); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo asset('global/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo asset('global/plugins/uniform/css/uniform.default.css'); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo asset('global/plugins/bootstrap-switch/css/bootstrap-switch.min.css'); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo asset('global/plugins/select2/select2.css'); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo asset('admin/pages/css/login-soft.css'); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo asset('global/css/components.css'); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo asset('global/css/plugins.css'); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo asset('admin/layout/css/layout.css'); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo asset('admin/layout/css/themes/default.css'); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo asset('admin/layout/css/custom.css'); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo asset('app.css'); ?>" rel="stylesheet" type="text/css"/>
</head>

<body class="login">
<!-- BEGIN LOGO -->
    <div class="logo">
        <a href="/">
            <img src="<?php echo asset('admin/layout/img/logo.png'); ?>" />
        </a>
    </div>

    <div class="menu-toggler sidebar-toggler"></div>

    <div class="content">
        <!-- BEGIN LOGIN FORM -->
        <form action="register" method="POST">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <h3 class="form-title">Đăng ký</h3>
            <?php $regErrors = Session::get('regErrors'); ?>
            <?php if (!empty($regErrors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($regErrors as $i => $regError): ?>
                        <li><?php echo $regError; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Tên đăng nhập</label>

                <div class="input-icon">
                    <i class="fa fa-user"></i>
                    <input class="form-control placeholder-no-fix" type="text" value="<?php echo htmlentities(Session::get('username', '')); ?>" autocomplete="off" placeholder="Tên đăng nhập" name="username"/>
                </div>
            </div>
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Email</label>

                <div class="input-icon">
                    <i class="fa fa-user"></i>
                    <input class="form-control placeholder-no-fix" type="text" value="<?php echo htmlentities(Session::get('email', '')); ?>" autocomplete="off" placeholder="Email" name="email"/>
                </div>
            </div>
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Họ và tên</label>

                <div class="input-icon">
                    <i class="fa fa-user"></i>
                    <input class="form-control placeholder-no-fix" type="text" value="<?php echo htmlentities(Session::get('full_name', '')); ?>" autocomplete="off" placeholder="Họ và tên" name="full_name"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Mật khẩu</label>

                <div class="input-icon">
                    <i class="fa fa-lock"></i>
                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Mật khẩu" name="password"/>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Nhập lại mật khẩu</label>

                <div class="input-icon">
                    <i class="fa fa-lock"></i>
                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Nhập lại mật khẩu" name="confirm_password"/>
                </div>
            </div>

            <div class="form-actions" style="margin-bottom: 10px;">
                <a href="<?php echo url('/'); ?>" class="btn red pull-left">
                    <i class="m-icon-swapleft m-icon-white"></i> Đăng nhập
                </a>
                <button type="submit" class="btn blue pull-right">
                    Đăng ký <i class="m-icon-swapright m-icon-white"></i>
                </button>
            </div>
        </form>
        <!-- END LOGIN FORM -->

    </div>

    <script src="<?php echo asset('global/plugins/respond.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/excanvas.min.js');?>" type="text/javascript"></script>
    <![endif]-->
    <script src="<?php echo asset('global/plugins/jquery.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/jquery-migrate.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/bootstrap/js/bootstrap.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/jquery-slimscroll/jquery.slimscroll.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/jquery.blockui.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/jquery.cokie.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/uniform/jquery.uniform.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/bootstrap-switch/js/bootstrap-switch.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/jquery-validation/js/jquery.validate.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/backstretch/jquery.backstretch.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/select2/select2.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/scripts/metronic.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/layout/scripts/layout.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/layout/scripts/quick-sidebar.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/layout/scripts/demo.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/pages/scripts/login-soft.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('app.js');?>" type="text/javascript"></script>

    <script>
        QTHC.baseUrl = <?php echo json_encode($baseUrl); ?>;
        jQuery(document).ready(function () {
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            QuickSidebar.init(); // init quick sidebar
            Demo.init(); // init demo features
            Login.init();

            $.backstretch([
                "<?php echo asset('admin/pages/media/bg/1.jpg');?>",
                "<?php echo asset('admin/pages/media/bg/2.jpg');?>",
                "<?php echo asset('admin/pages/media/bg/3.jpg');?>",
                "<?php echo asset('admin/pages/media/bg/4.jpg');?>"
            ], {
                    fade: 1000,
                    duration: 8000
                }
            );
        });
    </script>
</body>
<!-- END BODY -->
</html>