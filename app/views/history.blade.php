@extends('layouts.main')

@section('content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Công việc cá nhân ngày <span id="jsShowDate"><?php echo htmlentities($reportDate); ?></span>&nbsp;&nbsp;&nbsp;<button style="position:relative; top:-3px;" class="btn default date-picker" type="button"><i class="fa fa-calendar"></i></button>
</h3>
<!-- END PAGE HEADER-->

<!-- BEGIN DASHBOARD STATS -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <table class="table table-striped table-hover" id="jsHistoryTable">
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
            $('#jsHistoryTable').dataTable({
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
            var tableWrapper = $("#jsHistoryTable_wrapper");
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
                window.location = QTHC.baseUrl + '/history/' + mDate.getDate() + '-' + (mDate.getMonth() + 1) + '-' + mDate.getFullYear();
            }
        });
    });
</script>
@stop