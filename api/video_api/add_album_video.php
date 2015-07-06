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
 * title, description, tag, vid, picpath, size, timelen, status, playnum, specialid
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
 * specialid ��Ƶ�����ר��id
 * 
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
$special_db = pc_base::load_model('special_model');
$special_content_db = pc_base::load_model('special_content_model');
$content_data_db = pc_base::load_model('special_c_data_model');
$type_db = pc_base::load_model('type_model');

pc_base::load_app_func('global', 'video');

//��֤��Ϣ
$data = $video_data = array();

$data['specialid'] = intval($_POST['specialid']);
if (!$data['specialid']) {
	echo json_encode(array('msg'=>'Specialid do not empty', 'code'=>'1'));
	exit;
} 
if (!$special_info = $special_db->get_one(array('id'=>$data['specialid']))) {
	echo json_encode(array('msg'=>'The system does not exist this special', 'code'=>2));
	exit;
}
$data['title'] = $video_data['title'] = safe_replace($_POST['title']);
if (!$data['title']) {
	echo json_encode(array('msg'=>'Video\'s title not empty', 'code'=>3));
	exit;
}
$content = $_POST['desc'] ? addslashes($_POST['desc']) : '';
$data['description'] = $video_data['description'] = substr($content, 0, 255);
$data['keywords'] = $video_data['keywords'] = $_POST['tag'] ? addslashes($_POST['tag']) : '';
$vid = $video_data['vid'] = $_POST['vid'];
if (!$vid) {
	echo json_encode(array('msg'=>'Vid do not empty', 'code'=>4));
	exit;
}
//�Ƚ���Ƶ���뵽��Ƶ���У���ȡ��videoid
//�ж�vid�Ƿ��Ѿ�������Ƶ����
if (!$video_store = $video_store_db->get_one(array('vid'=>$vid))) {
	$video_data['status'] = $_POST['status'] ? intval($_POST['status']) : 21;
	$video_data['picpath'] = safe_replace( format_url($_POST['picPath']) );
	$video_data['addtime'] = intval(substr($_POST['uploadTime'], 0, 10));
	$video_data['timelen'] = intval($_POST['videoTime']);
	$video_data['size'] = intval($_POST['videoSize']);
	if (strtolower(CHARSET)!='utf-8') {
		$video_data = array_iconv($video_data, 'utf-8', 'gbk');
	}
	$videoid = $video_store_db->insert($video_data, true);
} else {
	$videoid = $video_store['vid'];
}
//����special_content�������ֶ�
$res = $type_db->get_one(array('parentid'=>$data['specialid'], 'module'=>'special'), 'typeid', 'listorder ASC');
$data['typeid'] = $res['typeid'];
$data['thumb'] = $video_data['picpath'];
$data['videoid'] = $videoid;
//���POST����
$data['inputtime'] = SYS_TIME;
$data['updatetime'] = SYS_TIME;
if (strtolower(CHARSET)!='utf-8') {
	$data = array_iconv($data, 'utf-8', 'gbk');
}
$contentid = $special_content_db->insert($data, true);
// ������ͳ�Ʊ��������
$count = pc_base::load_model('hits_model');
$hitsid = 'special-c-'.$data['specialid'].'-'.$contentid;
$count->insert(array('hitsid'=>$hitsid, 'views'=>intval($_POST['playnum'])));
//�����ݼӵ�data����
$content = iconv('utf-8', 'gbk', $content);
$content_data_db->insert(array('id'=>$contentid, 'content'=>$content));
//����search��
$search_db = pc_base::load_model('search_model');
$siteid = $special_info['siteid'];
$type_arr = getcache('type_module_'.$siteid,'search');
$typeid = $type_arr['special'];
$searchid = $search_db->update_search($typeid ,$contentid,'',$data['title'], $data['inputtime']);
//��ȡר���url
$html = pc_base::load_app_class('html', 'special');
$urls= $html->_create_content($contentid);
$special_content_db->update(array('url'=>$urls[0], 'searchid'=>$searchid), array('id'=>$contentid));
if ($_POST['end_status']) {
	$html->_index($data['specialid'], 20, 5);
}
echo json_encode(array('msg'=>'Add Success', 'code'=>'200'));
exit;