<?php
/**
 * Filename: userm.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    Iknow
 * @subpackage Model
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model: UserM
 *
 * @package    Iknow
 * @subpackage Model
 */

class UserM extends CI_Model {
	/**
	* @access private
	* @var    string
	*/
	var $userTableName = "user";
	/**
	* @access private
	* @var    string
	*/
	var $flwTableName = "follow";
	/**
	* @access private
	* @var    string
	*/
	var $topicTableName = "topic";
	/**
	* @access private
	* @var    string
	*/
	var $grpTableName = "groups";
	/**
	* @access private
	* @var    string
	*/
	var $blgTableName = "belong";
	/**
	*
	* UserM构造函数
	*
	*/
	function __constuct()
	{
		parent::__constuct();
		$this->load->model('avatarm');
	}
	
	function login($username, $pwd)
	{
		$query = $this->sqlm->_where($this->userTableName,array('email'=>$username));
		if ($query->num_rows() > 0)
		{
			$result=$query->row_array(0);
			$rpwd=$result['pwd'];
			if ($rpwd==$pwd) {
				$_SESSION['id']=$result['id'];
				$_SESSION['uinfo']=array('id'=>$result['id'],'nick'=>$result['nick'],'email'=>$result['email'],
					'status'=>$result['status'],'ava'=>base_url()."avatar/t/".$result['id'],
					'isAdmin'=>$this->userm->belongTo($result['id'],'admin'));
				return $result['id'];
			}
			else return 0;
		}
		else return 0;
	}

	function get_current_user_info() {
		return $_SESSION['uinfo'];
	}

	function emailExists($mail)
	{
		$query = $this->sqlm->_where($this->userTableName,array('email'=>$mail));
		return ($query->num_rows() > 0)?($query->row_array(0)['id']):0;
	}

	function resetP($uid, $NewPwd)
	{
		$query = $this->sqlm->_where($this->userTableName,array('id'=>$uid));
		if ($query->num_rows() > 0) 
		{
			$this->sqlm->_update($this->userTableName,array('pwd'=>$NewPwd),array('id'=>$uid));		
			return TRUE;
		} 
		else 
		{
			return FALSE;
		}
	}
	
	function register($info)
	{
		$data=array();
		$data['email']=$info['email'];
		$data['pwd']=$info['pwd'];
		if (isset($info['nick'])) $data['nick']=$info['nick']; else $data['nick']="";
		if (isset($info['sig'])) $data['sig']=$info['sig']; else $data['sig']=" ";
		$this->sqlm->_insert($this->userTableName, $data);
		return $this->db->insert_id(); //mysql_insert_id()
	}

	function logout()
	{
		session_destroy();
	}
	
	function isLogin()
	{
		if (isset($_SESSION['id'])) return $_SESSION['id'];
		else return 0;
	}

	function getAvatar($uid)
	{
		$query=$this->sqlm->_where($this->userTableName,array('id'=>$uid));
		if ($query->num_rows()<=0) return 0;
		$result=$query->row_array(0);
		return $result['avatarid'];
	}

	function isFollowed($a,$b) {
		$query=$this->sqlm->_where($this->flwTableName,array('followerid'=>$a,'userid'=>$b));
		return $query->num_rows();
	}

	function getGIDByName($gn) {
		$query=$this->sqlm->_where($this->grpTableName,array('title'=>$gn));
		if ($query->num_rows()==0) return "";
		return $query->row_array(0)['id'];
	}

	function belongTo($uid, $grp) {
		$gid=$this->getGIDByName($grp);
		$query=$this->sqlm->_where($this->blgTableName,array('uid'=>$uid,'gid'=>$gid));
		return $query->num_rows();
	}

	function dofollow($a,$b) {
		if ($b=='0') {
			return 0;
		}
		else {
			$query=$this->sqlm->_insert($this->flwTableName,array('followerid'=>$a,'userid'=>$b));
			return $this->db->affected_rows();
		}
	}

	function unfollow($a,$b) {
		if ($b=='0') {
			return 0;
		}
		else {
			$query=$this->sqlm->_delete($this->flwTableName,array('followerid'=>$a,'userid'=>$b));
			return $this->db->affected_rows();
		}
	}

	function getDetails($id)
	{
		$returnArray = array();
			$query=$this->sqlm->_where($this->userTableName,array('id'=>$id));
			if ($query->num_rows()<=0) return FALSE;
			$result=$query->row_array();
			$returnArray['id'] = $result['id'];
			$returnArray['nick'] = $result['nick'];
			$returnArray['sig'] = $result['sig'];
			$returnArray['ava'] = base_url()."avatar/t/".$id;
			$returnArray['following'] = $this->isFollowed($this->auth,$result['id'])?'yes':'no';
			$returnArray['isAdmin'] = $this->belongTo($result['id'],'admin');
			//$returnArray['avatar'] = $result['avatar'];
			//$returnArray['ext'] = $this->avatarm->getExt($result['avatar']);
		return $returnArray;
	}


		
	function setProfile($id,$info)
	{
		$query=$this->sqlm->_where($this->userTableName,array('id'=>$id));
		if ($query->num_rows()<=0){
			return FALSE;
		}
		$data=array();
		if (isset($info['nick'])) $data['nick']=$info['nick'];
		if (isset($info['sig'])) $data['sig']=$info['sig'];
		$this->sqlm->_update($this->userTableName,$data,array('id'=>$id));
		return TRUE;
	}

	function getUserOV($uid) {
		$query=$this->db->query("CALL `overview1`($uid)");
		$res=$query->result_array();
		return $res;
	}
	
	function userExists($uid) {
		if (!is_numeric($uid))return false;
		$uid=IntVal($uid);
		$query=$this->sqlm->_where($this->userTableName,array('id'=>$uid));
		return ($query->num_rows()>0);
	}	
	
	function setAvatar($uid, $id)
	{
		$this-> sqlm -> _update($this->userTableName,array('avatarid'=>$id),array("id"=>$uid));
		return $this->db->affected_rows();
	}

	function getFollowees($uid) {
		$query=$this->db->query("SELECT `userid`,`nick` from ".$this->flwTableName." join ".$this->userTableName
			." on `id`=`userid` where `followerid`='$uid'");		
		return $query->result_array();
	}

}

/* End of file userm.php */
/* Location: ./application/models/userm.php */
