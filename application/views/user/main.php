<?php $this->load->view('frame/header');?>


<div class="container" style="width:850px;margin-top:80px;">
	<div class="row">
		<div style="padding-bottom:30px;background:#FEFEFE;border-radius:4px;box-shadow:1px 1px 1px #888888;">
			<div id="featureDIV" style="background:#AAAABB;height:180px;"> </div> 
			<div id="avatarDIV" data-toggle="tooltip" data-placement="top" title="更换头像" style="cursor:pointer;float:left;height:124px;width:124px;margin-top:-40px;margin-left:60px;background:#FFFFFF;padding:2px 2px 2px 2px;border-radius:3px;">
				<img style="height:120px;width:120px;" src="<?php echo $info['ava']; ?>" alt="更换头像"  onclick="$('#avat').click();" id="avaIMG"/>
				<form enctype="multipart/form-data" method="post" action="" id="form0">
					<input type="file" style="display:none" id="avat" name="ava" onchange="changeAVA();"/>
				</form>
			</div>
				<h3 id="nick" style="margin-top:20px;margin-left:250px;"><?php echo $info['nick'];?></h3>
				<input type="text" class="form-control" style="display:none;margin-top:20px;margin-left:250px;width:300px;" id="e_nick"/>
				<div style="margin-top:10px;margin-left:250px;">
								<div class="input-group input-group-sm" style="width:420px;">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i> 简介</span>
								<span  class="form-control" style="" id="sig" ><?php echo $info['sig'];?></span>
								<input type="text" class="form-control" style="display:none;" id="e_sig"/>
								</div>
				</div>
				<div id="eduDIV">
				</div>
				<button style="margin-left:250px;margin-top:10px;margin-right:5px;" class=" btn btn-success btn-xs btn_rm" onclick="addEDU();"><i class="glyphicon glyphicon-plus"></i></button>

                <a style="cursor:pointer;float:right;margin-right:15px;" id="edit_btn"><i class="glyphicon glyphicon-pencil"></i> <span id="wd">编辑</span></a>

		</div>
	</div>
	<div class="row">
		<div style="height:330px;background:#FEFEFE;border-radius:4px;box-shadow:1px 1px 1px #888888; margin-top:20px;" id="concern">
			<div class="col-xs-12 col-md-2" style="height:100%;background:#ffffff;box-shadow:1px 1px 1px #888888;">
				<h2 style="text-align:center;">关注</h2>		
			</div>
			<div class="col-xs-12 col-md-10" style="height:100%;background:#f6f6f6;">
				<div class="col-xs-6 col-md-6">
					<div class="row">
						<h3 id="fi">领域</h3>
					</div>
					<div class="row" id="tag_show" style="padding:15px 0 0 0;">
					</div>
				</div>
				<div class="col-xs-6 col-md-6">
					<div class="row">
						<h3 id="pe">人</h3>
						<div class="row" id="flw_show" style="padding:15px 0 0 0;">
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<div class="row">
		<div style="height:330px;background:#FEFEFE;border-radius:4px;box-shadow:1px 1px 1px #888888; margin-top:20px;" id="ask">
			<div class="col-xs-12 col-md-2" style="height:100%;background:#ffffff;box-shadow:1px 1px 1px #888888;">
				<h2 style="text-align:center;">提问</h2>		
				<h2 style="text-align:center;" id="qnum">0个</h2>
			</div>
			<div class="col-xs-12 col-md-10" style="height:100%;background:#f6f6f6;padding-top:15px;padding-bottom:15px;" id="que_show">
			</div>
		</div>
	</div>
	<div class="row">
		<div style="height:330px;background:#FEFEFE;border-radius:4px;box-shadow:1px 1px 1px #888888; margin-top:20px;margin-bottom:40px;" id="reply">
			<div class="col-xs-12 col-md-2" style="height:100%;background:#ffffff;box-shadow:1px 1px 1px #888888;">
				<h2 style="text-align:center;">回答</h2>		
				<h2 style="text-align:center;" id="anum">0个</h2>
			</div>
			<div class="col-xs-12 col-md-10" style="height:100%;background:#f6f6f6;padding-top:15px;padding-bottom:15px;" id="ans_show">
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var tp=0;
	var cols={};
	var majs={};
	$(function() {
 		$("[data-toggle='tooltip']").tooltip();
		$.ajax({
			type: 'post',
			url: BASE_URL+'user/ajax_getOV',
			data: JSON.stringify({'ovid':'<?php echo $uid;?>'})
		}).done(function(data){
			data=JSON.parse(data);
			data=data[0];
			$('#qnum').html(data['qcnt']);
			$('#anum').html(data['acnt']);
			$('#fi').html(data['fcnt']+' 个领域');
			$('#pe').html(data['flcnt']+' 人');
		}).fail(function(){
			alert("出现错误，请稍后再试");
		});

		$.ajax({
			type: 'post',
			url: BASE_URL+'topic/getOneAns',
			data: JSON.stringify({'uid':'<?php echo $uid;?>'})
		}).done(function(data){
			data=JSON.parse(data);
			var cnt=data.length;
			if (cnt>6) {
				$('#reply').css('height',(72*cnt).toString()+'px');
			}
			for (var i=0;i<cnt;++i) {
				$('#ans_show').append(genStrip('回答',data[i]));
			}
		}).fail(function(){			
			alert("出现错误，请稍后再试");
		});

		$.ajax({
			type: 'post',
			url: BASE_URL+'topic/getOneTags',
			data: JSON.stringify({'uid':'<?php echo $uid;?>'})
		}).done(function(data){
			data=JSON.parse(data);
			var cnt=data.length;
			if (cnt>20) {
				$('#concern').css('height',(16*cnt).toString()+'px');
			}
			for (var i=0;i<cnt;++i) {
				$('#tag_show').append(genLabel('loseTag',data[i]));
			}
		}).fail(function(){
			alert("出现错误，请稍后再试");
		});

		$.ajax({
			type: 'post',
			url: BASE_URL+'user/getFollowee',
			data: JSON.stringify({'uid':'<?php echo $uid;?>'})
		}).done(function(data){
			data=JSON.parse(data);
			console.log(data);
			var cnt=data.length;
			if (cnt>9) {
				$('#concern').css('height',(16*cnt).toString()+'px');
			}
			for (var i=0;i<cnt;++i) {
				$('#flw_show').append(genPeo(data[i]));
			}
		}).fail(function(){
			alert("出现错误，请稍后再试");
		});

		$.ajax({
			type: 'post',
			url: BASE_URL+'topic/getOneQue',
			data: JSON.stringify({'uid':'<?php echo $uid;?>'})
		}).done(function(data){
			data=JSON.parse(data);
			var cnt=data.length;
			if (cnt>6) {
				$('#ask').css('height',(72*cnt).toString()+'px');
			}
			for (var i=0;i<cnt;++i) {
				$('#que_show').append(genStrip('提出',data[i]));
			}
		}).fail(function(){
			alert("出现错误，请稍后再试");
		});

		$.ajax({
			type: 'post',
			url: BASE_URL+'user/ajax_getEduById',
			data: JSON.stringify({'uid':'<?php echo $uid;?>'})
		}).done(function(data){
			data=JSON.parse(data);
			for (var i=0;i<data.length;++i){
				var it=data[i];
				var d=genEdu(it['id'],it['col'],it['maj']);
				$('#eduDIV').append(d);
			}
			$('#eduDIV .input-group-btn').hide();
			$('.btn_rm').hide();
		}).fail(function(){
			alert("出现错误，请稍后再试");
		});
		$('#edit_btn').click(function() {
			$('#wd').html(tp?"编辑":"保存");
			if (tp) {
				$('#nick').show();
				$('#e_nick').hide();
				$('#sig').show();
				$('#e_sig').hide();
				$('#eduDIV .input-group-btn').hide();
				$('.btn_rm').hide();
				$('.jlab').show();
				var rdata={'nick':$('#e_nick').val(),'sig':$('#e_sig').val()};
						$.ajax({
							type: 'post',
							url: BASE_URL+'user/ajax_setProfile',
							data: JSON.stringify(rdata)
							}).done(function(data){
								data=JSON.parse(data);
								if (!data['status']) {
									$('#nick').html($('#e_nick').val());
									$('#sig').html($('#e_sig').val());
								} else {
									alert(data['message']);
								}
							}).fail(function(){
								alert("出现错误，请稍后再试");
							});

			}else {
				$.ajax({
					type: 'post',
					url: BASE_URL+'user/ajax_getColleges',
				}).done(function(data){
					data=JSON.parse(data);
					cols=data;
					$(".colul").html('');
						for (var i=0;i<cols.length;++i) {
							$(".colul").append("<li><a style=\"cursor:pointer;\"onclick=\"changeCOL($(this));\">"+cols[i]['title']+"</a></li>");
						}

				}).fail(function(){
					alert("出现错误，请稍后再试");
				});
				$.ajax({
					type: 'post',
					url: BASE_URL+'user/ajax_getMajors',
				}).done(function(data){
					data=JSON.parse(data);
					majs=data;

					$(".majul").html('');
					for (var i=0;i<majs.length;++i) {
						$(".majul").append("<li><a style=\"cursor:pointer;\" onclick=\"changeMAJ($(this));\">"+majs[i]['title']+"</a></li>");
					}

				}).fail(function(){
					alert("出现错误，请稍后再试");
				});

				$('#eduDIV .input-group-btn').show();
				$('.jlab').hide();
				$('#nick').hide();
				$('#sig').hide();
				$('.btn_rm').show();
				$('#e_nick').show();
				$('#e_nick').val($('#nick').html());
				$('#e_sig').show();
				$('#e_sig').val($('#sig').html());
			}
			tp^=1;			
		});
	});

</script>


<?php $this->load->view('frame/footer');?>