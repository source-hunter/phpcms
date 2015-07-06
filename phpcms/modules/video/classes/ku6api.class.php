<?php
/**
 * 
 * ----------------------------
 * ku6api class
 * ----------------------------
 * 
 * An open source application development framework for PHP 5.0 or newer
 * 
 * ���Ǹ��ӿ��࣬��Ҫ������Ƶģ�͸�ku6vms֮���ͨ��
 * @package	PHPCMS V9.1.16
 * @author		chenxuewang
 * @copyright	CopyRight (c) 2006-2012 �Ϻ�ʢ�����緢չ���޹�˾
 *
 */

class ku6api {
	public $ku6api_sn, $ku6api_key;
	private $ku6api_url,$http,$xxtea;
	
	/**
	 * 
	 * ���췽�� ��ʼ���û����ʶ���롢������Կ��
	 * @param string $ku6api_skey vmsϵͳ�е����ʶ����
	 * @param string $ku6api_sn vmsϵͳ�����õ�ͨ�ż�����Կ
	 * 
	 */
	public function __construct($ku6api_sn = '', $ku6api_skey = '') {
		$this->ku6api_skey = $ku6api_skey;
		$this->ku6api_sn = $ku6api_sn;
		if (!$this->ku6api_sn) {
			$this->set_sn();
		}
		$this->ku6api_url = pc_base::load_config('ku6server', 'api_url');
		$this->ku6api_api = pc_base::load_config('ku6server', 'api');
		$this->http = pc_base::load_sys_class('http');
		$this->xxtea = pc_base::load_app_class('xxtea', 'video');
		
	}

	/**
	 * 
	 * �������ʶ���뼰�����Կ
	 * 
	 */
	private function set_sn() {
		//��ȡ����ƽ̨������Ϣ
		$setting = getcache('video', 'video');
		if ($setting['sn'] && $setting['skey']) {
			$this->ku6api_skey = $setting['skey'];
			$this->ku6api_sn = $setting['sn'];
		}
	}
	
	/**
	 * 
	 * vms_add ��Ƶ��ӷ��� ϵͳ�������Ƶ�ǵ��ã�ͬ����ӵ�vmsϵͳ��
	 * @param array $data �������Ƶ��Ϣ ��Ƶ���⡢���ܵ�
	 */
	public function vms_add($data = array()) {
		if (is_array($data) && !empty($data)) {
			//��������
			$data['tag'] = $this->get_tag($data);
			$data['v'] = 1;
			$data['channelid'] = $data['channelid'] ? intval($data['channelid']) : 1;
			//��gbk����תΪutf-8����
			if (CHARSET == 'gbk') {
				$data = array_iconv($data);
			}
			$data['sn'] = $this->ku6api_sn;
			$data['method'] = 'VideoAdd';
			$data['posttime'] = SYS_TIME;
			$data['token'] = $this->xxtea->encrypt($data['posttime'], $this->ku6api_skey);
			//��vms post���ݣ�����ȡ����ֵ
			$this->http->post($this->ku6api_url, $data);
			$get_data = $this->http->get_data();
			$get_data = json_decode($get_data, true);
			if($get_data['code'] != 200) {
				$this->error_msg = $get_data['msg'];
				return false;
			}
			return $get_data;
			
		} else {
			$this->error_msg = '';
			return false; 
		}
	}
	
