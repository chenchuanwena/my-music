<?php
header("Content-type: text/html; charset=gb2312"); 

 	
include 'yeepayCommon.php';
require_once 'HttpClient.class.php';
 		
$data = array();
$data['p0_Cmd']    = "RefundResults";
$data['p1_MerId']  = $p1_MerId;
$data['p2_Order']  = $_REQUEST['p2_Order'];
$data['pb_TrxId']  = $_REQUEST['pb_TrxId'];

$hmacstring        = HmacMd5(implode($data),$merchantKey);
$data['hmac']      = $hmacstring ;

//��������
$respdata  = HttpClient::quickPost($reqURL_onLine, $data);
//var_dump($respdata );
//��Ӧ����ת����
$arr  =  getresp($respdata);
//echo "return:".$arr['hmac'];
//����ǩ��
$hmacLocal = HmacLocal($arr);
$safeLocal= gethamc_safe($arr);
//echo "local:".$safeLocal;
//��ǩ
if($arr['hmac'] != $hmacLocal || $arr['hmac_safe'] != $safeLocal)

{
	
	echo "ǩ����֤ʧ��";
	return;
}	

?> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<title>�˿��ѯ���</title>
</head>
	<body>	
		<br /> <br />
		<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0" style="border:solid 1px #107929">
			<tr>
		  		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
					�˿��ѯ���
				</th>
		  	</tr>

			<tr >
				<td width="25%" align="left">&nbsp;ҵ������</td>
				<td width="5%"  align="center"> : </td> 
				<td width="45"  align="left"> <?php echo $arr['r0_Cmd'];?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r0_Cmd</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;��ѯ���</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r1_Code'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r1_Code</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;�ױ���ˮ��</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r2_TrxId'];?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r2_TrxId</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;�˿������</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r4_Order'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r4_Order</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;�˿�������</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"><?php echo $arr['refundStatus'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">refundStatus</td> 
			</tr>


			<tr>
				<td width="25%" align="left">&nbsp;��������״̬</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['refundFrpStatus'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">refundFrpStatus</td> 
			</tr> 


		</table>


	</body>
</html>
