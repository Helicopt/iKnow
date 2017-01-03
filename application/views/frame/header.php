<!DOCTYPE html>
<html lang="en">
	<head>
		<title>iKnow - 知识值得分享</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/css/bootstrap-theme.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/css/bootstrap.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/css/trumbowyg.min.css"/>
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/jquery-3.1.1.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/login.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/user.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/trumbowyg.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/css/global.css"/>
	</head>
	<body style="background-color:#EFEFEF;">
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle = "collapse"  data-target = "#target-menu">  
						<span class="sr-only">wt</span>  
						<span class="icon-bar"></span>  
						<span class="icon-bar"></span>  
						<span class="icon-bar"></span>  
					</button> 
					<a class="navbar-brand" style="font-size:18px;" href="#">iKnow</a>
				</div>

				<div class="collapse navbar-collapse" id = "target-menu">
					<ul class="nav navbar-nav">
						<li class="<?php echo $cata=="home"?"active":""; ?>"><a href="./"><i class="glyphicon glyphicon-home"></i> 首页</a></li>
						<li class="<?php echo $cata=="explore"?"active":""; ?>"><a href="./explore"><i class="glyphicon glyphicon-globe"></i> 探索</a></li>
						<li class="<?php echo $cata=="topic"?"active":""; ?>"><a href="#"><i class="glyphicon glyphicon-book"></i> 话题</a></li>
						<?php if ($uid) echo '<li class="'.($cata=="msg"?"active":"").'"><a href="./user/msgs"><i class="glyphicon glyphicon-envelope"></i> 消息</a></li>'; ?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<?php if ($uid) echo '<li style="margin-top:6px;"><button class="btn btn-success" data-toggle="modal" data-target="#ques" style="float:right;"><i class="glyphicon glyphicon-edit"></i> 提问</button></li>'; ?>
						<li><a href="<?php echo $uid?'./user':"./"; ?>"><i class="glyphicon glyphicon-user"></i> <?php echo $uid?$info['nick']:"登录"; ?></a></li>
						<?php if (!$uid) echo '<li><a href="./?action=register"> 注册</a></li>';
								else  echo '<li><a href="./register/logout"> 退出</a></li>'; ?> 
					</ul>
				</div>
			</div>
		</nav>
		<div class="modal fade" id="ques" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog"  style="width:800px;">
			        <div class="modal-content">
			            <div class="modal-header">
			                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			                <h4 class="modal-title" id="myModalLabel">发起问题</h4>
			            </div>
			            <div class="modal-body">
			            	<h3>问题的标题</h3>
			            	<input class="form-control" type="text" id="qtitle"/>
			            	<h3>问题的描述</h3>
			            	<textarea id="editor">
			            	</textarea>
			            </div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			                <button type="button" class="btn btn-primary" id="submit_que">提交</button>
			            </div>
			        </div><!-- /.modal-content -->
			    </div><!-- /.modal -->
		</div>
		<script type="text/javascript">
			$(function() {
				$('#editor').trumbowyg({
					fullscreenable: false,
    				closable: true,
				    btns: ['bold', 'italic', '|', 'insertImage']
				});
			});
		</script>
		<div class="row">
		</div>