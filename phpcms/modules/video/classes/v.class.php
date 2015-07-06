<?php
/**
 * 
 * ----------------------------
 * v class
 * ----------------------------
 * 
 * An open source application development framework for PHP 5.0 or newer
 * 
 * ����࣬��Ҫ������Ƶģ�����ݴ���
 * @package	PHPCMS V9.1.16
 * @author		chenxuewang
 * @copyright	CopyRight (c) 2006-2012 �Ϻ�ʢ�����緢չ���޹�˾
 *
 */

class v {
	
	private $db;
	
	public function __construct(&$db) {
		$this->db = & $db;
	}
	
	/**
	 * 
	 * add �����Ƶ����������Ƶ��⵽��Ƶ����
	 * @param array $data ��Ƶ��Ϣ����
	 */
	public function add($data = array()) {
		if (is_array($data) && !empty($data)) {
			$data['status'] = 1;
			$data['userid'] = defined('IN_ADMIN') ? 0 : intval(param::get_cookie('_userid'));
			$data['vid'] = safe_replace($data['vid']);
			$vid = $this->db->insert($data, true);
			return $vid ? $vid : false; 
		} else {
			return false;
		}
	}
	
	/**
	 * function edit 
	 * �༭��Ƶ�������û����±༭���ϴ�����Ƶ
	 * @param array $data ��Ƶ��Ƶ��Ϣ���� ����title description tag vid ����Ϣ
	 * @param int $vid ��Ƶ������Ƶ������
	 */
	public function edit($data = array(), $vid = 0) {
		if (is_array($data) && !empty($data)) {
			$vid = intval($vid);
			if (!$vid) return false;
			unset($data['vid']);
			$this->db->update($data, "`videoid` = '$vid'");
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * function del_video
	 * ɾ����Ƶ���е���Ƶ
	 * @param int $vid ��ƵID
	 */
	public function del_video($vid = 0) {
		$vid = intval($vid);
		if (!$vid) return false;
		//ɾ����Ƶ���������ݣ�����������ҳ
		$this->db->delete(array('videoid'=>$vid));
		return true;
	}
}
?>