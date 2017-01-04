<?php 
/**
 * Filename: User.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    Iknow
 * @subpackage Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

	var $data = array();

	function __construct() {
		parent::__construct();
		$acc=urldecode($this->security->xss_clean(file_get_contents("php://input")));
		$data=json_decode($acc, TRUE);	
		if ($this->auth) $this->data=$data;
		else {
			echo json_encode(array("status"=>0));
			exit();
		}
	}

	public function index() {
		$this->load->view('user/main',array(
			'cata'=>'profile',
			'uid'=>$this->auth,
			'info'=>$this->userm->getDetails($this->auth)
			));
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

	public function ajax_profile() {
		$userDetails=$this->userm->getAllInfo($this->auth);
		echo json_encode(array("status"=>SUCCESS_MSG,"profile"=>$userDetails));		
	}
		
	public function ajax_look() {
		$d=$this->data;
		$status=UNKNOWN_MSG;
		if (!isset($d['id'])) 
		{
			$status=FAIL_MSG;
			$userDetails=array();
		}
		else {
			$userDetails=$this->userm->getDetails($this->auth,$d['id']);
			$status=SUCCESS_MSG;
		}
		echo json_encode(array("status"=>$status,"profile"=>$userDetails));
	}

	public function ajax_setProfile() {
		$status=$this->userm->setProfile($this->auth,$this->data)?SUCCESS_MSG:FAIL_MSG;
		echo json_encode(array("status"=>$status));
	}

	public function ajax_getOV() {
		$d=$this->data;
		$ovid=0;
		if (!isset($d['ovid'])) {
			$ovid=$this->auth;
		} else $ovid=$d['ovid'];
		echo json_encode($this->userm->getUserOV($ovid));
	}
		
	// public function setAvatar() {
	// 	if (!$this->auth) return;		
	// 	$d=$this->data;
	// 	if (isset($d['pic']))
	// 	{
	// 		$ImgId=$this->avatarm->addImg($this->auth,$d['pic']);
	// 		echo json_encode(array("status"=>1,"k"=>$ImgId));		
	// 	}
	// 	else 
	// 	{
	// 		echo json_encode(array("status"=>2));					
	// 	}
	// }

	public function ajax_getColleges() {
		$this->load->model('edum');
		echo json_encode($this->edum->getAllCollege());
	}
		
	public function ajax_getMajors() {
		$this->load->model('edum');
		echo json_encode($this->edum->getAllmajor());
	}

	public function ajax_getEduById() {
		$this->load->model('edum');
		if (isset($this->data['uid'])) $uid=$this->data['uid'];
		else $uid=$this->auth;
		echo json_encode($this->edum->getEduById($uid));		
	}

	public function look($oid='0') {
		if ($oid=='0'||!is_numeric($oid)) $oid=$this->auth;
		if ($oid!=$this->auth)
		$this->load->view('user/other',
			array(
				'cata'=>'profile',
				'uid'=>$this->auth,
				'oid'=>$oid,
				'info'=>$this->user_info,
				'other'=>$this->userm->getDetails($oid)
				));
		else $this->index();
	}

	public function dofollow() {
		$d=$this->data;
		$uid='0';
		if (isset($d['uid'])) $uid=$d['uid'];
		$status=$this->userm->dofollow($this->auth,$uid)?SUCCESS_MSG:FAIL_MSG;
		echo json_encode(array("status"=>$status,"message"=>""));
	}

	public function unfollow() {
		$d=$this->data;
		$uid='0';
		if (isset($d['uid'])) $uid=$d['uid'];
		$status=$this->userm->unfollow($this->auth,$uid)?SUCCESS_MSG:FAIL_MSG;
		echo json_encode(array("status"=>$status,"message"=>""));
	}

	public function ajax_editEdu() {
		$d=$this->data;
		$status=UNKNOWN_MSG;
		$message="";
		if (!isset($d['eid'])) {
			$status=FAIL_MSG;
			$message="fail";
		}
		if (!isset($d['cid'])) {
			$d['cid']=0;
		}
		if (!isset($d['mid'])) {
			$d['mid']=0;
		}
		$this->load->model('edum');
		if ($d['mid']!=0||$d['cid']!=0) {
			$status=$this->edum->editEduById($d['eid'],$d['cid'],$d['mid'],$this->auth)?SUCCESS_MSG:FAIL_MSG;
			if ($status==FAIL_MSG) $message="请检查院校或者专业是否有误";
		} else $message="没有修改";
		echo json_encode(array('status'=>$status,'message'=>$message));
	}
	
	public function ajax_rmEdu() {
		$d=$this->data;
		$status=UNKNOWN_MSG;
		$message="";
		if (!isset($d['eid'])) {
			$status=FAIL_MSG;
			$message="fail";
		}
		$this->load->model('edum');
		$status=$this->edum->delEduById($d['eid'],$this->auth)?SUCCESS_MSG:FAIL_MSG;
		if ($status==FAIL_MSG) $message="删除失败";
		echo json_encode(array('status'=>$status,'message'=>$message));
	}
		
	public function ajax_addEdu() {
		$d=$this->data;
		$status=UNKNOWN_MSG;
		$message="";
		$this->load->model('edum');
		$n_id=$this->edum->addEduById($this->auth);
		$status=$n_id?SUCCESS_MSG:FAIL_MSG;
		if ($status==FAIL_MSG) $message="失败";
		echo json_encode(array('status'=>$status,'message'=>$message,'eid'=>$n_id));
	}
		
	public function ajax_pwdSet() {
		$status=UNKNOWN_MSG;
		$message="";
		$d=$this->data;
		if (!isset($d['op'])||!isset($d['np'])) {
			$status=FAIL_MSG;
			$message="fail";
			return;
		}
		if (strlen($d['np'])!=64||!preg_match("/^[A-Za-z0-9]*$/", $d['np'])) {
			$status=FAIL_MSG;
			$message="密码不合法";
		}
		if (strlen($d['op'])!=64||!preg_match("/^[A-Za-z0-9]*$/", $d['op'])) {
			$status=FAIL_MSG;
			$message="密码不合法";
		}
		if ($status==UNKNOWN_MSG) {
			if (!$this->userm->validatePWD($this->auth,$d['op'])) {
				$status=FAIL_MSG;
				$message="旧密码错误";
			}
			if ($status==UNKNOWN_MSG) $status=$this->userm->resetP($this->auth,$d['np'])?SUCCESS_MSG:FAIL_MSG;
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

	public function profile() {
		$this->load->view('user/profile');
	}
	
	// public function lookAt() {
	// 	$this->load->view('user/look');
	// }
	
	public function resetPWD() {
		$this->load->view('user/resetPWD');
	}

	public function getFollowee() {
		$d=$this->data;
		$uid=0;
		if (!isset($d['uid'])) {
			$uid=$this->auth;
		} else $uid=$d['uid'];
		echo json_encode($this->userm->getFollowees($uid));
	}

	
}

/* End of file User.php */
/* Location: ./application/controllers/User.php */
