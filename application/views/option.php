

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>option records</title>

<link
	href="/pay_init/admin/statics/tablecloth/tablecloth.css"
	rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript"
	src="/pay_init/admin/statics/tablecloth/tablecloth.js"></script>
<!-- end -->

<style>
body {
	font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica,
		sans-serif;
	margin: 0;
	padding: 0;
	background: #f1f1f1;
	font: 70% Arial, Helvetica, sans-serif;
	color: #555;
	line-height: 150%;
	text-align: left;
}

a {
	font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica,
		sans-serif;
	text-decoration: none;
	color: #057fac;
}

a:hover {
	font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica,
		sans-serif;
	text-decoration: none;
	color: #999;
}

h1 {
	font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica,
		sans-serif;
	font-size: 140%;
	margin: 0 20px;
	line-height: 80px;
}

h2 {
	font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica,
		sans-serif;
	font-size: 120%;
}

#container {
	font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica,
		sans-serif;
	margin: 0 auto;
	width: 900px;
	background: #f1f1f1;
	padding-bottom: 20px;
	text-align: center;
	font-size: 13px;
}

#content {
	margin: 0 20px;
}

p.sig {
	font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica,
		sans-serif;
	margin: 0 auto;
	width: 680px;
	padding: 1em 0;
}

form {
	font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica,
		sans-serif;
	margin: 1em 0;
	padding: .2em 20px;
	background: #eee;
}
</style>

</head>

<body>
<?php $type=isset($type) ? $type : ''?>
<form
		action="/pay_init/admin/index.php/option_records/page"
		method="post" style="text-align: center">
		<select name="type">
			<option value='cdkey' <?echo @$type=='cdkey'?'selected':''; ?>>帐号</option>
		</select> <input type="text" name="search" /><br /> <input
			type="submit" value="提交" />

	</form>
	<div id="container">
		<div align="center"><?= $page_data ?></div>

		<div id="content">



			<!-- all you need with Tablecloth is a regular, well formed table. No need for id's, class names... -->
			<table cellspacing="0" cellpadding="0">
				<tr>
					<th>帐号</th>
					<th>金卡数</th>
					<th>操作类型</th>
					<th>角色名</th>
					<th>时间</th>
				</tr>
				<?php
				foreach ( $data as $info ) {
					?>
				<tr>
					<td><?=$info['cdkey'] ?></td>
					<td><?=$info['point'] ?></td>
					<td><?echo $info['event']==1 ?'存入金卡':'提取金卡' ?></td>
					<td><?echo $info['memo']!='' ?$info['memo']:'充值不做人物记录' ?></td>
					<td><?=$info['time'] ?></td>
				</tr>
				<?} ?>
			</table>
		</div>
		<div align="center"><?= $page_data ?></div>
		<p class="footer">
			Page rendered in <strong>{elapsed_time}</strong> seconds
		</p>
	</div>
</body>
</html>