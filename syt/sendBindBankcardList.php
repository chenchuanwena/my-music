<?php

// 绑卡查询
include("yeepay/yeepayMPay.php");
include("config.php");
/* *
* 对变量进行 JSON 编码
* @param mixed value 待编码的 value ，除了resource 类型之外，可以为任何数据类型，该函数只能接受 UTF-8 编码的数据
* @return string 返回 value 值的 JSON 形式
*/
function json_encode_ex( $value)
{
     if ( version_compare( PHP_VERSION,'5.4.0','<'))
    {
         $str = json_encode( $value);
         $str =  preg_replace_callback(
                                    "#\\\u([0-9a-f]{4})#i",
                                     function( $matchs)
                                    {
                                          return  iconv('UCS-2BE', 'UTF-8',  pack('H4',  $matchs[1]));
                                    },
                                      $str
                                    );
         return  $str;
    }
     else
    {
         return json_encode( $value, JSON_UNESCAPED_UNICODE);
    }
} 
$yeepay = new yeepayMPay($merchantaccount,$merchantPublicKey,$merchantPrivateKey,$yeepayPublicKey);
$identity_id = trim($_POST['identityid']);
$identity_type = intval($_POST['identitytype']);

$data = $yeepay->getBinds($identity_type,$identity_id);
if( array_key_exists('error_code', $data))	
return;

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>4.8 查询绑卡信息列表结果</title>
</head>
	<body>
		<br /> <br />
		<table width="80%" border="0" align="center" cellpadding="5" cellspacing="0" 
							style="word-break:break-all; border:solid 1px #107929">
			<tr>
		  		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
					4.8 查询绑卡信息列表结果
				</th>
		  	</tr>

			<tr>
				<td width="15%" align="left">&nbsp;商户编号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left">  <?php echo $merchantaccount;?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="25%" align="left">merchantaccount</td> 
			</tr>

			<tr>
				<td width="15%" align="left">&nbsp;用户标识</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left">  <?php echo $data['identityid'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="25%" align="left">identityid</td> 
			</tr>

			<tr>
				<td width="15%" align="left">&nbsp;用户标识类型</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['identitytype'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="25%" align="left">identitytype</td> 
			</tr>

			<tr>
				<td width="15%" align="left" rowspan="6">&nbsp;已绑定的银行卡</td>
				<td width="5%"  align="center" rowspan="6"> : </td> 
				<td width="50%" align="left" rowspan="6"> <?php  echo json_encode_ex($data['cardlist']);?>
				</td>
				<td width="5%"  align="center" rowspan="6"> - </td> 
				<td width="25%" align="left" rowspan="6">cardlist</td> 
			</tr>

		</table>

	</body>
</html>
