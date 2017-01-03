<?php
/**
 * Filename: sqlm.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    Iknow
 * @subpackage Model
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model: SqlM
 *
 * @package    Iknow
 * @subpackage Model
 */

class SqlM extends CI_Model {

	/**
	*
	* SqlM构造函数
	*
	*/
	function __constuct()
	{
		parent::__constuct();
	}
	
	function _where($tb, $d) {
		$sqls="select * from `$tb` where true ";
		foreach ($d as $key => $value) {
			$sqls.="and `$tb`.`$key`='$value' ";
		}
		return $this->db->query($sqls);
	}

	function _update($tb, $d1, $d2) {
		$sqls="update `$tb` set ";
		foreach ($d1 as $key => $value) {
			$sqls.="`$key`='$value',";
		}
		if (substr($sqls, -1)==",") $sqls=substr($sqls, 0, -1);
		$sqls.=" where true ";
		foreach ($d2 as $key => $value) {
			$sqls.="and `$key`='$value' ";
		}
		return $this->db->query($sqls);
	}

	function _insert($tb, $d) {
		$sqls="insert into `$tb`";
		$keys="";
		$values="";
		$first=true;
		foreach ($d as $key => $value) {
			if (!$first) {
				$keys.=",";
				$values.=",";
			}
			$first=false;
			$keys.="`$key`";
			if ($value!=NULL) $values.="'$value'"; else $values.="NULL";
		}
		return $this->db->query($sqls."($keys) values($values)");
	}

	function _delete($tb, $d) {
		$sqls="delete from `$tb` where true ";
		foreach ($d as $key => $value) {
			$sqls.="and `$tb`.`$key`='$value' ";
		}
		return $this->db->query($sqls);
	}

}

/* End of file sqlm.php */
/* Location: ./application/models/sqlm.php */
