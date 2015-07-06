<?php 
defined('IN_PHPCMS') or exit('No permission resources.');

/**
 * 
 * ------------------------------------------
 * video import class
 * ------------------------------------------
 * 
 * ����KU6��Ƶ
 *  
 * @copyright	CopyRight (c) 2006-2012 �Ϻ�ʢ�����緢չ���޹�˾
 * 
 */
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('form', 0, 0);
pc_base::load_app_func('global', 'video'); 
pc_base::load_sys_class('push_factory', '', 0);

class import extends admin {
	
	public $db,$module_db; 
	public function __construct() {
		parent::__construct();
		$this->db = pc_base::load_model('video_store_model');
		$this->module_db = pc_base::load_model('module_model');
		$this->userid = $_SESSION['userid'];
		pc_base::load_app_class('ku6api', 'video', 0);
		pc_base::load_app_class('v', 'video', 0);
		$this->v =  new v($this->db);
		
		//��ȡ����ƽ̨������Ϣ
		$this->setting = getcache('video');
		if(empty($this->setting) && ROUTE_A!='setting') {
			showmessage(L('video_setting_not_succfull'), 'index.php?m=video&c=video&a=setting&meunid='.$_GET['meunid']);
		}
		$this->ku6api = new ku6api($this->setting['sn'], $this->setting['skey']);
	}
	
