<?php if (!defined('THINK_PATH')) exit(); switch($addons_config["comment_type"]): case "1": ?><!-- CHANYAN BEGIN -->
		<div id="SOHUCS" sid="<?php echo ($param['type']); echo ($param['id']); ?>"></div>
		<script charset="utf-8" type="text/javascript" src="http://changyan.sohu.com/upload/changyan.js" ></script>
		<script type="text/javascript">
			window.changyan.api.config({
				appid: '<?php echo ($addons_config["comment_appid_changyan"]); ?>',
				conf: '<?php echo ($addons_config["comment_appkey_changyan"]); ?>'
			});
		</script> <!-- CHANGYAN END -->
		<style><?php echo ($addons_config["comment_css_changyan"]); ?></style><?php break;?>
	<?php case "2": ?><!-- UY BEGIN -->

		<div id="uyan_frame"></div>
		<script type="text/javascript" src="http://v2.uyan.cc/code/uyan.js?uid=<?php echo ($addons_config["comment_uid_youyan"]); ?>"></script>
		<script type="text/javascript">
			$.post("<?php echo addons_url('SocialComment://Sso/uysso');?>");
			$(function(){
			var listobj  = $('#uyan_frame').find('#uyan_cmt_list');			
				$("a[href='http://www.uyan.cc']").css({'display': 'none'});
			})							
		</script>
		<style><?php echo ($addons_config["comment_css_youyan"]); ?></style>
		<!-- UY END --><?php break;?>
	<?php case "3": ?><!-- Duoshuo Comment BEGIN -->
		<div class="ds-thread" data-thread-key="<?php echo ($param['type']); echo ($param['id']); ?>" data-form-positon="<?php echo ($addons_config["comment_form_pos_duoshuo"]); ?>" data-limit="<?php echo ($addons_config["comment_data_list_duoshuo"]); ?>" data-order="<?php echo ($addons_config["comment_data_order_duoshuo"]); ?>"></div>
		<script type="text/javascript">
			var duoshuoQuery = {
				short_name: "<?php echo ($addons_config["comment_short_name_duoshuo"]); ?>",
				sso: { 
				   login: "<?php echo U('/Member/login');?>",
				   logout: "<?php echo U('/Member/logout');?>/from=<?php echo U('/');?>"
			   }
			};
			(function() {
				var ds = document.createElement('script');
				ds.type = 'text/javascript';ds.async = true;
				ds.src = 'http://static.duoshuo.com/embed.js';
				ds.charset = 'UTF-8';
				(document.getElementsByTagName('head')[0]
				|| document.getElementsByTagName('body')[0]).appendChild(ds);
			})();
		</script>
		<style><?php echo ($addons_config["comment_css_duoshuo"]); ?></style>
		<!-- Duoshuo Comment END --><?php break; endswitch;?>