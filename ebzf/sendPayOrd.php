<?php
header("Content-type: text/html; charset=gb2312");
include 'yeepayCommon.php';
function getKFHJ($url){

    if($url=="http://pre.hengcai88.com/"){
        return 'pre';
    }
    if($url=="http://www.hc88asia.org/"){
        return 'abtest';
    }
    if($url=="http://10.63.15.242/"){
        return 'dev';
    }
    return 'online';

}

//christ begin
$tmpKeyHttpQuery = '@NX#IYlQvfIKhUBC';
$tmpHttpQuery=$_REQUEST;
$baseStr=$tmpHttpQuery['deposit_id'] . $tmpHttpQuery['deposit_mark'] . $tmpHttpQuery['member'] . $tmpHttpQuery['out_trade_no'] . $tmpHttpQuery['total_fee'];
$sign = md5($tmpHttpQuery['deposit_id'] . $tmpHttpQuery['deposit_mark'] . $tmpHttpQuery['member'] . $tmpHttpQuery['out_trade_no'] . $tmpHttpQuery['total_fee'] . $tmpKeyHttpQuery);
if(isset($_SERVER["HTTP_REFERER"])){
    $url = $_SERVER["HTTP_REFERER"];
    $referer_domain_arr=parse_url($url);
    $referer_domain=$referer_domain_arr['scheme'].'://'.$referer_domain_arr['host'].'/';
}else{
    $url=base64_decode($tmpHttpQuery['send_url']);
    $referer_domain=$url;
}
$kfhj=getKFHJ($referer_domain);
$referer_domain=$kfhj.'|ebzf|'.$tmpHttpQuery['deposit_id'].'|'.$url;
if($tmpHttpQuery['sign']!=$sign){
    echo '���ݴ�������,3��󷵻�!';
    header("refresh:3;url=$url");
    exit;
}
$host="http://".$_SERVER['HTTP_HOST'];
$_REQUEST['p2_Order']=$tmpHttpQuery['out_trade_no'];
$_REQUEST['p3_Amt']=$tmpHttpQuery['total_fee'];
$_REQUEST['p5_Pid']=$tmpHttpQuery['out_trade_no'];
$_REQUEST['p6_Pcat']='�û���ֵ';
$_REQUEST['p7_Pdesc']='�û���ֵ:'.$tmpHttpQuery['out_trade_no'];
$_REQUEST['p8_Url']=$host.'/ebzf/callback.php';
$_REQUEST['p9_SAF']=$baseStr.'|'.$sign;
$_REQUEST['pb_ServerNotifyUrl']=$host.'/ebzf/callback_ebzf.php';
$_REQUEST['pa_MP']=$referer_domain;
$_REQUEST['pd_FrpId']='';
$_REQUEST['pm_Period']=7;
$_REQUEST['pn_Unit']='day';
$_REQUEST['pr_NeedResponse']=1;
$_REQUEST['pt_UserName']='';
$_REQUEST['pt_PostalCode']='';
$_REQUEST['pt_Address']='';
$_REQUEST['pt_TeleNo']='';
$_REQUEST['pt_Mobile']='';
$_REQUEST['pt_Email']='';
$_REQUEST['pt_LeaveMessage']='';
//end christ
#	�̼������û�������Ʒ��֧����Ϣ.
##�ױ�֧��ƽ̨ͳһʹ��GBK/GB2312���뷽ʽ,�������õ����ģ���ע��ת��
$data = array();
#ҵ������
$data['p0_Cmd']				= "Buy";
#	�̻�������,ѡ��.
$data['p1_MerId']     = $p1_MerId;
##����Ϊ""���ύ�Ķ����ű����������˻�������Ψһ;Ϊ""ʱ���ױ�֧�����Զ�����������̻�������.
$data['p2_Order']			= $_REQUEST['p2_Order'];
#	֧�����,����.
##��λ:Ԫ����ȷ����.
$data['p3_Amt']			  = $_REQUEST['p3_Amt'];
#	���ױ���,�̶�ֵ"CNY".
$data['p4_Cur']				= "CNY";
#	��Ʒ����
##����֧��ʱ��ʾ���ױ�֧���������Ķ�����Ʒ��Ϣ.
$data['p5_Pid']			  = $_REQUEST['p5_Pid'];
#	��Ʒ����
$data['p6_Pcat']		  = $_REQUEST['p6_Pcat'];
#	��Ʒ���� 
$data['p7_Pdesc']		  = $_REQUEST['p7_Pdesc'];
#	�̻�����֧���ɹ����ݵĵ�ַ,֧���ɹ����ױ�֧������õ�ַ�������γɹ�֪ͨ.
$data['p8_Url']			  = $_REQUEST['p8_Url'];	
#	�ͻ���ַ
$data['p9_SAF']			  = $_REQUEST['p9_SAF'];
#	�̻���չ��Ϣ
$data['pa_MP']			 = $_REQUEST['pa_MP'];
##�̻�����������д1K ���ַ���,֧���ɹ�ʱ��ԭ������.
$data['pb_ServerNotifyUrl']=$_REQUEST['pb_ServerNotifyUrl'];
#�������ص���ַ

