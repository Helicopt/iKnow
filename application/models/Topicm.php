<?php
/**
 * Filename: topicm.php
 *
 * @author     helicopter <fwtt20071028@126.com>
 * @version    1.0
 * @package    Idea
 * @subpackage Model
 */

defined('BASEPATH') OR exit('No direct script access allowed');

define('sPENDING', 1);
define('sFINISHED', 2);
define('sCLOSED', 4);

/**
 * Model: TopicM
 *
 * @package    Idea
 * @subpackage Model
 */

class TopicM extends CI_Model {
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
	* @access private
	* @var    string
	*/
	var $ansTableName = "answer";
	/**
	* @access private
	* @var    string
	*/
	var $cmtTableName = "comment";
	/**
	* @access private
	* @var    string
	*/
	var $infoTableName = "info";
	/**
	* @access private
	* @var    string
	*/
	var $tagTableName = "tag";
	/**
	*
	* TopicM构造函数
	*
	*/
	function __constuct()
	{
		parent::__constuct();
	}	

	function addInfo($val) {
		$this->sqlm->_insert($this->infoTableName,array('value'=>$val));
		return $this->db->insert_id();
	}

	function topic_exists($tid) {
		$query=$this->sqlm->_where($this->topicTableName,array('id'=>$tid));
		return $query->num_rows();
	}


	function getInfoById($iid) {
		$query=$this->sqlm->_where($this->infoTableName,array('id'=>$iid));
		$html="";
		if ($query->num_rows()>0) {
			$html=$query->row_array(0);
		}
		return $html['value'];
	}

	function getTags() {
		$query=$this->sqlm->_where($this->tagTableName,array());
		return $query->result_array();
	}
	
	function addQ($uid,$info)
	{
		$data=array();
		$data['title']=$info['title'];
		$data['infoid']=$this->addInfo($info['html']);
		$data['author']=$uid;
		$data['status']=sPENDING;
		$data['views']=0;
		$data['actTime']=time();
		$this->sqlm->_insert($this->topicTableName, $data);		
		return $this->db->insert_id();
	}

	function addA($uid,$info)
	{
		$data=array();
		$data['topicid']=$info['tid'];
		$data['infoid']=$this->addInfo($info['html']);
		$data['author']=$uid;
		$data['status']=sPENDING;
		$data['actTime']=time();
		$this->sqlm->_insert($this->ansTableName, $data);		
		return $this->db->insert_id();
	}


	function addC($uid,$info)
	{
		$data=array();
		$data['ansid']=$info['aid'];
		$data['txt']=$info['val'];
		$data['author']=$uid;
		$data['status']=sPENDING;
		$this->sqlm->_insert($this->cmtTableName, $data);
		return $this->db->insert_id();
	}

	function updateAct($id,$tst)
	{
		$this->db->update($this->topicTableName, array("actTime"=>$tst), array('id'=>$id));	
		$query=$this->db->get_where($this->topicTableName,array('id'=>$id));
		if ($query->num_rows()>0)
		{
			$r=$query->row_array(0);
			if ($r['parent']>0) $this->updateAct($r['parent'],$tst);
		}
	}

	function briefTalk($d)
	{
		$res=array("id"=>$d['id'],"type"=>$d['type']&1,"owner"=>$this->userm->getAvatar($d['owner']),"title"=>$d['title'],"desc"=>$d['_desc'],
			'brief'=>$this->userm->briefInfo($d['owner']),'mid'=>$this->userm->midInfo($d['owner']),
			'author'=>$this->userm->authInfo($d['owner']),"parent"=>$d['parent'],"s"=>$d['status'],"dep"=>$d['dep']);
		if ($d['dep']<=0)
		{
			$tmp=$this->imgm->getImgByTID($d['id']);
			if ($tmp!=null)
			{
				$res['ext']=$tmp['ext'];
				$res['k']=$tmp['k'];
			}
			else $res['k']=0;
		}
		return $res;
	}

