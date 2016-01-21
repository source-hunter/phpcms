<?php 

/**
 * ��������ַ���
 * @param string $lenth ����
 * @return string �ַ���
 */
function create_randomstr($lenth = 6) {
	return random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
}

/**
 * 
 * @param $password ����
 * @param $random �����
 */
function create_password($password='', $random='') {
	if(empty($random)) {
		$array['random'] = create_randomstr();
		$array['password'] = md5(md5($password).$array['random']);
		return $array;
	}
	return md5(md5($password).$random);
}
/**
 * ������볤���Ƿ���Ϲ涨
 *
 * @param STRING $password
 * @return 	TRUE or FALSE
 */
function is_password($password) {
	$strlen = strlen($password);
	if($strlen >= 6 && $strlen <= 20) return true;
	return false;
}

 /**
 * ����������Ƿ��д����ַ�
 *
 * @param char $string Ҫ�����ַ�������
 * @return TRUE or FALSE
 */
function is_badword($string) {
	$badwords = array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n","#");
	foreach($badwords as $value){
		if(strpos($string, $value) !== FALSE) {
			return TRUE;
		}
	}
	return FALSE;
}

/**
 * ����û����Ƿ���Ϲ涨
 *
 * @param STRING $username Ҫ�����û���
 * @return 	TRUE or FALSE
 */
function is_username($username) {
	$strlen = strlen($username);
	if(is_badword($username) || !preg_match("/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/", $username)){
		return false;
	} elseif ( 20 < $strlen || $strlen < 2 ) {
		return false;
	}
	return true;
}
?>