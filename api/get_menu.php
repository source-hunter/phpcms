<?php
/**
 * ��ȡ�����˵��ӿ�
 */
defined('IN_PHPCMS') or exit('No permission resources.'); 
if(!$_GET['callback'] || !$_GET['act'])  showmessage(L('error'));

switch($_GET['act']) {
	case 'ajax_getlist':
		ajax_getlist();
	break;
	
	case 'ajax_getpath':
		ajax_getpath($_GET['parentid'],$_GET['keyid'],$_GET['callback'],$_GET['path']);
	break;	
	case 'ajax_gettopparent':
		ajax_gettopparent($_GET['id'],$_GET['keyid'],$_GET['callback'],$_GET['path']);
	break;		
}


/**
 * ��ȡ�����б�
 */
function ajax_getlist() {

	$cachefile = $_GET['cachefile'];
	$cachefile = str_replace(array('/', '//'), '', $cachefile);
	//$cachefile = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $cachefile);
	$path = $_GET['path'];
	$path = str_replace(array('/', '//'), '', $path);
	//$path = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $path);
	$title = $_GET['title'];
	$key = $_GET['key'];
	$infos = getcache($cachefile,$path);
	$where_id = intval($_GET['parentid']);
	$parent_menu_name = ($where_id==0) ? '' : trim($infos[$where_id][$key]);
	foreach($infos AS $k=>$v) {
		if($v['parentid'] == $where_id) {
			if ($v['parentid']) $parentid = $infos[$v['parentid']]['parentid'];
			$s[]=iconv(CHARSET,'utf-8',$v['catid'].','.trim($v[$key]).','.$v['parentid'].','.$parent_menu_name.','.$parentid);
		}
	}
	if(count($s)>0) {
		$jsonstr = json_encode($s);
		echo trim_script($_GET['callback']).'(',$jsonstr,')';
		exit;			
	} else {
		echo trim_script($_GET['callback']).'()';exit;			
	}
}

/**
 * ��ȡ��������·��·��
 * @param $parentid ����ID
 * @param $keyid �˵�keyid
 * @param $callback json����callback����
 * @param $result �ݹ鷵�ؽ������
 * @param $infos
 */
function ajax_getpath($parentid,$keyid,$callback,$path = 'commons',$result = array(),$infos = array()) {
	$path = str_replace(array('/', '//'), '', $path);
	$keyid = str_replace(array('/', '//'), '', $keyid);
	$parentid = intval($parentid);
	if(!$infos) {
		$infos = getcache($keyid,$path);
	}
	if(array_key_exists($parentid,$infos)) {
		$result[]=iconv(CHARSET,'utf-8',trim($infos[$parentid]['catname']));
		return ajax_getpath($infos[$parentid]['parentid'],$keyid,$callback,$path,$result,$infos);
	} else {
		if(count($result)>0) {
			krsort($result);
			$jsonstr = json_encode($result);
			echo trim_script($callback).'(',$jsonstr,')';
			exit;
		} else {
			$result[]=iconv(CHARSET,'utf-8',$datas['title']);
			$jsonstr = json_encode($result);
			echo trim_script($callback).'(',$jsonstr,')';
			exit;
		}
	}
}
/**
 * ��ȡ��������ID
 * Enter description here ...
 * @param  $linkageid �˵�id
 * @param  $keyid �˵�keyid
 * @param  $callback json����callback����
 * @param  $infos �ݹ鷵�ؽ������
 */
function ajax_gettopparent($id,$keyid,$callback,$path,$infos = array()) {
	$path = str_replace(array('/', '//'), '', $path);
	$keyid = str_replace(array('/', '//'), '', $keyid);
	$id = intval($id);
	if(!$infos) {
		$infos = getcache($keyid,$path);
	}
	if($infos[$id]['parentid']!=0) {
		return ajax_gettopparent($infos[$id]['parentid'],$keyid,$callback,$path,$infos);
	} else {
		echo trim_script($callback).'(',$id,')';
		exit;		
	}
}
?>