	/**
	* ִ����Ƶ���� 
	*/
	public function doimport(){
		$importdata = $_POST['importdata'];
		$select_category = intval($_POST['select_category']);//��ĿID
		$is_category = intval($_POST['is_category']);//�Ƿ�����Ŀ
 		$siteid = get_siteid();
		$ids = $_POST['ids'];
		$datas = array();
 		if(is_array($ids)){
 			foreach ($_POST['importdata'] as $vv) {//���鹴ѡ����
				if(in_array($vv['vid'], $ids)) {
					$datas[] = $vv;
				}
			}
			
			$video_store_db = pc_base::load_model('video_store_model');
			$content_model = pc_base::load_model('content_model');
			$content_model->set_catid($select_category);
			$CATEGORYS = getcache('category_content_'.$siteid,'commons');
			$modelid = $CATEGORYS[$select_category]['modelid'];// ��ѡ��Ƶ��Ŀ��Ӧ��modelid
			$model_field = pc_base::load_model('sitemodel_field_model');
			$r = $model_field->get_one(array('modelid'=>$modelid, 'formtype'=>'video'), 'field');
			$fieldname = $r['field'];//�����Ƶ�ֶ�
			
			//�����Ƽ�λʹ��
			$this->push = push_factory::get_instance()->get_api('admin');
  			//ѭ����ѡ���ݣ���������ku6vms���ӿڽ�����⣬�ɹ�����뱾ϵͳ��Ӧ��Ŀ�����Զ�����video_content��Ӧ��ϵ 
			$new_s = array();
 			foreach ($datas as $data) {
  				$data['cid'] = $select_category;
				$data['import'] = 1;
				$data['channelid'] = 1;
				$return_data = array();
  				$return_data = $this->ku6api->vms_add($data);//����VMS,�����ܲ���ʹ�õ�vid
				//$new_s[] = $return_data;
   				$vid = $return_data['vid'];
				if(!$vid){
					showmessage('����VMSϵͳʱ����������',HTTP_REFERER);
				}
  				//�뱾����Ƶ��
				
				$video_data = array();
				$video_data['title'] = str_cut($data['title'],80,false);
				$video_data['vid'] = $vid;
				$video_data['keywords'] = str_cut($data['tag'],36);
				$video_data['description'] = str_cut($data['desc'],200);
				$video_data['status'] = $data['status'];
				$video_data['addtime'] = $data['uploadtime'] ? substr($data['uploadtime'],0,10) : SYS_TIME;
				$video_data['picpath'] = safe_replace( format_url($data['picpath']) );
 				$video_data['timelen'] = intval($data['timelen']);
				$video_data['size'] = intval($data['size']); 
				$video_data['channelid'] = 1; 
				
				$videoid = $video_store_db->insert($video_data, true);//������Ƶ��
 				
				if($is_category==1){//��Ƶֱ�ӷ�����ָ����Ŀ
					//���POST����
					//����ģ��id���õ���Ƶ�ֶ���
					$content_data = array();
					
					$content_data[$fieldname] = 1;
					$content_data['catid'] = $select_category;
					$content_data['title'] = str_cut($data['title'],80,' '); 
					$content_data['content'] = $data['desc']; 
					$content_data['description'] = str_cut($data['desc'],198,' '); 
					$content_data['keywords'] = str_cut($data['tag'],38,' ');
					$content_data = array_filter($content_data,'rtrim');
					$content_data['thumb'] = $data['picpath']; 
					$content_data['status'] = 99;  
					//���POST����,���ʱ���Զ���Ӧ��ϵ 
					$_POST[$fieldname.'_video'][1] = array('videoid'=>$videoid, 'listorder'=>1); 
					//���ӿڣ��������ݿ�
					$cid = $content_model->add_content($content_data); 
					
					//���Ƽ�λ
					$position = $_POST['sub']['posid'];
					if($position){
						$info = array();//����ύ��Ϣ����
						$pos_content_data = $content_data;
						$pos_content_data['id'] = $cid;
						$pos_content_data['inputtime'] = SYS_TIME;
						$pos_content_data['updatetime'] = SYS_TIME;
						$info[$cid]= $pos_content_data;//��Ϣ����
						
						$pos_array = array();//�Ƽ�λID��Ҫ������������ʹ��
						$pos_array[] = $position;
						
						$post_array = '';//position ����
						$post_array['modelid'] = $modelid;
						$post_array['catid'] = $select_category;
						$post_array['id'] = $cid; 
						$post_array['posid'] = $pos_array;
						$post_array['dosubmit'] = '�ύ';
						$post_array['pc_hash'] = $_GET['pc_hash'];
						 
						$this->push->position_list($info, $post_array);//����admin position_list()����
					}
					
					//���µ������ 
					if ($data['viewcount']) {
						$views = intval($data['viewcount']);
						$hitsid = 'c-'.$modelid.'-'.$cid;
						$count = pc_base::load_model('hits_model');
						$count->update(array('views'=>$views), array('hitsid'=>$hitsid));
					} 
				}
				 
  			}
			$page = intval($_POST['page']) + 1;
			if($_POST['fenlei'] || $_POST['keyword']){
				$forward = "?m=video&c=video&a=import_ku6video&menuid=".$_POST['menuid']."&fenlei=".$_POST['fenlei']."&srctype=".$_POST['srctype']."&videotime=".$_POST['videotime']."&keyword=".$_POST['keyword']."&dosubmit=%CB%D1%CB%&page=".$page;
			}else{
				$forward = "?m=video&c=video&a=import_ku6video&menuid=".$_POST['menuid'];
			}
			
     		showmessage('KU6��Ƶ����ɹ������ڷ��أ�',$forward);
		}else{
 			showmessage('��ѡ��Ҫ�������Ƶ��',HTTP_REFERER);
		}
	} 
	
	/**
	* ��ȡվ����Ŀ����
	*/
	 
	/**
	 * 
	 * ��Ƶ�б�
	 */
	public function init() {
		$where = '1';
		$page = $_GET['page'];
		$pagesize = 20;
		if (isset($_GET['type'])) {
			if ($_GET['type']==1) {
				$where .= ' AND `videoid`=\''.$_GET['q'].'\'';
			} else {
				$where .= " AND `title` LIKE '%".$_GET['q']."%'";
			}
		}
		if (isset($_GET['start_time'])) {
			$where .= ' AND `addtime`>=\''.strtotime($_GET['start_time']).'\'';
		}
		if (isset($_GET['end_time'])) {
			$where .= ' AND `addtime`<=\''.strtotime($_GET['end_time']).'\'';
		}
		if (isset($_GET['status'])) {
			$status = intval($_GET['status']);
			$where .= ' AND `status`=\''.$status.'\'';
		}
		$infos = $this->db->listinfo($where, 'videoid DESC', $page, $pagesize);
		$pages = $this->db->pages;
		include $this->admin_tpl('video_list');		
	}   
}

?>