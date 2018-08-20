@extends('layouts.main')

@section('content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Phân công báo cáo
</h3>
<!-- END PAGE HEADER-->

<!-- BEGIN DASHBOARD STATS -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <table class="table table-striped table-hover" id="jsSynthesizeTable">
                    <thead>
                        <tr>
                            <th>
                                STT
                            </th>
                            <th>
                                Cán bộ
                            </th>
                            <th>
                                Email
                            </th>
                            <th>
                                Ngày báo cáo
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $index => $e): ?>
                            <tr>
                                <td>
                                    <?php echo ($index + 1); ?>
                                </td>
                                <td>
                                    <?php echo htmlentities($e['full_name']); ?>
                                </td>
                                <td>
                                    <a href="mailto:<?php echo htmlentities($e['email']); ?>"><?php echo htmlentities($e['email']); ?></a>
                                </td>
                                <td>
                                    <select class="select2" multiple data-placeholder="Chọn ngày...">
                                      <option value="2">Thứ 2</option>
                                      <option value="3">Thứ 3</option>
                                      <option value="4">Thứ 4</option>
                                      <option value="5">Thứ 5</option>
                                      <option value="6">Thứ 6</option>
                                      <option value="7">Thứ 7</option>
                                      <option value="8">Chủ nhật</option>
                                    </select>
                                </td>
                                <td>
                                    <a data-project-id="1" href="javascript:void(0);" class="delete btn btn-default btn-xs black dialog-success jsJoinProjectBtn">
                                        <i class="fa fa-trash-o"></i>
                                        <span>Cập nhật</span>
                                    </a>
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

        $(".select2").select2({
            width: '100%'
        });
    });
</script>
@stop