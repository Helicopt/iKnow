<?php $this->load->view('frame/header');?>

<div class="container" style="width:800px;background:#FEFEFE;margin-top:80px;box-shadow:0px 0px 1px #888888;padding:30px 60px 40px 30px;">
	<div class="row"  style="">
		<h3>分类话题</h3>
		<div id="fav_list"></div>
	</div>
</div>


<script type="text/javascript">

$(function(){
		$.ajax({
			type: 'post',
			url: BASE_URL+'topic/sort_topic',
		}).done(function(data){
			data=JSON.parse(data);
			fav_r=data['info'];
			console.log(fav_r);
			for (var k in fav_r) {
				var item=fav_r[k];
				console.log(item);
				$('#fav_list').append("<div id='t_"+k+"' style='height:250px;'></div>");
				$('#t_'+k).append("<h4>"+item['title']+"<h4/>");
				var itt=item['info'];
				for (var l in itt) {
					var ittt=itt[l];
					$('#t_'+k).append(genStrip2('提出',ittt));
				}
			}
		}).fail(function(){
			alert("出现错误，请稍后再试");
		});

});


</script>


<?php $this->load->view('frame/footer');?>