<?php 
/**
 * Filename: Topic.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    iKnow
 * @subpackage Controller
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Topic extends MY_Controller {

	var $data = array();
	var $auth = 0;
	var $AccessType = 0;

	function __construct() {
		parent::__construct();
		$this->load->model('userm');
		$this->load->model('topicm');
		$acc=urldecode($this->security->xss_clean(file_get_contents("php://input")));
		$data=json_decode($acc, TRUE);	
		if ($this->auth) $this->data=$data;
		else {
			echo json_encode(array("status"=>0));
			exit();
		}
	}

	public function index() {
		if (!$this->auth) return;
		echo json_encode(array("status"=>1,"tps"=>$this->topicm->getDefaultPage(10)));		
	}	
	public function myTopicA() {
		if (!$this->auth) return;
		echo json_encode(array("status"=>1,"tps"=>$this->topicm->getMyDefault(10,$this->auth)));		
	}	
		
	public function works() {
		if (!$this->auth) return;
		echo json_encode(array("status"=>1,"tps"=>$this->topicm->getWorksPage(10)));		
	}	
	public function myTopicB() {
		if (!$this->auth) return;
		echo json_encode(array("status"=>1,"tps"=>$this->topicm->getMyWorks(10,$this->auth)));		
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

	public function newTopic() {
		$origin=urldecode(file_get_contents("php://input"));
		$origin=preg_replace('/<script[\S|\s]*script[\s]*\>/', '', $origin);
		$origin=preg_replace('/<iframe[\S|\s]*iframe[\s]*\>/', '', $origin);		
		$d=json_decode($origin,TRUE);
		$status=UNKNOWN_MSG;
		$message="";
		if (strpos($origin, 'script')!=FALSE||strpos($origin, 'iframe')!=FALSE) {
			$status=FAIL_MSG;
			$message="含有非法代码";
		}
		$tid=0;
		if (!isset($d['title'])||!isset($d['html'])||strlen($d['title'])<3||strlen($d['html'])<10) 
		{
			$status=FAIL_MSG;  
			$message="标题和描述过短";
		}
		if (isset($d['title'])&&strlen($d['title'])>64) 
		{
			$status=FAIL_MSG;  
			$message="标题过长";
		}
		if ($status==UNKNOWN_MSG)
		{
			$tid=$this->topicm->addQ($this->auth,$d);
			$status=($tid>0)?SUCCESS_MSG:FAIL_MSG;
			if ($status==SUCCESS_MSG) $message="提交成功";
			else $message="提交失败";
		}
		echo json_encode(array("status"=>$status,"message"=>$message,"tid"=>$tid));		
	}

	public function ansTopic() {
		$origin=urldecode(file_get_contents("php://input"));
		$origin=preg_replace('/<script[\S|\s]*script[\s]*\>/', '', $origin);
		$origin=preg_replace('/<iframe[\S|\s]*iframe[\s]*\>/', '', $origin);		
		$d=json_decode($origin,TRUE);
		$status=UNKNOWN_MSG;
		$message="";
		if (strpos($origin, 'script')!=FALSE||strpos($origin, 'iframe')!=FALSE) {
			$status=FAIL_MSG;
			$message="含有非法代码";
		}
		$tid=0;
		if (!isset($d['html'])||!isset($d['tid'])||strlen($d['html'])<10||!is_numeric($d['tid'])) 
		{
			$status=FAIL_MSG;  
			$message="数据有误";
		}
		if ($status==UNKNOWN_MSG)
		{
			$tid=$this->topicm->addA($this->auth,$d);
			$status=($tid>0)?SUCCESS_MSG:FAIL_MSG;
			if ($status==SUCCESS_MSG) $message="提交成功";
			else $message="提交失败";
		}
		echo json_encode(array("status"=>$status,"message"=>$message,"aid"=>$tid));		
	}

	public function addComment() {
		$d=$this->data;
		$status=UNKNOWN_MSG;
		$message="";
		$tid=0;
		if (!isset($d['val'])||!isset($d['aid'])||strlen($d['val'])<1||!is_numeric($d['aid'])) 
		{
			$status=FAIL_MSG;  
			$message="数据有误";
		}
		if ($status==UNKNOWN_MSG)
		{
			$tid=$this->topicm->addC($this->auth,$d);
			$status=($tid>0)?SUCCESS_MSG:FAIL_MSG;
			if ($status==SUCCESS_MSG) $message="提交成功";
			else $message="提交失败";
		}
		echo json_encode(array("status"=>$status,"message"=>$message,"cmid"=>$tid));
	}

	public function t($tid=0){
		if (is_numeric($tid)&&$this->topicm->topic_exists($tid))
		$this->load->view('topic/viewTopic',array(
			'cata'=>'topic',
			'uid'=>$this->auth,
			'info'=>$this->userm->getDetails($this->auth),
			'tid'=>$tid
			));
		else $this->load->view('errors/html/error_404',array('heading'=>'Not Found!','message'=>''));
	}

	public function viewTopic() {
		if (!isset($_GET['tid'])) 
		{
			$this->load->view('errors/html/error_404',array('heading'=>'Not Found!','message'=>''));
			return;
		}else $tid=$_GET['tid'];
		$info=$this->topicm->viewTalk($this->auth,$tid);
		$status=($info!='')?SUCCESS_MSG:FAIL_MSG;
		$todo=array("status"=>$status,'info'=>$info);
		echo json_encode($todo);
	}

	public function viewTopicAns() {
		$message="";
		if (!isset($_GET['tid'])) 
		{
			$this->load->view('errors/html/error_404',array('heading'=>'Not Found!','message'=>''));
			return;
		}else $tid=$_GET['tid'];
		$info=$this->topicm->viewAnsOfTalk($this->auth,$tid);
		$status=($info!='')?SUCCESS_MSG:FAIL_MSG;
		if ($status==FAIL_MSG) $message="fail";
		$todo=array("status"=>$status,'info'=>$info,'cnt'=>count($info),"message"=>$message);
		echo json_encode($todo);
	}

	public function viewComments() {
		$message="";
		if (!isset($_GET['aid'])) 
		{
			$this->load->view('errors/html/error_404',array('heading'=>'Not Found!','message'=>''));
			return;
		}else $tid=$_GET['aid'];
		$info=$this->topicm->viewCmtOfAns($this->auth,$tid);
		$status=($info!='')?SUCCESS_MSG:FAIL_MSG;
		if ($status==FAIL_MSG) $message="fail";
		$todo=array("status"=>$status,'info'=>$info,'cnt'=>count($info),"message"=>$message);
		echo json_encode($todo);
	}

	public function getTags() {
		echo json_encode($this->topicm->getTags());
	}

	public function wrapTopic() {
		if (!$this->auth) return;		
		$d=$this->data;
		$status=-1;
		if (!isset($d['tid'])||!isset($d['ds'])) 
		{
			$status=2;  
		}
		if ($status<0&&$this->topicm->getOwner($d['tid'])!=$this->auth) 
		{
			$status=0;
		}
		$t=json_decode($d['ds'],TRUE);
		if ($status<0)
		{
			$status=$this->topicm->wrapTalk($this->auth,$d['tid'],$t);
		}
		echo json_encode(array("status"=>$status));		

	}
	
}

/* End of file Topic.php */
/* Location: ./application/controllers/Topic.php */
