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

define('tVISIBLE', 4);
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
	*
	* TopicM构造函数
	*
	*/
	function __constuct()
	{
		$this->load->model('userm');
		$this->load->model('imgm');
		parent::__constuct();
	}	
	
	function addTalk($id,$info,$parent=0)
	{
		if ($parent>0)
		{
			$p=$this->viewTalkInfo($parent);	
			if ((((int)$p['s'])&sPENDING)==0) return -1;
		}
		$data=array();
		$data['title']=$info['title'];
		$data['_desc']=$info['desc'];
		$data['owner']=$id;
		$data['parent']=$parent;
		if ($parent==0) $data['dep']=0; else $data['dep']=$p['dep']+1;
		$data['status']=sPENDING;
		if (isset($info['type'])) $data['type']=$info['type']; else $data['type']=1;
		$data['type']|=tVISIBLE;
		$tst=date('Y-m-d H:i:s');
		$data['actTime']=$tst;
		$this->updateAct($parent,$tst);
		$this->db->insert($this->topicTableName, $data);		
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

	function getSub($id)
	{
		$ans = array();
		$subQ = array();
		$query=$this->db->order_by('id','ASC')->get_where($this->topicTableName,array('parent'=>$id));		
		$cnt=$query->num_rows();
		for ($i=0;$i<$cnt;++$i)
		{
			$result=$query->row_array($i);
			$type=$result['type'];
			if (!($type&tVISIBLE)) continue;
			if ($type&1) $subQ["t".$i]=$this->briefTalk($result);
			else $ans["t".$i]=$this->briefTalk($result);
		}
		return array('ans' => $ans, 'subQ' => $subQ);
	}

	function viewTalk($uid,$id)
	{

		$returnArray = array();
			$query=$this->db->get_where($this->topicTableName,array('id'=>$id));
			if ($query->num_rows()<=0) return null;
			$result=$query->row_array(0);
			$type=$result['type'];
			if (!($type&tVISIBLE)) return null;
			$returnArray['title'] = $result['title'];
			$returnArray['desc'] = $result['_desc'];
			$returnArray['owner'] = $result['owner'];
			$returnArray['parent'] = $result['parent'];
			$returnArray['dep'] = $result['dep'];
			$returnArray['author'] = $this->userm->authInfo($result['owner']);
			$returnArray['brief'] = $this->userm->briefInfo($result['owner']);
			$returnArray['s'] = $result['status'];
			$returnArray['type'] = $type&1;
			$sub=$this->getSub($id);
			$returnArray['subQ'] = $sub['subQ'];
		if ($result['dep']<=0)
		{
			$tmp=$this->imgm->getImgByTID($result['id']);
			if ($tmp!=null)
			{
				$returnArray['ext']=$tmp['ext'];
				$returnArray['k']=$tmp['k'];
			}
			else $returnArray['k']=0;
		}
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
