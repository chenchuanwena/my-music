<?php
//交易记录查询
include("yeepay/yeepayMPay.php");
include("config.php");
$yeepay = new yeepayMPay($merchantaccount,$merchantPublicKey,$merchantPrivateKey,$yeepayPublicKey);
$order_id = trim($_POST['orderid']);
$yborder_id = trim($_POST['yborderid']);
$data = $yeepay->getOrder($order_id,$yborder_id);
if( array_key_exists('error_code', $data))	
return; 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>2、 交易记录查询接口</title>
</head>
	<body>
		<br /> <br />
		<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0" 
							style="word-break:break-all; border:solid 1px #107929">
			<tr>
		  		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
				2、	交易记录查询
				</th>
		  	</tr>

			<tr>
				<td width="25%" align="left">&nbsp;商户编号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left">  <?php echo $merchantaccount;?>  </td>
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
				<td width="25%" align="left">&nbsp;订单金额「分」</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['amount'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">amount</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;交易币种</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['currency'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">currency</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;付款方手续费「分」</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['sourcefee'];?>   </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">sourcefee</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;收款方手续费「分」</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['targetfee'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">targetfee</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;付款方实付金额「分」</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['sourceamount'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">sourceamount</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;收款方实收金额「分」</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['targetamount'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">targetamount</td> 
			</tr>


			<tr>
				<td width="25%" align="left">&nbsp;下单时间</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['ordertime'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">ordertime</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;交易时间</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['closetime'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">closetime</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;支付类型</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['type'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">type</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;订单状态</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['status'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">status</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;累计退款金额</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['refundtotal'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">refundtotal</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;商品类别码</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['productcatalog'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">productcatalog</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;商品名称</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['productname'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">productname</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;商品描述</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['productdesc'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">productdesc</td> 
			</tr>

		</table>

	</body>
</html>
