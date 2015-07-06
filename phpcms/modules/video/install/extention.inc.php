<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');
$parentid = $menu_db->insert(array('name'=>'video', 'parentid'=>'0', 'm'=>'video', 'c'=>'video', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'), true);
$parentid = $menu_db->insert(array('name'=>'video', 'parentid'=>$parentid, 'm'=>'video', 'c'=>'video', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'video_manage', 'parentid'=>$parentid, 'm'=>'video', 'c'=>'video', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'video_upload', 'parentid'=>$parentid, 'm'=>'video', 'c'=>'video', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'video_edit', 'parentid'=>$parentid, 'm'=>'video', 'c'=>'video', 'a'=>'edit', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'video_delete', 'parentid'=>$parentid, 'm'=>'video', 'c'=>'video', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$o_mid = $menu_db->insert(array('name'=>'video_open', 'parentid'=>$parentid, 'm'=>'video', 'c'=>'video', 'a'=>'open', 'data'=>'', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'video_inputinfo', 'parentid'=>$o_mid, 'm'=>'video', 'c'=>'video', 'a'=>'open', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'complete_info', 'parentid'=>$o_mid, 'm'=>'video', 'c'=>'video', 'a'=>'complete_info', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'subscribe_manage', 'parentid'=>$parentid, 'm'=>'video', 'c'=>'video', 'a'=>'subscribe_list', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'sub_delete', 'parentid'=>$parentid, 'm'=>'video', 'c'=>'video', 'a'=>'sub_del', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'import_ku6_video', 'parentid'=>$parentid, 'm'=>'video', 'c'=>'video', 'a'=>'import_ku6video', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'video_store', 'parentid'=>$parentid, 'm'=>'video', 'c'=>'video', 'a'=>'video2content', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'player_manage', 'parentid'=>$parentid, 'm'=>'video', 'c'=>'player', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'video_stat', 'parentid'=>$parentid, 'm'=>'video', 'c'=>'stat', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'));

if (module_exists('special')) {
	$special_db = pc_base::load_model('special_model');
	if( !$special_db->field_exists('aid') ){
		$menu_db->insert(array('name'=>'album_import', 'parentid'=>868, 'm'=>'special', 'c'=>'album', 'a'=>'import', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
		$special_db->query("ALTER TABLE  `phpcms_special` ADD  `aid` INT UNSIGNED NOT NULL DEFAULT  '0' AFTER  `siteid`");
	}
	if (!$special_db->field_exists('isvideo')) {
		$special_db->query("ALTER TABLE `phpcms_special` ADD `isvideo` TINYINT( 1 ) UNSIGNED NOT NULL");
	}
	$special_content_db = pc_base::load_model('special_content_model');
	if (!$special_content_db->field_exists('videoid')) {
		$special_content_db->query("ALTER TABLE `phpcms_special_content` ADD `videoid` INT UNSIGNED NOT NULL DEFAULT  '0'");
	}
}

//�滻category_videoģ���Ƽ�λֵ
$position_db = pc_base::load_model('position_model');
$position_1 = $position_db->insert(array('modelid'=>'0', 'catid'=>0, 'name'=>'��Ƶ��ҳ����ͼ�Ƽ�', 'maxnum'=>20, 'extention'=>'', 'listorder'=>'', 'siteid'=>1),true);
$position_2 = $position_db->insert(array('modelid'=>'0', 'catid'=>0, 'name'=>'��Ƶ��ҳͷ���Ƽ�', 'maxnum'=>20, 'extention'=>'', 'listorder'=>'', 'siteid'=>1),true);
$position_3 = $position_db->insert(array('modelid'=>'0', 'catid'=>0, 'name'=>'��Ƶ��ҳÿ���ȵ��Ƽ�', 'maxnum'=>20, 'extention'=>'', 'listorder'=>'', 'siteid'=>1),true);
$position_4 = $position_db->insert(array('modelid'=>'0', 'catid'=>0, 'name'=>'��Ƶ��Ŀ�����Ƽ�', 'maxnum'=>20, 'extention'=>'', 'listorder'=>'', 'siteid'=>1),true);

$tpl_file = PC_PATH.'templates'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'content'.DIRECTORY_SEPARATOR.'category_video.html';
if(!file_exists($tpl_file)){
	showmessage($tpl_file.' template does not exist.');
}
if(!is_writable($tpl_file)) {
	showmessage($tpl_file.' template does not writable.');
}
$content = file_get_contents($tpl_file);

$content = str_replace('*position1*',$position_1,$content);
$content = str_replace('*position2*',$position_2,$content);
$content = str_replace('*position3*',$position_3,$content);
$content = str_replace('*position4*',$position_4,$content);

file_put_contents($tpl_file,$content);

$language = array('video'=>'��Ƶ', 'video_manage'=>'��Ƶ�����', 'video_upload'=>'��Ƶ�ϴ�','video_edit'=>'�޸���Ƶ', 'video_delete'=>'ɾ����Ƶ', 'video_open'=>'���뿪ͨ', 'video_inputinfo'=>'��Ƶ����', 'complete_info'=>'��д����', 'subscribe_manage'=>'���Ĺ���', 'sub_delete'=>'ɾ������', 'import_ku6_video'=>'����ku6��Ƶ', 'album_import'=>'��Ƶר������', 'video_store'=>'��Ƶ��', 'video_stat'=>'��Ƶͳ��', 'player_manage'=>'����������');
?>