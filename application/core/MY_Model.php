<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_MODEL extends CI_MODEL
{

	function _where($tb, $d) {
		$sqls="select * from `$tb` where true ";
		foreach ($d as $key => $value) {
			$sqls.="and `$tb`.`$key`='$value' ";
		}
		return $this->db->sql($sqls);
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
		return $this->db->sql($sqls);
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
		return $this->db->sql($sqls."($keys) values($values)");
	}

	function _delete($tb, $d) {
		$sqls="delete from `$tb` where true ";
		foreach ($d as $key => $value) {
			$sqls.="and `$tb`.`$key`='$value' ";
		}
		return $this->db->sql($sqls);
	}

}
