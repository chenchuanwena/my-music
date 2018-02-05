<?php if (!defined('THINK_PATH')) exit();?>
<div style="width:<?php echo ($list['width']); if(is_numeric($list['width'])): ?>px<?php endif; ?>;height:<?php echo ($list['height']); if(is_numeric($list['height'])): ?>px<?php endif; ?>" >
<?php echo ((isset($list['advshtml']) && ($list['advshtml'] !== ""))?($list['advshtml']):""); ?>
</div>