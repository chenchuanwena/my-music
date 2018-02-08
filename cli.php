<?php
@ini_set('memory_limit', '2048M');
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
//普通模式，解决官方分组不支持cli的问题
$depr = '/';
$path   = isset($_SERVER['argv'][1])?$_SERVER['argv'][1]:'';
if(!empty($path)) {
    $params = explode($depr,trim($path,$depr));
}

!empty($params)?$_GET['g']=array_shift($params):"";
!empty($params)?$_GET['m']=array_shift($params):"";
!empty($params)?$_GET['a']=array_shift($params):"";
if(count($params)>1) {
// 解析剩余参数 并采用GET方式获取
    preg_replace('@(\w+),([^,\/]+)@e', '$_GET[\'\\1\']="\\2";', implode(',',$params));
}
//define('APP_MODE','cli');
define('APP_DEBUG',True);
define( 'APP_PATH', dirname(__FILE__).'/Application/' );
require dirname(__FILE__).'/ThinkPHP/ThinkPHP.php';


/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 11:42
 */