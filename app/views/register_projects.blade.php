@extends('layouts.main')

@section('content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Đăng ký tham gia dự án
</h3>
<!-- END PAGE HEADER-->

<!-- BEGIN DASHBOARD STATS -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <table class="table table-striped table-hover" id="jsRegProjectTable">
                    <thead>
                        <tr>
                            <th>
                                STT
                            </th>
                            <th>
                                Dự án
                            </th>
                            <th>
                                Trạng thái
                            </th>
                            <th>
                                Hành động
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $index => $project): ?>
                            <tr>
                                <td>
                                    <?php echo ($index + 1); ?>
                                </td>
                                <td>
                                    <?php echo htmlentities($project['name']); ?>
                                </td>
                                <td>
                                    <?php if ($project['involved']): ?>
                                        <span class="label label-sm label-success jsLabelStatus">Đã tham gia</span>
                                    <?php else: ?>
                                        <span class="label label-sm label-danger jsLabelStatus">Chưa tham gia</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($project['involved']): ?>
                                        <a data-project-id="<?php echo $project['id'] ?>" href="javascript:void(0);" class="delete btn btn-default btn-xs black dialog-success jsLeaveProjectBtn">
                                            <i class="fa fa-trash-o"></i>
                                            <span>Rời dự án</span>
                                        </a>
                                    <?php else: ?>
                                        <a data-project-id="<?php echo $project['id'] ?>" href="javascript:void(0);" class="delete btn btn-default btn-xs black dialog-success jsJoinProjectBtn">
                                            <i class="fa fa-trash-o"></i>
                                            <span>Tham gia</span>
                                        </a>
                                    <?php endif; ?>
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
            $('#jsRegProjectTable').dataTable({
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

            $('#jsRegProjectTable').on('click', '.jsJoinProjectBtn', function() {
                var $this = $(this);
                var projectId = $this.data('projectId');
                QTHC.postWS('/joinProject', {
                    'data' : {
                        '_token' : QTHC.token,
                        'projectId' : projectId
                    },

                    'onDone' : function(res) {
                        if (res.status == 'OK') {
                            $this.removeClass('jsJoinProjectBtn').addClass('jsLeaveProjectBtn');
                            $this.find('span').text('Rời dự án');
                            $this.parents('tr').find('.jsLabelStatus').removeClass('label-danger').addClass('label-success').text('Đã tham gia');
                            QTHC.alertSuccess('Tham gia dự án thành công');
                        } else {
                            QTHC.alertError(res.msg);
                        }
                    },

                    'onFail' : function() {
                        QTHC.alertError('Hãy thử lại sau');
                    }
                });
            });

            $('#jsRegProjectTable').on('click', '.jsLeaveProjectBtn', function() {
                var $this = $(this);
                var projectId = $this.data('projectId');
                QTHC.postWS('/leaveProject', {
                    'data' : {
                        '_token' : QTHC.token,
                        'projectId' : projectId
                    },

                    'onDone' : function(res) {
                        if (res.status == 'OK') {
                            $this.removeClass('jsLeaveProjectBtn').addClass('jsJoinProjectBtn');
                            $this.find('span').text('Tham gia');
                            $this.parents('tr').find('.jsLabelStatus').removeClass('label-success').addClass('label-danger').text('Chưa tham gia');
                            QTHC.alertSuccess('Rời dự án thành công');
                        } else {
                            QTHC.alertError(res.msg);
                        }
                    },

                    'onFail' : function() {
                        QTHC.alertError('Hãy thử lại sau');
                    }
                });
            });
        }();
    });
</script>
@stop