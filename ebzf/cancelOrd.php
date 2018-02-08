<?php
header("Content-type: text/html; charset=gb2312"); 
?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=gb2312" />
 <meta http-equiv="x-ua-compatible" content="ie=edge">

	</head>
	<body>
		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" style="border:solid 1px #107929">
		  <tr>
		    <td><table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
		  </tr>
		  
		  <tr>
		  	<td colspan="2" bgcolor="#CEE7BD">易宝支付订单撤销接口演示：</td>
		  </tr>
			<form method="post" action="sendCancelOrd.php" targe="_blank">
		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;商户订单号</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="pb_TrxId" id="pb_TrxId" value="" />&nbsp;<span style="color:#FF0000;font-weight:100;">*</span></td>
      </tr>
      
     <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;版本号</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="pv_Ver" id="pv_Ver" value="1" /> </td>
      </tr>
      
		  <tr>
		  	<td align="left">&nbsp;</td>
		  	<td align="left">&nbsp;&nbsp;<input type="submit" value="提交" /></td>
      </tr>
    </form>
     <tr>
      	<td height="5" bgcolor="#6BBE18" colspan="2"></td>
      </tr>
      </table></td>
        </tr>
      </table>
	</body>
</html>