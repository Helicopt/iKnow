
function getUserPage(id) {
	return BASE_URL+'user/look/'+id;
}

function wrapAuthor(aid,au) {
	return '<div class="row" style="background:#fdfdfd;padding:0 15px 0 15px;">\
		<a href="'+getUserPage(au['id'])+'"><b>'+au['nick']+'</b></a> , \
		<span style="color:#888888;">'+au['sig']+'</span>\
	</div>';

}

function wrapContent(aid,htm,cT,aT,ag,da) {
	return '<div class="row" style="background:#ffffff;margin-top:10px;margin-bottom:10px;\
		padding:0 15px 0 15px;"'+htm+'\
	</div>\
	<div class="row"><span style="color:#cfcfcf;float:right;">创建于 '+cT+',&nbsp; \
	<span id="zan'+aid+'" style="cursor:pointer;color:#999999;" onclick="zan('+aid+','+da+','+ag+')">赞同('+ag+')</span>&nbsp; \
	<span id="cai'+aid+'" style="cursor:pointer;color:#999999;" onclick="cai('+aid+','+ag+','+da+')">反对('+da+')</span></span></div>\
	<div class="row">\
	<div class="panel panel-default">\
		<div class="panel-heading">\
			<span class="panel-title">\
				<a data-toggle="collapse" data-parent="#accordion" \
				   href="#collapse_'+aid+'" onclick="pullComments('+aid+');">\
					评论\
				</a>\
			</span>\
		</div>\
		<div id="collapse_'+aid+'" class="panel-collapse collapse">\
			<div class="panel-body" >\
				<div class="input-group input-group-sm">\
				<input type="text" class="form-control" placeholder="想说什么？" id="comin'+aid+'"/>\
				<span class="input-group-addon" style="cursor:pointer;" onclick="addComment('+aid+');">评论</span>\
				</div>\
				<div id="collapD_'+aid+'"> </div>\
			</div>\
		</div>\
	</div>\
	</div>\
	<div class="row" style="background:#eeeeee;height:1px;"></div>';
}


function genANS(item) {
	var aid=item['id'];
	//console.log(item);
	return '<div id="ans_'+aid+'" style="margin-top:15px;padding:15px 15px 10px 15px;">'
	+wrapAuthor(aid,item['author_info'])+wrapContent(aid,item['html'],item['createTime'],item['actTime'],item['ag'],item['da'])+'</div>';
}

function genComment(item) {
	return '<div class="row" style="margin-left:10px;margin-top:10px;"><a href="'+getUserPage(item['author'])+'"><b>'+item['author_info']['nick']+'</b></a> : '+item['txt']+'</div>';
}

function pullComments(aid) {
		$.ajax({
			url:BASE_URL+'topic/viewComments?aid='+aid,
			type:'get'
		}).done(function(data){
			data=JSON.parse(data);
			//console.log(data);
			if (!data['status']) {
				var cnt=data['cnt'];
				data=data['info'];
				$('#collapD_'+aid).html('');
				for (var i=0;i<cnt;++i) {
					var item=data[i];
					//console.log(item);
					$('#collapD_'+aid).append(genComment(item));
				}
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});

}

function addComment(aid) {
	var comm=$('#comin'+aid).val();
	if (comm.length==0) return;
	$.ajax({
		type: 'post',
		url: BASE_URL+'topic/addComment',
		data: JSON.stringify({'aid':aid,'val':comm})
		}).done(function(data){
			data=JSON.parse(data);
			if (!data['status']) {
				pullComments(aid);
				$('#comin'+aid).val('');
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});

}

function genStrip(verb,item) {
//	return '<div class="row" >在 '+item['time']+' '+verb+'了问题 <a href="'+BASE_URL+'topic/t/'+item['id']+'">'+item['title']+'</a></div>';
	return '<div class="row" style="margin:15px 15px 15px 15px;font-size:17px;"><div class="row">'+verb+'了问题 <a href="'+BASE_URL+'topic/t/'+item['id']+'">'+item['title']+'</a></div><div class="row" style="color:#888888;font-size:13px;">'+item['time']+'</div></div>';
}

function genStrip2(au,verb,item) {
	return '<div class="row" style="margin:15px 15px 15px 15px;font-size:17px;">\
	<div class="col-md-1" style="margin-right:15px;">'+genPeo2(au)+'</div>\
	<div class="col-md-10"><div class="row">'+verb+'了问题 <a href="'+BASE_URL+'topic/t/'+item['id']+'">'+item['title']+'</a></div><div class="row" style="color:#888888;font-size:13px;">'+item['actTime']+'</div></div>\
	</div>';
}

function genLabel(func,item) {
	return '<div class="label label-default" id="ssstag_'+item['id']+'" style="cursor:pointer;display:block;float:left;margin:10px 5px 10px 5px;" onclick="'+func+'('+item['id']+');">'+item['title']+'</div>';
}

function genLabelBig(func,item) {
	return '<div class="label label-default" id="ssstag_'+item['id']+'" style="font-size:18px;cursor:pointer;display:block;float:left;margin:10px 5px 10px 15px;" onclick="'+func+'('+item['id']+');">'+item['title']+'</div>';
}

function focusTag(tgid) {
	$.ajax({
		type: 'post',
		url: BASE_URL+'topic/focusTag/'+tgid,
		}).done(function(data){
			data=JSON.parse(data);
			if (!data['status']) {
				alert('已关注');
			} else {
				//if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});	
}


function loseTag(tgid) {
	$.ajax({
		type: 'post',
		url: BASE_URL+'topic/loseTag/'+tgid,
		}).done(function(data){
			data=JSON.parse(data);
			if (!data['status']) {
				$('#ssstag_'+tgid).hide();
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});	
}

function alterFav(ctrl, tid) {
	if (isFav=='yes') {
		$.ajax({
		type: 'post',
		url: BASE_URL+'topic/unfavor',
		data: JSON.stringify({'tid':tid})
		}).done(function(data){
			data=JSON.parse(data);
			if (!data['status']) {
				ctrl.attr('class','btn btn-warning btn-sm');
				ctrl.html('收藏');
				isFav='no';			
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});

	} else {
		$.ajax({
		type: 'post',
		url: BASE_URL+'topic/dofavor',
		data: JSON.stringify({'tid':tid})
		}).done(function(data){
			data=JSON.parse(data);
			if (!data['status']) {
				ctrl.attr('class','btn btn-success btn-sm');
				ctrl.html('已收藏');
				isFav='yes';
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});


	}

}

function zan(aid,cai,cnt) {
		$.ajax({
		type: 'post',
		url: BASE_URL+'topic/zan',
		data: JSON.stringify({'aid':aid})
		}).done(function(data){
			data=JSON.parse(data);
			if (!data['status']) {
				if (data['type']=='add') {
					cnt=cnt+1;
				}
				if (data['type']=='alter') {
					cnt=cnt+1;
					cai=cai-1;
				}
				$('#zan'+aid).html('赞同('+cnt+')');
				$('#cai'+aid).html('反对('+cai+')');
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});

}
function cai(aid,zan,cnt) {
		$.ajax({
		type: 'post',
		url: BASE_URL+'topic/cai',
		data: JSON.stringify({'aid':aid})
		}).done(function(data){
			data=JSON.parse(data);
			if (!data['status']) {
				if (data['type']=='add') {
					cnt=cnt+1;
				}
				if (data['type']=='alter') {
					cnt=cnt+1;
					zan=zan-1;
				}
				$('#zan'+aid).html('赞同('+zan+')');
				$('#cai'+aid).html('反对('+cnt+')');
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});

}
