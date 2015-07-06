<?php
defined('IN_PHPCMS') or exit('No permission resources.');
/**
 * ���ŷ��ͽӿ�
 */

$sms_report_db = pc_base::load_model('sms_report_model');
$session_storage = 'session_'.pc_base::load_config('system','session_storage');
pc_base::load_sys_class($session_storage);

if(empty($_SESSION['code'])) exit('-100');
if(empty($_GET['session_code']) || preg_match('/^([a-z0-9])$/i',$_GET['session_code']) || $_SESSION['code']!=$_GET['session_code']) exit('-101');

if(isset($_GET['mobile']) && !empty($_GET['mobile'])) {
	$mobile = $_GET['mobile'];
} else {
	$mobile = $_SESSION['mobile'];
}
$_SESSION['code'] = '';
if(!isset($_SESSION['csms'])) {
	$_SESSION['csms'] = 0;
} elseif($_SESSION['csms'] > 3) {
	exit('-1');
}
$_SESSION['csms'] += 1;

$siteid = get_siteid() ? get_siteid() : 1 ;
$sms_setting = getcache('sms','sms');
if(!preg_match('/^(?:13\d{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|18[0|2|3|5|6|7|8|9]\d{8}|14[5|7]\d{8})$/',$mobile)) exit('mobile phone error');
$posttime = SYS_TIME-86400;
$where = "`mobile`='$mobile' AND `posttime`>'$posttime'";
$num = $sms_report_db->count($where);
if($num > 3) {
	exit('-1');//���շ��Ͷ��������������� 3 ��
}
//ͬһIP 24Сʱ��������������
$allow_max_ip = 10;//����ע���൱�� 10 ����
$ip = ip();
$where = "`ip`='$ip' AND `posttime`>'$posttime'";
$num = $sms_report_db->count($where);
if($num >= $allow_max_ip) {
	exit('-3');//���յ�IP ���Ͷ����������� $allow_max_ip
}
if(intval($sms_setting[$siteid]['sms_enable']) == 0) exit('-99'); //���Ź��ܹر�


$sms_uid = $sms_setting[$siteid]['userid'];//���Žӿ��û�ID
$sms_pid = $sms_setting[$siteid]['productid'];//��ƷID
$sms_passwd = $sms_setting[$siteid]['sms_key'];//32λ����

$posttime = SYS_TIME-600;
$rs = $sms_report_db->get_one("`mobile`='$mobile' AND `posttime`>'$posttime'");
if($rs['id_code']) {
	$id_code = $rs['id_code'];
} else {
	$id_code = random(6);//Ψһ��������չ��֤
}
//$send_txt = '�𾴵��û����ã�����'.$sitename.'��ע����֤��Ϊ��'.$id_code.'����֤����Ч��Ϊ5���ӡ�';
$send_txt = $id_code;

$send_userid = intval($_GET['send_userid']);//������id

pc_base::load_app_class('smsapi', 'sms', 0); //����smsapi��

$smsapi = new smsapi($sms_uid, $sms_pid, $sms_passwd); //��ʼ���ӿ���
//$smsapi->get_price(); //��ȡ����ʣ�����������ƶ��ŷ��͵�ip��ַ
$mobile = explode(',',$mobile);

$tplid = 1;
$sent_time = intval($_POST['sendtype']) == 2 && !empty($_POST['sendtime'])  ? trim($_POST['sendtime']) : date('Y-m-d H:i:s',SYS_TIME);
$smsapi->send_sms($mobile, $send_txt, $sent_time, CHARSET,$id_code,$tplid); //���Ͷ���
echo 0;
?>