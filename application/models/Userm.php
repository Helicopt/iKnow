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
	var $topicTableName = "topic";
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
				$_SESSION['uinfo']=array('id'=>$result['id'],'nick'=>$result['nick'],'email'=>$result['email'],'status'=>$result['status']);
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

	// function getAvatar($id)
	// {
	// 	$query=$this->db->get_where($this->userTableName,array('id'=>$id));
	// 	if ($query->num_rows()<=0) return 0;
	// 	$result=$query->row_array(0);
	// 	return $result['avatar'];
	// }
	function getDetails($id)
	{
		$returnArray = array();
			$query=$this->sqlm->_where($this->userTableName,array('id'=>$id));
			if ($query->num_rows()<=0) return FALSE;
			$result=$query->row_array();
			$returnArray['nick'] = $result['nick'];
			$returnArray['sig'] = $result['sig'];
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
	
	function userExists($uid) {
		if (!is_numeric($uid))return false;
		$uid=IntVal($uid);
		$query=$this->sqlm->_where($this->userTableName,array('id'=>$uid));
		return ($query->num_rows()>0);
	}	
	
	// function setAvatar($id,$k)
	// {
	// 	$query = $this -> db -> get_where($this->userTableName, array('id'=>$id));
	// 	if($query -> num_rows() > 0)
	// 	{
	// 		$query = $query -> row_array(0);
	// 		$this->db->update($this->userTableName,array('avatar'=>$k),array("id"=>$id));
	// 	}
	// 	else
	// 	{
	// 		return false;
	// 	}
	// }

}

/* End of file userm.php */
/* Location: ./application/models/userm.php */
