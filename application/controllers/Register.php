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
		$acc=urldecode($this->security->xss_clean(file_get_contents("php://input")));
		$data=json_decode($acc, TRUE);	
		$this->data=$data;
	}

	public function index() {
		redirect(base_url());
	}
	
	public function register() {
		$d=$this->data;
		$status=UNKNOWN_MSG;
		$message="";
		if (!isset($d['email'])||!isset($d['pwd'])) 
		{
			$status=FAIL_MSG;  
			$message="邮箱和密码不能为空";
		}
		else 
		if (!filter_var($d['email'],FILTER_VALIDATE_EMAIL))
		{
			$status=FAIL_MSG;  
			$message="邮箱不合法";			
		}
		else 
		if ($this->userm->emailExists($d['email']))
		{
			$status=FAIL_MSG;  
			$message="邮箱已存在";			
		}
		else 
		if (strlen($d['pwd'])!=64||!preg_match("/^[A-Za-z0-9]*$/", $d['pwd']))
		{
			$status=FAIL_MSG;  
			$message="密码不合法";			
		}
		if ($status==UNKNOWN_MSG)
		{
			$id=$this->userm->register($d);
			if ($id) {
				$message="注册成功";
				$status=SUCCESS_MSG;
			}
			else {
				$message="注册失败";
				$status=FAIL_MSG;
			}
		}
		echo json_encode(array("status"=>$status,"message"=>$message));		
	}

	public function login() {
		$d=$this->data;
		$status=UNKNOWN_MSG;
		$message="";
		if (!isset($d['email'])||!isset($d['pwd'])) {
			$status=FAIL_MSG;
			$message="fail";
			return;			
		}
		$status=$this->userm->login($d['email'],$d['pwd'])?SUCCESS_MSG:AUTH_MSG;
		if ($status==AUTH_MSG) $message="邮箱或密码错误";
		echo json_encode(array("status"=>$status,"message"=>$message));
	}

	public function logout() {
		$this->userm->logout();
		redirect(base_url());
	}
	
}

/* End of file Register.php */
/* Location: ./application/controllers/Register.php */
