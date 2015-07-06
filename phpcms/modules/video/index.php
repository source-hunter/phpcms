<?php 
defined('IN_PHPCMS') or exit('No permission resources.');

/**
 * 
 * ------------------------------------------
 * index
 * ------------------------------------------
 * @package 	PHPCMS V9.1.16
 * @author		������
 * @copyright	CopyRight (c) 2006-2012 �Ϻ�ʢ�����緢չ���޹�˾
 * 
 */

class index{
	public $db;
	public function __construct() { 
		pc_base::load_app_class('ku6api', 'video', 0);
		$this->userid = param::get_cookie('userid');
		$this->setting = getcache('video');
		if(empty($this->setting)) {
			showmessage(L('module_not_exists'));
		}
		$this->ku6api = new ku6api($this->setting['sn'], $this->setting['skey']);
	}
	
	/**
	 * 
	 * ��Ƶ�б�
	 */
	public function init() {
		 showmessage('����ת����ҳ...','index.php');
	}
	
	/**
	* �����嵥������ҳ
	*/
	public function playlist(){
		pc_base::load_app_func('util','content');
		if(isset($_GET['siteid'])) {
			$siteid = intval($_GET['siteid']);
		} else {
			$siteid = 1;
		}
		$CATEGORYS = getcache('category_content_'.$siteid,'commons');
		$title = strip_tags($_GET['title']);
		$contentid = intval($_GET['contentid']);
		$catid = intval($_GET['catid']);
 		$video_info = get_vid($contentid, $catid, $isspecial = 0);
  		include template('content','show_videolist');
	} 
	
	/**
	* ��Ƶר���б�ҳ
	* index.php?m=video&c=index&a=album
	*/
	public function album(){
		pc_base::load_app_func('util','content');
		$spid = $_GET['spid'];
		$page = $_GET['page'];
		if(isset($_GET['siteid'])) {
			$siteid = intval($_GET['siteid']);
		} else {
			$siteid = 1;
		}
 		include template('content','video_album');
	}
	
	
	
}

?>