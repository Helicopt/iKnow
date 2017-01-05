<?php $this->load->view('frame/header');?>

<div class="container" style="width:800px;background:#FEFEFE;margin-top:80px;box-shadow:0px 0px 1px #888888;padding:30px 60px 40px 30px;">
	<div class="row"  style="height:1000px;">
		<h4>随便看看</h4>
		<div id="fav_list"></div>
	</div>
</div>


<script type="text/javascript">

$(function(){
		$.ajax({
			type: 'post',
			url: BASE_URL+'topic/all_topic',
		}).done(function(data){
			data=JSON.parse(data);
			fav_r=data['info'];
			console.log(fav_r);
			for (var k in fav_r) {
				var item=fav_r[k];
				console.log(item);
				$('#fav_list').append(item['author_info']['nick']+genStrip2('提出',item));
			}
		}).fail(function(){
			alert("出现错误，请稍后再试");
		});

});


</script>


<?php $this->load->view('frame/footer');?>