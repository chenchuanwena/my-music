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
		  	<td colspan="2" bgcolor="#CEE7BD">�ױ�֧��������ѯ�ӿ���ʾ��</td>
		  </tr>
			<form method="post" action="sendQueryOrd.php" targe="_blank">
		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;�̻�������</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="p2_Order" id="p2_Order" value="" />&nbsp;<span style="color:#FF0000;font-weight:100;">*</span></td>
      </tr>
      <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;�汾��</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="pv_Ver" id="pv_Ver" value="3.0" />&nbsp;<span style="color:#FF0000;font-weight:100;">*</span></td>
      </tr>
	  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;��ѯ����</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="p3_ServiceType" id="p3_ServiceType" value="2" placeholder="ֵΪ1ʱ��ʾ�˿��ѯ,����������ֵ��ʾ���׶�����ѯ��"/>&nbsp;<span style="color:#FF0000;font-weight:100;">*</span></td>
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