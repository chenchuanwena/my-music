<?php
header("Content-type: text/html; charset=gb2312");
include 'yeepayCommon.php';
function debugLog($msg = '',$fileName = 'tmp/YbzfCallback/'){
    $path = $fileName.'/'.date("Ym").'/'.date('Ymd_H').'.txt';
    if(!file_exists(dirname($path))){
        mkdir(dirname($path),0777,true);
    }
    $arrContent = array(
        date('Y-m-d H:i:s').chr(9).$_SERVER['REMOTE_ADDR'],
        $_SERVER['REQUEST_URI'],
        var_export($msg,true),
    );
    $content = implode(chr(10).chr(13),$arrContent).chr(10).chr(13);
    file_put_contents($path, $content, FILE_APPEND );
    chdir(getcwd());//把工作目录改成原来的
}
//debugLog('有回调：回调数据'.json_encode($_REQUEST),'tmp/YbzfCallback/');
#	只有支付成功时易宝支付才会通知商户.
##支付成功回调有两次，都会通知到在线支付请求参数中的p8_Url上：浏览器重定向;服务器点对点通讯.

#	解析返回参数.
$data=array();

$data['p1_MerId']		 = $_REQUEST['p1_MerId'];
$data['r0_Cmd']		   = $_REQUEST['r0_Cmd'];
$data['r1_Code']	   = $_REQUEST['r1_Code'];
$data['r2_TrxId']    = $_REQUEST['r2_TrxId'];
$data['r3_Amt']      = $_REQUEST['r3_Amt'];
$data['r4_Cur']		   = $_REQUEST['r4_Cur'];
$data['r5_Pid']		   = $_REQUEST['r5_Pid'] ;
$data['r6_Order']	   = $_REQUEST['r6_Order'];
$data['r7_Uid']		   = $_REQUEST['r7_Uid'];
$data['r8_MP']		   = $_REQUEST['r8_MP'] ;
$data['r9_BType']	   = $_REQUEST['r9_BType'];
$data['hmac']			   = $_REQUEST['hmac'];
$data['hmac_safe']   = $_REQUEST['hmac_safe'];
//var_dump($data);

//var_dump($data);
//本地签名
$hmacLocal = HmacLocal($data);
// echo "</br>hmacLocal:".$hmacLocal;
$safeLocal= gethamc_safe($data);
// echo "</br>safeLocal:".$safeLocal;
$YbzfCallBackApi = new YbzfCallBackApi($data,$_GET);

//验签
//if($data['hmac']	 != $hmacLocal    || $data['hmac_safe'] !=$safeLocal)
//{
//    echo "验签失败";
//    return;
//}else{
//    if ($data['r1_Code']=="1" ){
//
//        if($data['r9_BType']=="1"){
//
//            echo  "支付成功！在线支付页面返回";
//        }elseif($data['r9_BType']=="2"){
//            #如果需要应答机制则必须回写success.
//            $YbzfCallBackApi = new YbzfCallBackApi($data,$_GET);
//             //echo "SUCCESS";
//            return;
//        }
//
//    }
//}
/**
 * 易宝支付回调上分接口
 *
 *  @author pete@hengcai88.com
 * @datetime 2017-08-22 15:02
 */



class YbzfCallBackApi
{

    //商户号id
    private $memberId = '10015553368';

    //商户号秘钥
    private $assKey = 'o6612YZ9A65Mqa637JiCZS54u4IY8zuwz96344pxUE5542Z39Ny729j5421Y';

    //环境回调地址
    private $callBackURLList = array(
        'dev' => 'http://10.63.15.242/highadmin/_api/depositserver.php',
        'pre' => 'http://pre.hengcai88.com/highadmin/_api/depositserver.php',
        //灰度环境
        'abtest'=>'http://www.hc88asia.org/highadmin/_api/depositserver.php',
        'online' => 'http://payapi.0001cai.info/highadmin/_api/depositserver.php'
    );

    //支付方式id
    private $depositIds = array(
        'dev' => array(
            'ebzf' =>24,

        ),
        'pre' => array(
            'ebzf' =>24,
        ),
        'abtest' => array(
            'ebzf' =>24,
        ),
        'online' => array(
            'ebzf' =>24,
        )
    );

    private $depositKey =1235;

    private $callBackURL,$port,$isNotify,$bankType,$depositId;

    function __construct($data = array(),$args = array())
    {
        //获取回调数据
        $this->data = $data;

        //获取Get参数
        $this->args = $args;

        //处理数据
        $this->handleData();

        //验证数据
        $this->verification();

        //回调上分
        $this->doIt();
    }

