<?php
//��ȡ�˿�������˼�¼
include("yeepay/yeepayMPay.php");
include("config.php");
$yeepay = new yeepayMPay($merchantaccount,$merchantPublicKey,$merchantPrivateKey,$yeepayPublicKey);
$startdate = trim($_POST['startdate']);
$enddate = trim($_POST['enddate']);
$data = $yeepay->getClearRefundData($startdate,$enddate);

if( array_key_exists('error_code', $data))	
return;
else{
echo "$data";
}
?>
