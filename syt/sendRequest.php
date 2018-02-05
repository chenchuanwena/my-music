<?php

include("yeepay/yeepayMPay.php");
include("config.php");
header("Content-type: text/html; charset=utf-8");
$yeepay = new yeepayMPay($merchantaccount,$merchantPublicKey,$merchantPrivateKey,$yeepayPublicKey);
$cardno          =  trim($_POST['cardno']);
$idcardtype      =  trim($_POST['idcardtype']);
$idcard          =  trim($_POST['idcard']);
$owner           =  trim($_POST['owner']);
$order_id        =  trim($_POST['orderid']);
$transtime       =  intval($_POST['transtime']);
$amount          =  intval($_POST['amount']);
$currency        =  intval($_POST['currency']);
$product_catalog =  trim($_POST['productcatalog']);
$product_name    =  trim($_POST['productname']);
$product_desc    =  trim($_POST['productdesc']);
$identity_type   =  intval($_POST['identitytype']);
$identity_id     =  trim($_POST['identityid']);
$user_ip         =  trim($_POST['userip']);
$paytool         =  trim($_POST['paytool']);
$directpaytype   =  intval($_POST['directpaytype']);
$user_ua         =  trim($_POST['userua']);
$terminaltype    =  intval($_POST['terminaltype']);
$terminalid      =  trim($_POST['terminalid']);
$callbackurl     =  trim($_POST['callbackurl']);
$fcallbackurl     =  trim($_POST['fcallbackurl']);
$orderexp_date    =  intval($_POST['orderexpdate']);
$paytypes        = trim($_POST['paytypes']);
$version         = intval($_POST['version']);
$data = $yeepay->webPay($order_id,$transtime,$amount,$cardno,$idcardtype,$idcard,$owner,$product_catalog,$identity_id,$identity_type,$user_ip,$paytool,$directpaytype,$user_ua,
	$callbackurl,$fcallbackurl,$currency,$product_name,$product_desc,$terminaltype,$terminalid,$orderexp_date,$paytypes,$version);
if( array_key_exists('error_code', $data))
	return;
/*if($paytool =='2')
 {}
 else{
 echo($url);
header('Location:'.$url);
}*/

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>支付请求成功的响应参数</title>
</head>
<body>
<br /> <br />
<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0"
	   style="word-break:break-all; border:solid 1px #107929">
	<tr>
		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
			支付请求成功的响应参数
		</th>
	</tr>

	<tr>
		<td width="25%" align="left">&nbsp;商户编号</td>
		<td width="5%"  align="center"> : </td>
		<td width="50%" align="left">  <?php echo $data['merchantaccount'];?>  </td>
		<td width="5%"  align="center"> - </td>
		<td width="15%" align="left">merchantaccount</td>
	</tr>

	<tr>
		<td width="25%" align="left">&nbsp;商户订单号</td>
		<td width="5%"  align="center"> : </td>
		<td width="50%" align="left"> <?php echo $data['orderid'];?> </td>
		<td width="5%"  align="center"> - </td>
		<td width="15%" align="left">orderid</td>
	</tr>

	<tr>
		<td width="25%" align="left">&nbsp;易宝流水号</td>
		<td width="5%"  align="center"> : </td>
		<td width="50%" align="left"> <?php echo $data['yborderid'];?> </td>
		<td width="5%"  align="center"> - </td>
		<td width="15%" align="left">yborderid</td>
	</tr>

	<tr>
		<td width="25%" align="left">&nbsp;支付链接</td>
		<td width="5%"  align="center"> : </td>
		<td width="50%" align="left"> <?php echo $data['payurl'];?> </td>
		<td width="5%"  align="center"> - </td>
		<td width="15%" align="left">payurl</td>
	</tr>





	<!--二进制数据生成二维码图片,仅供参考		-->
	<tr>
		<td width="25%" align="left">&nbsp;二维码图片 </td>
		<td width="5%"  align="center">  ： </td>
		<td width="50%" align="left"> <?php
			if(empty($data['imghexstr']))
			{echo "";}
			else{
				$img= hex2byte($data['imghexstr']);
				$filename = "2weima.png";    // 写入的文件
				$file = fopen("./".$filename,"w");//打开文件准备写入
				fwrite($file,$img);//写入
				fclose($file);//关闭
				echo "<img src=$filename> ";}
			?>
		</td>
		<td width="5%"  align="center">   </td>
		<td width="15%" align="left" > </td>
	</tr>

</table>

</body>
</html>
