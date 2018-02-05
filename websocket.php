<?php
header("Content-type: text/html; charset=utf-8");

$key    = "06";
$b    = str_split($key, 1);//跟上面对应不同，在于，元素是字符，上面数组元素的是ASCII码，可以用ord转换
$s    = join('', $b);

socket_write($socket, $in, strlen($in));
error_reporting(E_ALL);
set_time_limit(0);
echo "<h2>TCP/IP Connection</h2>\n";

$port = 9000;
$ip = "10.63.34.29";

/*
 +-------------------------------
 *    @socket连接整个过程
 +-------------------------------
 *    @socket_create
 *    @socket_connect
 *    @socket_write
 *    @socket_read
 *    @socket_close
 +--------------------------------
 */

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket < 0) {
    echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
}else {
    echo "OK.\n";
}
$prepare="试图连接 '$ip' 端口 '$port'...\n";
$result = socket_connect($socket, $ip, $port);
if ($result < 0) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
}else {
    $connectOk="连接OK\n";
    echo iconv("UTF-8","GB2312",$connectOk);
}

$in='christ';
$out = '';
function convertString($str){
  echo  iconv("UTF-8","GB2312",$str);
}

if(!socket_write($socket, $in, strlen($in))) {
    echo "socket_write() failed: reason: " . socket_strerror($socket) . "\n";
}else {
    $sendSuccess="发送到服务器信息成功！\n";
    $sendSuccess.="发送的内容为:<font color='red'>$in</font> <br>";
    convertString($sendSuccess);
}
$key    = "06";
$b    = str_split($key, 1);//跟上面对应不同，在于，元素是字符，上面数组元素的是ASCII码，可以用ord转换
$s    = join('', $b);
socket_write($socket, $in, strlen($in));

while($out = socket_read($socket, 8192)) {
    $recievMessage="接收服务器回传信息成功！\n";
    $recievMessage.="接受的内容为:".$out;
    convertString($recievMessage);
}

socket_write($socket, $in, strlen($in));

echo "close SOCKET...\n";
socket_close($socket);
echo "close OK\n";
?>