    /**
     * [回调上分]
     * @author pete@hengcai88.com
     * @datetime 2017-08-22T15:12:09+0800
     * @return   [type]                   [description]
     */
    public function doIt(){

        $data = array(
            'comment' => $this->data['r6_Order'],//订单ID
            'bank_id' => $this->depositId,//支付ID
            'amount' => $this->data['r3_Amt'],//金额
            'incomebankcard' => $this->data['p1_MerId'],//商户ID
            'fee' => 0,//充值手续费
            'callbackpipe' => 'ew43rgRE'
        );

        $verifymd5 = md5($this->data['r3_Amt'].$this->data['p1_MerId'].$this->depositKey);

        $curlData = array( // 要提交的数据
            'data' => json_encode($data),
            'verifymd5' => $verifymd5
        );
        //$this->debugLog(json_encode($curlData)."\r\n".$this->callBackURL,'tmp/YbzfCallback/');
       // file_put_contents('tmp/log.txt',json_encode($curlData)."\r\n".$this->callBackURL);
        $curlPost = http_build_query($curlData);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->callBackURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $output = curl_exec($ch);

        $output = substr($output, -2, 2);
        if ($output == '88' || $output == '77') {
            if($this->isNotify == 1){
                echo "SUCCESS";
            }else{
                $this->debugLog("充值成功！");
                echo "充值成功！";
            }
            exit;
        }

        if($this->isNotify == 1){
            $this->debugLog("-1");
            echo "-1";
        }else{
            $this->debugLog("充值失败，请重试。");
            echo "充值失败，请重试。";
        }
    }
    function convertUrlQuery($query)
    {
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }
    /**
     * [规范格式并处理数据]
     * @author pete@hengcai88.com
     * @datetime 2017-08-22T15:12:40+0800
     * @return   [type]                   [description]
     */
    private function handleData(){

        //回调方可能使用get传参
        if(empty($this->data)){
            $this->data = $this->args;
        }

        //商户号
        if(!isset($this->data['p1_MerId'])){
            $this->debugLog('商户号不存在');
            exit('a21dqw');
        }
        //订单号
        if(!isset($this->data['r6_Order'])){
            $this->debugLog('订单号不存在');
            exit('ddh87z');
        }
        //订单金额
        if(!isset($this->data['r3_Amt'])){
            $this->debugLog('订单金额不存在');
            exit('ddje87z');
        }
        //支付平台订单号
        if(!isset($this->data['r2_TrxId'])){
            $this->debugLog('支付平台订单号不存在');
            exit('zfptdd8');
        }
        //交易状态
        if(!isset($this->data['r1_Code'])){
            $this->debugLog('交易状态不存在');
            exit('jyzt871');
        }
        //环境
        $marsh=explode('|',$this->data['r8_MP']);
        $this->port=$marsh[0];
        $this->type = $marsh[1];
        //支付渠道id
        if (!isset($this->depositIds[$this->port][$this->type])) {
            $this->debugLog('支付渠道异常');
            exit('zfqdi71');
        }

        $this->data['p1_MerId'] = trim($this->data['p1_MerId']);
        $this->data['r6_Order'] = trim($this->data['r6_Order']);
        $this->data['r3_Amt'] = number_format(trim($this->data['r3_Amt']),2,".","");
        $this->data['r2_TrxId'] = trim($this->data['r2_TrxId']);
        $this->data['r1_Code'] = trim($this->data['r1_Code']);



        $this->depositId = $this->depositIds[$this->port][$this->type];

        $this->isNotify = 1;
    }

    /**
     * [验证回调数据]
     * @author pete@hengcai88.com
     * @datetime 2017-08-22T15:13:21+0800
     * @return   [type]                   [description]
     */
    private function verification(){

        //验证商户id
        if($this->data['p1_MerId'] != $this->memberId){
            $this->debugLog('商户id错误');
            exit('shiderr');
        }

        //验证是否支付成功
        if($this->data['r1_Code'] != 1){
            $this->debugLog('支付失败 code'.$this->data['r1_Code']);
            $this->debugLog(json_encode($this->data));
            exit('zfsberr');
        }

        //验证回调环境是否存在
        if(!isset($this->callBackURLList[$this->port])){
            $this->debugLog('回调环境url不存在 port:'.$this->port);
            exit('hdhjerr');
        }

        $this->callBackURL = $this->callBackURLList[$this->port];
        global $hmacLocal,$safeLocal;
        if($this->data['hmac']	 != $hmacLocal    || $this->data['hmac_safe'] !=$safeLocal)
        {
            $this->debugLog('验签失败 port:'.$this->port);
            exit('yqsberr');
        }else{
            if ($this->data['r1_Code']=="1" ){
                if($this->data['r9_BType']=="1"){
                    echo  "支付成功！在线支付页面返回";
                }elseif($this->data['r9_BType']=="2"){
                    #如果需要应答机制则必须回写success.

                    //echo "SUCCESS";
                    //return;
                }

            }else{
                echo -1;
                exit;
            }
        }
    }

    /**
     * [回调日志]
     * @author pete@hengcai88.com
     * @datetime 2017-08-22T15:18:57+0800
     * @return   [type]                   [description]
     */
    private function debugLog($msg = '',$fileName = '/tmp/YbzfCallback/'){
        $path = $fileName.'/'.date("Ym").'/'.date('Ymd_H').'.txt';
        if(!file_exists(dirname($path))){
            mkdir(dirname($path),0777,true);
        }
        $arrContent = array(
            date('Y-m-d H:i:s').chr(9).$_SERVER['REMOTE_ADDR'],
            $_SERVER['REQUEST_URI'],
            var_export($msg,true),
        );
        $content = implode(chr(10).chr(13),$arrContent).chr(10).chr(13);
        file_put_contents($path, $content, FILE_APPEND );
        chdir(getcwd());//把工作目录改成原来的
    }
}