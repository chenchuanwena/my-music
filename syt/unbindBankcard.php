
<html>
<head>
	<meta charset="UTF-8" />
	<title>4.9 解绑卡</title>
</head>
	<body>
		<br>
		<br>
		<table width="80%" border="0" align="center" cellpadding="10" cellspacing="0" style="border:solid 1px #107929">
			<tr>
		  		<th align="center" height="20" colspan="5" bgcolor="#6BBE18">
					4.9 解绑卡
				</th>
		  	</tr> 

			<form method="post" action="sendUnbindBankcard.php" accept-charset="UTF-8">
				<tr >
					<td width="20%" align="left">&nbsp;绑卡ID</td>
					<td width="5%"  align="center"> : &nbsp;</td> 
					<td width="55%" align="left"> 
						<input size="70" type="text" name="bindid" />
					</td>
					<td width="5%"  align="center"> - </td> 
					<td width="15%" align="left">bindid</td> 
				</tr>

				<tr >
					<td width="20%" align="left">&nbsp;用户标识</td>
					<td width="5%"  align="center"> : &nbsp;</td> 
					<td width="55%" align="left"> 
						<input size="70" type="text" name="identityid" />
					</td>
					<td width="5%"  align="center"> - </td> 
					<td width="15%" align="left">identityid</td> 
				</tr>

				<tr >
					<td width="20%" align="left">&nbsp;用户标识类型</td>
					<td width="5%"  align="center"> : &nbsp;</td> 
					<td width="55%" align="left"> 
						<input size="70" type="text" name="identitytype" />
					</td>
					<td width="5%"  align="center"> - </td> 
					<td width="15%" align="left">identitytype</td> 
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
