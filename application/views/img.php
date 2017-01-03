<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php 
	$this -> load -> helper(array('form', 'url'));
	echo form_open_multipart('image/upload/'.$key);
?>
<input type="file" class = "btn btn-large btn-info" accept=".jpg,.jpge,.png,.gif" name="img" /><br>
<input type="submit" class = "btn btn-large btn-info" value="submit" />
<?php echo form_close();?>
