<?php 

/**
 * ��ȡ��Ƶģ�͵���Ŀ
 **/
function video_categorys() {
	$siteid = isset($_GET['siteid']) ? intval($_GET['siteid']) : 1;
	$sitemodel_field = pc_base::load_model('sitemodel_field_model'); //����ģ���ֶ����ݿ���
	$result = $sitemodel_field->select(array('formtype'=>'video', 'siteid'=>$siteid), 'modelid'); //����վ���µ���Ƶģ��
	if (is_array($result)) {
		$models = '';
		foreach ($result as $r) {
			$models .= $r['modelid'].',';
		}
	}
	$models = substr(trim($models), 0, -1);
	$cat_db = pc_base::load_model('category_model'); //������Ŀ���ݿ���
	$where = '`modelid` IN ('.$models.') AND `type`=0 AND `siteid`=\''.$siteid.'\'';
	$result = $cat_db->select($where, '`catid`, `catname`, `parentid`, `siteid`, `child`', '', '`listorder` ASC, `catid` ASC', '', 'catid');
	return $result;
}

/**
 * ��ȡģ���µ���Ƶ�ֶ�����
 * @param int $catid ��Ŀid
 */
function get_video_field($catid = 0) {
	static $categorys;
	if (!$catid) return false;
	$siteid = isset($_GET['siteid']) ? intval($_GET['siteid']) : 1;
	if (!$categorys) {
		$categorys = getcache('category_content_'.$siteid, 'commons');
	}
	$modelid = $categorys[$catid]['modelid'];
	$model_field = pc_base::load_model('sitemodel_field_model');
	$r = $model_field->get_one(array('modelid'=>$modelid, 'formtype'=>'video'));
	return $r['field'] ? $r['field'] : '';
}
?>