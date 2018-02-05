<?php

define('APP_MODE','cli');
require( realpath(dirname(__FILE__) . '/../') .DIRECTORY_SEPARATOR. 'index.php'); // 引入项目入口文件
require( realpath(dirname(__FILE__) ) .DIRECTORY_SEPARATOR. 'BaseCli.php');
//require( realpath(dirname(__FILE__) ) .DIRECTORY_SEPARATOR. 'a.php');
error_reporting(E_ALL ^ E_NOTICE);


class GetMusic
{
    const CLI_ADMIN_ID = 255;
    private $bDoUnLock  = FALSE;   // 是否允许释放 LOCK 文件
    private $iLotteryId = 0;       // 彩种ID  `lottery`.lotteryid
    private $_tmpIssueInfo;
    private $_debug = 0;
    /**
     * 析构
     */
    public function __destruct()
    {
        parent::__destruct();
        if ($this->bDoUnLock)
        {
            @unlink( $this->_getBaseFileName() . '.locks' );
            if (!$this->_debug)
            {
               // $oIssueInfo  = A::singleton("model_issueinfo");
                $oIssueInfo->updateItem($this->_tmpIssueInfo['issueid'], array('statusfetch' => 2));
            }
        }
    }

    public function _runCli()
    {
        $member=new \Home\Model\MemberModel();

        $member->login(1);
        //set_time_limit(1200);
        // Step: 01 初步检测 CLI 参数合法性
        if( !isset($this->aArgv[1]) || !is_numeric($this->aArgv[1]) )
        {
            $this->halt('Error : Lottery ID #1001' );
        }
        echo "\nCRON:".date('Y-m-d H:i:s')."\n";
        $this->iLotteryId = $this->aArgv[1];

        //   $this->iLotteryId=$_GET['id'];

        $this->_debug = isset($this->aArgv[2]) ? intval($this->aArgv[2]) : 0;
        if ($this->_debug)
        {
            echo "*** Debug mode ***\n";
        }
        // Step: 02 检查是否已有相同CLI在运行中
        $sLocksFileName = $this->_getBaseFileName() . '.locks';
        if( file_exists( $sLocksFileName ) )
        {
            $stat = stat($sLocksFileName);
            if( (time() - $stat['mtime']) > 1800 )
            {
                echo "文件被锁超过1800秒，被强制删除";
                @unlink($sLocksFileName);
            }
            else
            {
                $this->halt( '[' . date('Y-m-d H:i:s') .'] The CLI is running'."\n");
            }
        }
        $this->bDoUnLock = true;
        file_put_contents( $sLocksFileName ,"running" ); // CLI 独占锁
echo 234242;exit;

        echo "\n";
        return TRUE;
    }
}

$oCli = new GetMusic(TRUE);
$oCli->_runCli();
/*
$oDrawsource = A::singleton("model_drawsource");
$lottery = array('lotteryid' => 7, 'cnname' => 'JX-11Y');
$result = $oDrawsource->fetchDrawNumber($lottery);
$result = $oDrawsource->fetchDrawNumber($lottery, '20100604-49');
 *
 */
EXIT;
?>