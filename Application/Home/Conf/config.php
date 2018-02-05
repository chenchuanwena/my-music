<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

/**
 * 前台配置文件
 */

$view_conf=include './Data/Conf/home_view.php';

$config = array(

    // 预先加载的标签库
    'TAGLIB_PRE_LOAD'=>    'JYmusic\\TagLib\\JY,JYmusic\\TagLib\\ME',

    /* 数据缓存设置 */
    'DATA_CACHE_PREFIX' => 'jy_', // 缓存前缀
    'DATA_CACHE_TYPE'   => 'File', // 数据缓存类型
    
    /* 模板相关配置  */
    'TMPL_FILE_DEPR'=>'_',
    'VIEW_PATH' => './Template/',    	

    /* SESSION 和 COOKIE 配置 */
    'SESSION_PREFIX' => 'jy_home_', //session前缀
    'COOKIE_PREFIX'  => 'jy_home_', // Cookie前缀 避免冲突

    /* 错误页面模板 */
    'TMPL_ACTION_ERROR'     =>  'Public:error', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  'Public:success', // 默认成功跳转对应的模板文件
	//'TMPL_EXCEPTION_FILE'   =>  'Public:exception',// 异常页面的模板文件
	//'URL_MODEL'            => 2, //URL访问模式,可选参数0、1、2、3,代表以下四种模式：// 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
	

	// 定义静态缓存规则 
	'HTML_CACHE_RULES'  => array( 
		'Index'=>array('index'),
		'Music:'=>array('music/{:action}'),
		'Artist:index'=>array('artist'),
		'Artist:'=>array('artist/{:action}'),
		'Album:index'=>array('album'),
		'Album:'=>array('album/{:action}'),
		'Genre:index'=>array('genre'),
		'Genre:'=>array('genre/{:action}'),
		'Ranks'=>array('ranks'),
		'Tag:index'=>array('tag'),
		'Tag:'=>array('tag/{:action}')		
     ) 

);


// +-------------------------------------------------------------+
// | Author: 陈传文 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

/**
 * 后台配置文件
 */

$upload_config= array(
	/* 数据缓存设置 */
	//'DATA_CACHE_PREFIX'    => 'jy_', // 缓存前缀
	//'DATA_CACHE_TYPE'      => 'File', // 数据缓存类型
	'URL_MODEL'            => 3, //URL模式

	/* 音乐上传相关配置 */
	'MUSIC_UPLOAD' => array(
		'mimes'    => '', //允许上传的文件MiMe类型
		'autoSub'  => true, //自动子目录保存文件
		'exts'     => 'mp3,mp4,m4a,ogg,wma,wmv', //允许上传的文件后缀
		'rootPath' => './Uploads/Music/', //保存根路径
		'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
		'savePath' => '', //保存路径
		'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
		'saveExt'  => '', //文件保存后缀，空则使用原后缀
		'replace'  => false, //存在同名是否覆盖
		'hash'     => true, //是否生成hash编码
		'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
	), //下载模型上传配置（文件上传类配置）


	/* 文件上传相关配置 */
	'DOWNLOAD_UPLOAD' => array(
		'mimes'    => '', //允许上传的文件MiMe类型
		'maxSize'  => 20*1024*1024, //上传的文件大小限制 (0-不做限制)
		'exts'     => 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml，txt,zip,gtp,gp5,gp4', //允许上传的文件后缀
		'autoSub'  => true, //自动子目录保存文件
		'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
		'rootPath' => './Uploads/Download/', //保存根路径
		'savePath' => '', //保存路径
		'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
		'saveExt'  => '', //文件保存后缀，空则使用原后缀
		'replace'  => false, //存在同名是否覆盖
		'hash'     => true, //是否生成hash编码
		'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
	), //下载模型上传配置（文件上传类配置）

	/* 图片上传相关配置 */
	'PICTURE_UPLOAD' => array(
		'mimes'    => '', //允许上传的文件MiMe类型
		'autoSub'  => true, //自动子目录保存文件
		'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
		'rootPath' => './Uploads/Picture/', //保存根路径
		'savePath' => '', //保存路径
		'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
		'saveExt'  => '', //文件保存后缀，空则使用原后缀
		'replace'  => false, //存在同名是否覆盖
		'hash'     => true, //是否生成hash编码
		'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
	), //图片上传相关配置（文件上传类配置）

	/* 编辑器图片上传相关配置 */
	'EDITOR_UPLOAD' => array(
		'mimes'    => '', //允许上传的文件MiMe类型
		'maxSize'  => 2*1024*1024, //上传的文件大小限制 (0-不做限制)
		'exts'     => 'jpg,gif,png,jpeg,rar,txt,zip,gtp,gp5,gp4', //允许上传的文件后缀
		'autoSub'  => true, //自动子目录保存文件
		'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
		'rootPath' => './Uploads/Editor/', //保存根路径
		'savePath' => '', //保存路径
		'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
		'saveExt'  => '', //文件保存后缀，空则使用原后缀
		'replace'  => false, //存在同名是否覆盖
		'hash'     => true, //是否生成hash编码
		'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
	),
//	/* 模板相关配置 */
//	'TMPL_PARSE_STRING' => array(
//		'__STATIC__' => __ROOT__ . '/Public/static',
//		'__ADDONS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Addons',
//		'__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
//		'__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
//		'__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
//	),

	/* SESSION 和 COOKIE 配置 */
	//'SESSION_PREFIX' => 'jy_admin', //session前缀
	//'COOKIE_PREFIX'  => 'jy_admin_', // Cookie前缀 避免冲突
	'VAR_SESSION_ID' => 'session_id',	//修复uploadify插件无法传递session_id的bug

	/* 后台错误页面模板 */
	//'TMPL_ACTION_ERROR'     =>  MODULE_PATH.'View/Public/error.html', // 默认错误跳转对应的模板文件
	//'TMPL_ACTION_SUCCESS'   =>  MODULE_PATH.'View/Public/success.html', // 默认成功跳转对应的模板文件
	// 'TMPL_EXCEPTION_FILE'   =>  MODULE_PATH.'View/Public/exception.html',// 异常页面的模板文件

);
return array_merge($config,$view_conf,$upload_config);
