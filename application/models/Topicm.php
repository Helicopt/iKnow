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
	var $focusTableName = "focus";
	/**
	* @access private
	* @var    string
	*/
	var $fieldTableName = "field";
	/**
	* @access private
	* @var    string
	*/
	var $assessTableName = "assess";
	/**
	* @access private
	* @var    string
	*/
	var $favTableName = "favor";
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

	function getTTags($tid) {
		$query=$this->db->query("SELECT `tgid`,`title` from ".$this->fieldTableName.
			" join ".$this->tagTableName." on `tgid`=`id` where `tpid`=".$tid);
		return $query->result_array();
	}

	function addTags($tid,$tgid) {
		$this->sqlm->_insert($this->fieldTableName,array('tgid'=>$tgid,'tpid'=>$tid));
		return $this->db->affected_rows();
	}
	
	function focusTags($uid,$tgid) {
		$k=$this->sqlm->_where($this->focusTableName,array('tgid'=>$tgid,'uid'=>$uid))->num_rows();
		if ($k==0) {
			$this->sqlm->_insert($this->focusTableName,array('tgid'=>$tgid,'uid'=>$uid));
			return $this->db->affected_rows();
		} else return 0;
	}
	
	function loseTags($uid,$tgid) {
		$this->sqlm->_delete($this->focusTableName,array('tgid'=>$tgid,'uid'=>$uid));
		return $this->db->affected_rows();
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

	function editQ($uid,$info)
	{
		if ($this->getOwner($info['tid'])!=$uid&&!$this->userm->belongTo($uid,'admin')) return false;
		$data=array();
		$data['title']=$info['title'];
		$data['infoid']=$this->addInfo($info['html']);
		$data['actTime']=time();
		$this->sqlm->_update($this->topicTableName, $data, array('id'=>$info['tid']));		
		return $this->db->affected_rows();
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

	function getOneQue($uid) {
		$query=$this->sqlm->_where($this->topicTableName,array('author'=>$uid));
		$cnt=$query->num_rows();
		$res=array();
		for ($i=0;$i<$cnt;++$i) {
			$it=$query->row_array($i);
			$res[]=array(
				'id'=>$it['id'],
				'title'=>$it['title'],
				'time'=>$it['createTime']
				);
		}
		return $res;
	}

	function getTitle($tid) {
		// $q=$this->db->query("call `get_title`($tid)");
		$q=$this->db->query("select `title` from topic where id='$tid'");
		$res="";
		if ($q->num_rows()>0) {
			$res=$q->row_array(0)['title'];
		}
		return $res;
	}

	function getOneTags($uid) {
		$query=$this->db->query("SELECT `tgid`,`title` from ".$this->focusTableName." join ".$this->tagTableName
			." on `tgid`=`id` where `uid`='$uid'");
		$cnt=$query->num_rows();
		$res=array();
		for ($i=0;$i<$cnt;++$i) {
			$it=$query->row_array($i);
			$res[]=array(
				'id'=>$it['tgid'],
				'title'=>$it['title'],
				);
		}
		return $res;
	}


	function getOneAns($uid) {
		$query=$this->sqlm->_where($this->ansTableName,array('author'=>$uid));
		$cnt=$query->num_rows();
		$res=array();
		$tmp=$query->result_array();
		for ($i=0;$i<$cnt;++$i) {
			$it=$tmp[$i];
			$res[]=array(
				'id'=>$it['topicid'],
				'title'=>$this->getTitle($it['topicid']),
				'time'=>$it['createTime']
				);
		}
		return $res;
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

	function getAgree($aid) {
		$query=$this->db->query("SELECT * from ".$this->assessTableName." where ansid='$aid' and value='agree'");
		return $query->num_rows();
	}

	function getDisagree($aid) {
		$query=$this->db->query("SELECT * from ".$this->assessTableName." where ansid='$aid' and value='disagree'");
		return $query->num_rows();
	}

	function zan($uid, $aid) {
		$query=$this->db->query("SELECT * from ".$this->assessTableName." where ansid='$aid' and uid='$uid'");
		$cnt=$query->num_rows();
		if ($cnt==0) {
			$this->sqlm->_insert($this->assessTableName,array('uid'=>$uid,'ansid'=>$aid,'value'=>'agree'));
			return 1;
		} else {
			if ($query->row_array(0)['value']=='disagree') {
				$this->sqlm->_update($this->assessTableName,array('value'=>'agree'),array('uid'=>$uid,'ansid'=>$aid));
				return 2;
			} else return 0;
		}
		return -1;
	}


	function cai($uid, $aid) {
		$query=$this->db->query("SELECT * from ".$this->assessTableName." where ansid='$aid' and uid='$uid'");
		$cnt=$query->num_rows();
		if ($cnt==0) {
			$this->sqlm->_insert($this->assessTableName,array('uid'=>$uid,'ansid'=>$aid,'value'=>'disagree'));
			return 1;
		} else {
			if ($query->row_array(0)['value']=='agree') {
				$this->sqlm->_update($this->assessTableName,array('value'=>'disagree'),array('uid'=>$uid,'ansid'=>$aid));
				return 2;
			} else return 0;
		}
		return -1;
	}

	function viewAnsOfTalk($uid,$tid) {
		$query=$this->sqlm->_where($this->ansTableName,array('topicid'=>$tid));
		$cnt=$query->num_rows();
		$res=array();
		for ($i=0;$i<$cnt;++$i) {
			$it=$query->row_array($i);
			$item=$it;
			$item['author_info']=$this->userm->getDetails($it['author']);
			$item['html']=$this->getInfoById($it['infoid']);
			$item['ag']=$this->getAgree($it['id']);
			$item['da']=$this->getDisagree($it['id']);
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

	function isFavor($uid,$tid) {
		$query=$this->sqlm->_where($this->favTableName,array('uid'=>$uid,'tpid'=>$tid));
		return $query->num_rows();		
	}
	function dofavor($uid,$tid) {
		if ($tid=='0') {
			return 0;
		}
		else {
			$query=$this->sqlm->_insert($this->favTableName,array('uid'=>$uid,'tpid'=>$tid));
			return $this->db->affected_rows();
		}
	}

	function unfavor($uid,$tid) {
		if ($tid=='0') {
			return 0;
		}
		else {
			$query=$this->sqlm->_delete($this->favTableName,array('uid'=>$uid,'tpid'=>$tid));
			return $this->db->affected_rows();
		}
	}

	function closed($tid) {
		$query=$this->db->query("SELECT * from ".$this->topicTableName." WHERE id='$tid' and is_closed(status)");						
		if ($query->num_rows()>0) return true;
		else return false;
	}

	function viewTalk($uid,$id)
	{

		$returnArray = array();
			//$query=$this->sqlm->_where($this->topicTableName,array('id'=>$id));
			//$type=$result['status'];
			//if (($type&sCLOSED)&&!$this->userm->belongTo($uid,'admin')) return null;
			if ($this->userm->belongTo($uid,'admin')) {
				$query=$this->sqlm->_where($this->topicTableName,array('id'=>$id));
			} else {
				$query=$this->db->query("SELECT * from ".$this->topicTableName." WHERE id='$id' and NOT is_closed(status)");				
			}
			if ($query->num_rows()<=0) return '';
			$result=$query->row_array(0);
			$returnArray['createTime'] = $result['createTime'];
			$returnArray['actTime'] = $result['actTime'];
			$returnArray['title'] = $result['title'];
			$returnArray['html'] = $this->getInfoById($result['infoid']);
			$returnArray['author'] = $result['author'];
			$returnArray['author_info'] = $this->userm->getDetails($result['author']);
			$returnArray['s'] = $result['status'];
			$returnArray['tags'] = $this->getTTags($result['id']);
			$returnArray['favor'] = $this->isFavor($uid,$result['id'])?'yes':'no';

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
		$query=$this->sqlm->_where($this->topicTableName,array('id'=>$id));
		if ($query->num_rows()>0) 
		{
			$res=$query->row_array();
			return $res['author'];
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

	function all_rec($uid) {
		$query=$this->db->query("SELECT * from topic order by actTime desc");
		$cnt=$query->num_rows();
		$res=array();
		for ($i=0;$i<$cnt;++$i) {
			$it=$query->row_array($i);
			$res[]=array('id'=>$it['id'],'title'=>$it['title'],'actTime'=>$it['actTime'],'createTime'=>$it['createTime'],
				'author'=>$it['author'],'author_info'=>$this->userm->getDetails($it['author']));
		}
		return $res;
	}

	function peo_rec($uid) {
		$query=$this->db->query("SELECT * from topic where author in (select userid from follow where followerid='$uid')  order by actTime desc");
		$cnt=$query->num_rows();
		$res=array();
		for ($i=0;$i<$cnt;++$i) {
			$it=$query->row_array($i);
			$res[]=array('id'=>$it['id'],'title'=>$it['title'],'actTime'=>$it['actTime'],'createTime'=>$it['createTime'],
				'author'=>$it['author'],'author_info'=>$this->userm->getDetails($it['author']));
		}
		return $res;
	}

	function fav_rec($uid) {
		$query=$this->db->query("SELECT * from topic where id in (select tpid from favor where uid='$uid') order by actTime desc");
		$cnt=$query->num_rows();
		$res=array();
		for ($i=0;$i<$cnt;++$i) {
			$it=$query->row_array($i);
			$res[]=array('id'=>$it['id'],'title'=>$it['title'],'actTime'=>$it['actTime'],'createTime'=>$it['createTime'],
				'author'=>$it['author'],'author_info'=>$this->userm->getDetails($it['author']));
		}
		return $res;
	}

	function fie_rec($uid) {
		$res=array();
		$query1=$this->db->query("SELECT tgid from focus where uid='$uid'");
		$cnt1=$query1->num_rows();
		for ($i=0;$i<$cnt1;++$i) {
			$it1=$query1->row_array($i)['tgid'];
			$query2=$this->db->query("SELECT * from field join topic on tpid=topic.id where tgid='$it1'");
			$cnt2=$query2->num_rows();
			for ($j=0;$j<$cnt2;++$j) {
				$it=$query2->row_array($j);
				$res[]=array('id'=>$it['id'],'title'=>$it['title'],'actTime'=>$it['actTime'],'createTime'=>$it['createTime'],
					'author'=>$it['author'],'author_info'=>$this->userm->getDetails($it['author']));
			}
		}
		return $res;
	}

	function sort_rec($uid) {
		$res=array();
		$query1=$this->db->query("SELECT * from tag");
		$cnt1=$query1->num_rows();
		for ($i=0;$i<$cnt1;++$i) {
			$it1=$query1->row_array($i);
			$tg=$it1['id'];
			$res2=array();
			$query2=$this->db->query("SELECT * from field join topic on tpid=topic.id where tgid='$tg'");
			$cnt2=$query2->num_rows();
			for ($j=0;$j<$cnt2;++$j) {
				$it=$query2->row_array($j);
				$res2[]=array('id'=>$it['id'],'title'=>$it['title'],'actTime'=>$it['actTime'],'createTime'=>$it['createTime'],
					'author'=>$it['author'],'author_info'=>$this->userm->getDetails($it['author']));
			}
			$res[]=array('tag'=>$it1,'info'=>$res2);
		}
		return $res;
	}

}

/* End of file topicm.php */
/* Location: ./application/models/topicm.php */
