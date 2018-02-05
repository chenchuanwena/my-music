<?php
// 解绑卡
include("yeepay/yeepayMPay.php");
include("config.php");

$yeepay = new yeepayMPay($merchantaccount,$merchantPublicKey,$merchantPrivateKey,$yeepayPublicKey);

$bind_id = trim($_POST['bindid']);
$identity_id = trim($_POST['identityid']);
$identity_type = intval($_POST['identitytype']);

$data = $yeepay->unbind($bind_id,$identity_id,$identity_type);
if( array_key_exists('error_code', $data))	
return; 
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>4.9 解绑卡</title>
</head>
	<body>
		<br /> <br />
		<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0" 
							style="word-break:break-all; border:solid 1px #107929">
			<tr>
		  		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
					4.9 解绑卡结果
				</th>
		  	</tr>

			<tr>
				<td width="25%" align="left">&nbsp;商户编号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $merchantaccount;?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">merchantaccount</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;绑卡ID</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left">  <?php echo $data['bindid'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">bindid</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;用户标识</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left">  <?php echo $data['identityid'];?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">identityid</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;用户标识类型</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left">  <?php echo $data['identitytype'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">identitytype</td> 
			</tr>

		</table>

	</body>
</html>
