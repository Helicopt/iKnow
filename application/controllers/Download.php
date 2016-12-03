<?php

/**
 * Filename: Download.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    Idea
 * @subpackage Controller
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends CI_Controller {

	var $filePath = "./file/";
	var $version = "1.0.0.0";

	function __construct() {
		parent::__construct();
		$this->load->model('visitm');
	}

	public function index()
	{
		echo json_encode(array("v"=>$this->version,"url"=>base_url("download/app"),"s"=>"更新：添加了更新时的说明"));
	}

	public function da()
	{
		$this->visitm->visit($this->version,"download");
		$this->load->helper('download');
		$data = file_get_contents($this->filePath."app-release.apk");
		$name = "idea-".$this->version.".apk";
		force_download($name, $data);		
	}

	public function app()
	{
		$this->load->view('app');
	}

	public function count()
	{
		echo "downloads: ";
		echo $this->visitm->getCNT($this->version,"download");
	}
}
/* End of file Download.php */
/* Location: ./application/controllers/Download.php */
