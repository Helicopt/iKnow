<?php

/**
 * Filename: Img.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    Idea
 * @subpackage Controller
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Avatar extends CI_Controller {

	var $PicPath = "./avatar/";

	function __construct() {
		parent::__construct();
		$this->load->model('userm');
		$this->load->model('avatarm');
		$this->load->model('topicm');
	}

	public function index()
	{
		echo json_encode(array('version' => '1.0', 'author' => 'fwt', 'projectName' => 'ideaWorkshop'));
		echo date('Y-m-d H:i:s');
		echo "\n<br>AvatarSystem<br>";
		$key=isset($_GET['key'])?$_GET['key']:"0";
		$key=$this->security->xss_clean($key);
		$key=urlencode($key);
		$this->load->view("avatar",array("key"=>$key));
	}

	public function upload($k="0")
	{
		$k=$this->security->xss_clean($k);
		if (!$this->avatarm->check($k)) return FALSE;
		$config['upload_path'] = $this->PicPath;
		$config['allowed_types'] = 'jpg|jpeg|gif|png';
		$config['file_name'] = $k.".".$this->avatarm->getExt($k);
		$config['max_size'] = '1024';
		$config['max_width']  = '400';
		$config['max_height']  = '400';

		$this -> load -> library('upload',$config);

		$result = $this -> upload -> do_upload('img');

		if(!$result)
		{
			echo $this -> upload -> display_errors();
			return FALSE;
		}

		$finfo=$this -> upload -> data();

		$config['image_library'] = "gd2";
		$config['source_image'] = $this->PicPath .$finfo['file_name'];
		$config['maintain_ratio'] = TRUE;
		$config['width'] = "200";
		$config['height'] = "200";

		$this -> load -> library('image_lib');

		$this -> image_lib -> initialize($config);

		if(!$this -> image_lib -> resize())
		{
			echo $this -> image_lib -> display_errors();
			return FALSE;
		}
		$tid=$this->avatarm->getTIDByKey($k);
		$oldKey=$this->userm->getAvatar($tid);
		$this->avatarm->sign($k,$oldKey);
		$this->userm->setAvatar($tid,$k);
		echo "OK";
	}

	public function k($k="0")
	{
		$k=$this->security->xss_clean($k);
		if (!$this->avatarm->exists($k)) return FALSE;
		$ext=$this->avatarm->getExt($k);
		$url=$this->PicPath.$k.".".$ext;
		header("Content-type:image/".$ext);
		switch ($ext) {
			case 'png':
				$img=imagecreatefrompng($url);
				imagepng($img);
				imagedestroy($img);				
				break;
			case 'jpeg':
				$img=imagecreatefromjpeg($url);
				imagejpeg($img);
				imagedestroy($img);				
				break;
			case 'jpg':
				$img=imagecreatefromjpeg($url);
				imagejpeg($img);
				imagedestroy($img);				
				break;
			case 'gif':
				$img=imagecreatefromgif($url);
				imagegif($img);
				imagedestroy($img);				
				break;
			default:
				# code...
				break;
		}
	}

	public function t($k="0")
	{
		$k=$this->security->xss_clean($k);
		if (substr($k,0,3)=="av_"||strlen($k)>30){
			$this->k($k);
		}
		else 
		{
			$k=$this->userm->getAvatar($k);
			$this->k($k);
		}
	}
}
/* End of file Avatar.php */
/* Location: ./application/controllers/Avatar.php */
