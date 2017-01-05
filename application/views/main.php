<?php $this->load->view('frame/header');?>

<div class="container" style="width:800px;background:#FEFEFE;margin-top:80px;box-shadow:0px 0px 1px #888888;padding:30px 50px 40px 60px;">
	<div class="row"  style="">
		<h3>来自关注的人</h3>
		<hr>
		<div id="peo_list"></div>
	</div>
</div>

<div class="container" style="width:800px;background:#FEFEFE;margin-top:20px;box-shadow:0px 0px 1px #888888;padding:30px 50px 40px 60px;">
	<div class="row"  style="">
		<h3>来自关注的领域</h3>
		<hr>
		<div id="fie_list"></div>
	</div>
</div>

<div class="container" style="width:800px;background:#FEFEFE;margin-top:20px;margin-bottom:80px;box-shadow:0px 0px 1px #888888;padding:30px 50px 40px 60px;">
	<div class="row"  style="">
		<h3>我的收藏</h3>
		<hr>
		<div id="fav_list"></div>
	</div>
</div>


<script type="text/javascript">

$(function(){
		$.ajax({
			type: 'post',
			url: BASE_URL+'topic/recommand',
		}).done(function(data){
			data=JSON.parse(data);
			peo_r=data['peo'];
			fie_r=data['field'];
			fav_r=data['favor'];
			console.log(peo_r);
			console.log(fie_r);
			console.log(fav_r);
			$cnt=0;
			for (var k in peo_r) {
				var item=peo_r[k];
				console.log(item);
				$('#peo_list').append(genStrip2(item['author_info'],'提出',item));
				++$cnt;
				if ($cnt>5) break;
			}
			$cnt=0;
			for (var k in fie_r) {
				var item=fie_r[k];
				console.log(item);
				$('#fie_list').append(genStrip2(item['author_info'],'提出',item));
				++$cnt;
				if ($cnt>5) break;
			}
			$cnt=0;
			for (var k in fav_r) {
				var item=fav_r[k];
				console.log(item);
				$('#fav_list').append(genStrip2(item['author_info'],'提出',item));
				++$cnt;
				if ($cnt>5) break;
			}
		}).fail(function(){
			alert("出现错误，请稍后再试");
		});

});


</script>


<?php $this->load->view('frame/footer');?>