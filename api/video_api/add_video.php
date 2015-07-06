<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 


/**
 * 
 * ��Ƶ��ӽ��սӿ� ��vmsϵͳ�������Ƶ������ku6��Ƶʱ������ô˽ӿ�ͬ����Щ��Ƶ
 * 
 * @author				chenxuewang
 * @link				http://www.phpcms.cn http://www.ku6.cn
 * @copyright			CopyRight (c) 2006-2012 ���������������Ƽ����޹�˾
 * @license			http://www.phpcms.cn/license/
 * 
 * 
 * *************************************
 *              			           *
 *                 ����˵��            *
 *                                     *
 * ************************************* 
 * 
 * title, description, tag, vid, picpath, size, timelen, status, playnum, catid, posid
 * 
 * title, ��Ƶ����
 * 
 * descrption ��Ƶ���
 * 
 * tag ��Ƶ��ǩ
 * 
 * vid����Ƶvid����Ƶ��Ψһ�ı�ʾ����������Ƶ
 * 
 * picpath ��Ƶ����ͼ
 * 
 * size ��Ƶ��С
 * 
 * timelen ��Ƶ����ʱ��
 * 
 * status ��ƵĿǰ��״̬
 * 
 * playnum ��Ƶ���Ŵ���
 * 
 * catid ���뵽��ϵͳ��ĿID �����������Ƶ�����ȷ���Ϊ���ݣ�ͬʱ����Ƶ������Ƶ���й��Ժ�ʹ��
 * 
 * posid ��ϵͳ�Ƽ�λID ����Ϊ�գ���Ϊ��ʱ����Ҫ����Ƶ��ӵ��Ƽ�λ����
 * 
 * 
 * ************************************
 *              			          *
 *                 �� �� ֵ           *
 *                                    *
 * ************************************ 
 * 
 * �ӿ�ִ�к�Ӧ������Ӧ��ֵ֪ͨvmsϵͳ
 * ����ֵ��ʽ json���ݣ�array('msg'=>'Add Success', 'code'=>'100')
 */

//��������ģ��
$video_store_db = pc_base::load_model('video_store_model');
$content = pc_base::load_model('content_model');
$cat_db = pc_base::load_model('category_model');
$model_field = pc_base::load_model('sitemodel_field_model');
$video_setting = getcache('video', 'video');
//����v.class
pc_base::load_app_func('global', 'video');
pc_base::load_app_class('v', 'video', 0);
$v = new v($db);

//��֤��Ϣ
$data = $video_data = array();
$data['catid'] = intval($_POST['catid']);
if (!$data['catid']) {
	$data['catid'] = $video_setting['catid'];
} 
$cat_info = $cat_db->get_one(array('catid'=>$data['catid']));

$data['title'] = $video_data['title'] = $_POST['title'];
if (!$data['title']) {
	echo json_encode(array('msg'=>'The parameter title must have a value', 'code'=>3));
	exit;
}
if (!$_POST['picpath'] || strripos($_POST['picpath'],'.jpg')===false) {
	echo json_encode(array('msg'=>'The parameter picpath must have a value', 'code'=>5));
	exit;
}
$data['content'] = $_POST['description'] ? addslashes($_POST['description']) : '';
$data['description'] = $video_data['description'] = substr($data['content'], 0, 255);
$data['keywords'] = $video_data['keywords'] = $_POST['tag'] ? $_POST['tag'] : '';
$video_data['timelen'] = intval($_POST['timelen']);
$video_data['size'] = intval($_POST['size']);
$video_data['vid'] = $_POST['vid'];
if (!$video_data['vid']) {
	echo json_encode(array('msg'=>'The parameter vid must have a value', 'code'=>4));
	exit;
}

//�Ƚ���Ƶ���뵽��Ƶ���У���ȡ��videoid
//�ж�vid�Ƿ��Ѿ�������Ƶ����
if (!$video_store = $video_store_db->get_one(array('vid'=>$video_data['vid']))) {
	$video_data['status'] = $_POST['ku6status'] ? intval($_POST['ku6status']) : 1;
	$video_data['picpath'] = safe_replace( format_url($_POST['picpath']) );
	$video_data['addtime'] = $_POST['createtime'] ? $_POST['createtime'] : SYS_TIME;
	$video_data['channelid'] = 1;
	if (strtolower(CHARSET)!='utf-8') {
		$video_data = array_iconv($video_data, 'utf-8', 'gbk');
	}
	$videoid = $video_store_db->insert($video_data, true);
} else {
	$videoid = $video_store['videoid'];
}
if (!$cat_info) {
	echo json_encode(array('msg'=>'Add Success', 'code'=>'200'));
	exit;
}
//������Ŀ��Ϣȡ��վ��id��ģ��id
$siteid = $cat_info['siteid'];
$modelid = $cat_info['modelid'];
//����ģ��id���õ���Ƶ�ֶ���
$r = $model_field->get_one(array('modelid'=>$modelid, 'formtype'=>'video'), 'field');
$fieldname = $r['field'];
if ($_POST['posid']) {
	$data['posids'][] = $_POST['posid'];
}
$data['thumb'] = safe_replace( format_url($_POST['picpath']) );
$data[$fieldname] = 1;
//���POST����
$_POST[$fieldname.'_video'][1] = array('videoid'=>$videoid, 'listorder'=>1);
$data['status'] = ($video_data['status'] == 21 || $_POST['status']==1) ? 99 : 1;
//��������ģ��
if (strtolower(CHARSET)!='utf-8') {
	$data = array_iconv($data, 'utf-8', 'gbk');
}
$content->set_model($modelid); 
$cid = $content->add_content($data);
//���¶�Ӧ��ϵ
//$content_video_db = pc_base::load_model('video_content_model');
//$content_video_db->insert(array('contentid'=>$cid, 'videoid'=>$videoid, 'modelid'=>$modelid, 'listorder'=>1));
//���µ������ 
if ($_POST['playnum']) {
	$views = intval($_POST['playnum']);
	$hitsid = 'c-'.$modelid.'-'.$cid;
	$count = pc_base::load_model('hits_model');
	$count->update(array('views'=>$views), array('hitsid'=>$hitsid));
}

echo json_encode(array('msg'=>'Add Success', 'code'=>'200'));
exit;
?>