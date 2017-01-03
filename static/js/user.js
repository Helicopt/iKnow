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
		url: './user/ajax_editEdu',
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
		url: './user/ajax_editEdu',
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
		url: './user/ajax_rmEdu',
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
		url: './user/ajax_addEdu'
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

$('#submit_que').click(function() {
	var data=$('#editor').val();
	var title=$('#qtitle').val();
	alert(data);

});