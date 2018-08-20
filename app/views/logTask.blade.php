@extends('layouts.main')

@section('content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Báo cáo công việc ngày <span id="jsShowDate"><?php echo Date('d-m-Y'); ?></span>&nbsp;&nbsp;&nbsp;<button style="position:relative; top:-3px;" class="btn default date-picker" type="button"><i class="fa fa-calendar"></i></button>
</h3>
<!-- END PAGE HEADER-->

<!-- BEGIN DASHBOARD STATS -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    Chi tiết công việc
                </div>
            </div>
            <div class="portlet-body">
                <form id="jsUpdateTaskForm" class="form-horizontal form-row-seperated">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <div class="form-body ">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Dự án</label>
                            <div class="col-md-5">
                                <select id="jsSelectProject" name="project" class="bs-select form-control">
                                    @foreach ($projects as $project)
                                        <option value="{{{$project->id}}}">{{{$project->name}}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Công việc trong ngày</label>
                            <div class="col-md-5">
                                <textarea id="jsCurrentTask" class=" form-control" name="currentTask" rows="6" placeholder="Nhập công việc trong ngày">{{{ !empty($report) ? $report['today_task'] : '' }}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Công việc dự kiến</label>
                            <div class="col-md-5">
                                <textarea id="jsNextTask" class=" form-control" name="nextTask" rows="6" placeholder="Nhập công việc dự kiến ngày tiếp theo">{{{ !empty($report) ? $report['next_task'] : '' }}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Ghi chú</label>
                            <div class="col-md-5">
                                <textarea id="jsNotice" class=" form-control" name="notice" rows="6" placeholder="Nhập ghi chú">{{{ !empty($report) ? $report['notice'] : '' }}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-5">
                                <button type="submit" class="btn blue"><i class="fa fa-save"></i> Cập nhật</button>
                            </div>
                        </div>
                    </div>
                    <input id="jsDate" type="hidden" value="<?php echo Date('Y-m-d'); ?>" name="mdate"/>
                </form>
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
        var getReport = function() {
            var select = $('#jsSelectProject');
            var projectId = select.val();
            var date = $('#jsDate').val();

            QTHC.getWS('/getTasks/' + projectId + '/' + date, {
                'onDone' : function(res) {
                    if (res.status == 'OK') {
                        var report = res.report;
                        $('#jsCurrentTask').val(report['today_task']);
                        $('#jsNextTask').val(report['next_task']);
                        $('#jsNotice').val(report['notice']);
                    } else {
                        QTHC.alertError(res.msg);
                    }
                },

                'onFail' : function() {
                    QTHC.alertError('Hãy thử lại sau');
                }
            });
        };

        $('.date-picker').datepicker({
            rtl: Metronic.isRTL(),
            orientation: "left",
            autoclose: true,
            language: 'vi',
            todayHighlight : true
        }).on('changeDate', function(e) {
            mDate = $('.date-picker').datepicker('getDate');
            if (!isNaN(mDate.getFullYear())) {
                $('#jsDate').val(mDate.getFullYear() + '-' + (mDate.getMonth() + 1) + '-' + mDate.getDate());
                $('#jsShowDate').text(mDate.getDate() + '-' + (mDate.getMonth() + 1) + '-' + mDate.getFullYear());
                getReport();
            }
        });

        $('#jsUpdateTaskForm').on('submit', function(e) {
            e.stopImmediatePropagation();
            e.preventDefault();
            var form = $(this);
            QTHC.postWS('/logTask', {
                'data' : form.serialize(),
                'onDone' : function(res) {
                    if (res.status == 'OK') {
                        window.setTimeout(function() {QTHC.popupSuccess('Đã ghi nhận công việc');}, 200);
                    } else {
                        QTHC.alertError(res.msg);
                    }
                },

                'onFail' : function() {
                    QTHC.alertError('Hãy thử lại sau');
                }
            });
        });

        $('#jsSelectProject').on('change', function() {
            getReport();
        });
    });
</script>
@stop