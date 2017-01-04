<?php $this->load->view('frame/header');?>


<div class="container" style="width:850px;margin-top:80px;box-shadow:2px 2px 5px #888888;">
	<div class="row" style="height:120px;">
		<div class="col-xs-3 col-md-3" style="height:100%;padding:0 1px 0 0;">
			<div style="height:100%;padding-top:10px;background:#FEFEFE;">
				<div id="avatarDIV" style="float:left;height:64px;width:64px;margin-top:10px;margin-left:20px;background:#FFFFFF;padding:2px 2px 2px 2px;border-radius:3px;">
					<img style="height:60px;width:60px;" src="" id="auav"/>
				</div>
					<h4 style="margin-top:20px;margin-left:100px;" id="authNick"></h4>

			</div>
		</div>
		<div class="col-xs-9 col-md-9" style="background:#FAFAFA;height:100%;">
			<h2 id="ttitle" style="margin-top:25px;">
			</h2>
			<div id="favD" style="float:right;margin-top:-55px;">
			</div>			
			<div id="TTags">
			</div>
		</div>
	</div>
	<div class="row" id="tbody" style="background:#FFFFFF;margin-top:5px;padding:20px 20px 10px 20px;">
	</div>	

		<div class="modal fade" id="ed_q" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
			<div class="modal-dialog"  style="width:800px;">
			        <div class="modal-content">
			            <div class="modal-header">
			                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			                <h4 class="modal-title" id="myModalLabel2">修改问题</h4>
			            </div>
			            <div class="modal-body">
			            	<h4>问题的标题</h4>
			            	<input class="form-control" type="text" id="eqtitle" placeholder="4~64个字符"/>
			            	<h4>问题的描述</h4>
			            	<textarea id="eeditor">
			            	</textarea>
			            </div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			                <button type="button" class="btn btn-primary" id="resubmit_que">提交</button>
			            </div>
			        </div><!-- /.modal-content -->
			    </div><!-- /.modal -->
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
	var isFav='no';
	var cols={};
	var majs={};
	var auid=<?php echo $uid;?>;
	var canEdit=false;
	var isAdmin=<?php echo $info['isAdmin']; ?>;
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
				var ttags=data['tags'];
				for (var i=0;i<ttags.length;++i) {
					var item=ttags[i];
					$('#TTags').append(genLabel('focusTag',{'id':item['tgid'],'title':item['title']}));
				}
				canEdit=auid==data['author']||isAdmin;
				isFav=data['favor'];
				$('#favD').attr('onclick','alterFav($("#favD"),'+tid+');');
		if (isFav=='yes') {
			$('#favD').attr('class','btn btn-success btn-sm');
			$('#favD').html('已收藏');
		} else {
			$('#favD').attr('class','btn btn-warning btn-sm');
			$('#favD').html('收藏');			
		}
				$('#ttitle').html(data['title']);
				$('#tbody').html(data['html']);
				$('#tbody').append('<br><span style="color:#cfcfcf;">创建于 '+data['createTime']+'，最后修改 '+data['actTime']+' '+(canEdit?'<a data-toggle="modal" data-target="#ed_q">修改</a>':'')+'</span>');
				var au=data['author_info'];
				$('#authNick').html('<a href="'+BASE_URL+'user/look/'+data['author']+'">'+au['nick']+'</a>');
				$('#auav').attr('src',au['ava']);
				if (canEdit) {
					$('#eeditor').trumbowyg({
							fullscreenable: false,
		    				closable: true,
						    btns: ['bold', 'italic', '|', 'insertImage']
						});
					$('#eeditor').trumbowyg('html',data['html']);
					$('#eqtitle').val(data['title']);

				}
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
			var ans_data=$('#ans_editor').trumbowyg('html');
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
		$('#resubmit_que').click(function() {
			var req_data=$('#eeditor').trumbowyg('html');
			var req_tit=$('#eqtitle').val();
			console.log(req_data);
			console.log(req_tit);
			$.ajax({
				url:BASE_URL+'topic/editTopic',
				type:'post',
				data:JSON.stringify({'tid':tid,'html':req_data,'title':req_tit})
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