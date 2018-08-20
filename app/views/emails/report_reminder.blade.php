<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
        <div>Dear!</div>
		<div>Anh/chị chưa gửi báo cáo công việc ngày {{$reportDate}}.</div>
        <div>Hãy gửi báo cáo công việc trước <span style="color:#f00;font-weight: bold;"><?php echo Config::get('app.synthesizeTime'); ?></span> hàng ngày. Sau thời gian này các báo cáo sẽ bị ghi nhận là muộn</div>
        <div><a href="{{$url}}">Vào hệ thống báo cáo công việc tại đây.</a></div>
        <div>Thanks & Best Regards</div>
	</body>
</html>
