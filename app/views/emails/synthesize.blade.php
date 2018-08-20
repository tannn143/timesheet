<html>
	<head>
        <meta charset="UTF-8" />
    </head>
	<body>
		<div>
			<div style ="font-size: 30px;color: #000000;">Tổng hợp công việc ngày <?php echo htmlentities($reportDate);?> <?php echo $departmentName != null ? (' phòng ' . $departmentName) : ''?></div>
            <br>
			<table style="width: 100%;border-collapse: collapse;">
                <thead style="height:40px;text-align:center;background: #cccccc;color:#000000;">
                    <tr style="height:40px;">
                        <td style="border: 1px solid #000;width:5%;"><b>Stt</b></td>
                        <td style="border: 1px solid #000;width:10%;"><b>Dự án</b></td>
                        <td style="border: 1px solid #000;width:10%;"><b>Cán bộ thực hiện</b></td>
                        <td style="border: 1px solid #000;width:30%;"><b>Công việc trong ngày</b></td>
                        <td style="border: 1px solid #000;width:30%;"><b>Công việc dự kiến</b></td>
                        <td style="border: 1px solid #000;width:15%;"><b>Ghi chú</b></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $currentProject = null;
                    $iFirstReportOfProject = 0;
                    foreach ($reports as $i => $report) {
                        if ($report['project']['name'] != $currentProject) {
                            $iFirstReportOfProject = $i;
                            $reports[$iFirstReportOfProject]['rowspan'] = 1;
                            $currentProject = $report['project']['name'];
                        } else {
                            $reports[$iFirstReportOfProject]['rowspan'] = $reports[$iFirstReportOfProject]['rowspan'] + 1;
                            $reports[$i]['rowspan'] = 1;
                        }
                    }
                    ?>
                    <?php
                    $currentProject = null;
                    $hasLateUser = false;
                    foreach ($reports as $i => $report): ?>
                        <?php
                        $report['today_task'] = str_replace(PHP_EOL, '<br>', htmlentities($report['today_task']));
                        $report['next_task'] = str_replace(PHP_EOL, '<br>', htmlentities($report['next_task']));
                        $report['notice'] = str_replace(PHP_EOL, '<br>', htmlentities($report['notice']));
                        ?>
                        <tr style="background: #FFF;height:40px;">
                            <td style="text-align:center;border: 1px solid #000;width:5%;"><?php echo $i + 1;?></td>
                            <?php if ($report['project']['name'] != $currentProject): ?>
                                <?php $currentProject = $report['project']['name'];?>
                                <td rowspan="<?php echo $report['rowspan'];?>" style="border: 1px solid #000;width:10%;"><?php echo htmlentities($report['project']['name']);?></td>
                            <?php endif; ?>

                            <?php if ($report['is_late'] == '1'): ?>
                                <?php $hasLateUser = true; ?>
                                <td style="border: 1px solid #000;background:#ff0;width:10%;"><?php echo htmlentities($report['user']['full_name']);?></td>
                                <td style="border: 1px solid #000;background:#ff0;width:30%;"><?php echo $report['today_task'];?></td>
                                <td style="border: 1px solid #000;background:#ff0;width:30%;"><?php echo $report['next_task'];?></td>
                                <td style="border: 1px solid #000;background:#ff0;width:15%;"><?php echo $report['notice'];?></td>
                            <?php else: ?>
                                <td style="border: 1px solid #000;width:10%;"><?php echo htmlentities($report['user']['full_name']);?></td>
                                <td style="border: 1px solid #000;width:30%;"><?php echo $report['today_task'];?></td>
                                <td style="border: 1px solid #000;width:30%;"><?php echo $report['next_task'];?></td>
                                <td style="border: 1px solid #000;width:15%;"><?php echo $report['notice'];?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
			</table>

            <?php if ($hasLateUser): ?>
                <br>
                <div><span style="background:#ff0;">Gửi báo cáo muộn</span></div>
            <?php endif; ?>

            <?php if (!empty($lateUsers)): ?>
                <br>
                <div>Chưa gửi báo cáo:
                    <span style="color:red;">
                        <?php foreach ($lateUsers as $i => $user): ?>
                            <?php if ($i == count($lateUsers) - 1): ?>
                                <?php echo htmlentities($user['full_name']); ?>
                            <?php else: ?>
                                <?php echo htmlentities($user['full_name']) . ', '; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </span>
                </div>
            <?php endif; ?>

            <?php if (!empty($allUsersOff)): ?>
                <br>
                <div>Danh sách xin nghỉ phép: </div>
                <table style="width: 100%;border-collapse: collapse;">
                    <thead style="height:40px;text-align:center;background: #cccccc;color:#000000;">
                        <tr style="height:40px;">
                            <td style="border: 1px solid #000;width:5%;"><b>Stt</b></td>
                            <td style="border: 1px solid #000;width:25%;"><b>Họ và tên</b></td>
                            <td style="border: 1px solid #000;width:25%;"><b>Ngày xin nghỉ phép</b></td>
                            <td style="border: 1px solid #000;width:30%;"><b>Lý do</b></td>
                            <td style="border: 1px solid #000;width:15%;"></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($allUsersOff as $i => $userOff): ?>
                        <tr style="background: #FFF;height:40px;">
                            <td style="text-align:center;border: 1px solid #000;width:5%;"><?php echo $i + 1;?></td>
                            <td style="border: 1px solid #000;width:25%;"><?php echo htmlentities($userOff['full_name']);?></td>
                            <td style="border: 1px solid #000;width:25%;"><?php echo htmlentities($userOff['date_off']);?></td>
                            <td style="border: 1px solid #000;width:30%;"><?php echo htmlentities($userOff['reason']);;?></td>
                            <td style="border: 1px solid #000;width:15%;"><a href="<?php echo $userOff['rejectUrl'];;?>">Không đồng ý</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="background: #FFF;height:40px;">
                        <td style="text-align:center;border: 1px solid #000;width:100%;" colspan="5"><a href="{{$acceptAllUrl}}">Đồng ý các yêu cầu chưa xử lý</a></td>
                    </tr>
                    </tbody>
                </table>
            <?php endif; ?>
            <br>
            <div><a href="{{$url}}">Vào hệ thống báo cáo công việc tại đây.</a></div>
		</div>
	</body>
</html>