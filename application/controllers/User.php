<?php 
/**
 * Filename: User.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    Idea
 * @subpackage Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	var $data = array();
	var $auth = 0;
	var $AccessType = 0;

	function __construct() {
		parent::__construct();
		$this->load->model('userm');
		$this->load->model('avatarm');
		$acc=urldecode($this->security->xss_clean(file_get_contents("php://input")));
		$len=strlen($acc);
		$needCheck=!($this->uri->segment(1)=='user'&&($this->uri->segment(2)=='register'));
		if ($len>0&&$acc[$len-1]=='=')
		{
			$acc=substr($acc,0,strlen($acc)-1);
			$data=json_decode($acc, TRUE);			
			$this->AccessType=0;
		}
		else 
		{
			$data=json_decode($acc, TRUE);			
			$data=$data[0];
			$this->AccessType=1;
		}
		if ($needCheck)
		{
			if (isset($data['uem'])&&isset($data['upw']))
			{
				if ($this->AccessType==1) $this->auth=$this->userm->islogin();
				if ($this->AccessType==0) $this->auth=$this->userm->login($data['uem'],$data['upw']);
				//if ($this->AccessType==1) $this->auth=$this->userm->login('admin@a.bc','40bd001563085fc35165329ea1ff5c5ecbdbbeef');
			}
		}
		else $this->auth=-1;
		if ($this->auth) $this->data=$data;
		else echo json_encode(array("status"=>0));
	}

	public function index() {
		if (!$this->auth) return;
		echo json_encode(array("status"=>1,"id"=>$this->auth));		
	}
	
	public function register() {
		$d=$this->data;
		$status=-1;
		$message="";
		if (!isset($d['email'])||!isset($d['pwd'])) 
		{
			$status=2;  
			$message="邮箱和密码不能为空";
		}
		else 
		if (!filter_var($d['email'],FILTER_VALIDATE_EMAIL))
		{
			$status=2;  
			$message="邮箱不合法";			
		}
		else 
		if ($this->userm->emailExists($d['email']))
		{
			$status=2;  
			$message="邮箱已存在";			
		}
		else 
		if (strlen($d['pwd'])!=40||!preg_match("/^[A-Za-z0-9]*$/", $d['pwd']))
		{
			$status=2;  
			$message="密码不合法";			
		}
		if ($status<0)
		{
			$status=$this->userm->register($d);
			if ($status==1) $message="注册成功";
			else $message="注册失败";
		}
		echo json_encode(array("status"=>$status,"message"=>$message));		
	}

	public function profile() {
		if (!$this->auth) return;		
		$userDetails=$this->userm->getUserDetails($this->auth);
		echo json_encode(array("status"=>1,"profile"=>$userDetails));		
	}
		
	public function look() {
		if (!$this->auth) return;		
		$d=$this->data;
		$status=1;
		if (!isset($d['id'])) 
		{
			$status=2;
			$userDetails=array();
		}
		else $userDetails=$this->userm->getUserDetails($d['id']);
		echo json_encode(array("status"=>$status,"profile"=>$userDetails));		
	}

	public function setProfile() {
		if (!$this->auth) return;		
		$status=$this->userm->setProfile($this->auth,$this->data)?1:2;	
		echo json_encode(array("status"=>$status));		
	}
		
	public function setAvatar() {
		if (!$this->auth) return;		
		$d=$this->data;
		if (isset($d['pic']))
		{
			$ImgId=$this->avatarm->addImg($this->auth,$d['pic']);
			echo json_encode(array("status"=>1,"k"=>$ImgId));		
		}
		else 
		{
			echo json_encode(array("status"=>2));					
		}
	}
		
	public function pwdSet() {
		if (!$this->auth) return;		
		$d=$this->data;
		$status=$this->userm->resetP($this->auth,$d['np']);
		echo json_encode(array("status"=>$status));		
	}
	
}

/* End of file User.php */
/* Location: ./application/controllers/User.php */
