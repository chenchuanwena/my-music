<?php if (!defined('THINK_PATH')) exit(); if( ($addons_config['openbutton'] == 1 ) OR ($addons_config['openimg'] == 1) OR ($addons_config['openselect'] == 1)): ?><div class="bdsharebuttonbox" data-tag="share_1">
    <?php if($addons_config['openbutton'] == 1): if(is_array($addons_config["buttonlist"])): $i = 0; $__LIST__ = $addons_config["buttonlist"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a class="bds_<?php echo ($vo); ?>" data-cmd="<?php echo ($vo); ?>"></a><?php endforeach; endif; else: echo "" ;endif; ?>
    <a class="bds_more" data-cmd="more">更多</a>
    <a class="bds_count" data-cmd="count"></a><?php endif; ?>
</div>
<script>
    window._bd_share_config = {
		<?php if($addons_config['openbutton'] == 1): ?>share : [{
            "bdSize" : <?php echo ($addons_config['button_size']==null?16:$addons_config['button_size']); ?>
        }],<?php endif; ?>
        <?php if($addons_config['openimg'] == 1): ?>image : [{
            viewType : 'list',
            viewPos : 'top',
            viewColor : 'black',
            viewSize : <?php echo ($addons_config['img_size']==null?16:$addons_config['img_size']); ?>,
            viewList : [<?php if(is_array($addons_config["imglist"])): $i = 0; $__LIST__ = $addons_config["imglist"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($key == 0): ?>'<?php echo ($vo); ?>'<?php else: ?>,'<?php echo ($vo); ?>'<?php endif; endforeach; endif; else: echo "" ;endif; ?>]
        }],<?php endif; ?>
        <?php if($addons_config['openselect'] == 1): ?>selectShare : [{
            "bdselectMiniList" : [<?php if(is_array($addons_config["selectlist"])): $i = 0; $__LIST__ = $addons_config["selectlist"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($key == 0): ?>'<?php echo ($vo); ?>'<?php else: ?>,'<?php echo ($vo); ?>'<?php endif; endforeach; endif; else: echo "" ;endif; ?>]
        }]<?php endif; ?>
    }
    with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=86835285.js?cdnversion='+~(-new Date()/36e5)];
</script><?php endif; ?>