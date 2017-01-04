<?php $this->load->view('frame/header');?>


<div class="container" style="width:850px;margin-top:80px;">
	<div class="row">
		<div style="padding-bottom:50px;background:#FEFEFE;border-radius:4px;box-shadow:1px 1px 1px #888888;">
			<div id="featureDIV" style="background:#AAAABB;height:180px;"> </div> 
			<div id="avatarDIV"  style="float:left;height:124px;width:124px;margin-top:-40px;margin-left:60px;background:#FFFFFF;padding:2px 2px 2px 2px;border-radius:3px;">
				<img style="height:120px;width:120px;" src="<?php echo $other['ava']; ?>"  id="avaIMG"/>
			</div>
			<div id="followD" style="float:left;" onclick="alterF(<?php echo $oid;?>);">
			</div>
				<h3 id="nick" style="margin-top:20px;margin-left:250px;"><?php echo $other['nick'];?></h3>
				<div style="margin-top:10px;margin-left:250px;">
								<div class="input-group input-group-sm" style="width:420px;">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i> 简介</span>
								<span  class="form-control" style="" id="sig" ><?php echo $other['sig'];?></span>
								</div>
				</div>
				<div id="eduDIV">
				</div>

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
	var following="<?php echo $other['following']; ?>";
	$(function() {
		$('#followD').css('display','block');
		$('#followD').css('width','60px');
		$('#followD').css('margin-left','-92px');
		$('#followD').css('margin-top','90px');
		if (following=='yes') {
			$('#followD').attr('class','btn btn-success btn-xs');
			$('#followD').html('已关注');
		} else {
			$('#followD').attr('class','btn btn-primary btn-xs');
			$('#followD').html('关注');			
		}
		$.ajax({
			type: 'post',
			url: BASE_URL+'user/ajax_getOV',
			data: JSON.stringify({'ovid':'<?php echo $oid;?>'})
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
			data: JSON.stringify({'uid':'<?php echo $oid;?>'})
		}).done(function(data){
			data=JSON.parse(data);
			var cnt=data.length;
			if (cnt>8) {
				$('#reply').css('height',(45*cnt).toString()+'px');
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
			data: JSON.stringify({'uid':'<?php echo $oid;?>'})
		}).done(function(data){
			data=JSON.parse(data);
			var cnt=data.length;
			if (cnt>20) {
				$('#concern').css('height',(16*cnt).toString()+'px');
			}
			for (var i=0;i<cnt;++i) {
				$('#tag_show').append(genLabel('focusTag',data[i]));
			}
		}).fail(function(){
			alert("出现错误，请稍后再试");
		});

		$.ajax({
			type: 'post',
			url: BASE_URL+'user/getFollowee',
			data: JSON.stringify({'uid':'<?php echo $oid;?>'})
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
			data: JSON.stringify({'uid':'<?php echo $oid;?>'})
		}).done(function(data){
			data=JSON.parse(data);
			var cnt=data.length;
			if (cnt>8) {
				$('#ask').css('height',(45*cnt).toString()+'px');
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
			data: JSON.stringify({'uid':'<?php echo $oid;?>'})
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
	});

</script>


<?php $this->load->view('frame/footer');?>