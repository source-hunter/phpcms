<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>member_common.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidatorregex.js" charset="UTF-8"></script>
<script type="text/javascript">
<!--
$(function(){
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});
	$("#companyname").formValidator({onshow:"<?php echo L('input').L('��˾����');?>",onfocus:"<?php echo L('��˾���Ʋ��ܿ�');?>"}).inputValidator({min:1,max:999,onerror:"<?php echo L('��˾���Ʋ���Ϊ��');?>"});
	$("#address").formValidator({onshow:"<?php echo L('input').L('��˾��ַ');?>",onfocus:"<?php echo L('��ϵ��ַ���ܿ�');?>"}).inputValidator({min:1,max:999,onerror:"<?php echo L('��ϵ��ַ����Ϊ��');?>"});
	$("#telephone").formValidator({onshow:"<?php echo L('input').L('��ϵ�绰');?>",onfocus:"<?php echo L('��ϵ�绰���ܿ�');?>"}).inputValidator({min:1,max:999,onerror:"<?php echo L('��ϵ�绰����Ϊ��');?>"});
	$("#contact_name").formValidator({onshow:"<?php echo L('input').L('��ϵ��');?>",onfocus:"<?php echo L('��ϵ�˲��ܿ�');?>"}).inputValidator({min:1,max:999,onerror:"<?php echo L('��ϵ�˲���Ϊ��');?>"});
	$("#contact_telephone").formValidator({onshow:"<?php echo L('input').L('��ϵ��ʽ ');?>",onfocus:"<?php echo L('��ϵ��ʽ����Ϊ��');?>"}).inputValidator({min:1,max:999,onerror:"<?php echo L('��ϵ��ʽ����Ϊ��');?>"});
	$("#contact_mobile").formValidator({onshow:"<?php echo L('input').L('�ֻ�����Ϊ��');?>",onfocus:"<?php echo L('�ֻ�����Ϊ��');?>"}).inputValidator({min:1,max:999,onerror:"<?php echo L('�ֻ�����Ϊ��');?>"});
	$("#email").formValidator({onshow:"������E-mail",onfocus:"������E-mail",oncorrect:"E-mail��ʽ��ȷ"}).regexValidator({regexp:"email",datatype:"enum",onerror:"E-mail��ʽ����"});
})
//-->
</script>
<div class="pad-10">
<div class="explain-col search-form">
<?php echo L('subscribe_notic');?>
</div>
<div class="common-form">
<form name="myform" action="?m=video&c=video&a=complete_info&pc_hash=<?php echo $_GET['pc_hash'];?>" method="post" id="myform">
<input type="hidden" name="info[uid]" id="uid" value="<?php echo $uid;?>">
<input type="hidden" name="info[snid]" id="snid" value="<?php echo $snid;?>">
<fieldset>
	<legend><?php echo L('��λ����');?></legend>
<table width="100%" class="table_form">
	<tr>
		<td  width="120"><?php echo L('��˾����');?></td> 
		<td><input name="info[companyname]"  type="text" id="companyname"  size="40" value="<?php echo $complete_info['companyname'];?>"> </td>
	</tr>
	<tr>
		<td  width="120"><?php echo L('�绰');?></td> 
		<td><input type="text" name="info[telephone]" size="20" value="<?php echo $complete_info['telephone'];?>" id="telephone"></td>
	</tr>
	<tr>
		<td  width="120"><?php echo L('��ϵ��ַ');?> </td> 
		<td><input type="text" name="info[address]" size="40" value="<?php echo $complete_info['address'];?>" id="address"></td>
	</tr>
	<tr>
		<td  width="120"><?php echo L('��ַ');?> </td> 
		<td> <input type="text" name="info[website]" size="40" value="<?php echo $complete_info['website'];?>" id="website"></td>
	</tr>
	<tr>
		<td  width="120"><?php echo L('ҵ����');?> </td> 
		<td> <textarea name="info[description]" id="description" style="width:400px;height:46px;"><?php echo $complete_info['description'];?></textarea></td>
	</tr> 
</table>
</fieldset>
<br>
<fieldset>
	<legend><?php echo L('��ϵ������');?></legend>
<table width="100%" class="table_form">
	<tr>
		<td  width="120"><?php echo L('��ϵ��');?></td> 
		<td><input name="info[contact_name]"  type="text" id="contact_name"  size="40" value="<?php echo $complete_info['contact_name'];?>"> </td>
	</tr>
	<tr>
		<td  width="120"><?php echo L('��ϵ�绰');?></td> 
		<td><input type="text" name="info[contact_telephone]" size="40" value="<?php echo $complete_info['contact_telephone'];?>" id="contact_telephone"></td>
	</tr>
	<tr>
		<td  width="120"><?php echo L('��������');?></td> 
		<td><input type="text" name="info[email]" size="40" value="<?php echo $complete_info['email'];?>" id="email"></td>
	</tr>
	<tr>
		<td  width="120"><?php echo L('�ֻ�');?> </td> 
		<td><input type="text" name="info[mobile]" size="20" value="<?php echo $complete_info['mobile'];?>" id="mobile"></td>
	</tr>
	<tr>
		<td  width="120"><?php echo L('QQ');?> </td> 
		<td> <input type="text" name="info[qq]" size="20" value="<?php echo $complete_info['qq'];?>" id="qq"></td>
	</tr> 
</table>
</fieldset>
<div class="bk15"></div>
<input name="dosubmit" type="submit" value="<?php echo L('submit')?>" class="button" id="dosubmit">
</form>
</div>

</body>
</html>