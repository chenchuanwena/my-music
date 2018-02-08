<?php
//获取消费清算对账单记录
include("yeepay/yeepayMPay.php");
include("config.php");
$yeepay = new yeepayMPay($merchantaccount,$merchantPublicKey,$merchantPrivateKey,$yeepayPublicKey);
$startdate = trim($_POST['startdate']);
$enddate = trim($_POST['enddate']);
$data = $yeepay->getClearPayData($startdate,$enddate);
if( array_key_exists('error_code', $data))	
return;
else{
echo "$data";}
?>
