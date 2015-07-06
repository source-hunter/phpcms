<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 

/**
 * 
 * ��Ƶ�޸Ľ��սӿ� ��vmsϵͳ���޸���Ƶʱ������ô˽ӿڸ�����Щ��Ƶ
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
 * title, description, tag, vid, picpath, size, timelen, status, playnum
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
 * 
 * 
 * ************************************
 *              			          *
 *                 �� �� ֵ           *
 *                                    *
 * ************************************ 
 * 
 * �ӿ�ִ�к�Ӧ������Ӧ��ֵ֪ͨvmsϵͳ
 * ����ֵ��ʽ json���ݣ�array('msg'=>'Edit Success', 'code'=>'100')
 */

//��������ģ��
$video_store_db = pc_base::load_model('video_store_model');
pc_base::load_app_func('global', 'video');

//��֤��Ϣ
$data = array();

$vid = $_POST['vid'];
if (!$vid) {
	echo json_encode(array('msg'=>'Vid do not empty', 'code'=>4));
	exit;
}
if ($_POST['title'])		$data['title'] = safe_replace($_POST['title']);
if ($_POST['description'])  $data['description'] = safe_replace($_POST['description']);
if ($_POST['keywords'])		$data['keywords'] = safe_replace($_POST['tag']);
if ($_POST['picpath'])		$data['picpath'] = safe_replace(format_url($_POST['picpath']));
if ($_POST['size'])			$data['size'] = $_POST['size'];
if ($_POST['timelen'])		$data['timelen'] = intval($_POST['timelen']);
if ($_POST['ku6status'])	$data['status'] = intval($_POST['ku6status']);
if ($_POST['playnum'])		$data['playnum'] = intval($_POST['playnum']);

if ($data['status']<0 || $data['status']==24) {
	$r = $video_store_db->get_one(array('vid'=>$vid), 'videoid'); //ȡ��videoid���Ա��������
	$videoid = $r['videoid'];
	//$video_store_db->delete(array('vid'=>$vid)); //ɾ������Ƶ
	/**
	 * ������Ƶ���ݶ�Ӧ��ϵ����ģ�ͣ�������ɾ����Ƶ��ص����ݡ�
	 * �ڶ�Ӧ��ϵ���н����ϵ�����������ݵľ�̬ҳ
	 */
	$video_content_db = pc_base::load_model('video_content_model');
	$result = $video_content_db->select(array('videoid'=>$videoid));
	if (is_array($result) && !empty($result)) {
		//���ظ���html��
		$html = pc_base::load_app_class('html', 'content');
		$content_db = pc_base::load_model('content_model');
		$url = pc_base::load_app_class('url', 'content');
		foreach ($result as $rs) {
			$modelid = intval($rs['modelid']);
			$contentid = intval($rs['contentid']);
			$video_content_db->delete(array('videoid'=>$videoid, 'contentid'=>$contentid, 'modelid'=>$modelid));
			$content_db->set_model($modelid);
			$table_name = $content_db->table_name;
			$r1 = $content_db->get_one(array('id'=>$contentid));
			/**
			 * �ж��������ҳ�����˾�̬ҳ������¾�̬ҳ
			 */
			if (ishtml($r1['catid'])) {
				$content_db->table_name = $table_name.'_data';
				$r2 = $content_db->get_one(array('id'=>$contentid));
				$r = array_merge($rs, $r2);unset($r1, $r2);
				if($r['upgrade']) {
					$urls[1] = $r['url'];
				} else {
					$urls = $url->show($r['id'], '', $r['catid'], $r['inputtime']);
				}
				$html->show($urls[1], $r, 0, 'edit');
			} else {
				continue;
			}
		}
	}
} elseif ($data['status']==21) {
	$r = $video_store_db->get_one(array('vid'=>$vid), 'videoid'); //ȡ��videoid���Ա��������
	$videoid = $r['videoid'];
	/**
	 * ������Ƶ���ݶ�Ӧ��ϵ����ģ�ͣ�������ɾ����Ƶ��ص����ݡ�
	 * �ڶ�Ӧ��ϵ�����ҳ���Ӧ������id�����������ݵľ�̬ҳ
	 */
	$video_content_db = pc_base::load_model('video_content_model');
	$result = $video_content_db->select(array('videoid'=>$videoid));
	if (is_array($result) && !empty($result)) {
		//���ظ���html��
		$html = pc_base::load_app_class('html', 'content');
		$content_db = pc_base::load_model('content_model');
		$content_check_db = pc_base::load_model('content_check_model');
		$url = pc_base::load_app_class('url', 'content');
		foreach ($result as $rs) {
			$modelid = intval($rs['modelid']);
			$contentid = intval($rs['contentid']);
			$content_db->set_model($modelid);
			$c_info = $content_db->get_one(array('id'=>$contentid), 'thumb');

			$where = array('status'=>99);
			if (!$c_info['thumb']) $where['thumb'] = $data['picpath'];
			$content_db->update($where, array('id'=>$contentid));
			$checkid = 'c-'.$contentid.'-'.$modelid;
			$content_check_db->delete(array('checkid'=>$checkid));
			$table_name = $content_db->table_name;
			$r1 = $content_db->get_one(array('id'=>$contentid));
			/**
			 * �ж��������ҳ�����˾�̬ҳ������¾�̬ҳ
			 */
			if (ishtml($r1['catid'])) {
				$content_db->table_name = $table_name.'_data';
				$r2 = $content_db->get_one(array('id'=>$contentid));
				$r = array_merge($r1, $r2);unset($r1, $r2);
				if($r['upgrade']) {
					$urls[1] = $r['url'];
				} else {
					$urls = $url->show($r['id'], '', $r['catid'], $r['inputtime']);
				}
				$html->show($urls[1], $r, 0, 'edit');
				
			} else {
				continue;
			}
		}
	}
}
//�޸���Ƶ���е���Ƶ
if (strtolower(CHARSET)!='utf-8') {
	$data = array_iconv($data, 'utf-8', 'gbk');
}
$video_store_db->update($data, array('vid'=>$vid));
echo json_encode(array('msg'=>'Edit successful', 'code'=>200,'vid'=>$vid));
?>