<?php
//echo file_get_contents("http://pre.hengcai88.com/");exit;
// +----------------------------------------------------------------------
// | JYmusic
// +---------------------------------------------------------------------
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
//总入口变量过滤，daddslashes函数已废弃
function daddslashes_new($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = daddslashes_new($val);
		}
	} else {
		$string = addslashes(htmlspecialchars(strip_tags($string))); // 新增去除html或者php标记和xss
		$string = str_replace("&amp;", "&", $string);
	}
	return $string;
}
$_GET		= daddslashes_new($_GET);
$_POST		= daddslashes_new($_POST);
$_COOKIE	= daddslashes_new($_COOKIE);
$_SERVER	= daddslashes_new($_SERVER);
$_FILES		= daddslashes_new($_FILES);
$_REQUEST	= daddslashes_new($_REQUEST);

// 危险字符串定义
$evilWords = array(
	array('select','from'),
	array('select','unhex'),
	array('select','char'),
	array('delete', 'from'),
	array('update', 'set'),
	array('insert', 'into'),
	array('replace', 'into'),
	array('information_schema')
);

// 递归检测请求数据中的危险字符串
sanitizeRequestRecursive($_REQUEST, $evilWords);

/**
 * 递归检测请求数据中的危险字符串
 * @param array $array
 * @param array $words
 * @return void
 */
function sanitizeRequestRecursive(array $array, $words = array()) {
	foreach ($array as $key => $value) {
		if (in_array($key, array('controller', 'action'))) continue;
		if (is_numeric($value)) continue;

		if (is_array($value)) {
			call_user_func('sanitizeRequestRecursive', $value, $words);
		} else {

			foreach ($words as $group) {
				$filterResult = array_filter($group, function($w) use ($value) {
					return (stripos($value, $w) !== false ? true : false);
				});

				if (count($filterResult) == count($group)) {
					echo "您输入的参数不合法";
					exit;
				}
			}
		}
	}
}
/**
 *
 * 系统调试设置
 * 项目正式部署后请设置为false
 */
define('APP_DEBUG', true);

/**
 * 应用目录设置
 * 安全期间，建议安装调试完成后移动到非WEB目录
 */
define ( 'APP_PATH', './Application/' );



/**

 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */



define ( 'RUNTIME_PATH', './Runtime/' );

/*静态缓存路径*/
define ( 'HTML_PATH', './Runtime/Html/' );

/*判断移动访问*/

if (is_mobile()){
	define('BIND_MODULE','Mobile');
}
function is_mobile(){
	if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
		return true;

	if(isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT'])
		return true;

	if (isset ($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], 'wap'))

		return true;

	if (isset ($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array(
			'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
		);
		if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return true;
		}
	}
	if (isset ($_SERVER['HTTP_ACCEPT'])) {
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return true;
		}
	}
}

/**
 * 引入核心入口
 * ThinkPHP亦可移动到WEB以外的目录
 */
require realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'ThinkPHP/ThinkPHP.php';