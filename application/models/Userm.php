<?php
/**
 * Filename: userm.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    Idea
 * @subpackage Model
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model: UserM
 *
 * @package    Idea
 * @subpackage Model
 */

class UserM extends CI_Model {
	/**
	* @access private
	* @var    string
	*/
	var $userTableName = "users";
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
		$query = $this->db->get_where($this->userTableName, array('email'=>$username, 'pwd'=>$pwd));
		if ($query->num_rows() > 0)
		{
			$result=$query->row_array(0);
			return $result['id'];
		}
		else return 0;
	}

	function emailExists($mail)
	{
		$query = $this->db->get_where($this->userTableName, array('email'=>$mail));
		return ($query->num_rows() > 0);
	}

	function resetP($uid, $NewPwd)
	{
		$query = $this->db->get_where($this->userTableName, array('id'=>$uid));
		if ($query->num_rows() > 0) 
		{
			$this->db->update($this->userTableName,array('pwd'=>$NewPwd),array("id"=>$uid));		
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
		if (isset($info['college'])) $data['college']=$info['college']; else $data['college']="";
		if (isset($info['major'])) $data['major']=$info['major']; else $data['major']="";
		if (isset($info['sign'])) $data['sign']=$info['sign']; else $data['sign']="";
		if (isset($info['grade'])) $data['grade']=$info['grade']; else $data['grade']=-1;
		if (isset($info['avatar'])) $data['avatar']=$info['avatar']; else $data['avatar']=0;
		return $this->db->insert($this->userTableName, $data);		
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

	function getAvatar($id)
	{
		$query=$this->db->get_where($this->userTableName,array('id'=>$id));
		if ($query->num_rows()<=0) return 0;
		$result=$query->row_array(0);
		return $result['avatar'];
	}
	function getUserDetails($id)
	{
		$returnArray = array();
			$query=$this->db->get_where($this->userTableName,array('id'=>$id));
			if ($query->num_rows()<=0) return FALSE;
			$result=$query->row_array();
			$returnArray['nick'] = $result['nick'];
			$returnArray['college'] = $result['college'];
			$returnArray['major'] = $result['major'];
			$returnArray['grade'] = $result['grade'];
			$returnArray['sign'] = $result['sign'];
			$returnArray['avatar'] = $result['avatar'];
			$returnArray['ext'] = $this->avatarm->getExt($result['avatar']);
		return $returnArray;
	}

	function briefInfo($id)
	{
		$r=$this->getUserDetails($id);
		if ($r!=FALSE)
		{
			return $r['nick'].", ".$r['college']."  ".$r['major']." ".(string)$r['grade']."年级,  ".$r['sign'];
		}
		return "";
	}

	function midInfo($id)
	{
		$r=$this->getUserDetails($id);
		if ($r!=FALSE)
		{
			return $r['nick'].", ".$r['college'];
		}
		return "";
	}

	function authInfo($id)
	{
		$r=$this->getUserDetails($id);
		if ($r!=FALSE)
		{
			return $r['nick'];
		}
		return "";
	}
		
	function setProfile($id,$info)
	{
		$query=$this->db->get_where($this->userTableName,array('id'=>$id));
		if ($query->num_rows()<=0){
			return FALSE;
		}
		$data=array();
		if (isset($info['nick'])) $data['nick']=$info['nick'];
		if (isset($info['college'])) $data['college']=$info['college'];
		if (isset($info['major'])) $data['major']=$info['major'];
		if (isset($info['sign'])) $data['sign']=$info['sign'];
		if (isset($info['grade'])&&is_numeric($info['grade'])) $data['grade']=$info['grade'];
		$this->db->update($this->userTableName,$data,array('id'=>$id));
		return TRUE;
	}
	
	function userExists($uid) {
		if (!is_numeric($uid))return false;
		$uid=IntVal($uid);
		$query=$this->db->get_where($this->userTableName,array('id'=>$uid));
		return ($query->num_rows()>0);
	}	
	
	function setAvatar($id,$k)
	{
		$query = $this -> db -> get_where($this->userTableName, array('id'=>$id));
		if($query -> num_rows() > 0)
		{
			$query = $query -> row_array(0);
			$this->db->update($this->userTableName,array('avatar'=>$k),array("id"=>$id));
		}
		else
		{
			return false;
		}
	}

}

/* End of file userm.php */
/* Location: ./application/models/userm.php */
