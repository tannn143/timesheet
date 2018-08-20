@extends('layouts.main')

@section('content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Đăng ký nghỉ phép&nbsp;&nbsp;
    <select id="jsSelectMonth" class="form-control bs-select input-small">
        <?php for($i = 1; $i <= 12; $i++): ?>
            <?php if ($i == Date('m')): ?>
                <option selected="selected" value="<?php echo $i?>">Tháng <?php echo $i . ' / ' . $currentYear;?></option>
            <?php else: ?>
                <option value="<?php echo $i?>">Tháng <?php echo $i . ' / ' . $currentYear;?></option>
            <?php endif; ?>
        <?php endfor; ?>
    </select>
</h3>
<!-- END PAGE HEADER-->

<!-- BEGIN DASHBOARD STATS -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <table class="table table-striped table-hover" id="jsDayOffTable">
                    <thead>
                        <tr>
                            <th>
                                Ngày
                            </th>
                            <th>
                                Lý do
                            </th>
                            <th>
                                Hành động
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datesOff as $i => $date): ?>
                            <tr>
                                <td>
                                    <span class="jsDateOff"><?php echo $date['display']; ?></span>
                                </td>
                                <td>
                                    <?php if ($date['is_off']): ?>
                                        <span class="jsReasonLabel"><?php echo htmlentities($date['reason']);?></span>
                                    <?php else: ?>
                                        <input type="text" class="form-control jsReason" placeholder="Lý do xin nghỉ" value="<?php echo htmlentities($date['reason']);?>">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($date['is_off']): ?>
                                        <a href="javascript:void(0);" class="btn btn-default btn-xs black jsDeleteBtn">
                                            <i class="fa fa-trash-o"></i>
                                            <span>Hủy</span>
                                        </a>
                                    <?php else: ?>
                                        <a href="javascript:void(0);" class="btn btn-default btn-xs black jsRegisterBtn">
                                            <i class="fa fa-trash-o"></i>
                                            <span>Xin phép</span>
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
        var oTable = null;
        var InitTable = function() {
            oTable = $('#jsDayOffTable').dataTable({
                "lengthMenu": [
                    [16, -1],
                    [16, "Tất cả"] // change per page values here
                ],
                // set the initial value
                "pageLength": 16,

                "language": {
                    "lengthMenu": " _MENU_ ngày"
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
            var tableWrapper = $("#jsDayOffTable_wrapper");
            tableWrapper.find(".dataTables_length select").select2({
                showSearchInput: false //hide search box with special css class
            });
        }();

        $('#jsSelectMonth').on('change', function() {
            var selectedMonth = $(this).val();
            QTHC.getWS('/getDatesOff/' + selectedMonth, {
                'onDone' : function(res) {
                    if (res.status == 'OK') {
                        oTable.fnClearTable();
                        $.each(res.dates, function(index, d) {
                            var isOff = d['is_off'];
                            var btn = '<a href="javascript:void(0);" class="btn btn-default btn-xs black jsRegisterBtn"><i class="fa fa-trash-o"></i> <span>Xin phép</span></a>';
                            var reason = '<input type="text" class="form-control jsReason" placeholder="Lý do xin nghỉ" value="' + QTHC.h(d['reason']) + '">';
                            if (isOff) {
                                btn = '<a href="javascript:void(0);" class="btn btn-default btn-xs black jsDeleteBtn"><i class="fa fa-trash-o"></i> <span>Hủy</span></a>';
                                reason = '<span class="jsReasonLabel">' + QTHC.h(d['reason']) + '</span>';
                            }
                            oTable.fnAddData([
                                '<span class="jsDateOff">' + d['display'] + '</span>',
                                reason,
                                btn
                            ]);
                        });
                        QTHC.alertSuccess('Đã lấy thông tin nghỉ phép tháng ' + selectedMonth);
                    } else {
                        QTHC.alertError(res.msg);
                    }
                },

                'onFail' : function() {
                    QTHC.alertError('Hãy thử lại sau');
                }
            });
        });

        $('#jsDayOffTable').on('click', '.jsRegisterBtn', function() {
            var $this = $(this);
            QTHC.confirm('Bạn có chắc muốn đăng ký nghỉ phép?', function() {
                var fullDate = $this.parents('tr').find('.jsDateOff').text();
                var fullDateArr = fullDate.split('/');
                var reason = $this.parents('tr').find('.jsReason').val();

                QTHC.postWS('/addDateOff', {
                    'data' : {
                        '_token' : QTHC.token,
                        'date' : fullDateArr[0],
                        'month' : fullDateArr[1],
                        'reason' : reason
                    },

                    'onDone' : function(res) {
                        if (res.status == 'OK') {
                            $this.parents('tr').find('.jsReason').replaceWith('<span class="jsReasonLabel">'+QTHC.h(reason)+'</span>');
                            $this.removeClass('jsRegisterBtn').addClass('jsDeleteBtn');
                            $this.find('span').text('Hủy');
                            window.setTimeout(function() {QTHC.popupSuccess('Đăng ký nghỉ phép thành công');}, 200);
                        } else {
                            QTHC.alertError(res.msg);
                        }
                    },

                    'onFail' : function() {
                        QTHC.alertError('Hãy thử lại sau');
                    }
                });
            });
        });

        $('#jsDayOffTable').on('click', '.jsDeleteBtn', function() {
            var $this = $(this);

            QTHC.confirm('Bạn có chắc muốn hủy ngày nghỉ phép?', function() {
                var fullDate = $this.parents('tr').find('.jsDateOff').text();
                var fullDateArr = fullDate.split('/');
                var reason = $this.parents('tr').find('.jsReasonLabel').text();

                QTHC.postWS('/removeDateOff', {
                    'data' : {
                        '_token' : QTHC.token,
                        'date' : fullDateArr[0],
                        'month' : fullDateArr[1]
                    },

                    'onDone' : function(res) {
                        if (res.status == 'OK') {
                            $this.parents('tr').find('.jsReasonLabel').replaceWith('<input type="text" class="form-control jsReason" placeholder="Lý do xin nghỉ" value="' + QTHC.h(reason) + '">');
                            $this.removeClass('jsDeleteBtn').addClass('jsRegisterBtn');
                            $this.find('span').text('Xin phép');
                            window.setTimeout(function() {QTHC.popupSuccess('Hủy ngày nghỉ phép thành công');}, 200);
                        } else {
                            QTHC.alertError(res.msg);
                        }
                    },

                    'onFail' : function() {
                        QTHC.alertError('Hãy thử lại sau');
                    }
                });
            });
        });
    });
</script>
@stop