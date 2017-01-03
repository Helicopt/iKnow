<?php
/**
 * Filename: edum.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    Iknow
 * @subpackage Model
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model: EduM
 *
 * @package    Iknow
 * @subpackage Model
 */

class EduM extends CI_Model {
	/**
	* @access private
	* @var    string
	*/
	var $eduTableName = "edu";
	/**
	* @access private
	* @var    string
	*/
	var $collegeTableName = "college";
	/**
	* @access private
	* @var    string
	*/
	var $majorTableName = "major";
	/**
	*
	* EduM构造函数
	*
	*/
	function __constuct()
	{
		parent::__constuct();
	}

	function getAllCollege() {
		$query=$this->sqlm->_where($this->collegeTableName,array());
		$res=$query->result_array();
		return $res;
	}
	
	function getAllMajor() {
		$query=$this->sqlm->_where($this->majorTableName,array());
		$res=$query->result_array();
		return $res;
	}
	
	function getEduById($uid) {
		$query=$this->db->query("SELECT `edu`.`id`,`college`.`title` as `col`,`major`.`title` as `maj`  FROM `edu`  join `college` join `major` 
			on `edu`.`cid`=`college`.`id` and `edu`.`mid`=`major`.`id` WHERE `uid`='$uid'");
		$res=$query->result_array();
		return $res;
	}

	function editEduById($eid,$cid,$mid,$uid) {
		$k=array();
		if ($cid!=0) $k['cid']=$cid;
		if ($mid!=0) $k['mid']=$mid;
		$query=$this->sqlm->_update($this->eduTableName,$k,array('id'=>$eid,'uid'=>$uid));
		return $this->db->affected_rows();
	}
	
	function addEduById($uid,$cid=1,$mid=1) {
		$query=$this->sqlm->_insert($this->eduTableName,array('cid'=>$cid,'mid'=>$mid,'uid'=>$uid));		
		return $this->db->insert_id();
	}
	
	function delEduById($eid,$uid) {
		$query=$this->sqlm->_delete($this->eduTableName,array('id'=>$eid,'uid'=>$uid));		
		return $this->db->affected_rows();
	}
	

}

/* End of file edum.php */
/* Location: ./application/models/edum.php */
