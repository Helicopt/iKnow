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
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/topic.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/trumbowyg.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/css/global.css"/>
		<script type="text/javascript">BASE_URL='<?php echo base_url();?>';</script>
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
						<li class="<?php echo $cata=="home"?"active":""; ?>"><a href="<?php echo base_url();?>"><i class="glyphicon glyphicon-home"></i> 首页</a></li>
						<li class="<?php echo $cata=="explore"?"active":""; ?>"><a href="<?php echo base_url();?>explore"><i class="glyphicon glyphicon-globe"></i> 探索</a></li>
						<li class="<?php echo $cata=="topic"?"active":""; ?>"><a href="#"><i class="glyphicon glyphicon-book"></i> 话题</a></li>
						<?php if ($uid) echo '<li class="'.($cata=="msg"?"active":"").'"><a href="'.base_url().'user/msgs"><i class="glyphicon glyphicon-envelope"></i> 消息</a></li>'; ?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<?php if ($uid) echo '<li style="margin-top:8px;margin-left:10px;"><button class="btn btn-success" data-toggle="modal" data-target="#ques"><i class="glyphicon glyphicon-edit"></i> 提问</button></li>'; ?>
						<li><a href="<?php echo $uid?base_url().'user':base_url(); ?>"><i class="glyphicon glyphicon-user"></i> <?php echo $uid?$info['nick']:"登录"; ?></a></li>
						<?php if (!$uid) echo '<li><a href='.base_url().'"?action=register"> 注册</a></li>';
								else  echo '<li><a href="'.base_url().'register/logout"> 退出</a></li>'; ?> 
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
			            	<h4>问题的标题</h4>
			            	<input class="form-control" type="text" id="qtitle" placeholder="4~64个字符"/>
			            	<h4>问题的领域</h4>
				<div class="input-group" >
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default 
                        dropdown-toggle" data-toggle="dropdown">领域
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu tagul" id="tag_s">
                            <li>
                            	<a style="cursor:pointer;" onclick="alterTags($(this));">
                            	<i class="glyphicon glyphicon-ok"></i>
                            	数据库原理
                            	</a>
                            </li>
                        </ul>
                    </div>
                    <span class="form-control" id="tags_ti">
                    	添加领域...
                    </span>
                </div>
			            	<h4>问题的描述</h4>
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
			var tags={};
			var in_tags={};
			$(function() {

			$('#submit_que').click(function() {
				var data=$('#editor').val();
				var title=$('#qtitle').val();
				$.ajax({
					type: 'post',
					data: JSON.stringify({'title':title,'html':data}),
					url: BASE_URL+'topic/newTopic'
					}).done(function(data){
						data=JSON.parse(data);
						if (!data['status']) {
							window.location="./topic/t/"+data['tid'];
						} else {
							if (data['status']!=-1) alert(data['message']);
						}
					}).fail(function(data){
						alert("出现错误，请稍后再试");
					});
			});	
			$('#editor').trumbowyg({
					fullscreenable: false,
    				closable: true,
				    btns: ['bold', 'italic', '|', 'insertImage']
				});
				$.ajax({
					type: 'post',
					url: BASE_URL+'Topic/getTags',
				}).done(function(data){
					//console.log(data);
					data=JSON.parse(data);
					tags=data;
					$(".tagul").html('');
						for (var i=0;i<tags.length;++i) {
							$(".tagul").append("<li id=\"tag_"+i+"\"><a style=\"cursor:pointer;\"onclick=\"alterTags($(this));\">\
								<i style=\"display:none;\" class=\"glyphicon glyphicon-ok\" id=\"itag"+i+"\"></i> "+tags[i]['title']+"</a></li>");
							in_tags[i]=0;
						}


				}).fail(function(){
					alert("出现错误，请稍后再试");
				});
			});
		</script>
		<div class="row">
		</div>