	/**
	 * function vms_edit
	 * ��Ƶ�༭ʱ���� ��Ƶ�ı�ʱͬ������vmsϵͳ�ж�Ӧ����Ƶ
	 * @param array $data
	 */
	public function vms_edit($data = array()) {
		if (is_array($data ) && !empty($data)) {
			//��������
			$data['tag'] = $this->get_tag($data);
			//��gbk����תΪutf-8����
			if (CHARSET == 'gbk') {
				$data = array_iconv($data);
			}
			$data['sn'] = $this->ku6api_sn;
			$data['method'] = 'VideoEdit';
			$data['posttime'] = SYS_TIME;
			$data['token'] = $this->xxtea->encrypt($data['posttime'], $this->ku6api_skey);
			//��vms post���ݣ�����ȡ����ֵ
			$this->http->post($this->ku6api_url, $data);
			$get_data = $this->http->get_data();
			$get_data = json_decode($get_data, true);
			if($get_data['code'] != 200) {
				$this->error_msg = $get_data['msg'];
				return false;
			}
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * function delete_v
	 * ɾ����Ƶʱ��֪ͨvmsϵͳ�ӿڡ�
	 * @param string $ku6vid vmsϵͳ��ku6vid
	 */
	public function delete_v($ku6vid = '') {
		if (!$ku6vid) return false;
		//����post����
		$data['sn'] = $this->ku6api_sn;
		$data['method'] = 'VideoDel';
		$data['posttime'] = SYS_TIME;
		$data['token'] = $this->xxtea->encrypt($data['posttime'], $this->ku6api_skey);
		$data['vid'] = $ku6vid;
		//��vms post����
		$this->http->post($this->ku6api_url, $data);
		$get_data = $this->http->get_data();
		$get_data = json_decode($get_data, true);
		if($get_data['code'] != 200 && $get_data['code']!=112) {
			$this->error_msg = $get_data['msg'];
			return false;
		}
		return true;
	}
	/**
	 * 
	 * ��ȡ��Ƶtag��ǩ
	 * @param array $data ��Ƶ��Ϣ����
	 * @return string $tag ��ǩ
	 */
	private function get_tag($data = array()) {
		if (is_array($data) && !empty($data)) {
			if ($data['keywords']) $tag = trim(strip_tags($data['keywords']));
			else $tag = $data['title'];
			return $tag;
		}
	}
	
	/**
	 * function update_video_status_from_vms
	 * ��Ƶ״̬�ı�ӿ�
	 * @param array $get ��Ƶ��Ϣ
	 */
	public function update_video_status_from_vms() {
		if (is_array($_GET) && !empty($_GET)) {
			$size = $_GET['size'];
			$timelen = intval($_GET['timelen']);
			$status = intval($_GET['ku6status']);
			$vid = $_GET['vid'];
			$picpath = format_url($_GET['picpath']);
			//��֤����
			/* ��֤vid */
			if(!$vid) return json_encode(array('status'=>'101','msg'=>'vid not allowed to be empty'));
			/* ��֤��Ƶ��С */
			if($size<100) return json_encode(array('status'=>'103','msg'=>'size incorrect'));
			/* ��֤��Ƶʱ�� */
			if($timelen<1) return json_encode(array('status'=>'104','msg'=>'timelen incorrect'));
			
			$db = pc_base::load_model('video_store_model');
			$r = $db->get_one(array('vid'=>$vid));
			if ($r) {
				$db->update(array('size'=>$size, 'picpath'=>$picpath, 'status'=>$status), array('vid'=>$vid));
				if ($status==21) {
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
						$url = pc_base::load_app_class('url', 'content');
						foreach ($result as $rs) {
							$modelid = intval($rs['modelid']);
							$contentid = intval($rs['contentid']);
							$content_db->set_model($modelid);
							$content_db->update(array('status'=>99), array('id'=>$contentid));
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
				} elseif ($data['status']<0 || $data['status']==24) {
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
				}
				return json_encode(array('status'=>'200','msg'=>'Success'));
			} else {
				return json_encode(array('status'=>'107','msg'=>'Data is empty!'));
			}
		}
		json_encode(array('status'=>'107','msg'=>'Data is empty!'));
	}
	
	/**
	 * function get_categroys
	 * ��cmsϵͳ����Ƶģ�͵���Ŀȡ��������ͨ���ӿڴ���vmsϵͳ��
	 * @param bloon $isreturn �Ƿ񷵻�option
	 * @param int $catid ��ѡ�е���Ŀ id
	 */
	public function get_categorys($isreturn = false, $catid = 0) {
		$siteid = get_siteid();
		$sitemodel_field = pc_base::load_model('sitemodel_field_model');
		$result = $sitemodel_field->select(array('formtype'=>'video', 'siteid'=>$siteid), 'modelid');
		if (is_array($result)) {
			$models = '';
			foreach ($result as $r) {
				$models .= $r['modelid'].',';
			}
		}
		$models = substr(trim($models), 0, -1);
		$cat_db = pc_base::load_model('category_model');
		if ($models) {
			$where = '`modelid` IN ('.$models.') AND `type`=0 AND `siteid`=\''.$siteid.'\'';
			$result = $cat_db->select($where, '`catid`, `catname`, `parentid`, `siteid`, `child`');
			if (is_array($result)) {
				$data = $return_data = array();
				foreach ($result as $r) {
					$sitename = $this->get_sitename($r['siteid']);
					$data[] = array('catid'=>$r['catid'], 'catname'=>$r['catname'], 'parentid'=>$r['parentid'], 'siteid'=>$r['siteid'], 'sitename'=>$sitename, 'child'=>$r['child']);
					$r['disabled'] = $r['child'] ? 'disabled' : '';
					if ($r['catid'] == $catid) { 
						$r['selected'] = 'selected';
					}
					$return_data[$r['catid']] = $r;
					
				}
				//��gbk����תΪutf-8����
				if (strtolower(CHARSET) == 'gbk') {
					$data = array_iconv($data);
				}
				$data = json_encode($data);	
				$postdata['sn'] = $this->ku6api_sn;
				$postdata['method'] = 'UserCat';
				$postdata['posttime'] = SYS_TIME;
				$postdata['token'] = $this->xxtea->encrypt($postdata['posttime'], $this->ku6api_skey);
				$postdata['data'] = $data;
				//��vms post���ݣ�����ȡ����ֵ
				$this->http->post($this->ku6api_url, $postdata);
				$get_data = $this->http->get_data();
				$get_data = json_decode($get_data, true);
				if($get_data['code'] != 200) {
					$this->error_msg = $get_data['msg'];
					return false;
				} elseif ($isreturn) {
					$tree = pc_base::load_sys_class('tree');
					$str  = "<option value='\$catid' \$selected \$disabled>\$spacer \$catname</option>";

					$tree->init($return_data);
					$string = $tree->get_tree(0, $str);
					return $string;
				} else {
					return true;
				}
			}
		}
		return array();
	}
	
	/**
	 * function get_ku6_channels
	 * ��ȡku6��Ƶ����Ϣ
	 */
	public function get_subscribetype() {
		//����post����
		$postdata['method'] = 'SubscribeType';
		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return $data['data'];
		}
		return false;
	}
	
	/**
	 * function get_ku6_channels
	 * ��ȡku6��Ƶ����Ϣ
	 */
	public function get_ku6_channels() {
		//����post����
		$postdata['method'] = 'Ku6Channel';
		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return $data['data'];
		}
		return false;
	}
	
	/**
	 * function subscribe ���Ĵ���
	 * �÷������û��Ķ�����Ϣpost��vms�����¼
	 * @param array $data ������Ϣ ���磺 array(array('channelid'=>102000, 'catid'=>16371, 'posid'=>8))
	 */
	public function subscribe($datas = array()) {
		//����post����
		$postdata['method'] = 'SubscribeAdd';
		$postdata['channelid'] = $datas['channelid'];
		$postdata['catid'] = $datas['catid'];
		$postdata['posid'] = $datas['posid'] ? $datas['posid'] : 0;

		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return $data;
		}
		return false;
	} 

