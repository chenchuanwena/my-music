<?php
//退款接口
include("yeepay/yeepayMPay.php");
include("config.php");
$yeepay = new yeepayMPay($merchantaccount,$merchantPublicKey,$merchantPrivateKey,$yeepayPublicKey);
$order_id       = trim($_POST['orderid']);
$amount         = intval($_POST['amount']);
$currency       = intval($_POST['currency']);
$origyborder_id = trim($_POST['origyborderid']);
$cause          = trim($_POST['cause']);
$data = $yeepay->refund($amount,$order_id,$origyborder_id,$currency,$cause);

if( array_key_exists('error_code', $data))	
return;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>4、 退货退款接口</title>
</head>
	<body>
		<br /> <br />
		<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0" 
							style="word-break:break-all; border:solid 1px #107929">
			<tr>
		  		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
					4、 退货退款接口
				</th>
		  	</tr>

			<tr>
				<td width="25%" align="left">&nbsp;商户编号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $merchantaccount;?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">merchantaccount</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;退款请求号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['orderid'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">orderid</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;订单的退款流水号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['yborderid'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">yborderid</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;订单的支付流水号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['origyborderid'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">origyborderid</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;退款金额「分」</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['amount'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">amount</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;已退手续费</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left">  <?php echo $data['fee'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">fee</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;交易币种</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['currency'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">currency</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;退款时间戳</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['timestamp'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">timestamp</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;剩余金额</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['remain'];?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">remain</td> 
			</tr>
		</table>

	</body>
</html>