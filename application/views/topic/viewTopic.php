<?php $this->load->view('frame/header');?>


<div class="container" style="width:850px;margin-top:80px;box-shadow:2px 2px 5px #888888;">
	<div class="row" style="height:120px;">
		<div class="col-xs-3 col-md-3" style="height:100%;padding:0 1px 0 0;">
			<div style="height:100%;padding-top:10px;background:#FEFEFE;">
				<div id="avatarDIV" style="float:left;height:64px;width:64px;margin-top:10px;margin-left:20px;background:#FFFFFF;padding:2px 2px 2px 2px;border-radius:3px;">
					<img style="height:60px;width:60px;" src="../../img/av_default.png" />
				</div>
					<h4 style="margin-top:20px;margin-left:100px;" id="authNick"></h4>

			</div>
		</div>
		<div class="col-xs-9 col-md-9" style="background:#FAFAFA;height:100%;">
			<h2 id="ttitle" style="margin-top:40px;">
			</h2>
		</div>
	</div>
	<div class="row" id="tbody" style="background:#FFFFFF;margin-top:5px;padding:20px 20px 10px 20px;">
	</div>
</div>

<div class="container" style="width:850px;margin-top:20px;margin-bottom:10px;box-shadow:2px 2px 5px #888888;background:#FEFEFE;padding-bottom:20px;">
	<h3 id="ans_cnt" style="margin-top:35px;margin-left:15px;">0个回答
	</h3>
	<div id="ansDIV">
	</div>
</div>

<div class="container" style="width:850px;margin-top:20px;margin-bottom:80px;box-shadow:2px 2px 5px #888888;background:#FEFEFE;padding-bottom:20px;">
	<h3>我来回答
	</h3>
	<textarea id="ans_editor">
	</textarea>
	<button class="btn btn-primary" style="float:right;" id="ans_sub">提交
	</button>
</div>

<script type="text/javascript">
	var tid='<?php echo $tid;?>';
	var cols={};
	var majs={};
	$(function() {
			$('#ans_editor').trumbowyg({
					fullscreenable: false,
    				closable: true,
				    btns: ['bold', 'italic', '|', 'insertImage']
				});
		$.ajax({
			url:BASE_URL+'topic/viewTopic?tid='+tid,
			type:'get'
		}).done(function(data){
			data=JSON.parse(data);
			//console.log(data);
			if (!data['status']) {
				data=data['info'];
				$('#ttitle').html(data['title']);
				$('#tbody').html(data['html']);
				$('#tbody').append('<br><span style="color:#cfcfcf;">创建于 '+data['createTime']+'，最后修改 '+data['actTime']+'</span>');
				var au=data['author_info'];
				$('#authNick').html(au['nick']);
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});

		$.ajax({
			url:BASE_URL+'topic/viewTopicAns?tid='+tid,
			type:'get'
		}).done(function(data){
			data=JSON.parse(data);
			//console.log(data);
			if (!data['status']) {
				var cnt=data['cnt'];
				$('#ans_cnt').html(cnt+" 个回答");
				data=data['info'];
				for (var i=0;i<cnt;++i) {
					var item=data[i];
					//console.log(item);
					$('#ansDIV').append(genANS(item));
				}
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});

		$('#ans_sub').click(function(){
			var ans_data=$('#ans_editor').val();
			$.ajax({
				url:BASE_URL+'topic/ansTopic',
				type:'post',
				data:JSON.stringify({'tid':tid,'html':ans_data})
			}).done(function(data){
				data=JSON.parse(data);
				//console.log(data);
				if (!data['status']) {
					window.location=window.location;
				} else {
					if (data['status']!=-1) alert(data['message']);
				}
			}).fail(function(data){
				alert("出现错误，请稍后再试");
			});
		});
	});

</script>


<?php $this->load->view('frame/footer');?>