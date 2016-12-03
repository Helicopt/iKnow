<?php 
/**
 * Filename: Topic.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    Idea
 * @subpackage Controller
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Topic extends CI_Controller {

	var $data = array();
	var $auth = 0;
	var $AccessType = 0;

	function __construct() {
		parent::__construct();
		$this->load->model('userm');
		$this->load->model('imgm');
		$this->load->model('avatarm');
		$this->load->model('topicm');
		$acc=urldecode($this->security->xss_clean(file_get_contents("php://input")));
		$len=strlen($acc);
		$needCheck=true;
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
		if (!$this->auth) return;		
		$d=$this->data;
		$status=-1;
		$message="";
		$tid=0;
		if (!isset($d['title'])||!isset($d['desc'])||$d['title']==""||$d['desc']=="") 
		{
			$status=2;  
			$message="标题和描述不能为空";
		}
		if ($status<0)
		{
			if (!isset($d['parent'])) $tid=$this->topicm->addTalk($this->auth,$d);
			else $tid=$this->topicm->addTalk($this->auth,$d,$d['parent']);
			if (!isset($d['parent'])&&isset($d['pic'])&&$tid>0) {
				$ImgId=$this->imgm->addImg($tid,$d['pic']);
			} else $ImgId="0";
			$status=($tid>0&&$ImgId!="-")?1:2;
			if ($status==1) $message="提交成功";
			else $message="提交失败";
		}
		echo json_encode(array("status"=>$status,"message"=>$message,"tid"=>$tid,"ImgId"=>$ImgId));		
	}

	public function viewTopic() {
		if (!$this->auth) return;		
		$d=$this->data;
		$status=-1;
		if (!isset($d['tid'])) 
		{
			$status=2;  
		}
		if ($status<0)
		{
			$info=$this->topicm->viewTalk($this->auth,$d['tid']);
			$status=($info!=null)?1:2;
		}
		$todo=array("status"=>$status,"author"=>$info['author'],"title"=>$info['title'],"brief"=>$info['brief'],"desc"=>$info['desc'],"subQ"=>$info['subQ'],'type'=>$info['type'],'owner'=>$info['owner'],'s'=>$info['s'],'parent'=>$info['parent'],'dep'=>$info['dep']);
		if (isset($info['k'])) $todo['k']=$info['k'];
		if (isset($info['ext'])) $todo['ext']=$info['ext'];
		echo json_encode($todo);		

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
