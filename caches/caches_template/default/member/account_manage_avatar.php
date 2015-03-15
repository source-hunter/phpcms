<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><?php include template('member', 'header'); ?>
<div id="memberArea">
	<?php include template('member', 'account_manage_left'); ?>
	<div class="col-auto">
		<div class="col-1 ">
			<h5 class="title"><?php echo L('modify').L('avatar');?></h5>
			<div class="content">
				
				<script language="javascript" type="text/javascript" src="<?php echo $phpsso_api_url;?>/statics/js/swfobject.js"></script>
				<script type="text/javascript">
					var flashvars = {
						'upurl':"<?php echo $upurl;?>&callback=return_avatar&"
					}; 
					var params = {
						'align':'middle',
						'play':'true',
						'loop':'false',
						'scale':'showall',
						'wmode':'window',
						'devicefont':'true',
						'id':'Main',
						'bgcolor':'#ffffff',
						'name':'Main',
						'allowscriptaccess':'always'
					}; 
					var attributes = {
						
					}; 
					swfobject.embedSWF("<?php echo $phpsso_api_url;?>/statics/images/main.swf", "myContent", "490", "434", "9.0.0","<?php echo $phpsso_api_url;?>/statics/images/expressInstall.swf", flashvars, params, attributes);

					function return_avatar(data) {
						if(data == 1) {
							window.location.reload();
						} else {
							alert('failure');
						}
					}
				</script> 
				<ul class="col-right col-avatar" id="avatarlist">
				  <?php $n=1; if(is_array($avatar)) foreach($avatar AS $k => $v) { ?>
					<li>
						<img src="<?php echo $v;?>" height="<?php echo $k;?>" width="<?php echo $k;?>" onerror="this.src='<?php echo $phpsso_api_url;?>/statics/images/member/nophoto.gif'"><br />
						<?php echo L('avatar');?><?php echo $k;?> x <?php echo $k;?>
					</li>
				  <?php $n++;}unset($n); ?>
				</ul>
				<div class="col-auto">
					<div id="myContent"> 
					  <p>Alternative content</p> 
					</div>
				</div>
			</div>
			<span class="o1"></span><span class="o2"></span><span class="o3"></span><span class="o4"></span>
		</div>
	</div>
</div>
<div class="clear"></div>
<?php include template('member', 'footer'); ?>