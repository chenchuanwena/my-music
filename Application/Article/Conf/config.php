<?php

$view_conf=include './Data/Conf/article_view.php';
$config = array(
    // 预先加载的标签库
    'TAGLIB_PRE_LOAD'     =>    'JYmusic\\TagLib\\JY,JYmusic\\TagLib\\ME',
    /* 数据缓存设置 */
    'DATA_CACHE_PREFIX' => 'jy_', // 缓存前缀 
	/* SESSION 和 COOKIE 配置 */
    'SESSION_PREFIX' => 'jy_home_', //session前缀
    'COOKIE_PREFIX'  => 'jy_home_', // Cookie前缀 避免冲突
    'VAR_SESSION_ID' => 'session_id',	//修复uploadify插件无法传递session_id的bug
    
	/* 模板相关配置 */
    'TMPL_FILE_DEPR'=>'_',
	'VIEW_PATH' => './Template/',
	
	/*默认绑定分类ID*/
	'ARTICLE_BIND_CATEGORY'	=> 1,
    
	//'TOKEN_ON'      =>    true,  // 是否开启令牌验证 默认关闭
    'DEFAULT_FILTER'        =>  'strip_tags,stripslashes', //过滤方法
    
	/* 错误页面模板 */
    'TMPL_ACTION_ERROR'     => 'Public_error', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   => 'Public_success', // 默认成功跳转对应的模板文件
);
return  array_merge($config,$view_conf);
