<?php
function sms_status($status = 0,$return_array = 0) {
	$array = array(
			'0'=>'���ͳɹ�',
			'1'=>'�ֻ�����Ƿ�',
			'2'=>'�û������ں������б�',
			'3'=>'�����û������������',
			'4'=>'��Ʒ���벻����',
			'5'=>'IP�Ƿ�',
			'6 '=>'Դ�������',
			'7'=>'�������ش���',
			'8'=>'��Ϣ���ȳ���60',
			'9'=>'���Ͷ������ݲ���Ϊ��',
			'10'=>'�û���������ͣ��ҵ��',
			'11'=>'wap���ӵ�ַ�������Ƿ�',
			'12'=>'5�����ڸ�ͬһ�����뷢�Ͷ��ų���10��',
			'13'=>'����ģ��IDΪ��',
			'14'=>'��ֹ���͸���Ϣ',
			'-1'=>'ÿ���ӷ������ֻ��ŵĶ��������ܳ���3��',
			'-2'=>'�ֻ��������',
			'-11'=>'�ʺ���֤ʧ��',
			'-10'=>'�ӿ�û�з��ؽ��',
		);
	return $return_array ? $array : $array[$status];
}

function checkmobile($mobilephone) {
		$mobilephone = trim($mobilephone);
		if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[01236789]{1}[0-9]{8}$|18[01236789]{1}[0-9]{8}$/",$mobilephone)){  
 			return  $mobilephone;
		} else {    
			return false;
		}
		
}

function get_smsnotice($type = '') {
	$url = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
	$urls = base64_decode('aHR0cDovL3Ntcy5waHBpcC5jb20vYXBpLnBocD9vcD1zbXNub3RpY2UmdXJsPQ==').$url."&type=".$type;
	$content = pc_file_get_contents($urls,5);
	if($content) {
		$content = json_decode($content,true);
		if($content['status']==1) {
			return strtolower(CHARSET)=='gbk' ?iconv('utf-8','gbk',$content['msg']) : $content['msg'];
		}
	}
	$urls = base64_decode('aHR0cDovL3Ntcy5waHBjbXMuY24vYXBpLnBocD9vcD1zbXNub3RpY2UmdXJsPQ==').$url."&type=".$type;
	$content = pc_file_get_contents($urls,3);
	if($content) {
		$content = json_decode($content,true);
		if($content['status']==1) {
			return strtolower(CHARSET)=='gbk' ?iconv('utf-8','gbk',$content['msg']) : $content['msg'];
		}
	}
	return '<font color="red">����ͨ�������޷����ʣ������޷�ʹ�ö���ͨ����</font>';
}

function sendsms($mobile, $send_txt, $tplid = 1, $id_code = '', $siteid=1) {

	pc_base::load_app_class('smsapi', 'sms', 0); //����smsapi��
	$sms_setting = getcache('sms','sms');
	$sms_uid = $sms_setting[$siteid]['userid'];//���Žӿ��û�ID
	$sms_pid = $sms_setting[$siteid]['productid'];//��ƷID
	$sms_passwd = $sms_setting[$siteid]['sms_key'];//32λ����

	$smsapi = new smsapi($sms_uid, $sms_pid, $sms_passwd); //��ʼ���ӿ���
	$mobile = explode(',',$mobile);
	
	$code = $smsapi->send_sms($mobile, $send_txt, 0, CHARSET,$id_code,$tplid,1); //���Ͷ���
	if($code==0) {
		return 0;
	} else {
		return sms_status($code,1);
	}
}