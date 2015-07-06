<?php
/**
 * �ղ�url�������¼
 * @param url ��ַ����urlencode����ֹ�������
 * @param title ���⣬��urlencode����ֹ�������
 * @return {1:�ɹ�;-1:δ��¼;-2:ȱ�ٲ���}
 */
defined('IN_PHPCMS') or exit('No permission resources.');

if(empty($_GET['title']) || empty($_GET['url'])) {
	exit('-2');	
} else {
	$title = $_GET['title'];
	$title = addslashes(urldecode($title));
	if(CHARSET != 'utf-8') {
		$title = iconv('utf-8', CHARSET, $title);
		$title = addslashes($title);
	}
	
	$title = new_html_special_chars($title);
	$url = safe_replace(addslashes(urldecode($_GET['url'])));
	$url = trim_script($url);
}
$_GET['callback'] = safe_replace($_GET['callback']);
//�ж��Ƿ��¼	
$phpcms_auth = param::get_cookie('auth');
if($phpcms_auth) {
	list($userid, $password) = explode("\t", sys_auth($phpcms_auth, 'DECODE', get_auth_key('login')));
	$userid = intval($userid);
	if($userid >0) {

	} else {
		exit(trim_script($_GET['callback']).'('.json_encode(array('status'=>-1)).')');
	} 
} else {
	exit(trim_script($_GET['callback']).'('.json_encode(array('status'=>-1)).')');
}

$favorite_db = pc_base::load_model('favorite_model');
$data = array('title'=>$title, 'url'=>$url, 'adddate'=>SYS_TIME, 'userid'=>$userid);
//����url�ж��Ƿ��Ѿ��ղع���
$is_exists = $favorite_db->get_one(array('url'=>$url, 'userid'=>$userid));
if(!$is_exists) {
	$favorite_db->insert($data);
}
exit(trim_script($_GET['callback']).'('.json_encode(array('status'=>1)).')');

?>