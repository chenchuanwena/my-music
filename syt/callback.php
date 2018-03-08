<?php
include 'config.php';
include 'yeepay/yeepayMPay.php';
/**
*此类文件是有关回调的数据处理文件，根据易宝回调进行数据处理

*/
$yeepay = new yeepayMPay($merchantaccount, $merchantPublicKey, $merchantPrivateKey, $yeepayPublicKey);
try {
	if ($_POST['data']=="" || $_POST['encryptkey'] == "")
	{
		echo "参数不正确！";
		return;
	}
	
	$data=$_POST['data'];
	$encryptkey=$_POST['encryptkey'];
	$return = $yeepay->callback($data, $encryptkey); //解密易宝支付回调结果
	echo "success";

}catch (yeepayMPayException $e) {
	echo "支付失败！";
	return;
}
?>