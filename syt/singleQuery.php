<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>2、订单查询接口</title>
</head>
	<body>
		<br>
		<br>
		<table width="80%" border="0" align="center" cellpadding="10" cellspacing="0" style="border:solid 1px #107929">
			<tr>
		  		<th align="center" height="20" colspan="5" bgcolor="#6BBE18">
					2、 订单查询接口
				</th>
		  	</tr> 

			<form method="post" action="sendSingleQuery.php">
				<tr >
					<td width="20%" align="left">&nbsp;商户订单号</td>
					<td width="5%"  align="center"> : &nbsp;</td> 
					<td width="55%" align="left"> 
						<input size="70" type="text" name="orderid" />
					</td>
					<td width="5%"  align="center"> - </td> 
					<td width="15%" align="left">orderid</td> 
				</tr>

				<tr >
					<td width="20%" align="left">&nbsp;易宝流水号</td>
					<td width="5%"  align="center"> : &nbsp;</td> 
					<td width="55%" align="left"> 
						<input size="70" type="text" name="yborderid" />
					</td>
					<td width="5%"  align="center"> - </td> 
					<td width="15%" align="left">yborderid</td> 
				</tr>
				
				<tr >
					<td width="20%" align="left">&nbsp;</td>
					<td width="5%"  align="center">&nbsp;</td> 
					<td width="55%" align="left"> 
						<input type="submit" value="submit" />
					</td>
					<td width="5%"  align="center">&nbsp;</td> 
					<td width="15%" align="left">&nbsp;</td> 
				</tr>

			</form>
		</table>
</body>
</html>
