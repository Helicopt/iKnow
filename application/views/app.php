<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
	<p style="font-size:30px;"></p>
	<script type='text/javascript'>
		window.onload = function(){
			if(!isWeiXin()){
				window.location='<?=base_url("download/da")?>';
			}
			else
			{
				var p = document.getElementsByTagName('p');
				p[0].innerHTML = "如不能下载，请在浏览器中打开";
			}
		}
		function isWeiXin(){
			var ua = window.navigator.userAgent.toLowerCase();
			if(ua.match(/MicroMessenger/i) == 'micromessenger'){
				return true;
			}else{
				return false;
			}
		}
	</script>
</html>
