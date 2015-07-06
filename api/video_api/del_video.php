<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 

/**
 * 
 * ��Ƶɾ�����սӿ� ��vmsϵͳ��ɾ����Ƶʱ������ô˽ӿ�
 * 
 * @author				chenxuewang
 * @link				http://www.phpcms.cn http://www.ku6.cn
 * @copyright			CopyRight (c) 2006-2012 ���������������Ƽ����޹�˾
 * @license				http://www.phpcms.cn/license/
 * 
 * 
 * *************************************
 *              			           *
 *                 ����˵��            *
 *                                     *
 * ************************************* 
 * 
 * vid����Ƶvid����Ƶ��Ψһ�ı�ʾ����������Ƶ
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

$vid = $_POST['ku6vid'];
if (!$vid) {
	echo json_encode(array('msg'=>'Vid do not empty', 'code'=>4));
	exit;
}

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

$video_store_db->update(array('status'=>'-30'), array('vid'=>$vid));
echo json_encode(array('msg'=>'Delete video successful', 'code'=>200,'vid'=>$vid));
?>