	/**
	 * function checkusersubscribe �ж��û��Ƿ��Ѿ�����
	 */
	public function checkusersubscribe($datas = array()) {
		$postdata['method'] = 'CheckUserSubscribe';
		$postdata['userid'] = $datas['userid'];

		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return $data;
		}
		return false;
	}	
	
	/**
	 * function subscribe ���û����Ĵ���
	 * �÷������û��Ķ�����Ϣpost��vms�����¼
	 * @param array $data ������Ϣ ���磺 array(array('userid'=>102000, 'catid'=>16371, 'posid'=>8))
	 */
	public function usersubscribe($datas = array()) {
		//����post����
		$postdata['method'] = 'UserSubscribeAdd';
		$postdata['userid'] = $datas['userid'];
		$postdata['catid'] = $datas['catid'];
		$postdata['posid'] = $datas['posid'] ? $datas['posid'] : 0;

		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return $data;
		}
		return false;
	}	
	
	/**
	 * Function sub_del ɾ������
	 * �û�ɾ������
	 * @param int $id ����id
	 */
	public function sub_del($id = 0) {
		if (!$id) return false;
		//����post����
		$postdata['method'] = 'SubscribeDel';
		$postdata['sid'] = $id;
		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return true;
		}
		return false;
	}
	
	/**
	 * Function user_sub_del ɾ�������û�
	 * ɾ�������û�
	 * @param int $id ����id
	 */
	public function user_sub_del($id = 0) {
		if (!$id) return false;
		//����post����
		$postdata['method'] = 'UserSubscribeDel';
		$postdata['sid'] = $id;
		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return true;
		}
		return false;
	}	
	
	/**
	 * fucntion get_subscribe ��ȡ����
	 * ��ȡ�Լ��Ķ�����Ϣ
	 */	
	public function get_subscribe() {
		//����post����
		$postdata['method'] = 'SubscribeSearch';
		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return $data['data'];
		} else {
			return false;
		}
	}
	
	/**
	 * fucntion get_subscribe ��ȡ�û�����
	 * ��ȡ�û��Լ��Ķ�����Ϣ
	 */	
	public function get_usersubscribe() {
		//����post����
		$postdata['method'] = 'UserSubscribeSearch';
		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return $data['data'];
		} else {
			return false;
		}
	}	
	
	/**
	 * Function flashuploadparam ��ȡflash�ϴ�������
	 * ��ȡflash�ϴ�������
	 */
	public function flashuploadparam () {
		//����post����
		$postdata['method'] = 'GetFlashUploadParam';
		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return $data['data'];
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Function get_albums
	 * ��ȡku6ר���б�
	 * @param int $page ��ǰҳ��
	 * @param int $pagesize ÿҳ����
	 * @return array ����ר������
	 */
	public function get_albums($page = 1, $pagesize = 20) {
		//����post����
		if ($_GET['start_time']) {
			$postdata['start_time'] = strtotime($_GET['start_time']);
		}
		if ($_GET['end_time']) {
			$postdata['end_time'] = strtotime($_GET['end_time']);
		}
		if ($_GET['keyword']) {
			$postdata['key'] = addslashes($_GET['keyword']);
		}
		if ($_GET['categoryid']) {
			$postdata['categoryid'] = intval($_GET['categoryid']);
		}
		$postdata['method'] = 'AlbumList';
		$postdata['start'] = ($page-1)*$pagesize;
		$postdata['size'] = $pagesize;
		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return $data;
		} else {
			return false;
		}
	}
	
	/**
	 * Function get_album_videoes
	 * ��ȡĳר���µ���Ƶ�б�
	 * @param int $albumid ר��ID
	 * @param int $page ��ǰҳ
	 * @param int $pagesize ÿҳ����
	 * @return array ��Ƶ����
	 */
	public function get_album_videoes($albumid = 0, $page = 1, $pagesize = 20) {
		//����post����
		$postdata['method'] = 'AlbumVideoList';
		$postdata['p'] = $page;
		$postdata['playlistid'] = $albumid;
		$postdata['s'] = $pagesize;
		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return $data['data'];
		} else {
			return false;
		}
	}
	
	/**
	 * Function get_album_info
	 * ��ȡר������ϸ��Ϣ
	 * @param int $albumid ר��id
	 */
	public function get_album_info($albumid = 0) {
		$albumid = intval($albumid);
		if (!$albumid) return false;
		$arr = array('method'=>'GetOneAlbum', 'id'=>$albumid);
		if ($data = $this->post($arr)) {
			return $data['list'];
		} else {
			return false;
		}
	}
	
	/**
	 * Function add_album_subscribe
	 * ���ר������
	 * @param array $data �������� �磺array(0=>array('specialid'=>1, 'id'=>1232131), 1=>array('specialid'=>2, 'id'=>4354323))
	 */
	public function add_album_subscribe($data = array()) {
		if (!is_array($data) || empty($data)) {
			return false;
		}
		//����post����
		$postdata['method'] = 'AlbumVideoSubscribe';
		$postdata['data'] = $data;
		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Function member_login_vms
	 * ��½��̨ͬʱ��½vms
	 * @param array $data
	 */
	public function member_login_vms() {
		//����post����
		$postdata = array();
		$postdata['method'] = 'SynLogin';
		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Function check_status
	 * ��½��̨ͬʱ��½vms
	 * @param array $data
	 */
	public function check_status($vid = '') {
		if (!$vid) return false;
		//����post����
		$postdata = array();
		$postdata['method'] = 'VideoStatusCheck';
		$postdata['vid'] = $vid;
		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return $data;
		} else {
			return false;
		}
	}
	
	/**
	 * Function http
	 * ִ��http post���ݵ��ӿ�
	 * @param array $datas post���ݲ��� �磺array('method'=>'AlbumVideoList', 'p'=>1, 's'=>6,....)
	 */
	private function post($datas = array()) {
		//����post����
		$data['sn'] = $this->ku6api_sn;
		$data['posttime'] = SYS_TIME;
		$data['token'] = $this->xxtea->encrypt($data['posttime'], $this->ku6api_skey);
		if (strtolower(CHARSET) == 'gbk') {
			$datas = array_iconv($datas, 'gbk', 'utf-8');
		}
		if (is_array($datas)) {
			foreach ($datas as $_k => $d) {
				if (is_array($d)) {
					$data[$_k] = json_encode($d);
				} else {
					$data[$_k] = $d;
				}
			}
		}
		//��vms post���ݣ�����ȡ����ֵ
		$this->http->post($this->ku6api_url, $data);
		$get_data = $this->http->get_data();
		$get_data = json_decode($get_data, true);
		//�ɹ�ʱvms����code=200 ��ku6����status=1
		if ($get_data['code'] == 200 || $get_data['status'] == 1) {
			//��gbk����תΪutf-8����
			if (strtolower(CHARSET) == 'gbk') {
				$get_data = array_iconv($get_data, 'utf-8', 'gbk');
			}
			return $get_data;
		} else {
			return $get_data;
		}
	}
	
	/**
	 * Function CHECK
	 * ��vms����vid
	 * @param string $vid vid
	 */
	public function check($vid = '') {
		if (!$vid) return false;
		//����post����
		$postdata['method'] = 'GetVid';
		$postdata['vid'] = $vid;
		$postdata['url'] = APP_PATH . 'api.php?op=video_api';
		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Function vms_update_video 
	 * ������Ƶ����Ƶ����ϵͳ
	 * @param array $data array of video
	 */
	public function vms_update_video($data = array()) {
		if (empty($data)) return false;
		//����post����
		$postdata['method'] = 'VideoUpdate';
		$postdata['data'] = $data;
		//��vms post���ݣ�����ȡ����ֵ
		if ($data = $this->post($postdata)) {
			return $data;
		} else {
			return false;
		}
	}

	/**
	 * Function Preview
	 * ��vms����vid
	 * @param string $vid vid
	 */
	public function Preview($vid = '') {
		if (!$vid) return false;
		//����post����
		$postdata['method'] = 'Preview';
		$postdata['vid'] = $vid;
		//��vms post���ݣ�����ȡ����ֵ
 		if ($data = $this->post($postdata)) {
			return $data;
		} else { 
  			return false;
		}
	}
	
	/**
	 * Function Ku6search
	 * ��vms��������
	 * @param string $vid vid
	 */
	public function Ku6search($keyword,$pagesize,$page,$srctype,$len,$fenlei,$fq) { 
		//����post����
		$postdata['method'] = 'search';
		$postdata['pagesize'] = $pagesize;
		$postdata['keyword'] = $keyword;
		$postdata['page'] = $page;
		$postdata['fenlei'] = $fenlei;
		$postdata['srctype'] = $srctype;
		$postdata['len'] = $len;
		$postdata['fq'] = $fq;
		
 		//��vms post���ݣ�����ȡ����ֵ
 		if ($data = $this->post($postdata)) { 
  			return $data;
		} else { 
   			return false;
		}
	}
	/**
	 * Function get_sitename
	 * ��ȡվ������
	 */
	private function get_sitename($siteid) {
		static $sitelist;
		if (!$sitelist) {
			$sitelist = getcache('sitelist', 'commons');
		}
		return $sitelist[$siteid]['name'];

	}
	
	/**
	 * Function update_vms 
	 * @������Ƶϵͳ������ϵͳ����û�
	 * @param $data POST����
	 */
	public function update_vms_member($data = array()) {
		if (empty($data)) return false;
		//����post����
		$data['sn'] = $this->ku6api_sn;
		$data['skey'] = $this->ku6api_skey;
		$postdata['data'] = json_encode($data);
		$api_url = pc_base::load_config('sub_config','member_add_dir').'MemberUpgrade.php';

		$data = $this->post_api($api_url, $postdata);
		
		//��vms post���ݣ�����ȡ����ֵ
 		if ($data) { 
  			return $data;
		} else { 
			return $data;
   			return false;
		}
	}

	/**
	 * Function testapi
	 * ���Խӿ������Ƿ���ȷ
	 */
	public function testapi() {
		$postdata['method'] = 'Test';
		$data = $this->post($postdata);
		if ($data['code']==200) {
			return true;
		} else {
			return false;
		}
	} 
	
	/******************����Ϊ��Ƶͳ��ʹ��*****************/
	
	/*
	* �����Ƶ����������ͼ
	*/
	public function get_stat_bydate($start_time,$end_time,$pagesize,$page){
		//����post����
		$postdata['pagesize'] = $pagesize; 
		$postdata['page'] = $page;
		$postdata['start_time'] = $start_time; 
		$postdata['end_time'] = $end_time; 
		$postdata['method'] = 'GetStatBydate'; 
		
 		//��vms post���ݣ�����ȡ����ֵ
		$data = $this->post($postdata);
		return $data;
	}
	
	/*
	* ���ݹؼ�����������Ƶ
	*/
	public function get_video_bykeyword($type,$keyword){
		$postdata['type'] = $type; 
		$postdata['keyword'] = $keyword; 
		$postdata['method'] = 'GetVideoBykeyword';  
 		//��vms post���ݣ�����ȡ����ֵ
		$data = $this->post($postdata);  
		if ($data['code']==200) { 
  			return $data;
		} else { 
 			echo '�������ִ�������ϵ����Ա!';exit;
   			return false;
		}
	}
	
	/*
	* �鿴��Ƶ��������
	*/
	public function show_video_stat($vid){
		if(!$vid) return false;
		$postdata['vid'] = $vid; 
		$postdata['method'] = 'ShowVideoStat';  
 		//��vms post���ݣ�����ȡ����ֵ
		$data = $this->post($postdata);  
		if ($data['code']==200) { 
  			return $data;
		} else { 
 			echo '�鿴��Ƶͳ�Ƴ�������ϵ����Ա!'; 
   			return false;
		}
		
	}
	
	/*
	* ��Ƶ������������ͼ 
	*/
	public function vv_trend(){  
		$postdata['method'] = 'VvTrend';   
		$data = $this->post($postdata);  
		if ($data['code']==200) { 
  			return $data;
		} else { 
 			echo '��Ƶ������������ͼ!'; 
   			return false;
		} 
	}
	
	
	/*
	* ��ʱ��鿴������Ƶ�������а��Բ��Ŵ�������
	* $date 2012-02-03
	*/
	/* ���μ�ע�ͣ�����Ƿ����ã�
	public function get_stat_single($date){
		//����post���� 
		$postdata['method'] = 'get_stat_single';
		$postdata['pagesize'] = $pagesize;
		$postdata['date'] = $date;
		$postdata['page'] = $page; 
		
 		//��vms post���ݣ�����ȡ����ֵ
 		if ($data = $this->post($postdata)) { 
  			return $data;
		} else { 
 			echo 'û�з��ز�ѯʱ�������ݣ�';exit;
   			return false;
		}
	}
	*/
	//��������
	public function complete_info($data){
		//����post����
		$postdata = $data; 
		$postdata['user_back'] = APP_PATH . 'api.php?op=video_api';   
 		//��vms post���ݣ�����ȡ����ֵ 
		
		$url = $this->ku6api_api."CompleteInfo.php"; 
		$return_data = $this->post_api($url, $postdata);
  		if ($return_data['code']=='200') { 
   			return $return_data['data'];
		} else { 
 			return '-1'; 
		} 
	} 
	
	/*
	* ����û���д����ϸ����
	* ����ֵ�����û����Ƶ�����
	*/
	public function Get_Complete_Info($data){
		if (empty($data)) return false; 
		$url = $this->ku6api_api."Get_Complete_Info.php"; 
		$return_data = $this->post_api($url, $data);
   		if ($return_data['code']=='200') { 
   			return $return_data['data'];
		} else { 
  			return false; 
		} 
	}
	
	/*
	* ����û���д����ϸ����
	* ����ֵ�����û����Ƶ�����
	*/
	public function check_user_back($url){
		if (empty($url)) return false; 
		$data['url'] = $url;
		$url = $this->ku6api_api."Check_User_Back.php"; 
		$return_data = $this->post_api($url, $data);
   		if ($return_data['code']=='200') { 
   			return 200;
		} else { 
  			return -200; 
		} 
	}
	
	//������֤�뵽ָ���ʼ�
	public function send_code($data){
		if (empty($data)) return false; 
		$new_data['email'] = $data['email'];
		$new_data['url'] = $data['url'];
		$url = $this->ku6api_api."Send_Code.php";  
		$return_data = $this->post_api($url, $new_data); 
    	return $return_data;
	}
	
	//��֤�������֤�룬����email and  code
	public function check_email_code($data){
		if (empty($data)) return false;  
		$url =  $this->ku6api_api."Check_Email_Code.php";  
		$return_data = $this->post_api($url, $data); 
		if($return_data['code']=='200'){
			return $return_data['data'];
		}else{
			return false;
		} 
	}
	
	
	/**
	 * Function 
	 * ��ȡ�������б�
	 */
	public function player_list() {
		$postdata['method'] = 'PlayerList';
		$data = $this->post($postdata);
		if ($data['code']==200) {
			return $data;
		} else {
			return false;
		}
	}
	/**
	 * Function 
	 * ��ȡ�������б�
	 */
	public function player_edit($field,$style) {
		$postdata['method'] = 'PlayerEdit';
		$postdata['field'] = $field;
		$postdata['style'] = $style;
		$data = $this->post($postdata);
		if ($data['code']==200) {
			return $data;
		} else {
			return false;
		}
	} 

	/**
	 * FUNCTION post_api
	 * @post���ݵ�api��post������post���ݵ�api�����v5����post_api��post��api����
	 * @$data array post����
	 */
	private function post_api($url = '', $data = array()) {
		if (empty($url) || !preg_match("/^(http:\/\/)?([a-z0-9\.]+)(\/api)(\/[a-z0-9\._]+)/i", $url) || empty($data)) return false;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Client SDK ');
		$output = curl_exec($ch);
		$return_data = json_decode($output,true);
   		return $return_data;
	}
}