<?php
header("Content-type: text/html; charset=gb2312"); 
$p2_Order = "WYTK" . date("ymd_His") . rand(10, 99);
?>
<!DOCTYPE html>
<html>
<head>
<meta  content="text/html; charset=gb2312" />
 <meta http-equiv="x-ua-compatible" content="ie=edge">
 	</head>
	<body>
		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" style="border:solid 1px #107929">
		  <tr>
		    <td><table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
		  </tr>
		    <tr>
		  	<td colspan="2" bgcolor="#CEE7BD">�ױ�֧�������˿�����ӿ���ʾ��</td>
		  </tr>
			<form method="post" action="sendRefundOrd.php" targe="_blank">
		  		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;�˿������</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="p2_Order" id="p2_Order" value="<?php echo $p2_Order ?>" />&nbsp;</td>
      </tr>
		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;�ױ�֧��������ˮ��</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="pb_TrxId" id="pb_TrxId" value="" />&nbsp;<span style="color:#FF0000;font-weight:100;">*</span></td>
      </tr>
      
		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;�˿���</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="p3_Amt" id="p3_Amt" value="" />&nbsp;<span style="color:#FF0000;font-weight:100;">*</span></td>
      </tr>
      		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;���ױ���</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="p4_Cur" id="p4_Cur" value="CNY" />&nbsp;<span style="color:#FF0000;font-weight:100;">*</span></td>
      </tr>
		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;�˿�˵��</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p5_Desc" id="p5_Desc" value="" /></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;</td>
		  	<td align="left">&nbsp;&nbsp;<input type="submit" value="�����ύ" /></td>
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