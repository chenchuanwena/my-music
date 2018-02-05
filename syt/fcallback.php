<?php
include 'config.php';
include 'yeepay/yeepayMPay.php';
/**
*此类文件是有关回调的数据处理文件，根据易宝回调进行数据处理

*/
$yeepay = new yeepayMPay($merchantaccount, $merchantPublicKey, $merchantPrivateKey, $yeepayPublicKey);
try {
	if ($_GET['data']=="" || $_GET['encryptkey'] == "")
	{
		echo "参数不正确！";
		return;
	}
	//echo "success";
	$data=$_GET['data'];
	$encryptkey=$_GET['encryptkey'];
	$return = $yeepay->callback($data, $encryptkey); //解密易宝支付回调结果
	echo "CallBackResult:<br>";
	$keyname=array_keys($return);
	
	for ($i=0;$i<count($return);$i++)
{
	echo $keyname[$i].":".$return[$keyname[$i]]."<br>";
}

	echo "data:".$data."<br>";
	echo "encryptkey:".$encryptkey."<br>";
	
}catch (yeepayMPayException $e) {
	echo "支付失败！";
	return;
}
?>