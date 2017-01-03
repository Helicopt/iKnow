<?php 
/**
 * Filename: Register.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    Iknow
 * @subpackage Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends MY_Controller {

	var $data = array();

	function __construct() {
		parent::__construct();
		$acc=urldecode($this-> security->xss_clean(file_get_contents("php://input")));
		$data=json_decode($acc, TRUE);			
		$this-> data=$data;
	}

	public function index() {
		if (!$this-> auth)
			$this-> load-> view('user/register');
		else redirect(base_url());
	}
		
}

/* End of file Register.php */
/* Location: ./application/controllers/Register.php */