	// function getSub($id)
	// {
	// 	$ans = array();
	// 	$subQ = array();
	// 	$query=$this->db->order_by('id','ASC')->get_where($this->topicTableName,array('parent'=>$id));		
	// 	$cnt=$query->num_rows();
	// 	for ($i=0;$i<$cnt;++$i)
	// 	{
	// 		$result=$query->row_array($i);
	// 		$type=$result['type'];
	// 		if (!($type&tVISIBLE)) continue;
	// 		if ($type&1) $subQ["t".$i]=$this->briefTalk($result);
	// 		else $ans["t".$i]=$this->briefTalk($result);
	// 	}
	// 	return array('ans' => $ans, 'subQ' => $subQ);
	// }

	function viewAnsOfTalk($uid,$tid) {
		$query=$this->sqlm->_where($this->ansTableName,array('topicid'=>$tid));
		$cnt=$query->num_rows();
		$res=array();
		for ($i=0;$i<$cnt;++$i) {
			$it=$query->row_array($i);
			$item=$it;
			$item['author_info']=$this->userm->getDetails($it['author']);
			$item['html']=$this->getInfoById($it['infoid']);
			$res[]=$item;
		}
		return $res;
	}

	function viewCmtOfAns($uid,$tid) {
		$query=$this->sqlm->_where($this->cmtTableName,array('ansid'=>$tid));
		$cnt=$query->num_rows();
		$res=array();
		for ($i=0;$i<$cnt;++$i) {
			$it=$query->row_array($i);
			$item=$it;
			$item['author_info']=$this->userm->getDetails($it['author']);
			$item['txt']=$it['txt'];
			$res[]=$item;
		}
		return $res;
	}

	function viewTalk($uid,$id)
	{

		$returnArray = array();
			$query=$this->sqlm->_where($this->topicTableName,array('id'=>$id));
			if ($query->num_rows()<=0) return null;
			$result=$query->row_array(0);
			$type=$result['status'];
			if (($type&sCLOSED)&&!$this->userm->isAdmin()) return null;
			$returnArray['createTime'] = $result['createTime'];
			$returnArray['actTime'] = $result['actTime'];
			$returnArray['title'] = $result['title'];
			$returnArray['html'] = $this->getInfoById($result['infoid']);
			$returnArray['author'] = $result['author'];
			$returnArray['author_info'] = $this->userm->getDetails($result['author']);
			$returnArray['s'] = $result['status'];
			// $sub=$this->getSub($id);
			// $returnArray['subQ'] = $sub['subQ'];

		return $returnArray;
	}
		
	function viewTalkInfo($id)
	{

		$returnArray = array();
			$query=$this->db->get_where($this->topicTableName,array('id'=>$id));
			if ($query->num_rows()<=0) return null;
			$result=$query->row_array();
			$type=$result['type'];
			if (!($type&tVISIBLE)) return null;
			$returnArray['owner'] = $result['owner'];
			$returnArray['dep'] = $result['dep'];
			$returnArray['s'] = $result['status'];
			$returnArray['parent'] = $result['parent'];
			$returnArray['type'] = $type&1;
		return $returnArray;
	}

	function getOwner($id)
	{
		$query=$this->db->get_where($this->topicTableName,array('id'=>$id));
		if ($query->num_rows()>0) 
		{
			$res=$query->row_array();
			return $res['owner'];
		}
		else return -1;
	}

	function dismiss($tid)
	{
		$query=$this->db->get_where($this->topicTableName,array('parent'=>$tid));
		
		$cnt=$query->num_rows();
		for ($i=0;$i<$cnt;++$i)
		{
			$res=$query->row_array($i);
			$s=$res['status'];
			if (($s&sPENDING)==0) $s=$s+sPENDING;
			$todo=array('status'=>($s-sPENDING)|sFINISHED);
			$tp=$res['type']&(~tVISIBLE);
			$todo['type']=$tp;
			$this->db->update($this->topicTableName,$todo,array('id'=>$res['id']));	
			$this->dismiss($res['id']);
		}
	}