#	֧��ͨ������
##Ĭ��Ϊ""�����ױ�֧������.��������ʾ�ױ�֧����ҳ�棬ֱ����ת�������С�������֧��������һ��ͨ��֧��ҳ�棬���ֶο����ո�¼:�����б����ò���ֵ.			
$data['pd_FrpId']		  = $_REQUEST['pd_FrpId'];
#	������Ч��
$data['pm_Period']	  = $_REQUEST['pm_Period'];
#	������Ч�ڵ�λ
##Ĭ��Ϊ"day": ��;
$data['pn_Unit']	    = $_REQUEST['pn_Unit'];
#	Ӧ�����
$data['pr_NeedResponse']	    = $_REQUEST['pr_NeedResponse'];;
#	�û�����
$data['pt_UserName']					= $_REQUEST['pt_UserName'];
#	���֤��
$data['pt_PostalCode']			  = $_REQUEST['pt_PostalCode'];
#	����
$data['pt_Address']		        = $_REQUEST['pt_Address'];
#	���п���
$data['pt_TeleNo']			      = $_REQUEST['pt_TeleNo'];
#	�ֻ���
$data['pt_Mobile']			      = $_REQUEST['pt_Mobile'];
# �ʼ���ַ
$data['pt_Email']			        = $_REQUEST['pt_Email'];
# �û���ʶ
$data['pt_LeaveMessage']			= $_REQUEST['pt_LeaveMessage'];
#ǩ����
$hmac                         = HmacMd5(implode($data),$merchantKey);


?> 
<html>
<head>
<title>To YeePay Page</title>
</head>
<body onload="document.yeepay.submit();">
<form name='yeepay' action='<?php echo $reqURL_onLine; ?>' method='get'>
<input type='hidden' name='p0_Cmd'					value='<?php echo $data['p0_Cmd']; ?>'>
<input type='hidden' name='p1_MerId'				value='<?php echo $p1_MerId; ?>'>
<input type='hidden' name='p2_Order'				value='<?php echo $data['p2_Order']; ?>'>
<input type='hidden' name='p3_Amt'					value='<?php echo $data['p3_Amt']; ?>'>
<input type='hidden' name='p4_Cur'					value='<?php echo $data['p4_Cur']; ?>'>
<input type='hidden' name='p5_Pid'					value='<?php echo $data['p5_Pid']; ?>'>
<input type='hidden' name='p6_Pcat'					value='<?php echo $data['p6_Pcat']; ?>'>
<input type='hidden' name='p7_Pdesc'				value='<?php echo $data['p7_Pdesc']; ?>'>
<input type='hidden' name='p8_Url'					value='<?php echo $data['p8_Url']; ?>'>
<input type='hidden' name='p9_SAF'					value='<?php echo $data['p9_SAF']; ?>'>
<input type='hidden' name='pb_ServerNotifyUrl'					value='<?php echo $data['pb_ServerNotifyUrl']; ?>'>
<input type='hidden' name='pa_MP'						value='<?php echo $data['pa_MP']; ?>'>
<input type='hidden' name='pd_FrpId'				value='<?php echo $data['pd_FrpId']; ?>'>
<input type='hidden' name='pm_Period'				value='<?php echo $data['pm_Period']; ?>'>
<input type='hidden' name='pn_Unit'				  value='<?php echo $data['pn_Unit']; ?>'>
<input type='hidden' name='pr_NeedResponse'	value='<?php echo $data['pr_NeedResponse']; ?>'>
<input type='hidden' name='pt_UserName'			value='<?php echo $data['pt_UserName']; ?>'>
<input type='hidden' name='pt_PostalCode'		value='<?php echo $data['pt_PostalCode']; ?>'>
<input type='hidden' name='pt_Address'			value='<?php echo $data['pt_Address']; ?>'>
<input type='hidden' name='pt_TeleNo'				value='<?php echo $data['pt_TeleNo']; ?>'>
<input type='hidden' name='pt_Mobile'				value='<?php echo $data['pt_Mobile']; ?>'>
<input type='hidden' name='pt_Email'				value='<?php echo $data['pt_Email']; ?>'>
<input type='hidden' name='pt_LeaveMessage'	value='<?php echo $data['pt_LeaveMessage']; ?>'>
<input type='hidden' name='hmac'						value='<?php echo $hmac; ?>'>
</form>
</body>
</html>