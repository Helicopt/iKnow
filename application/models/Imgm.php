<?php
/**
 * Filename: imgm.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    iKnow
 * @subpackage Model
 */

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Model: ImgM
 *
 * @package    iKnow
 * @subpackage Model
 */

class ImgM extends CI_Model {
	/**
	* @access private
	* @var    string
	*/
	var $imgTableName = "img";

	/**
	*
	* ImgM构造函数
	*
	*/
	function __constuct()
	{
		parent::__constuct();
	}	

	function addImg($uid,$ext)
	{
		$data=array();
		date_default_timezone_set('PRC');
		$tst=date('Y-m-d H:i:s');
		$data['hash']=md5(($uid.$tst));
		$data['ext']=$ext;
		$this->sqlm->_insert($this->imgTableName, $data);		
		return $data['hash'];
	}


	function check($key)
	{
		$query=$this->sqlm->_where($this->imgTableName,array('hash'=>$key));
		if ($query->num_rows()<=0) return FALSE;
		return TRUE;
	}

	// function getImgByTID($id)
	// {
	// 	$query=$this->db->get_where($this->imgTableName,array('tid'=>$id,'status'=>1));
	// 	if ($query->num_rows()<=0) return null;
	// 	$result=$query->row_array(0);
	// 	return array("k"=>$result['k'],"ext"=>$result['ext']);
	// }

	// function exists($key)
	// {
	// 	$query=$this->db->get_where($this->imgTableName,array('k'=>$key));
	// 	if ($query->num_rows()<=0) return FALSE;
	// 	$result=$query->row_array(0);
	// 	if ($result['status']==1) return TRUE;
	// 	return FALSE;
	// }

	function getExt($key)
	{
		$query=$this->sqlm->_where($this->imgTableName,array('hash'=>$key));
		if ($query->num_rows()<=0) return "";
		$result=$query->row_array(0);
		return $result['ext'];
	}

	// function sign($key)
	// {
	// 	$this->db->update($this->imgTableName,array('status'=>1),array('k'=>$key));	
	// }
	

}

/* End of file imgm.php */
/* Location: ./application/models/imgm.php */
