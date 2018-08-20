<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title>Quản lý Timesheet</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description" />
    <meta content="" name="author" />

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="<?php echo asset('global/plugins/font-awesome/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/simple-line-icons/simple-line-icons.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/uniform/css/uniform.default.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/bootstrap-switch/css/bootstrap-switch.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/bootstrap-fileinput/bootstrap-fileinput.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/bootstrap-switch/css/bootstrap-switch.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/jquery-tags-input/jquery.tagsinput.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/typeahead/typeahead.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/bootstrap-datepicker/css/datepicker3.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/fullcalendar/fullcalendar.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/jqvmap/jqvmap/jqvmap.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/select2/select2.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/bootstrap-toastr/toastr.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/fullcalendar/fullcalendar.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/bootstrap-select/bootstrap-select.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/plugins/sweetalert/sweet-alert.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('admin/pages/css/tasks.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/css/components.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('global/css/plugins.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('admin/layout/css/layout.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('admin/layout/css/themes/darkblue.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('admin/layout/css/custom.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset('app.css');?>" rel="stylesheet" type="text/css" />
</head>

<body class="page-header-fixed page-quick-sidebar-over-content ">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="<?php echo url('/'); ?>">
                    <img src="<?php echo asset('admin/layout/img/logo.png');?>" alt="logo" class="logo-default" />
                </a>
            </div>
            <!-- END LOGO -->

            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu" style="clear:none;">
                <ul class="nav navbar-nav pull-right">
                    <!-- BEGIN NOTIFICATION DROPDOWN -->

                    <li class="dropdown dropdown-user">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <img alt="" class="img-circle hide1" src="<?php echo asset('admin/layout/img/avatar3_small.jpg');?>" />
                            <span class="username username-hide-on-mobile">{{{$user ? $user->username : 'Guest'}}}</span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (isEmployee()): ?>
                                <li>
                                    <a href="<?php echo url('/'); ?>"><i class="icon-plus"></i> Gửi báo cáo ngày</a>
                                </li>
                                <li>
                                    <a href="<?php echo url('history'); ?>"><i class="icon-user"></i> Cá nhân</a>
                                </li>
                            <?php endif; ?>

                            <?php if (isEmployee()): ?>
                                <li>
                                    <a href="<?php echo url('reports'); ?>"><i class="icon-bar-chart"></i> Tổng hợp phòng</a>
                                </li>
                            <?php endif; ?>

                            <?php if (isEmployee()): ?>
                                <li>
                                    <a href="<?php echo url('projects'); ?>"><i class="icon-settings"></i> Dự án</a>
                                </li>
                                <li>
                                    <a href="<?php echo url('regDayOff'); ?>"><i class="icon-calendar"></i> Nghỉ phép</a>
                                </li>
                            <?php endif; ?>

                            <li>
                                <a href="<?php echo url('logout'); ?>"><i class="icon-logout"></i> Đăng xuất</a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <div class="clearfix"></div>

    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <div class="page-content">
                @yield('content')
            </div>
        </div>
        <!-- END DASHBOARD STATS -->
        <div class="clearfix"></div>
    </div>

    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="page-footer-inner">
            2014 &copy; Mobifone
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
    <!-- END FOOTER -->

    <!--[if lt IE 9]>
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
    <script src="<?php echo asset('global/plugins/bootstrap-toastr/toastr.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/select2/select2.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/datatables/media/js/jquery.dataTables.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/fuelux/js/spinner.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/bootstrap-fileinput/bootstrap-fileinput.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/jquery.input-ip-address-control-1.0.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/bootstrap-switch/js/bootstrap-switch.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/jquery-tags-input/jquery.tagsinput.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/bootstrap-touchspin/bootstrap.touchspin.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/typeahead/handlebars.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/typeahead/typeahead.bundle.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/ckeditor/ckeditor.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/bootstrap-daterangepicker/moment.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/fullcalendar/fullcalendar.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/bootstrap-daterangepicker/daterangepicker.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/jquery-multi-select/js/jquery.multi-select.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/bootstrap-select/bootstrap-select.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/plugins/sweetalert/sweet-alert.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('global/scripts/metronic.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/layout/scripts/layout.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/layout/scripts/quick-sidebar.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/layout/scripts/demo.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/pages/scripts/index.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/pages/scripts/tasks.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/pages/scripts/components-form-tools.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/pages/scripts/components-pickers.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/pages/scripts/components-dropdowns.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/pages/scripts/calendar.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/pages/scripts/components-dropdowns.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('admin/pages/scripts/components-form-tools.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset('app.js');?>" type="text/javascript"></script>

    <script>
        QTHC.baseUrl = <?php echo json_encode($baseUrl); ?>;
        QTHC.token = <?php echo json_encode(csrf_token()); ?>;
        jQuery(document).ready(function() {
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            QuickSidebar.init(); // init quick sidebar
            Demo.init(); // init demo features
            Index.initDashboardDaterange();
            ComponentsDropdowns.init();
        });
    </script>
    @yield('script');
</body>
</html>
