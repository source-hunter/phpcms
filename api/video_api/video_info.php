<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 

/**
 * 
 * ��Ƶ״̬���սӿ� vmsϵͳ�յ�ku6ϵͳ����Ƶ״̬�ı�ʱpost��cmsϵͳ�У��˽ӿڸ���������ݸı���Ƶ������Ƶ��״̬
 * 
 * @author				chenxuewang
 * @link				http://www.phpcms.cn http://www.ku6.cn
 * @copyright			CopyRight (c) 2006-2012 ���������������Ƽ����޹�˾
 * @license			http://www.phpcms.cn/license/
 * ---------------------------------------------------------------------
 * ����˵��
 * vid, picpath, size, timelen, status
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
 */

$video_setting = getcache('video');
pc_base::load_app_func('global', 'video');

pc_base::load_app_class('ku6api', 'video', 0);
$ku6api = new ku6api($video_setting['skey'], $video_setting['sn']);

$msg = $ku6api->update_video_status_from_vms();
exit($msg);
?>