	function wrapTalk($uid,$tid,$t)
	{
		if ($t==null) return 2;
		$q=$this->db->get_where($this->topicTableName,array('id'=>$tid));
		if ($q->num_rows()<=0) return 2;
		$r=$q->row_array(0);
		$s=$r['status'];
		if (($s&sPENDING)==0) return 2;
		$this->db->update($this->topicTableName,array('status'=>($s-sPENDING)|sFINISHED),array('id'=>$tid));
		$query=$this->db->get_where($this->topicTableName,array('parent'=>$tid));
		$cnt=$query->num_rows();
		for ($i=0;$i<$cnt;++$i)
		{
			$res=$query->row_array($i);
			if (!isset($t[$res['id']])) return 2;
		}
		for ($i=0;$i<$cnt;++$i)
		{
			$res=$query->row_array($i);
			$s=$res['status'];
			if (($s&sPENDING)==0) $s=$s+sPENDING;
			$todo=array('status'=>($s-sPENDING)|sFINISHED);
			if ($t[$res['id']]==0)
			{
				$tp=$res['type']&(~tVISIBLE);
				$todo['type']=$tp;
			}
			$this->db->update($this->topicTableName,$todo,array('id'=>$res['id']));	
			$this->dismiss($res['id']);
		}
		return 1;
	}

	// function setProfile($id,$info)
	// {
	// 	$query=$this->db->get_where($this->userTableName,array('id'=>$id));
	// 	if ($query->num_rows()<=0){
	// 		return FALSE;
	// 	}
	// 	$data=array();
	// 	if (isset($info['nick'])) $data['nick']=$info['nick'];
	// 	if (isset($info['college'])) $data['college']=$info['college'];
	// 	if (isset($info['major'])) $data['major']=$info['major'];
	// 	if (isset($info['sign'])) $data['sign']=$info['sign'];
	// 	if (isset($info['grade'])) $data['grade']=$info['grade'];
	// 	$this->db->update($this->userTableName,$data,array('id'=>$id));
	// 	return TRUE;
	// }

	function getDefaultPage($cnt)
	{
		return $this->getLatest($cnt,0,TRUE,sPENDING);
	}

	function getWorksPage($cnt)
	{
		return $this->getLatest($cnt,0,TRUE,sFINISHED);
	}
	
	function getMyDefault($cnt,$id)
	{
		return $this->getLatest($cnt,$id,FALSE,sPENDING);
	}

	function getMyWorks($cnt,$id)
	{
		return $this->getLatest($cnt,$id,FALSE,sFINISHED);
	}
	
	function getLatest($cnt,$id=0,$isRoot=TRUE,$status=2147483647,$type=2147483647)
	{
		//echo "SELECT * FROM ".$this->topicTableName." WHERE ".(($isRoot)?"parent=0 AND":"")." (status&".(string)$status.")>0 AND (type&".(string)$type.")>0 AND (type&".(string)tVISIBLE.")>0 AND id=".(string)$id." ORDER BY actTime DESC LIMIT 0,".$cnt;
		if ($id>0) $query=$this->db->query("SELECT * FROM ".$this->topicTableName." WHERE ".(($isRoot)?"parent=0 AND":"")." (status&".(string)$status.")>0 AND (type&".(string)$type.")>0 AND (type&".(string)tVISIBLE.")>0 AND owner=".(string)$id." ORDER BY actTime DESC LIMIT 0,".$cnt);
		else $query=$this->db->query("SELECT * FROM ".$this->topicTableName." WHERE ".(($isRoot)?"parent=0 AND":"")." (status&".(string)$status.")>0 AND (type&".(string)$type.")>0 AND (type&".(string)tVISIBLE.")>0 ORDER BY actTime DESC LIMIT 0,".$cnt);
		$res=array();
		//echo $query->num_rows();
		for ($i=0;$i<$query->num_rows();++$i)
		{
			$result=$query->row_array($i);
			$res['t'.(string)$i]=$this->briefTalk($result);
		}
		return $res;		
	}	

}

/* End of file topicm.php */
/* Location: ./application/models/topicm.php */
