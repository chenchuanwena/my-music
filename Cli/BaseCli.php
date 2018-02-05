<?php

abstract class BaseCli
{
    protected $bEnableDebug   = TRUE;       // 开启 DEBUG 模式
    protected $aSysInfo       = array();    // 系统信息数组
    protected $sIdentifier    = '';         // CLI 程序标识符名, 未做 addslashes() 处理
    protected $bFinished      = FALSE;      // CLI 程序是否完整执行成功
    protected $sProcessStatus = '';         // CLI->_runCli() 函数返回信息
    protected $iProcessId     = 0;
    protected $aArgv          = array();
    protected $iArgc          = 0;

    protected $sLastError     = '';         // 最后错误信息, 未加 addslashes()
    protected $iProcessSql    = 0;          // 处理的 SQL 数量


    /**
     * 构造函数(初始化数据库连接对象)
     * @return void
     */
    public function __construct( $bEnableDebug = TRUE )
    {
        set_time_limit(1800);                // 默认CLI 程序30分钟为超时
        $this->_initSysInfo();               // 初始化 系统信息
        // 在生成对象时, 如果显示指定 FALSE, 则不开启调试, 否则都将默认开启 DEBUG 调试模式
        if( $bEnableDebug === TRUE )
        {
            $this->bEnableDebug = (bool)$bEnableDebug;
        }
        else
        {
            $this->bEnableDebug = FALSE;
        }
        //echo php_sapi_name();
        if( 'cli' !== strtolower(substr(php_sapi_name(), 0, 3))
            || !empty($_GET) || !empty($_POST) || !empty($_REQUEST) )
        {
            //die("Error: This Programe can only be run in CLI mode\n");  // 禁止网页 URL 形式调用
        }
        if( !function_exists('posix_getpid') )
        {
            $this->iProcessId = -1; // 非 WINDOWS 系统, 无法获取进程ID号
        }
        else
        {
            $this->iProcessId = posix_getpid();
        }

        $this->iArgc = $GLOBALS['argc'];
        $this->aArgv = $GLOBALS['argv'];
        $this->_initId();                    // 初始化 CLI 标识符
        $this->sProcessStatus = $this->_runCli();  // 多态模式的 CLI 主核心
        if( TRUE === $this->sProcessStatus )
        {
            $this->setFinished();
        }
    }


    /**
     * 析构函数
     * CLI 模式完成时的内存释放, 日志记录
     */
    public function __destruct()
    {
        $this->_doCleanUp();
        $this->saveLog( 'cli_runtime' );
        if( $this->bFinished == TRUE )
        { // 程序正常运行成功
            return TRUE;
        }
        else
        { // _cliRun() 未完整执行导致的 程序异常中断
            return FALSE;
        }
    }


    protected function _doCleanUp()
    {
        // 对结束时间, 消耗时间进行赋值
        if( TRUE == $this->bEnableDebug )
        {
            $this->aSysInfo['etime'] = TIMESTAMP;
            $this->aSysInfo['htime'] =  $this->aSysInfo['etime'] - $this->aSysInfo['stime'] ;
        }
    }


    /**
     * 初始化信息标识符, (用于日志记录中的'别名')
     * @param string $sIdentifier
     */
    protected function _initId()
    {
        // CLI 文件标识符 example:  |usr|home|tom|a.php argv1 argv2 argv3 argv4
        $sIdentifier = str_replace( DIRECTORY_SEPARATOR, '_', trim(__FILE__) );
        $this->sIdentifier = $sIdentifier;
        $iCountArgv = count(  $this->aArgv );
        if( $iCountArgv > 1 )
        {
            for( $i=1; $i<$iCountArgv; $i++ )
            {
                $this->sIdentifier .= ' '.$this->aArgv[$i];
            }
        }
    }


    /**
     * 初始化系统信息
     */
    function _initSysInfo()
    {
        if( FALSE == $this->bEnableDebug )
        { // 未开启 DEBUG 模式则不进行记录
            $this->aSysInfo = array(
                'stime' => NULL,           // CLI 开始执行时间 .毫秒
                'etime' => NULL,           // CLI 结束执行时间 .毫秒
                'htime' => NULL,           // CLI 总执行时间   .毫秒
                //'' =>
            );
            return ;
        }
        $this->aSysInfo = array(
            'stime' => TIMESTAMP, // CLI 开始执行时间 .毫秒
            'etime' => NULL,           // CLI 结束执行时间 .毫秒
            'htime' => NULL,           // CLI 总执行时间   .毫秒
            //'' =>
        );
    }


    /**
     * 设置 CLI 执行成功标记
     * 在派生的 _runCli() 中最后调用, 用于判断 CLI 是否异常中断
     */
    protected function setFinished()
    {
        $this->bFinished = TRUE;
    }


    /**
     * 用于派生类重写的 '业务流程实现函数/方法'
     * @return bool | string
     *     - 执行失败, 则返回 : '错误字符串'
     *     - 执行成功, 则返回全等于的 TRUE 类型
     * 即: 不全等于的任何字符|数字, 都认为业务流程执行失败.
     */
    protected function _runCli()
    {
        // do something ..
    }

    protected function _setSqlCount( $iCount = 0 )
    {
        $this->iProcessSql = $iCount;
    }


    protected function _getBaseFileName()
    {
        //	var_dump(dirname($_SERVER['PHP_SELF']));
        return dirname($_SERVER['PHP_SELF']). DS .basename( $_SERVER['PHP_SELF'] , '.php');
    }


    /**
     * 错误处理
     * @param string $sMessage
     */
    protected function halt( $sMessage = '', $bShowDebugTrace = FALSE )
    {
        $this->_doCleanUp();
        $this->sLastError =  $sMessage;

        // 执行日志记录操作
        if( TRUE == $this->bEnableDebug )
        {
            echo $this->sLastError ."\n";
            $this->saveLog();
        }
//        if( TRUE == $bShowDebugTrace )
//        {
//            print_r( A::getDebugTrace(FALSE) );
//        }
        EXIT;
    }


    /**
     * 将错误日志写入文本文件
     * @param string $sMessage
     */
    private function saveLog( $sDirName='cli_error', $sType = 'txt' )
    {
        $aLogs = array(
            'htime'      => $this->aSysInfo['htime'],   // CLI 消耗时间
            'stime'      => $this->aSysInfo['stime'],   // CLI 开始时间
            'etime'      => $this->aSysInfo['etime'],   // CLI 结束时间
            'pid'        => intval($this->iProcessId),  // pid
            'finished'   => intval($this->bFinished),   // CLI 程序是否完整执行成功
            'Finishstat' => $this->sProcessStatus,
            'lasterrmsg' => $this->sLastError,
            'sqlcount'   => intval($this->iProcessSql),
            // more..
        );
        //print_r($aLogs);
//
//        if( $sType == 'txt' )
//        {
//            $sMessage =  $this->sLastError . "\n  \$sSerial = ". serialize($aLogs);
//            if( !isset( $GLOBALS['oLogs'] ) )
//            {
//                $GLOBALS['oLogs'] = \Think\Log::log();
//            }
//            $GLOBALS['oLogs']->addDebug( $sMessage, $sDirName );
//        }
//        if( $sType == 'db' )
//        {
//            // TODO: 将日志写入数据库
//        }
    }
}
?>