<?php $this->load->view('frame/header');?>


<div class="container" style="width:850px;margin-top:80px;">
	<div class="row">
		<div style="padding-bottom:30px;background:#FEFEFE;border-radius:4px;box-shadow:1px 1px 1px #888888;">
			<div id="featureDIV" style="background:#AAAABB;height:180px;"> </div> 
			<div id="avatarDIV" style="float:left;height:124px;width:124px;margin-top:-40px;margin-left:60px;background:#FFFFFF;padding:2px 2px 2px 2px;border-radius:3px;">
				<img style="height:120px;width:120px;" src="./img/av_default.png" alt="更换头像" />
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
		<div style="height:330px;background:#FEFEFE;border-radius:4px;box-shadow:1px 1px 1px #888888; margin-top:20px;">
			<div class="col-xs-12 col-md-2" style="height:100%;background:#ffffff;box-shadow:1px 1px 1px #888888;">
				<h2 style="text-align:center;">关注</h2>		
			</div>
			<div class="col-xs-12 col-md-10" style="height:100%;background:#f6f6f6;">
				<div class="col-xs-6 col-md-6">
					<div class="row">
						<h3>领域</h3>
					</div>
				</div>
				<div class="col-xs-6 col-md-6">
					<div class="row">
						<h3>人</h3>
					</div>
				</div>
			</div>

		</div>
	</div>
	<div class="row">
		<div style="height:330px;background:#FEFEFE;border-radius:4px;box-shadow:1px 1px 1px #888888; margin-top:20px;">
			<div class="col-xs-12 col-md-2" style="height:100%;background:#ffffff;box-shadow:1px 1px 1px #888888;">
				<h2 style="text-align:center;">提问</h2>		
				<h2 style="text-align:center;" id="qnum">0个</h2>
			</div>
			<div class="col-xs-12 col-md-10" style="height:100%;background:#f6f6f6;">
			</div>
		</div>
	</div>
	<div class="row">
		<div style="height:330px;background:#FEFEFE;border-radius:4px;box-shadow:1px 1px 1px #888888; margin-top:20px;margin-bottom:40px;">
			<div class="col-xs-12 col-md-2" style="height:100%;background:#ffffff;box-shadow:1px 1px 1px #888888;">
				<h2 style="text-align:center;">回答</h2>		
				<h2 style="text-align:center;" id="qnum">0个</h2>
			</div>
			<div class="col-xs-12 col-md-10" style="height:100%;background:#f6f6f6;">
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var tp=0;
	var cols={};
	var majs={};
	$(function() {
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