<?php
header("Content-type: text/html; charset=gbk");
 
include 'yeepayCommon.php';
require_once 'HttpClient.class.php';
//���� 		
$data = array();
$data['p0_Cmd']    = "QueryOrdDetail";
$data['p1_MerId']  = $p1_MerId;
$data['p2_Order']  = $_REQUEST['p2_Order'];
$data['pv_Ver']    = $_REQUEST['pv_Ver'];
$data['p3_ServiceType']   = $_REQUEST['p3_ServiceType'];
$hmacstring        = HmacMd5(implode($data),$merchantKey);
$data['hmac']      = $hmacstring ;
//��������
$respdata  = HttpClient::quickPost($OrderURL_onLine, $data);
//var_dump($respdata );
//��Ӧ����
$arr  =  getresp($respdata);

//����ǩ��
$hmacLocal = HmacLocal($arr);
$safeLocal= gethamc_safe($arr);

//��ǩ
if($arr['hmac'] != $hmacLocal || $arr['hmac_safe'] != $safeLocal)

{
	
	echo "ǩ����֤ʧ��";
	return;
}	

  
?> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<title>������ѯ���</title>
</head>
	<body>	
		<br /> <br />
		<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0" style="border:solid 1px #107929">
			<tr>
		  		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
					������ѯ���
				</th>
		  	</tr>

			<tr >
				<td width="25%" align="left">&nbsp;ҵ������</td>
				<td width="5%"  align="center"> : </td> 
				<td width="45"  align="left"> <?php echo $arr['r0_Cmd'];?> </td>
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
				<td width="35%" align="left"> <?php echo $arr['r2_TrxId'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r2_TrxId</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;֧�����</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r3_Amt'];?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r3_Amt</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;���ױ���</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r4_Cur'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r4_Cur</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;��Ʒ����</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r5_Pid']?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r5_Pid</td> 
			</tr> 

			<tr>
				<td width="25%" align="left">&nbsp;�̻�������</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r6_Order'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r6_Order</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;��չ��Ϣ</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r8_MP'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r8_MP</td> 
			</tr>


			<tr>
				<td width="25%" align="left">&nbsp;�˿������</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['rw_RefundRequestID'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">rw_RefundRequestID</td> 
			</tr> 

			<tr>
				<td width="25%" align="left">&nbsp;��������ʱ��</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['rx_CreateTime']?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">rx_CreateTime</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;�����ɹ�ʱ��</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"><?php echo $arr['ry_FinshTime'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">ry_FinshTime</td> 
			</tr>


			<tr>
				<td width="25%" align="left">&nbsp;�˿�������</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['rz_RefundAmount'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">rz_RefundAmount</td> 
			</tr> 

			<tr>
				<td width="25%" align="left">&nbsp;֧��״̬</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['rb_PayStatus'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">rb_PayStatus</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;���˿����</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['rc_RefundCount'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">rc_RefundCount</td> 
			</tr>


			<tr>
				<td width="25%" align="left">&nbsp;���˿���</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['rd_RefundAmt'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">rd_RefundAmt</td> 
			</tr> 

		</table>

	</body>
</html>

