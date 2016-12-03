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

class Img extends CI_Controller {

	var $PicPath = "./img/";

	function __construct() {
		parent::__construct();
		$this->load->model('userm');
		$this->load->model('imgm');
		$this->load->model('topicm');
	}

	public function index()
	{
		echo json_encode(array('version' => '1.0', 'author' => 'fwt', 'projectName' => 'ideaWorkshop'));
		echo date('Y-m-d H:i:s');
		echo "\n<br>ImgSystem<br>";
		$key=isset($_GET['key'])?$_GET['key']:"0";
		$key=$this->security->xss_clean($key);
		$key=urlencode($key);
		$this->load->view("img",array("key"=>$key));
	}

	public function upload($k="0")
	{
		$k=$this->security->xss_clean($k);
		if (!$this->imgm->check($k)) return FALSE;
		$config['upload_path'] = $this->PicPath;
		$config['allowed_types'] = 'jpg|jpeg|gif|png';
		$config['file_name'] = $k.".".$this->imgm->getExt($k);
		$config['max_size'] = '2048';
		$config['max_width']  = '720';
		$config['max_height']  = '720';

		$this -> load -> library('upload',$config);

		$result = $this -> upload -> do_upload('img');

		if(!$result)
		{
			echo 2;
			return FALSE;
		}

		$finfo=$this -> upload -> data();

		$config['image_library'] = "gd2";
		$config['source_image'] = $this->PicPath .$finfo['file_name'];
		$config['maintain_ratio'] = TRUE;
		$config['width'] = "400";
		$config['height'] = "300";

		$this -> load -> library('image_lib');

		$this -> image_lib -> initialize($config);

		if(!$this -> image_lib -> resize())
		{
			echo $this -> image_lib -> display_errors();
			return FALSE;
		}		
		$this->imgm->sign($k);
		echo "OK";
	}

	public function k($k="0")
	{
		$k=$this->security->xss_clean($k);
		if (!$this->imgm->exists($k)) return FALSE;
		$ext=$this->imgm->getExt($k);
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
}
/* End of file Img.php */
/* Location: ./application/controllers/Img.php */
