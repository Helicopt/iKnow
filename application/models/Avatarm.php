<?php
/**
 * Filename: avatarm.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    Idea
 * @subpackage Model
 */

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Model: AvatarM
 *
 * @package    Idea
 * @subpackage Model
 */

class AvatarM extends CI_Model {
	/**
	* @access private
	* @var    string
	*/
	var $imgTableName = "avatar";
	/**
	* @access private
	* @var    string
	*/
	var $userTableName = "users";
	/**
	*
	* AvatarM构造函数
	*
	*/
	function __constuct()
	{
		$this->load->model('userm');
		parent::__constuct();
	}	

	function addImg($id,$ext)
	{
		$data=array();
		$data['tid']=$id;
		$tst=date('Y-m-d H:i:s');
		$data['k']="av_".md5(($id.$tst));
		$data['ext']=$ext;
		$this->db->insert($this->imgTableName, $data);		
		return $data['k'];
	}


	function check($key)
	{
		$query=$this->db->get_where($this->imgTableName,array('k'=>$key));
		if ($query->num_rows()<=0) return FALSE;
		$result=$query->row_array(0);
		if ($result['status']==0) return TRUE;
		return FALSE;
	}

	function getImgByTID($id)
	{
		$query=$this->db->get_where($this->imgTableName,array('tid'=>$id,'status'=>1));
		if ($query->num_rows()<=0) return null;
		$result=$query->row_array(0);
		return array("k"=>$result['k'],"ext"=>$result['ext']);
	}

	function exists($key)
	{
		$query=$this->db->get_where($this->imgTableName,array('k'=>$key));
		if ($query->num_rows()<=0) return FALSE;
		$result=$query->row_array(0);
		if ($result['status']==1) return TRUE;
		return FALSE;
	}

	function getTIDByKey($key)
	{
		$query=$this->db->get_where($this->imgTableName,array('k'=>$key));
		if ($query->num_rows()<=0) return 0;
		$result=$query->row_array(0);
		return $result['tid'];
	}

	function getExt($key)
	{
		$query=$this->db->get_where($this->imgTableName,array('k'=>$key));
		if ($query->num_rows()<=0) return "";
		$result=$query->row_array(0);
		return $result['ext'];
	}

	function sign($key,$oldKey='0')
	{
		if (strlen($oldKey)>2)
		$this->db->update($this->imgTableName,array('status'=>2),array('k'=>$oldKey));	
		$this->db->update($this->imgTableName,array('status'=>1),array('k'=>$key));	
	}
	

}

/* End of file avatarm.php */
/* Location: ./application/models/avatarm.php */
