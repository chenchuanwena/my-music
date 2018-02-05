<?php
// 银行卡信息查询
include("yeepay/yeepayMPay.php");
include("config.php");
$yeepay = new yeepayMPay($merchantaccount,$merchantPublicKey,$merchantPrivateKey,$yeepayPublicKey);
$cardno = trim($_POST['cardno']);
$data = $yeepay->bankcardCheck($cardno);
if( array_key_exists('error_code', $data))	
return;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<title>7、 银行卡信息查询结果</title>
</head>
	<body>
		<br /> <br />
		<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0" 
							style="word-break:break-all; border:solid 1px #107929">
			<tr>
		  		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
					7、 银行卡信息查询
				</th>
		  	</tr>

			<tr>
				<td width="25%" align="left">&nbsp;商户编号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $merchantaccount;?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">merchantaccount</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;银行卡号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left">  <?php echo $data['cardno'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">cardno</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;银行卡类型</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['cardtype'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">cardtype</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;银行名称</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left">  <?php echo $data['bankname'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">bankname</td> 
			</tr>
      <tr>
				<td width="25%" align="left">&nbsp;银行检查</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left">  <?php echo $data['bankcode'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">bankcode</td> 
			</tr>
			<tr>
				<td width="25%" align="left">&nbsp;该卡是否有效</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['isvalid'];?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">isvalid</td> 
			</tr>

		</table>

	</body>
</html>