<?php
header("Content-type: text/html; charset=gb2312"); 
date_default_timezone_set('Asia/Shanghai');
$p2_Order = "WY" . date("ymd_His") . rand(10, 99);
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
		  	<td colspan="2" bgcolor="#CEE7BD">����֧���ӿ���ʾ��</td>
		  </tr>
			<form method="post" action="sendPayOrd.php" targe="_blank">

		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;�̻�������</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p2_Order" id="p2_Order" value="<?php echo $p2_Order ;?>"/></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;&nbsp;֧�����</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p3_Amt" id="p3_Amt" value="0.01" />&nbsp;<span style="color:#FF0000;font-weight:100;">*</span></td>
      </tr>

		  <tr>
		  	<td align="left">&nbsp;&nbsp;��Ʒ����</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p5_Pid" id="p5_Pid"  value="����"/>&nbsp;<span style="color:#FF0000;font-weight:100;">ѡ��һ��֧��ʱ������</span></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;&nbsp;��Ʒ����</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p6_Pcat" id="p6_Pcat"  value="����"/></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;&nbsp;��Ʒ����</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p7_Pdesc" id="p7_Pdesc"  value="����"/></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;&nbsp;����֧���ɹ����ݵĵ�ַ</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p8_Url" id="p8_Url" value="http://www.my-music.cn/ebzf/callback.php" />&nbsp;<span style="color:#FF0000;font-weight:100;">*</span></td>
      </tr>
	   <tr>
		  	<td align="left">&nbsp;&nbsp;�ͻ���ַ</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p9_SAF" id="p9_SAF"  value="0"/></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;&nbsp;�̻���չ��Ϣ</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pa_MP" id="pa_MP"  value=""/></td>
      </tr>
        <tr>
             <td align="left">&nbsp;&nbsp;�������ص���ַ</td>
             <td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pb_ServerNotifyUrl" id="pb_ServerNotifyUrl"  value="http://www.my-music.cn/ebzf/callback.php"/></td>
         </tr>
	  <tr>
		  	<td align="left">&nbsp;&nbsp;֧��ͨ������</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="pd_FrpId" /><!--֧��ͨ���������ױ�֧����Ʒ(HTML��)ͨ�ýӿ�ʹ��˵����-->
      </tr>
		 <tr>
		  	<td align="left">&nbsp;&nbsp;������Ч��</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pm_Period" id="pm_Period"  value="7"/></td>
      </tr>
	  <tr>
		  	<td align="left">&nbsp;&nbsp;������Ч�ڵ�λ</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pn_Unit" id="pn_Unit"  value="day"/></td>
      </tr>
	   <tr>
		  	<td align="left">&nbsp;&nbsp;Ӧ�����</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pr_NeedResponse" id="pr_NeedResponse"  value="1"/></td>
      </tr>
		  
		  	<tr>
		  	<td align="left">&nbsp;&nbsp;�û�����</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pt_UserName" id="pt_UserName"  value=""/></td>
      </tr>
		  
		  	<tr>
		  	<td align="left">&nbsp;&nbsp;���֤��</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pt_PostalCode" id="pt_PostalCode"  value=""/></td>
      </tr>
		  
		  <tr>
		  	<td align="left">&nbsp;&nbsp;����</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pt_Address" id="pt_Address"  value=""/></td>
      </tr>
		  
		  <tr>
		  	<td align="left">&nbsp;&nbsp;���п���</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pt_TeleNo" id="pt_TeleNo"  value=""/></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;&nbsp;�ֻ���</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pt_Mobile" id="pt_Mobile"  value=""/></td>
      </tr>
		  
		  <tr>
		  	<td align="left">&nbsp;&nbsp;�ʼ���ַ</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pt_Email" id="pt_Email"  value=""/></td>
      </tr>
		  
		  
		  <tr>
		  	<td align="left">&nbsp;&nbsp;�û���ʶ</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pt_LeaveMessage" id="pt_LeaveMessage"  value=""/></td>
      </tr>
		  
		  
		  <tr>
		  	<td align="left">&nbsp;</td>
		  	<td align="left">&nbsp;&nbsp;<input type="submit" value="����֧��" /></td>
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
