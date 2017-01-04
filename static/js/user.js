function genEdu(eid,col,maj) {
	return '<div style="margin-top:10px;margin-left:250px;" id="edu_it'+eid+'">\
				<button style="float:left;margin-top:3px;margin-right:5px;" class=" btn btn-danger btn-xs btn_rm" onclick="rmEDU($(this));"><i class="glyphicon glyphicon-minus" id="del_e'+eid+'"></i></button>\
				<div class="input-group input-group-sm" style="width:420px;">\
				<span class="input-group-addon jlab"><i class="glyphicon glyphicon-book"></i> 学校</span>\
                    <div class="input-group-btn">\
                        <button type="button" class="btn btn-default \
                        dropdown-toggle" data-toggle="dropdown">院校\
                            <span class="caret"></span>\
                        </button>\
                        <ul class="dropdown-menu colul" id="col_dd'+eid+'">\
                            <li class="divider"></li>\
                        </ul>\
                    </div>\
				<span  class="form-control" id="edu'+eid+'" >'+col+'</span>\
				<input type="text" class="form-control" style="display:none;" id="e_sig_'+eid+'"/>\
				<span class="input-group-addon jlab">专业</span>\
                    <div class="input-group-btn">\
                        <button type="button" class="btn btn-default \
                        dropdown-toggle" data-toggle="dropdown">专业\
                            <span class="caret"></span>\
                        </button>\
                        <ul class="dropdown-menu majul" id="maj_dd'+eid+'">\
                            <li class="divider"></li>\
                        </ul>\
                    </div>\
				<span  class="form-control" id="maj'+eid+'" >'+maj+'</span>\
				<input type="text" class="form-control" style="display:none;" id="e_sig_'+eid+'"/>\
				</div>\
			</div>\
			';
}


function changeMAJ(item) {
	var eid=item.parent().parent().eq(0).attr('id').substr(6);
	var mid=0;
	for (var i=0;i<majs.length;++i) {
		if (majs[i]['title']==item.html()) {
			mid=majs[i]['id'];
			break;
		}
	}	
	$.ajax({
		type: 'post',
		url: BASE_URL+'user/ajax_editEdu',
		data: JSON.stringify({'eid':eid,'cid':'0','mid':mid})
		}).done(function(data){
			data=JSON.parse(data);
			if (!data['status']) {
				$('#maj'+eid).html(item.html());
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(){
			alert("出现错误，请稍后再试");
		});
}
function changeCOL(item) {
	var eid=item.parent().parent().eq(0).attr('id').substr(6);
	var cid=0;
	for (var i=0;i<cols.length;++i) {
		if (cols[i]['title']==item.html()) {
			cid=cols[i]['id'];
			break;
		}
	}	
	$.ajax({
		type: 'post',
		url: BASE_URL+'user/ajax_editEdu',
		data: JSON.stringify({'eid':eid,'cid':cid,'mid':'0'})
		}).done(function(data){
			data=JSON.parse(data);
			if (!data['status']) {
				$('#edu'+eid).html(item.html());
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});
}
function rmEDU(item) {
	var eid=item.parent().eq(0).attr('id').substr(6);
	$.ajax({
		type: 'post',
		url: BASE_URL+'user/ajax_rmEdu',
		data: JSON.stringify({'eid':eid})
		}).done(function(data){
			data=JSON.parse(data);
			if (!data['status']) {
				$('#edu_it'+eid).remove();
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});
}
function addEDU() {
	$.ajax({
		type: 'post',
		url: BASE_URL+'user/ajax_addEdu'
		}).done(function(data){
			data=JSON.parse(data);
			if (!data['status']) {
				var eid=data['eid'];
				$('#eduDIV').append(genEdu(eid,'',''));
				$('.jlab').hide();
				$('#nick').hide();
				$('#sig').hide();
					$(".colul").html('');
						for (var i=0;i<cols.length;++i) {
							$(".colul").append("<li><a style=\"cursor:pointer;\"onclick=\"changeCOL($(this));\">"+cols[i]['title']+"</a></li>");
						}
					$(".majul").html('');
					for (var i=0;i<majs.length;++i) {
						$(".majul").append("<li><a style=\"cursor:pointer;\" onclick=\"changeMAJ($(this));\">"+majs[i]['title']+"</a></li>");
					}
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});
}


function alterTags(item) {
	var tid=item.parent().eq(0).attr('id').substr(4);
	if (in_tags[tid]==1) {
		$('#itag'+tid).hide();
	} else {
		$('#itag'+tid).show();		
	}
	in_tags[tid]^=1;
	$('#tags_ti').html('');
	for (i in in_tags) {
		if (in_tags[i]==1) $('#tags_ti').append('<span class="label label-default">'+tags[i]['title']+'</span> ');
	}
}

function changeAVA() {
	if ($('#avat').val()=='') {
		alert('empty file!');
		return 0;
	}
	// $('#form0').attr('action',BASE_URL+'avatar/upload');
	// $('#form0').submit();
	$.ajaxFileUpload
            (
                {
                    url: BASE_URL+'avatar/upload', //用于文件上传的服务器端请求地址
                    secureuri: false, //是否需要安全协议，一般设置为false
                    fileElementId: 'avat', //文件上传域的ID
                    dataType: 'json', //返回值类型 一般设置为json
                    success: function (data, status)  //服务器成功响应处理函数
                    {
                        if (data.status==0) {
                        	window.location=window.location;
                        }else alert('fail');

                    },
                    error: function (data, status, e)//服务器响应失败处理函数
                    {
                        alert(e);
                    }
                }
            )
}

function genPeo(item) {
	return '<div style="float:left;margin-right:10px;height:80px;width:62px;text-align:center;">\
		<a href="'+BASE_URL+'user/look/'+item['userid']+'"><img style="height:60px;width:60px;" src="'+BASE_URL+'avatar/t/'+item['userid']+'"/>\
		'+item['nick']+'</a>\
	</div>';
}

function alterF(uid) {
	if (following=='yes') {
		$.ajax({
		type: 'post',
		url: BASE_URL+'user/unfollow',
		data: JSON.stringify({'uid':uid})
		}).done(function(data){
			data=JSON.parse(data);
			if (!data['status']) {
				window.location=window.location;
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});

	} else {
		$.ajax({
		type: 'post',
		url: BASE_URL+'user/dofollow',
		data: JSON.stringify({'uid':uid})
		}).done(function(data){
			data=JSON.parse(data);
			if (!data['status']) {
				window.location=window.location;
			} else {
				if (data['status']!=-1) alert(data['message']);
			}
		}).fail(function(data){
			alert("出现错误，请稍后再试");
		});


	}
}