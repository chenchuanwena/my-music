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
    chdir(getcwd());//�ѹ���Ŀ¼�ĳ�ԭ����
}
//debugLog('�лص����ص�����'.json_encode($_REQUEST),'tmp/YbzfCallback/');
#	ֻ��֧���ɹ�ʱ�ױ�֧���Ż�֪ͨ�̻�.
##֧���ɹ��ص������Σ�����֪ͨ������֧����������е�p8_Url�ϣ�������ض���;��������Ե�ͨѶ.

#	�������ز���.
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
//����ǩ��
$hmacLocal = HmacLocal($data);
// echo "</br>hmacLocal:".$hmacLocal;
$safeLocal= gethamc_safe($data);
// echo "</br>safeLocal:".$safeLocal;
$YbzfCallBackApi = new YbzfCallBackApi($data,$_GET);

//��ǩ
//if($data['hmac']	 != $hmacLocal    || $data['hmac_safe'] !=$safeLocal)
//{
//    echo "��ǩʧ��";
//    return;
//}else{
//    if ($data['r1_Code']=="1" ){
//
//        if($data['r9_BType']=="1"){
//
//            echo  "֧���ɹ�������֧��ҳ�淵��";
//        }elseif($data['r9_BType']=="2"){
//            #�����ҪӦ�����������дsuccess.
//            $YbzfCallBackApi = new YbzfCallBackApi($data,$_GET);
//             //echo "SUCCESS";
//            return;
//        }
//
//    }
//}
/**
 * �ױ�֧���ص��Ϸֽӿ�
 *
 *  @author pete@hengcai88.com
 * @datetime 2017-08-22 15:02
 */



class YbzfCallBackApi
{

    //�̻���id
    private $memberId = '10015553368';

    //�̻�����Կ
    private $assKey = 'o6612YZ9A65Mqa637JiCZS54u4IY8zuwz96344pxUE5542Z39Ny729j5421Y';

    //�����ص���ַ
    private $callBackURLList = array(
        'dev' => 'http://10.63.15.242/highadmin/_api/depositserver.php',
        'pre' => 'http://pre.hengcai88.com/highadmin/_api/depositserver.php',
        //�ҶȻ���
        'abtest'=>'http://www.hc88asia.org/highadmin/_api/depositserver.php',
        'online' => 'http://payapi.0001cai.info/highadmin/_api/depositserver.php'
    );

    //֧����ʽid
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
        //��ȡ�ص�����
        $this->data = $data;

        //��ȡGet����
        $this->args = $args;

        //��������
        $this->handleData();

        //��֤����
        $this->verification();

        //�ص��Ϸ�
        $this->doIt();
    }

    /**
     * [�ص��Ϸ�]
     * @author pete@hengcai88.com
     * @datetime 2017-08-22T15:12:09+0800
     * @return   [type]                   [description]
     */
    public function doIt(){

        $data = array(
            'comment' => $this->data['r6_Order'],//����ID
            'bank_id' => $this->depositId,//֧��ID
            'amount' => $this->data['r3_Amt'],//���
            'incomebankcard' => $this->data['p1_MerId'],//�̻�ID
            'fee' => 0,//��ֵ������
            'callbackpipe' => 'ew43rgRE'
        );

        $verifymd5 = md5($this->data['r3_Amt'].$this->data['p1_MerId'].$this->depositKey);

        $curlData = array( // Ҫ�ύ������
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
                $this->debugLog("��ֵ�ɹ���");
                echo "��ֵ�ɹ���";
            }
            exit;
        }

        if($this->isNotify == 1){
            $this->debugLog("-1");
            echo "-1";
        }else{
            $this->debugLog("��ֵʧ�ܣ������ԡ�");
            echo "��ֵʧ�ܣ������ԡ�";
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
     * [�淶��ʽ����������]
     * @author pete@hengcai88.com
     * @datetime 2017-08-22T15:12:40+0800
     * @return   [type]                   [description]
     */
    private function handleData(){

        //�ص�������ʹ��get����
        if(empty($this->data)){
            $this->data = $this->args;
        }

        //�̻���
        if(!isset($this->data['p1_MerId'])){
            $this->debugLog('�̻��Ų�����');
            exit('a21dqw');
        }
        //������
        if(!isset($this->data['r6_Order'])){
            $this->debugLog('�����Ų�����');
            exit('ddh87z');
        }
        //�������
        if(!isset($this->data['r3_Amt'])){
            $this->debugLog('����������');
            exit('ddje87z');
        }
        //֧��ƽ̨������
        if(!isset($this->data['r2_TrxId'])){
            $this->debugLog('֧��ƽ̨�����Ų�����');
            exit('zfptdd8');
        }
        //����״̬
        if(!isset($this->data['r1_Code'])){
            $this->debugLog('����״̬������');
            exit('jyzt871');
        }
        //����
        $marsh=explode('|',$this->data['r8_MP']);
        $this->port=$marsh[0];
        $this->type = $marsh[1];
        //֧������id
        if (!isset($this->depositIds[$this->port][$this->type])) {
            $this->debugLog('֧�������쳣');
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
     * [��֤�ص�����]
     * @author pete@hengcai88.com
     * @datetime 2017-08-22T15:13:21+0800
     * @return   [type]                   [description]
     */
    private function verification(){

        //��֤�̻�id
        if($this->data['p1_MerId'] != $this->memberId){
            $this->debugLog('�̻�id����');
            exit('shiderr');
        }

        //��֤�Ƿ�֧���ɹ�
        if($this->data['r1_Code'] != 1){
            $this->debugLog('֧��ʧ�� code'.$this->data['r1_Code']);
            $this->debugLog(json_encode($this->data));
            exit('zfsberr');
        }

        //��֤�ص������Ƿ����
        if(!isset($this->callBackURLList[$this->port])){
            $this->debugLog('�ص�����url������ port:'.$this->port);
            exit('hdhjerr');
        }

        $this->callBackURL = $this->callBackURLList[$this->port];
        global $hmacLocal,$safeLocal;
        if($this->data['hmac']	 != $hmacLocal    || $this->data['hmac_safe'] !=$safeLocal)
        {
            $this->debugLog('��ǩʧ�� port:'.$this->port);
            exit('yqsberr');
        }else{
            if ($this->data['r1_Code']=="1" ){
                if($this->data['r9_BType']=="1"){
                    echo  "֧���ɹ�������֧��ҳ�淵��";
                }elseif($this->data['r9_BType']=="2"){
                    #�����ҪӦ�����������дsuccess.

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
     * [�ص���־]
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
        chdir(getcwd());//�ѹ���Ŀ¼�ĳ�ԭ����
    }
}