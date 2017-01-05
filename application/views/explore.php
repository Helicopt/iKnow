<?php $this->load->view('frame/header');?>

<div class="container" style="width:800px;background:#FEFEFE;margin-top:80px;box-shadow:0px 0px 1px #888888;padding:30px 50px 40px 60px;">
	<div class="row"  style="">
		<h3>分类话题</h3>
		<hr>
		<div id="fav_list"></div>
	</div>
</div>


<script type="text/javascript">

$(function(){
		$.ajax({
			type: 'post',
			url: BASE_URL+'explore/sort_topic',
		}).done(function(data){
			data=JSON.parse(data);
			fav_r=data['info'];
			console.log(fav_r);
			for (var k in fav_r) {
				var item=fav_r[k];
				console.log(item);
				$('#fav_list').append("<div id='t_"+k+"' style=''></div>");
				$('#t_'+k).append("<div class='row' style='margin-bottom:15px;'>"+genLabelBig('focusTag',item['tag'])+"</div>");
				var itt=item['info'];
				var cnt=0;
				for (var l in itt) {
					var ittt=itt[l];
					$('#t_'+k).append(genStrip2(ittt['author_info'],'提出',ittt));
					++cnt;
				}				
				$('#t_'+k).append("<hr>");
				if (cnt==0) {
					$('#t_'+k).hide();
				}
			}
		}).fail(function(){
			alert("出现错误，请稍后再试");
		});

});


</script>


<?php $this->load->view('frame/footer');?>
