<?php
date_default_timezone_set("Asia/Shanghai");
echo 'success2';exit;
function debugLog($msg = '',$fileName = 'tmp/YbzfCallback/'){
    $path = $fileName.'/'.date("Ym").'/'.date('Ymd_H').'.txt';
    if(!file_exists(dirname($path))){
        mkdir(dirname($path),0777,true);
    }
    $arrContent = array(
        date('Y-m-d H:i:s').chr(9).$_SERVER['REMOTE_ADDR'],
        $_SERVER['REQUEST_URI'],
        var_export($msg,true),
    );
    $content = implode(chr(10).chr(13),$arrContent).chr(10).chr(13);
    file_put_contents($path, $content, FILE_APPEND );
    chdir(getcwd());//把工作目录改成原来的
}
//debugLog('测试一下');
echo file_get_contents("http://pre.hengcai88.com/");exit;
