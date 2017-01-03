<!DOCTYPE html>
<html lang="en">
	<head>
		<title>iKnow - 知识值得分享</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/css/bootstrap-theme.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/css/bootstrap.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/css/global.css"/>
	</head>
	<body style="background-color:#EFEFEF;">
		<div class="container" style="width:450px;margin-top:50px;">
				<div style="margin:auto auto;width:280px;">
					<span class="label label-default" style="font-size:64px;line-height:160px;" >iKnow</span>
				</div>
				<div id="forms">
				<ul class="nav nav-tabs" style="margin-top:30px;">
					<li class="active" style="width:210px;text-align:center;"><a href="#login_t" data-toggle="tab" id="login_tb">登录</a></li>
					<li  style="width:210px;text-align:center;"><a href="#regi_t" data-toggle="tab" id="reg_tb">注册</a></li>
				</ul>
				<div id="tab_content" class="tab-content">
					<div class="tab-pane fade in active" id="login_t">
						<form class="bs-example bs-example-form" role="form">
							<br>
							<div class="input-group" id="lm_gr">
								<span class="input-group-addon">邮箱</span>
								<input type="text" class="form-control" placeholder="example: hello@gmail.com" id="email_login" maxlength='128'>
								</div>
							<br>
							<div class="input-group" id="lp_gr">
								<span class="input-group-addon">密码</span>
								<input type="password" class="form-control" id="pwd_login" maxlength='32'>
							</div>
							<a href="#">忘记密码？</a>
							<br>
							<br>
							<div style="width:300px; margin:auto auto;">
								<button type="button" class="btn btn-primary" id="btn_login" style="width:300px; ">登录</button>
							</div>
						</form>
					</div>
					<div class="tab-pane fade" id="regi_t">
						<form class="bs-example bs-example-form" role="form">
							<br>
							<div class="input-group" id="rn_gr">
								<span class="input-group-addon">名字</span>
								<input type="text" class="form-control" placeholder="1~32个字符组成" id="nick_reg" maxlength='32'>
								</div>
							<br>
							<div class="input-group" id="rm_gr">
								<span class="input-group-addon">邮箱</span>
								<input type="text" class="form-control" placeholder="如 hello@gmail.com" id="email_reg" maxlength='128'>
								</div>
							<br>
							<div class="input-group" id="rp_gr">
								<span class="input-group-addon">密码</span>
								<input type="password" class="form-control" id="pwd_reg" placeholder="6~32个字符" maxlength='32'>
							</div>
							<br>
							<div class="input-group" id="rrp_gr">
								<span class="input-group-addon">重复</span>
								<input type="password" class="form-control" id="rpwd_reg" placeholder="重复输入密码" maxlength='32'>
							</div>
							<br>
							<br>
							<div style="width:300px; margin:auto auto;">
								<button type="button" class="btn btn-primary" id="btn_reg" style="width:300px; ">注册</button>
							</div>
						</form>
					</div>						
				</div>
				</div>
				<br>
				<a href="./explore">随意看看</a>
		</div>

		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/jquery-3.1.1.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/login.js"></script>
		<script type="text/javascript">
			if (<?php echo $reg?'true':'false'; ?>) {
				$('#reg_tb').click();
			}
			$(function() {
				$('#btn_reg').click(function(){
					if ((rdata=reg_chk())!=false) {
						$.ajax({
							type: 'post',
							url: './register/register',
							data: JSON.stringify(rdata)
							}).done(function(data){
								data=JSON.parse(data);
								if (!data['status']) {
									$('#forms').html('<div class="alert alert-success">'+data['message']+'，<a href="./">点击登录</a></div>');
								} else {
									alert(data['message']);
								}
							}).fail(function(){
								alert("出现错误，请稍后再试");
							});
					}
				});
				$('#btn_login').click(function(){
					if ((rdata=login_chk())!=false) {
						$.ajax({
							type: 'post',
							url: './register/login',
							data: JSON.stringify(rdata)
							}).done(function(data){
								data=JSON.parse(data);
								if (!data['status']) {
									window.location="./";
								} else {
									alert(data['message']);
								}
							}).fail(function(){
								alert("出现错误，请稍后再试");
							});
					}
				});
				$('#reg_tb').click(function() {
					$('#email_reg').val($('#email_login').val());
					$('#pwd_reg').val($('#pwd_login').val());
				});
			});
		</script>
	</body>
</html>