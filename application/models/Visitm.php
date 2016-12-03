<?php
/**
 * Filename: visitm.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    Idea
 * @subpackage Model
 */

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Model: VisitM
 *
 * @package    Idea
 * @subpackage Model
 */

class VisitM extends CI_Model {
	/**
	* @access private
	* @var    string
	*/
	var $logTableName = "log";
	/**
	*
	* VisitM构造函数
	*
	*/
	function __constuct()
	{
		parent::__constuct();
	}	

	function visit($what,$request)
	{
		$query = $this->db->get_where($this->logTableName, array('what'=>$what,'request'=>$request));
		if ($query->num_rows() > 0) 
		{
			$result=$query->row_array();
			$this->db->update($this->logTableName,array('auth'=>$result['auth']+1),array("id"=>$result['id']));		
			return TRUE;
		} 
		else 
		{
			$this->db->insert($this->logTableName,array('auth'=>1,'what'=>$what,'request'=>$request));		
			return FALSE;
		}		
	}

	function getCNT($what,$request)
	{
		$query = $this->db->get_where($this->logTableName, array('what'=>$what,'request'=>$request));
		if ($query->num_rows() > 0) 
		{
			$result=$query->row_array();
			return $result['auth'];
		} 
		else 
		{
			return 0;
		}				
	}
	

}

/* End of file visitm.php */
/* Location: ./application/models/visitm.php */
