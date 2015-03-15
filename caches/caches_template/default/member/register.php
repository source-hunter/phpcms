<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>phpcmsV9 - <?php echo L('member','','member').L('manage_center');?></title>
<script type="text/javascript" src="<?php echo JS_PATH;?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>member_common.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>formvalidator.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>formvalidatorregex.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>dialog.js"></script>
<link href="<?php echo CSS_PATH;?>reset.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_PATH;?>dialog_simp.css" rel="stylesheet" type="text/css" />
<script language="JavaScript">
<!--
$(function(){
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});

	$("#username").formValidator({onshow:"<?php echo L('input').L('username');?>",onfocus:"<?php echo L('username').L('between_2_to_20');?>"}).inputValidator({min:2,max:20,onerror:"<?php echo L('username').L('between_2_to_20');?>"}).regexValidator({regexp:"ps_username",datatype:"enum",onerror:"<?php echo L('username').L('format_incorrect');?>"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=member&c=index&a=public_checkname_ajax",
		datatype : "html",
		async:'false',
		success : function(data){
            if( data == "1" ) {
                return true;
			} else {
                return false;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "<?php echo L('deny_register').L('or').L('user_already_exist');?>",
		onwait : "<?php echo L('connecting_please_wait');?>"
	});
	$("#password").formValidator({onshow:"<?php echo L('input').L('password');?>",onfocus:"<?php echo L('password').L('between_6_to_20');?>"}).inputValidator({min:6,max:20,onerror:"<?php echo L('password').L('between_6_to_20');?>"});
	$("#pwdconfirm").formValidator({onshow:"<?php echo L('input').L('cofirmpwd');?>",onfocus:"<?php echo L('passwords_not_match');?>",oncorrect:"<?php echo L('passwords_match');?>"}).compareValidator({desid:"password",operateor:"=",onerror:"<?php echo L('passwords_not_match');?>"});
	$("#email").formValidator({onshow:"<?php echo L('input').L('email');?>",onfocus:"<?php echo L('email').L('format_incorrect');?>",oncorrect:"<?php echo L('email').L('format_right');?>"}).inputValidator({min:2,max:32,onerror:"<?php echo L('email').L('between_2_to_32');?>"}).regexValidator({regexp:"email",datatype:"enum",onerror:"<?php echo L('email').L('format_incorrect');?>"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=member&c=index&a=public_checkemail_ajax",
		datatype : "html",
		async:'false',
		success : function(data){	
            if( data == "1" ) {
                return true;
			} else {
                return false;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "<?php echo L('deny_register').L('or').L('email_already_exist');?>",
		onwait : "<?php echo L('connecting_please_wait');?>"
	});
	$("#nickname").formValidator({onshow:"<?php echo L('input').L('nickname');?>",onfocus:"<?php echo L('nickname').L('between_2_to_20');?>"}).inputValidator({min:2,max:20,onerror:"<?php echo L('nickname').L('between_2_to_20');?>"}).regexValidator({regexp:"ps_username",datatype:"enum",onerror:"<?php echo L('nickname').L('format_incorrect');?>"}).ajaxValidator({
			type : "get",
			url : "",
			data :"m=member&c=index&a=public_checknickname_ajax",
			datatype : "html",
			async:'false',
			success : function(data){
				if( data == "1" ) {
					return true;
				} else {
					return false;
				}
			},
			buttons: $("#dosubmit"),
			onerror : "<?php echo L('already_exist').L('already_exist');?>",
			onwait : "<?php echo L('connecting_please_wait');?>"
		});

	$(":checkbox[name='protocol']").formValidator({tipid:"protocoltip",onshow:"<?php echo L('read_protocol');?>",onfocus:"<?php echo L('read_protocol');?>"}).inputValidator({min:1,onerror:"<?php echo L('read_protocol');?>"});
	
	<?php if($member_setting['mobile_checktype']=='2' && $sms_setting['sms_enable']=='1') { ?>
	$("#mobile").formValidator({onshow:"请输入手机号码",onfocus:"请输入手机号码"}).inputValidator({min:1,max:11,onerror:"请输入正确的手机号码"});
	<?php } ?>
	$("#mobile_verify").formValidator({onshow:"请输入手机收到的验证码",onfocus:"请输入手机收到的验证码"}).inputValidator({min:1,onerror:"请输入手机收到的验证码"}).ajaxValidator({
					type : "get",
					url : "api.php",
					data :"op=sms_idcheck&action=id_code",
					datatype : "html",
					<?php if($member_setting['mobile_checktype']=='2') { ?>
					getdata:{mobile:"mobile"},
					<?php } ?>
					async:"false",
					success : function(data){
						if( data == "1" ) {
							return true;
						} else {
  							return false;
						}
					},
					buttons: $("#dosubmit"),
					onerror : "验证码错误",
					onwait : "请稍候..."
				});

	<?php echo $formValidator;?>

	<?php if(!isset($_GET['modelid']) && !isset($_GET['t']) && !empty($member_setting['showregprotocol'])) { ?>
		show_protocol();
	<?php } ?>
});

function show_protocol() {
	art.dialog({lock:false,title:'<?php echo L('register_protocol');?>',id:'protocoliframe', iframe:'?m=member&c=index&a=register&protocol=1',width:'500',height:'310',yesText:'<?php echo L('agree');?>'}, function(){
		$("#protocol").attr("checked",true);
	});
}

//-->
</script>
<link href="<?php echo CSS_PATH;?>table_form.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.submit,.pass-logo a,.form-login .input label,.item span,#content h2 span em{display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline;}
.blue,.blue a{color:#377abe},.submit input{cursor:hand;}
.log{line-height:24px; height:24px;float:right; font-size:12px}
.log span{color:#ced9e7}
.log a{color:#049;text-decoration: none;}
.log a:hover{text-decoration: underline;}
#header{ height:94px; background:url(<?php echo IMG_PATH;?>member/h.png) repeat-x}
#header .logo{ padding-right:100px;float:left;background:url(<?php echo IMG_PATH;?>member/login-logo.png) no-repeat right 2px;}
#header .content{width:920px; margin:auto; height:60px;padding:10px 0 0 0}
#content{width:920px; margin:auto; padding:20px 0 0 0; overflow:auto}
.form-login{width:420px; padding-left:40px}
#content h2{font-size:25px;color:#494949;border-bottom: 1px dashed #CCC;padding-bottom:3px; margin-bottom:10px}
#content h2 span{font-size:12px; font-weight:normal}
#content h2 span em{background: url(<?php echo IMG_PATH;?>member/order.png) no-repeat 0px -16px; width:15px; height:15px; line-height:15px; text-align:center; margin-right:5px; color:#FFF}
#content h2 span.on{ color:#333; font-weight:700}
#content h2 span.on em{background-position: 0px 0px;}

.form-login .input{ padding:7px 0; overflow:hidden; clear:both}
.form-login .input label{ width:84px;font-size:14px; color:#8c8686; text-align:right; float:left}
.form-login .input .form{ width:560px; float:left}
.take,.reg{padding:0 0 0 84px}
.take .submit{margin-top:10px}
.form-login .hr{background: url(<?php echo IMG_PATH;?>member/line.png) no-repeat left center; height:50px;}
.form-login .hr hr{ display:none}

.form-reg{padding:10px 0 0 14px; width:700px; border-right:1px solid #ccc}
.form-reg .input label{ width:120px}
.form-reg .input label.type{ width:auto; color:#000; padding-right:10px}
.form-reg .reg{padding:10px 0 0 120px}
.form-reg .reg .submit{ margin-bottom:5px}

.submit{padding-left:3px}
.submit,.submit input{ background: url(<?php echo IMG_PATH;?>member/but.png) no-repeat; height:29px;width:auto;_width:0;overflow:visible !ie}
.submit input{background-position: right top; border:none; padding:0 10px 0 7px; font-size:14px}
.reg{ color:#666; line-height:24px}
.reg .submit{background-position: left -35px; height:35px}
.reg .submit input{background-position: right -35px; font-weight:700; color:#fff; height:35px}
.reg-auto{ padding:10px 0 0 20px}
.reg-auto p{ margin-bottom:10px; color:#666;}
.col-1{position:relative; float:right; border:1px solid #c4d5df; zoom:1;background: url(<?php echo IMG_PATH;?>member/member_title.png) repeat-x; width:310px; margin: auto; height:304px}
.col-1 span.o1,
	.col-1 span.o2,
	.col-1 span.o3,
	.col-1 span.o4{position:absolute;width:3px;height:3px;background: url(<?php echo IMG_PATH;?>fillet.png) no-repeat}
	.col-1 span.o1{background-position: left -6px; top:-1px; left:-1px}
	.col-1 span.o2{background-position: right -6px; top:-1px; right:-1px}
	.col-1 span.o3{background-position: left bottom; bottom:-1px; left:-1px}
	.col-1 span.o4{background-position: right bottom; bottom:-1px; right:-1px;}
.col-1 .title{color:#386ea8; padding:5px 10px 3px}
.col-1 div.content{padding:0px 10px 10px}
.col-1 div.content h5{background: url(<?php echo IMG_PATH;?>member/ext-title.png) no-repeat 2px 10px; height:34px}
.col-1 div.content h5 strong{ visibility: hidden}
.pass-logo{ margin:auto; width:261px; padding-top:15px}
.pass-logo a img{ border:1px solid #ddd}
.pass-logo a{border:3px solid #fff}
.pass-logo a.logo,
.pass-logo a:hover{border:3px solid #e8f1f1;}
.pass-logo p{border-top: 1px solid #e1e4e8; padding-top:15px}
.item{padding:10px 0; vertical-align:middle; margin-bottom:10px}
.item span{ color:#8c8686}

#footer{color:#666; line-height:24px;width:920px; margin:auto; text-align:center; padding:12px 0; margin-top:52px; border-top:1px solid #e5e5e5}
#footer a{color:#666;}

.point{border:1px solid #ffbf7a; background:#fffced; margin-bottom:10px; margin-right:100px;margin-left:50px;position:relative}
.point .content{padding:8px 10px;}
.point .content .title{color:#ff8400}
.point .content p{color:#777; text-indent:20px}
.point span.o1,
	.point span.o2,
	.point span.o3,
	.point span.o4{position:absolute;width:3px;height:3px;background: url(<?php echo IMG_PATH;?>fillet.png) no-repeat; overflow:hidden}
	.point span.o1{background-position: left top; top:-1px; left:-1px}
	.point span.o2{background-position: right top; top:-1px; right:-1px}
	.point span.o3{background-position: left -3px; bottom:-1px; left:-1px}
	.point span.o4{background-position: right -3px; bottom:-1px; right:-1px;}
.submit button.hqyz{margin:0px; padding:0px; border:none; cursor:pointer; }
.submit button.hqyz{
    background-position: 100% 0%;
    border: medium none;
    font-size: 12px;
    padding: 0 10px 0 7px;
}
.submit button.hqyz{
    background: url("<?php echo IMG_PATH;?>member/but.png") no-repeat 100% 0px;
    height: 29px;
    width: auto;
	
}

#mobile_div input{*margin-bottom:12px;*_padding:0px 0px 6px 0px}
</style>
</head>
<body>
<div id="header">
	<div class="content">
	<div class="logo"><img src="<?php echo IMG_PATH;?>v9/member_logo.jpg"/></div>
    <span class="log"></span>
    </div>
</div>

<div id="content">
	<h2><?php echo L('member').L('register');?>&nbsp;&nbsp;&nbsp;&nbsp;<span <?php if(!isset($_GET['t'])) { ?>class="on"<?php } ?>><em>1</em><?php echo L('fill_in').L('info');?></span>
	<?php if($member_setting['enablemailcheck']) { ?>
	<span <?php if(isset($_GET['t']) && $_GET['t']==2) { ?>class="on"<?php } ?>><em>2</em><?php echo L('email').L('validate');?></span>
	<span><em>3</em><?php echo L('register').L('success');?></span>
	<?php } elseif ($member_setting['registerverify']) { ?>
	<span class="on"><em>2</em><?php echo L('administrator').L('verify');?></span><span> <em>3</em><?php echo L('register').L('success');?></span>
	<?php } ?>
	</h2>

<?php if(!isset($_GET['t'])) { ?>
<form method="post" action="" id="myform">
	<input type="hidden" name="siteid" value="<?php echo $siteid;?>" />

	<div class="col-left form-login form-reg">

		<?php if($member_setting['choosemodel'] && count($modellist)>1) { ?>
		<!--是否开启选择会员模型选项-->
    	<div class="point">
            <div class="content">
				<strong class="title"><?php echo L('notice');?></strong>
				<p><?php echo L('register_notice');?></p>
				<p><?php echo $description;?></p>
            </div>
            <span class="o1"></span><span class="o2"></span><span class="o3"></span><span class="o4"></span>
        </div>

		<div class="input"><label><?php echo L('member_model');?>：</label>
			<?php $n=1; if(is_array($modellist)) foreach($modellist AS $k => $v) { ?>
			<label class="type"><input name="modelid" type="radio" value="<?php echo $k;?>" <?php if($k==$modelid) { ?>checked<?php } ?> onclick="changemodel($(this).val())" /><?php echo $v['name'];?></label>
			<?php $n++;}unset($n); ?>
		</div>
		<?php } else { ?>
			<?php $n=1; if(is_array($modellist)) foreach($modellist AS $k => $v) { ?>
			<input name="modelid" type="hidden" value="<?php echo $k;?>"/>
			<?php $n++;}unset($n); ?>
		<?php } ?>

    	<div class="input"><label><?php echo L('username');?>：</label><input type="text" id="username" name="username" size="36" class="input-text"></div>
        <div class="input"><label><?php echo L('password');?>：</label><input type="password" id="password" name="password" size="36" class="input-text"></div>
        <div class="input"><label><?php echo L('cofirmpwd');?>：</label><input type="password" name="pwdconfirm" id="pwdconfirm" size="36" class="input-text"></div>
        <div class="input"><label><?php echo L('email');?>：</label><input type="text" id="email" name="email" size="36" class="input-text"></div>
		<div class="input"><label><?php echo L('nickname');?>：</label><input type="text" id="nickname" name="nickname" size="36" class="input-text"></div>
		<?php if($member_setting['choosemodel']) { ?>
			<!--是否开启选择会员模型选项-->
			<script language="JavaScript">
			<!--
				function changemodel(modelid) {
					redirect('<?php echo APP_PATH;?>index.php?m=member&c=index&a=register&modelid='+modelid+'&siteid=<?php echo $siteid;?>');
				}
			//-->
			</script>

			<?php $n=1; if(is_array($forminfos)) foreach($forminfos AS $k => $v) { ?>
				<div class="input"><label><?php if($v['isbase']) { ?><font color=red>*</font><?php } ?> <?php echo $v['name'];?>：<?php if($v['tips']) { ?><br />(<?php echo $v['tips'];?>)<?php } ?></label><div class="form"><?php echo $v['form'];?></div></div>
			<?php $n++;}unset($n); ?>
		<?php } ?>
		
		
		<?php if($sms_setting['sms_enable']=='1') { ?>
		<div class="input"><label><?php echo L('checkcode');?>：</label><input type="text" id="code" name="code" size="10" class="input-text"><?php echo form::checkcode('code_img', '5', '14', 120, 26);?></div>

		<div class="input"><label> 手机号码：</label><div class="form"><div id="mobile_div"><input type="text" name="mobile" id="mobile" value="" size="15" class="input-text" title="此服务免费,验证码将以短信免费发送到您的手机"> 
			<div class="submit"><button onclick="get_verify()" type="button" class="hqyz">获取短信验证码</button></div> <span id="mobileTip"></span>
			<br>
			</div>
			<div id="mobile_send_div" style="display:none">此服务免费,验证码已发送到<span id="mobile_send"></span>，<span id="edit_mobile" style="display:none"><a href="javascript:void();" onclick="edit_mobile()">修改号码</a>，</span> 如果超过120秒未收到验证码，您可以免费重新获取。<br><br>
			<div class="submit"><button type="button" id="GetVerify" onclick="get_verify()" class="hqyz">重获短信验证码</button></div> <br><br></div> 
			<script language="JavaScript">
			<!--
				var times = 120;
				var isinerval;
				function get_verify() {
					var session_code = $('#code').val();
					if(session_code=='') {
						alert('请输入验证码');
						$('#code').focus();
						return false;
					}
					var mobile = $("#mobile").val();
					var partten = /^1[3-9]\d{9}$/;
					if(!partten.test(mobile)){
						alert("请输入正确的手机号码");
						$('#mobile').focus();
						return false;
					}
					
					$.get("api.php?op=sms",{ mobile: mobile,session_code:session_code,random:Math.random()}, function(data){
						if(data=="0") {
							$("#mobile_send").html(mobile);
							$("#mobile_div").css("display","none");
							$("#mobile_send_div").css("display","");
							times = 120;
							$("#GetVerify").attr("disabled", true);
							isinerval = setInterval("CountDown()", 1000);
						} else if(data=="-1") {
							alert("你今天获取验证码次数已达到上限");
						} else if(data=="-100") {
							$('#code').val('');
							alert("验证码已失效，请点击图片验证码获取新的验证码！");
							$('#code').focus();
						} else if(data=="-101") {
							alert("验证码错误！");
							$('#code').focus();
						} else {
							alert("短信发送失败");
						}
					});
					
				}
				function CountDown() {
					if (times < 1) {
						$("#GetVerify").html("获取短信验证码").attr("disabled", false);
						$("#edit_mobile").css("display","");
						clearInterval(isinerval);
						return;
					}
					$("#GetVerify").html(times+"秒后重获验证码");
					times--;
				}
				function edit_mobile() {
					$("#mobile_div").css("display","");
					$("#mobile_send_div").css("display","none");
				}
			//-->
			</script>
		    </div></div>
			<div class="input"><label>短信校验码：</label><input type="text" name="mobile_verify" id="mobile_verify" value="" size="14" class="input-text"></div>	
		
		<?php } ?>
		
		<?php if($member_setting['enablcodecheck']=='1' && $sms_setting['sms_enable']!='1') { ?>
		<div class="input"><label><?php echo L('checkcode');?>：</label><input type="text" id="code" name="code" size="10" class="input-text"><?php echo form::checkcode('code_img', '5', '14', 120, 26);?></div>
		<?php } ?>
		
		
		<!-- 注释验证码
        
		-->
        	<div class="reg">
                <div class="submit"><input type="submit" name="dosubmit" value="<?php echo L('agree_protocol_post');?>"></div><br />
                <input type="checkbox" name="protocol" id="protocol" value=""><a href="javascript:void(0);" onclick="show_protocol();return false;" class="blue"><?php echo L('click_read_protocol');?></a>
            </div>
        </div>
</form>
<?php } elseif (isset($_GET['t']) && $_GET['t']==2) { ?>
<div class="col-left form-login form-reg">
<?php $emailurl = param::get_cookie('email') ? str_replace('@', '',strstr(param::get_cookie('email'), '@')) : '';?>
<?php echo param::get_cookie('_username');?> <?php echo L('hellow');?>，<?php echo L('login_email_authentication');?> <?php if($emailurl) { ?> <?php echo L('please_click');?><a href="http://mail.<?php echo $emailurl;?>"><?php echo L('login_email');?></a><br><br>
如果没有收到邮件，请点击<a onclick="$('#send_newemail').show()"><font color="red">这里</font></a>更换邮箱试试<br><br>
<div style="display:none" id="send_newemail">
<input type="text" id="newemail" name="newemail" size="36" class="input-text"> 
<div class="submit"><input type="submit" name="dosubmit" value="重新发送新邮箱验证" onclick="javascript:send_newmail();"></div></div>
<script language="JavaScript">
function send_newmail() {
	//var mail_type = $('input[checkbox=mail_type][checked]').val();
	var newemail = $('#newemail').val();
 $.post('?m=member&c=index&a=send_newmail&newemail='+newemail,{},function(data){
 	if(data=='1'){alert('发送成功，请查看验证！');}else if(data=='-1'){alert('邮箱已被占用！');}else{alert('发送错误，请联系管理员！');}
	});
} 
</script>
<?php } ?>
</div>
<?php } elseif (isset($_GET['t']) && $_GET['t']==3) { ?>
<div class="col-left form-login form-reg">
<?php echo param::get_cookie('_username');?> <?php echo L('hellow');?>，<?php echo L('please_wait_administrator_verify');?>
</div>
<?php } else { ?>
<script language="JavaScript">
<!--
	redirect("<?php echo APP_PATH;?>index.php?m=member&c=index&a=login");
//-->
</script>
<?php } ?>

    <div class="col-auto reg-auto">
    	<p class="f14">
        <?php echo L('already_have_account');?>
        </p>
        <div class="submit"><input type="submit" name="dosubmit" value="<?php echo L('login');?>" onclick="redirect('<?php echo APP_PATH;?>index.php?m=member&c=index&a=login')"></div>
    </div>
</div>

<?php include template('member', 'footer'); ?>