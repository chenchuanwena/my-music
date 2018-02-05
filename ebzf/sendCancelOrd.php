<?php
header("Content-type: text/html; charset=gb2312"); 

 	
include 'yeepayCommon.php';
require_once 'HttpClient.class.php';
 		
$data = array();
$data['p0_Cmd']    = "CancelOrd";
$data['p1_MerId']  = $p1_MerId;
$data['pb_TrxId']  = $_REQUEST['pb_TrxId'];
$data['pv_Ver']    = $_REQUEST['pv_Ver'];
$hmacstring        = HmacMd5(implode($data),$merchantKey);
$data['hmac']      = $hmacstring ;

//��������
$respdata  = HttpClient::quickPost($reqURL_onLine, $data);
//var_dump($respdata );
//��Ӧ����
$arr  =  getresp($respdata);
//echo "return:".$arr['hmac'];
//����ǩ��
$hmacLocal = HmacLocal($arr);
$safeLocal= gethamc_safe($arr);

//echo "local:".$hmacLocal;
//��ǩ
if($arr['hmac'] != $hmacLocal   || $arr['hmac_safe'] != $safeLocal)
{
	
	echo "ǩ����֤ʧ��";
	return;
}	

?> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<title>����ȡ��</title>
</head>
	<body>	
		<br /> <br />
		<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0" style="border:solid 1px #107929">
			<tr>
		  		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
					����ȡ��
				</th>
		  	</tr>

			<tr>
				<td width="25%" align="left">&nbsp;ҵ������</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r0_Cmd'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r0_Cmd</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;�������</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r1_Code'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r1_Code</td> 
			</tr>

		</table>
	</body>
</html>

