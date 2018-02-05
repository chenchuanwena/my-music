<?php if (!defined('THINK_PATH')) exit();?>											
<?php if($addons_config["link_type"] == 1): ?><!-- 普通友情链接 -->
  <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="margin:5px; display: inline-block;"><a href="<?php echo ($vo["link"]); ?>" target="_blank"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
<?php elseif($addons_config["link_type"] == 2): ?>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="margin:5px; display: inline-block;">
		<a href="<?php echo ($vo["link"]); ?>" target="_blank" >
			<img src="<?php echo ($vo["cover_link"]); ?>" style="max-height:60px" title="<?php echo ($vo["title"]); ?>"/>
		</a>
	</li><?php endforeach; endif; else: echo "" ;endif; ?>
<?php else: ?><!-- 图片友情链接 -->
  <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="margin:5px; display: inline-block;">
		<a href="<?php echo ($vo["link"]); ?>" target="_blank">
			<div><img  height="50"  src="<?php echo ($vo["cover_link"]); ?>"></div>
			<span style="width:100%; display:block; text-align:center"><?php echo ($vo["title"]); ?></span>
		</a>		
   </li><?php endforeach; endif; else: echo "" ;endif; endif; ?>