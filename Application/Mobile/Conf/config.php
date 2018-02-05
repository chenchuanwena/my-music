<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

/**
 * 前台手机模块配置文件
 */
$view_conf	= include './Data/Conf/mobile_view.php';
$config 	= array(

    // 预先加载的标签库
    'TAGLIB_PRE_LOAD'=>    'JYmusic\\TagLib\\JY,JYmusic\\TagLib\\ME',

    /* 数据缓存设置 */
    'DATA_CACHE_PREFIX' => 'jy_', // 缓存前缀       
    /* 模板相关配置  */
    'TMPL_FILE_DEPR'=>'_',
    'VIEW_PATH' => './Template/',    	

    /* SESSION 和 COOKIE 配置 */
    'SESSION_PREFIX' => 'jy_home_', //session前缀
    'COOKIE_PREFIX'  => 'jy_home_', // Cookie前缀 避免冲突
    'TMPL_ACTION_ERROR'     =>  'Public:error', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  'Public:success', // 默认成功跳转对应的模板文件

	// 定义静态缓存规则 
	'HTML_CACHE_RULES'  => array(  		
    	'Index:'=>array('index','600'),
		'Yc:'=>array('Yc/{:action}_{g}_{p}','1200'),
		/*'Yc:view'=>array('Yc/view/p/{p}','3600'),
		/*'Fc:'=>array('Fc/{:action}','3600'),
		'Fc:view'=>array('Fc/view/p/{p}','3600'),
		'Bz:'=>array('Bz/{:action}','3600'),
		'Bz:view'=>array('Bz/view/p/{p}','3600'),*/			
     ),     
     'TOKEN_ON'      =>    false,  // 是否开启令牌验证 默认关闭

);

return array_merge($config,$view_conf);
