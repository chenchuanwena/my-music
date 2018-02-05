
<?php
function random_str($length)
{
    //生成一个包含 大写英文字母, 小写英文字母, 数字 的数组
    $arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
    $str = '';
    $arr_len = count($arr);
    for ($i = 0; $i < $length; $i++)
    {
        $rand = mt_rand(0, $arr_len-1);
        $str.=$arr[$rand];
    }
    return $str;
}
?>  
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>5.1 退款接口演示</title>
</head>
	<body>
		<br>
		<br>
		<table width="80%" border="0" align="center" cellpadding="5" cellspacing="0" style="border:solid 1px #107929">
			<tr>
		  		<th align="center" height="20" colspan="5" bgcolor="#6BBE18">
					5.1 退款接口	
				</th>
		  	</tr> 

			<form method="post" action="sendRefund.php" target="_blank">
				<tr >
					<td width="25%" align="left">&nbsp;退款请求号</td>
					<td width="5%"  align="center"> : &nbsp;</td> 
					<td width="50%" align="left"> 
						<input size="60" type="text" name="orderid" value="<?php echo random_str(16);?>"/>
						<span style="color:#FF0000;font-weight:100;">*</span>
					</td>
					<td width="5%"  align="center"> - </td> 
					<td width="15%" align="left">orderid</td> 
				</tr>

				<tr >
					<td width="25%" align="left">&nbsp;将被退款订单的易宝流水号</td>
					<td width="5%"  align="center"> : &nbsp;</td> 
					<td width="50%" align="left"> 
						<input size="60" type="text" name="origyborderid" value="" />
						<span style="color:#FF0000;font-weight:100;">*</span>
					</td>
					<td width="5%"  align="center"> - </td> 
					<td width="15%" align="left">origyborderid</td> 
				</tr>

				<tr >
					<td width="25%" align="left">&nbsp;退款金额「分」</td>
					<td width="5%"  align="center"> : &nbsp;</td> 
					<td width="50%" align="left"> 
						<input size="60" type="text" name="amount" value="1" />
						<span style="color:#FF0000;font-weight:100;">*</span>
					</td>
					<td width="5%"  align="center"> - </td> 
					<td width="15%" align="left">amount</td> 
				</tr>

				<tr >
					<td width="25%" align="left">&nbsp;交易币种</td>
					<td width="5%"  align="center"> : &nbsp;</td> 
					<td width="50%" align="left"> 
						<input size="60" type="text" readonly="readonly" name="currency" value="156" />
						<span style="color:#FF0000;font-weight:100;">*</span>
					</td>
					<td width="5%"  align="center"> - </td> 
					<td width="15%" align="left">currency</td> 
				</tr>

				<tr >
					<td width="25%" align="left">&nbsp;退款说明</td>
					<td width="5%"  align="center"> : &nbsp;</td> 
					<td width="50%" align="left"> 
						<input size="60" type="text" name="cause" value=" 退款测试" />
					</td>
					<td width="5%"  align="center"> - </td> 
					<td width="15%" align="left">cause</td> 
				</tr>

				<tr >
					<td width="25%" align="left">&nbsp;</td>
					<td width="5%"  align="center">&nbsp;</td> 
					<td width="50%" align="left"> 
						<input type="submit" value="submit" />
					</td>
					<td width="5%"  align="center">&nbsp;</td> 
					<td width="15%" align="left">&nbsp;</td> 
				</tr>

			</form>
		</table>
</body>
</html>
