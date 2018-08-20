@extends('layouts.main')

@section('content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Tổng hợp báo cáo phòng ngày <span id="jsShowDate"><?php echo htmlentities($reportDate); ?></span>&nbsp;&nbsp;&nbsp;<button style="position:relative; top:-3px;" class="btn default date-picker" type="button"><i class="fa fa-calendar"></i></button>
</h3>
<!-- END PAGE HEADER-->

<!-- BEGIN DASHBOARD STATS -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div>
                    <?php if (!empty($usersOffInDate)): ?>
                        <span style="font-weight: bold;font-size: 16px;">Xin nghỉ phép:</span>

                        <span style="color: #ff0000;font-size: 16px;">
                            <?php foreach ($usersOffInDate as $index => $userOff): ?>
                                <?php if($index != count($usersOffInDate) - 1): ?>
                                    <?php echo htmlentities($userOff['full_name']) . ', '; ?>
                                <?php else: ?>
                                    <?php echo htmlentities($userOff['full_name']); ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </span>
                    <?php else: ?>
                        <span style="color: #00a402;font-size: 16px;">
                        Không có ai nghỉ phép hôm nay
                    </span>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if (!empty($lateUsers)): ?>
                        <span style="font-weight: bold;font-size: 16px;">Chưa gửi báo cáo:</span>

                        <span style="color: #ff0000;font-size: 16px;">
                            <?php foreach ($lateUsers as $index => $lateUser): ?>
                                <?php if($index != count($lateUsers) - 1): ?>
                                    <?php echo htmlentities($lateUser['full_name']) . ', '; ?>
                                <?php else: ?>
                                    <?php echo htmlentities($lateUser['full_name']); ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </span>
                    <?php else: ?>
                        <span style="color: #00a402;font-size: 16px;">
                            Đã báo cáo đầy đủ
                        </span>
                    <?php endif; ?>
                </div>
                <br/>
                <table class="table table-striped table-hover" id="jsSynthesizeTable">
                    <thead>
                        <tr>
                            <th>
                                STT
                            </th>
                            <th>
                                Dự án
                            </th>
                            <th>
                                Cán bộ thực hiện
                            </th>
                            <th>
                                Công việc trong ngày
                            </th>
                            <th>
                                Công việc dự kiến
                            </th>
                            <th>
                                Ghi chú
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $index => $report): ?>
                            <tr>
                                <td>
                                    <?php echo ($index + 1); ?>
                                </td>
                                <td>
                                    <?php echo htmlentities($report['project']['name']); ?>
                                </td>
                                <td>
                                    <?php echo htmlentities($report['user']['full_name']); ?>
                                </td>
                                <td>
                                    <?php
                                        $report['today_task'] = htmlentities($report['today_task']);
                                        echo str_replace(PHP_EOL, '<br>', $report['today_task']);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $report['next_task'] = htmlentities($report['next_task']);
                                        echo str_replace(PHP_EOL, '<br>', $report['next_task']);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $report['notice'] = htmlentities($report['notice']);
                                        echo str_replace(PHP_EOL, '<br>', $report['notice']);
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- END EXAMPLE TABLE PORTLET-->
@stop

@section('script')
<script>
    var mDate = null;
    $(document).ready(function() {
        var InitTable = function() {
            $('#jsSynthesizeTable').dataTable({
                "lengthMenu": [
                    [5, 10, 20, -1],
                    [5, 10, 20, "Tất cả"] // change per page values here
                ],
                // set the initial value
                "pageLength": -1,

                "language": {
                    "lengthMenu": " _MENU_ báo cáo"
                },
                "columnDefs": [{ // set default column settings
                    'orderable': true,
                    'targets': [0]
                }, {
                    "searchable": true,
                    "targets": [0]
                }],
                "order": [
                    [0, "asc"]
                ] // set first column as a default sort by asc
            });
            var tableWrapper = $("#jsSynthesizeTable_wrapper");
            tableWrapper.find(".dataTables_length select").select2({
                showSearchInput: false //hide search box with special css class
            });
        }();

        $('.date-picker').datepicker({
            rtl: Metronic.isRTL(),
            orientation: "left",
            autoclose: true,
            language: 'vi',
            todayHighlight : true
        }).on('changeDate', function(e) {
            mDate = $('.date-picker').datepicker('getDate');
            if (!isNaN(mDate.getDate())) {
                window.location = QTHC.baseUrl + '/reports/' + mDate.getDate() + '-' + (mDate.getMonth() + 1) + '-' + mDate.getFullYear();
            }
        });

        $('#jsRemindReportBtn').on('click', function() {
            QTHC.confirm('Gửi nhắc nhở báo cáo cho tất cả những người chưa gửi?', function() {
                QTHC.postWS('/remind', {
                    'data' : {
                        '_token' : QTHC.token,
                        'reportDate' : <?php echo json_encode($reportDate) ?>
                    },

                    'onDone' : function(res) {
                        if (res.status == 'OK') {
                            window.setTimeout(function() {QTHC.popupSuccess('Gửi nhắc nhở thành công');}, 200);
                        } else {
                            QTHC.alertError(res.msg);
                        }
                    },

                    'onFail' : function() {
                        QTHC.alertError('Kết nối mạng không ổn định. Hãy thử lại sau');
                    }
                });
            });
        });

        $('#jsSendReportBtn').on('click', function() {
            QTHC.confirm('Gửi tổng hợp báo cáo cho lãnh đạo phòng?', function() {
                QTHC.postWS('/sendAllReport', {
                    'data' : {
                        '_token' : QTHC.token,
                        'reportDate' : <?php echo json_encode($reportDate) ?>
                    },

                    'onDone' : function(res) {
                        if (res.status == 'OK') {
                            window.setTimeout(function() {QTHC.popupSuccess('Đã gửi tổng hợp báo cáo thành công');}, 200);
                        } else {
                            QTHC.alertError(res.msg);
                        }
                    },

                    'onFail' : function() {
                        QTHC.alertError('Kết nối mạng không ổn định. Hãy thử lại sau');
                    }
                });
            });
        });
    });
</script>
@stop