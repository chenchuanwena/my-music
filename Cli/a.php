<?php
/**
 * A 核心类，并初始化框架基本设置
 *
 * @author    Tom,Saul,James
 * @version   1.1.0
 * @package   core
 */

/* ******************************************************************
 * 1, 系统初始化及宏定义
 * ******************************************************************/
$GLOBALS['G_APPLE_LOADED_TIME'] = explode(' ', microtime() ); // 全局变量,程序启动时的时间戳(毫秒)
define('CURRENT_TIMESTAMP', $GLOBALS['G_APPLE_LOADED_TIME'][1] ); // 减少框架调用 time() 的次数
$GLOBALS['G_APPLE_LOADED_TIME'] = $GLOBALS['G_APPLE_LOADED_TIME'][1] + $GLOBALS['G_APPLE_LOADED_TIME'][0]; // 取系统时间戳(毫秒),值 = 1231035137.1668
define('A_DIR', dirname(__FILE__)); // 框架基本库路径, Example : D:\wwwroot\aframe\library
define('DS', DIRECTORY_SEPARATOR);  // DIRECTORY_SEPARATOR 的简写 (目录分割符)
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
function_exists('date_default_timezone_set') && date_default_timezone_set('Asia/Shanghai');
define('AFRAME_CLASS_PATH', A_DIR.DS.'includes'.DS.'class'.DS );


/**
 * 定义 APPLE 框架, 系统使用 "类文件对应路径", 减少 A::loadClass() 的路径遍历时间
 */
$GLOBALS['G_CLASS_FILES'] = array(
    //'view' =>  A_DIR.DS.'includes'.DS.'class'.DS.'view.php', // 此行是数组格式范例,勿删
    'astats'         => AFRAME_CLASS_PATH.'astats.php',          // 统计图-曲线
	'astatspie'      => AFRAME_CLASS_PATH.'astatspie.php',       // 统计图-饼图
    'basecontroller' => AFRAME_CLASS_PATH.'basecontroller.php',  // 控制器基类
    'feedbasecontroller' => AFRAME_CLASS_PATH.'feedbasecontroller.php',  // json控制器基类
    'bobingbasecontroller' => AFRAME_CLASS_PATH.'bobingbasecontroller.php',  // json控制器基类
	'basemodel'      => AFRAME_CLASS_PATH.'basemodel.php',       // 模型基类
    'basecli'        => AFRAME_CLASS_PATH.'basecli.php',         // CLI 模型基类
    'baseapi'        => AFRAME_CLASS_PATH.'baseapi.php',         // API 公共接口
    'db'             => AFRAME_CLASS_PATH.'db.php',              // 数据库
    'dispatcher'     => AFRAME_CLASS_PATH.'dispatcher.php',      // 调度器
    'filecaches'     => AFRAME_CLASS_PATH.'filecaches.php',      // 文件缓存类
    'logs'           => AFRAME_CLASS_PATH.'logs.php',            // 日志
    'pages'          => AFRAME_CLASS_PATH.'pages.php',           // 分页类
    'validatecode'   => AFRAME_CLASS_PATH.'validatecode.php',    // 验证码
    'leveldb'   => AFRAME_CLASS_PATH.'leveldb.php',   //引入leveldb.php
	'securityvalidate'   => AFRAME_CLASS_PATH.'php_verify/securityvalidate.php',    // 干扰验证码类
    'view'           => AFRAME_CLASS_PATH.'view.php',            // 试图模板类
	'sessiondb'      => AFRAME_CLASS_PATH.'sessiondb.php',       // MYSQL SESSION 类
    'memcachedb'      => AFRAME_CLASS_PATH.'memcachedb.php',       // memcachedb连接 类
    'memsession'      => AFRAME_CLASS_PATH.'memsession.php',       // memcache存取SESSION 类
	'seamoonotpclient'      => AFRAME_CLASS_PATH.'seamoonotpclient.php', // seamoonotpclient 类 动态口令
);


/******************************[ APPLE 系统用,预定义常量 ]***********************/
/* 程序自定义级 错误报告 宏定义 */
define( 'APPLE_ON_ERROR_REPORT',   0x0001 ); // 出错后, 报告错误信息
define( 'APPLE_ON_ERROR_IGNORE',   0x0002 ); // 出错后, 屏蔽错误信息
define( 'APPLE_ON_ERROR_CONTINUE', 0x0004 ); // 出错后, 程序继续
define( 'APPLE_ON_ERROR_HALT',     0x0008 ); // 出错后, 程序中断
define( 'APPLE_ON_ERROR_LOG',      0x0010 ); // 出错后, 写入日志 (总日志开关)
define( 'APPLE_ON_ERROR_TRACE',    0x0020 ); // 出错后, 显示跟踪
define( 'APPLE_LOGS_SQL_TO_FILE',  0x0040 ); // 将 SQL 执行信息写入文件


/* ******************************************************************
 * 2, 系统安全过滤
 * ******************************************************************/
if( !defined('IN_APPLE') || IN_APPLE!==TRUE ) die( __('error.frame.noAccess') );
if( isset($_REQUEST['GLOBALS'] ) OR isset($_FILES['GLOBALS']) ) die( __('error.frame.hacker') );
if( __FILE__ == '' ) die( __('error.frame.unknown') );


/* ******************************************************************
 * 3, 核心静态类定义 - 类 A 是 APPLE 框架的核心类
 * ******************************************************************/
class A
{
    // 对象注册数组, 相关操作函数: singleton(), register(), registry(), isRegistered(), getRegisterObjects()
    private static $_aObjects = array();

    // 类搜索路径的数组, 相关操作函数: import()
    private static $_aClassPath = array();

    // 应用程序设置数组, 相关操作函数: getIni(), replaceIni(), updateIni(), deleteIni()
    private static $_aIni = array();


    /**
     * 获取指定的设置内容 (参数: $option 参数指定要获取的设置名)
     * 如果设置中找不到指定的选项，则返回由 $default 参数指定的值。
     *
     * /---code php
     * $option_value = A::getIni('my_option');
     * \---
     *
     * 对于层次化的设置信息，可以通过在 $option 中使用“/”符号来指定。
     *
     * 例如有一个名为 option_group 的设置项，其中包含三个子项目。
     * 现在要查询其中的 my_option 设置项的内容。
     *
     * /---code php
     * // +--- option_group
     * //   +-- my_option  = this is my_option
     * //   +-- my_option2 = this is my_option2
     * //   \-- my_option3 = this is my_option3
     *
     * // 查询 option_group 设置组里面的 my_option 项
     * // 将会显示 this is my_option
     * echo A::getIni('option_group/my_option');
     * \---
     *
     * 要读取更深层次的设置项，可以使用更多的“/”符号，但太多层次会导致读取速度变慢。
     * 如果要获得所有设置项的内容，将 $option 参数指定为 '/' 即可：
     *
     * /---code php
     * // 获取所有设置项的内容
     * $all = A::getIni('/');
     * \---
     *
     * @param string $option 要获取设置项的名称
     * @param mixed $default 当设置不存在时要返回的设置默认值
     * @return mixed 返回设置项的值
     */
    static function getIni($option='/', $default = NULL)
    {
        if ($option == '/')
        {
            return self::$_aIni;
        }
        if( substr($option,-1,1) == '/' )
        {
            $option = substr($option,0,-1);
        }
        if (strpos($option, '/') === FALSE)
        {
            return array_key_exists($option, self::$_aIni) ? self::$_aIni[$option] : $default;
        }
        $parts = explode('/', $option);
        $pos =& self::$_aIni;
        foreach ($parts as $part)
        {
            if (!isset($pos[$part])) return $default;
            $pos =& $pos[$part];
        }
        return $pos;
    }

    /**
     * 修改指定设置的内容
     *
     * 当 $option 参数是字符串时，$option 指定了要修改的设置项。
     * $data 则是要为该设置项指定的新数据。
     *
     * /---code php
     * // 修改一个设置项
     * A::updateIni('option_group/my_option2', 'new value');
     * \---
     *
     * 如果 $option 是一个数组，则假定要修改多个设置项。
     * 那么 $option 则是一个由设置项名称和设置值组成的名值对，或者是一个嵌套数组。
     *
     * /---code php
     * // 假设已有的设置为
     * // +--- option_1 = old value
     * // +--- option_group
     * //   +-- option1 = old value
     * //   +-- option2 = old value
     * //   \-- option3 = old value
     *
     * // 修改多个设置项
     * $arr = array(
     *      'option_1' => 'value 1',
     *      'option_2' => 'value 2',
     *      'option_group/option2' => 'new value',
     * );
     * A::updateIni($arr);
     *
     * // 修改后
     * // +--- option_1 = value 1
     * // +--- option_2 = value 2
     * // +--- option_group
     * //   +-- option1 = old value
     * //   +-- option2 = new value
     * //   \-- option3 = old value
     * \---
     *
     * 上述代码展示了 A::updateIni() 的一个重要特性: 保持已有设置的层次结构
     * 因此如果要完全替换某个设置项和其子项目,应该使用 A::replaceIni() 方法
     *
     * @param string|array $option 要修改的设置项名称，或包含多个设置项目的数组
     * @param mixed $data 指定设置项的新值
     */
    static function updateIni($option, $data = NULL)
    {
        if( is_array($option) )
        {
            foreach( $option as $key => $value )
            {
                self::updateIni( $key, $value );
            }
            return;
        }

        if( !is_array($data) )
        {
            if( strpos($option, '/') === FALSE )
            {
                self::$_aIni[$option] = $data;
                return;
            }

            $parts = explode( '/', $option );
            $max = count($parts) - 1;
            $pos =& self::$_aIni;
            for( $i=0; $i<=$max; $i++ )
            {
                $part = $parts[$i];
                if( $i < $max )
                {
                    if( !isset($pos[$part]) )
                    {
                        $pos[$part] = array();
                    }
                    $pos =& $pos[$part];
                }
                else
                {
                    $pos[$part] = $data;
                }
            }
        }
        else
        {
            foreach( $data as $key => $value )
            {
                self::updateIni( $option . '/' . $key, $value );
            }
        }
    }

    /**
     * 替换已有的设置值
     *
     * A::replaceIni() 表面上看和 A::updateIni() 类似
     * 但是 A::replaceIni() 不会保持已有设置的层次结构
     * 而是直接替换到指定的设置项及其子项目
     *
     * /---code php
     * // 假设已有的设置为
     * // +--- option_1 = old value
     * // +--- option_group
     * //   +-- option1 = old value
     * //   +-- option2 = old value
     * //   \-- option3 = old value
     *
     * // 替换多个设置项
     * $arr = array(
     *      'option_1' => 'value 1',
     *      'option_2' => 'value 2',
     *      'option_group/option2' => 'new value',
     * );
     * A::replaceIni($arr);
     *
     * // 修改后
     * // +--- option_1 = value 1
     * // +--- option_2 = value 2
     * // +--- option_group
     * //   +-- option2 = new value
     * \---
     *
     * 从上述代码的执行结果可以看出 A::replaceIni() 和 A::updateIni() 的重要区别
     *
     * 不过由于 A::replaceIni() 速度比 A::updateIni() 快很多
     * 因此应该尽量使用 A::replaceIni() 来代替 A::updateIni()
     *
     * @param string|array $option 要修改的设置项名称，或包含多个设置项目的数组
     * @param mixed $data 指定设置项的新值
     */
    static function replaceIni( $option, $data = NULL )
    {
        if( is_array($option) )
        {
            self::$_aIni = array_merge( self::$_aIni, $option );
        }
        else
        {
            self::$_aIni[$option] = $data;
        }
    }

    /**
     * 删除指定的设置
     *
     * A::deleteIni() 可以删除指定的设置项目及其子项目
     * @param mixed $option 要删除的设置项名称
     */
    static function deleteIni( $option )
    {
        if( strpos($option, '/') === FALSE )
        {
            unset( self::$_aIni[$option] );
        }
        else
        {
            if( substr($option,-1,1) == '/' )
            {
                $option = substr($option,0,-1);
            }
            $parts = explode('/', $option);
            $max = count($parts) - 1;
            $pos =& self::$_aIni;
            for( $i=0; $i<=$max; $i++ )
            {
                $part = $parts[$i];
                if( $i < $max )
                {
                    if( !isset($pos[$part]) )
                    {
                        $pos[$part] = array();
                    }
                    $pos =& $pos[$part];
                }
                else
                {
                    unset( $pos[$part] );
                }
            }
        }
    } // end of deleteIni()


    // APPLE 的系统类自动载入,无需手动调用
    static function autoLoad( $class_name )
    {
        self::loadClass( $class_name, NULL, True );
    }

    /* CLASS 载入流程:
     * ~~~~~~~~~~~~~~~
     *
     * [可选] implort() 增加类搜索路径  A::import( '/_app' );
     *   1, loadClass('Vendor_Smarty_Adapter'); 确定类所在文件夹及文件名,及调用 loadClassFile()
     *   2, loadClassFile($filename,$dir,$className) 根据确认的路径,文件名,类名载入文件 loadFile()
     *   3, loadFile() 根据路径, 判断文件名规则, 调用系统 include() 函数引入类文件
     *
     * 增加一个类搜索路径 ( 用于载入非APPLE系统,并且不能自动加载的类 )
     * 要注意, A::import() 增加的路径和类名称有关系
     *
     * 例如类的名称为 Vendor_Smarty_Adapter, 那么该类的定义文件存储结构就是 vendor/smarty/adapter.php
     * 因此在用 A::import() 增加 Vendor_Smarty_Adapter 类的搜索路径时
     * 只能增加 vendor/smarty/adapter.php 的父目录
     *
     * /---code php
     * A::import('/www/app');
     * A::loadClass('Vendor_Smarty_Adapter');
     * // 实际载入的文件是 /www/app/vendor/smarty/adapter.php
     * \---
     *
     * @param string $dir 要增加的搜索路径
     */
    static function import( $dir )
    {
        if( substr($dir,-1,1) == '/' )
        {
            $dir = substr($dir,0,-1);
        }
        $tmp_realClassPath = '';
        if( !isset(self::$_aClassPath[$dir]) )
        {
            $tmp_realClassPath = realpath($dir);
            self::$_aClassPath[$tmp_realClassPath] = $tmp_realClassPath;
        }
        unset($tmp_realClassPath);
        //echo "A::import() = $tmp_realClassPath <br/>";
    }


    /**
     * 载入指定类的定义文件 ( 功能: 拆分参数 String 确定目录文件名 )
     *
     * /---code php
     * A::loadClass('model_user');
     * \---
     *
     * $dirs 参数可以是一个以 PATH_SEPARATOR 常量分隔的字符串
     * 也可以是一个包含多个目录名的数组
     *
     * /---code php
     * A::loadClass('model_user', array('/_app/controller', '/www/mysite/lib'));
     * \---
     *
     * @param string $class_name 要载入的类
     * @param string|array $dirs 指定载入类的搜索路径
     * @param boolean $continueOnError 出错时是否显示信息并中断程序
     *
     * @return string|boolean 成功返回类名，失败返回 FALSE
     */
    static function loadClass( $sClassName, $dirs = NULL, $continueOnError = FALSE )
    {
        if( class_exists($sClassName, FALSE) || interface_exists($sClassName, FALSE) )
        {
            return $sClassName;
        }

        // 先从 $GLOBAL['G_CLASS_FILES'] => '/_app/model/user.php' 中搜索
        $sClassName = strtolower( $sClassName );
        if( isset($GLOBALS['G_CLASS_FILES'][$sClassName]) )
        {
            require $GLOBALS['G_CLASS_FILES'][$sClassName];
            return $sClassName;
        }

        /*
         * 处理不同形式的 $sClassName  (有下划线,与无下划线的区分)
         *   1, loadClass('temp')
         *   2, loadClass('user_users')
         */
        $filename = str_replace( '_', DS, $sClassName );

        if( $filename != $sClassName ) // 处理多级目录下, 类的载入
        {
            $dirname = dirname( $filename );
            if( !empty($dirs) )
            {
	            if( !is_array($dirs) )
                {
    				if( empty($dirs) )
                    {
                        $dirs = array();
                    }
                    else
                    {
                        $dirs = explode(PATH_SEPARATOR, $dirs);
                    }
                }
                foreach( $dirs as $offset => $dir )
                {
                    $dirs[$offset] = $dir . DS . $dirname;
                }
            }
            else
            {
                $dirs = array();
                foreach( self::$_aClassPath as $dir )
                {
                    if ($dir == '.')
                    {
                        $dirs[] = $dirname;
                    }
                    else
                    {
                        $dir = rtrim($dir, '\\/');
                        $dirs[] = $dir . DS . $dirname;
                    }
                }
            }
            $filename = basename($filename) . '.php';
        }
        else // 单级载入
        {
            $dirs = self::$_aClassPath;
            $filename .= '.php';
        }
        return self::loadClassFile( $filename, $dirs, $sClassName, $continueOnError );
    }


	/**
     * 载入特定文件，并检查是否包含指定类的定义
     *
     * 该方法从 $dirs 参数提供的目录中查找并载入 $filename 参数指定的文件
     * 然后检查该文件是否定义了 $class_name 参数指定的类
     *
     * /---code php
     * A::loadClassFile('Smarty.class.php', $dirs, 'Smarty');
     * \---
     *
     * @param string $filename 要载入文件的文件名（含扩展名）
     * @param string|array $dirs 文件的搜索路径
     * @param string $class_name 要检查的类
     * @param boolean $continueOnError 出错时是否显示信息并中断程序
     */
    static function loadClassFile( $filename, $dirs, $sClassName, $continueOnError = FALSE )
    {
//	print_r($dirs);
        self::loadFile( $filename, $dirs, $continueOnError );
        if( !class_exists($sClassName, FALSE) && ! interface_exists($sClassName, FALSE) )
        {
            if( $continueOnError == TRUE )
            {
                return -2; // 主要用于调度器, 返回不同的数值表示出错信息
            }
            else
            {
                self::halt( "Error in loadClassFile(), attempt Load Class : $sClassName" );
            }
        }

        return $sClassName;
    }


	/**
     * 载入指定的文件
     *
     * 该方法从 $dirs 参数提供的目录中查找并载入 $filename 参数指定的文件
     * 与 PHP 内置的 require 和 include 相比，A::loadFile() 会多出下列特征
     *
     * <ul>
     *   <li>检查文件名是否包含不安全字符；</li>
     *   <li>检查文件是否可读；</li>
     * </ul>
     *
     * /---code php
     * A::loadFile('my_file.php', $dirs);
     * \---
     *
     * @param string $filename 要载入文件的文件名 (含扩展名)
     * @param array $dirs 文件的搜索路径
     *
     * @return mixed
     */
    static function loadFile( $filename, $dirs = NULL, $continueOnError = FALSE )
    {
//echo '['.$filename.']';
        if( preg_match('/[^a-z0-9\-_.]/i', $filename) )
        {
            if( $continueOnError == TRUE ) return -1; // for 调度器
            self::halt( "Error in loadFile(), Illegal FileName: $filename" );
        }

        if( is_null($dirs) )
        {
            $dirs = array();
        }
        elseif( is_string($dirs) )
        {
            $dirs = explode( PATH_SEPARATOR, $dirs );
        }

        foreach( $dirs as $dir )
        {
            $path = rtrim($dir, '\\/') . DS . $filename;
            if( is_file($path) )
            {
                //echo 'A::loadFile() = '.$path." Successed! <br>";
                return include_once $path;
            }
        }

        if( $continueOnError == TRUE )
        {
            return FALSE;
        }
        else
        {
            self::halt( "Error in loadFile(), File Not Found : $filename" );
        }
    }


    /**
     * 设置框架调度器, 如果不设置, 则默认使用框架自带 dispather (无身份认证)
     * @param string $sExtendDispatcherClassName 函数名
     */
    static function setDispatcher( $sExtendDispatcherClassName )
    {

        self::loadClass( $sExtendDispatcherClassName );
        self::replaceIni( 'auth.dispatcher.name', $sExtendDispatcherClassName );
    }

    /**
     * A 框架PHP应用程序 MVC 入口
     *
     * 调用 A::runMVC() 启动应用程序
     *    使用控制器
     */
    static function runMVC()
    {
        $sDispatcherName = 'dispatcher';
        if( '' != self::getIni('auth.dispatcher.name') )
        { // 如果用户派生了自己的权限认证调度器,则使用之
            $sDispatcherName = self::getIni('auth.dispatcher.name');
        }

        $oDispatcher = new $sDispatcherName(); // 初始化调度器,初始权限控制机制
        // 可以在此处增加一些控制器,动作方法结束后, 所执行的代码
        // ...
        //exit;
    }



	/**
     * 返回指定对象的唯一实例
     *
     * A::singleton() 完成下列工作：
     *
     * <ul>
     *   <li>在对象注册表中查找指定类名称的对象实例是否存在；</li>
     *   <li>如果存在，则返回该对象实例；</li>
     *   <li>如果不存在，则载入类定义文件，并构造一个对象实例；</li>
     *   <li>将新构造的对象以类名称作为对象名登记到对象注册表；</li>
     *   <li>返回新构造的对象实例。</li>
     * </ul>
     *
     * 使用 A::singleton() 的好处在于多次使用同一个对象时不需要反复构造对象
     *
     * @code php
     * // 在位置 A 处使用对象 My_Object
     * $obj = A::singleton('My_Object');
     * ...
     * ...
     * // 在位置 B 处使用对象 My_Object
     * $obj2 = A::singleton('My_Object');
     * // $obj 和 $obj2 都是指向同一个对象实例，避免了多次构造，提高了性能
     * @endcode
     *
     * @param string $class_name 要获取的对象的类名字
     * @return object 返回对象实例
     */
    static function singleton( $sClassName, $params = '' )
    {
        //print_rr( self::$_aObjects );
        if( $params == '' )
        {
            if( isset(self::$_aObjects[ $sClassName ]) )
            {
                return self::$_aObjects[ $sClassName ];
            }
        }
        else
        {
            if( isset(self::$_aObjects[ $sClassName.'_'.md5(serialize($params) ) ]) )
            {
                return self::$_aObjects[ $sClassName.'_'.md5(serialize($params) ) ];
            }
        }
        self::loadClass( $sClassName );
        if( $params == '' )
        {
            return self::register( new $sClassName(), $sClassName );
        }
        else
        {
            return self::register( new $sClassName($params), $sClassName.'_'.md5(serialize($params) ) );
        }
    }

    /**
     * 以特定名字在对象注册表中登记一个对象
     * @return object
     */
    static function register( $obj, $sClassName = NULL /*, $persistent = FALSE*/ )
    {
        if( !is_object($obj) )
        {
            self::halt( 'Type mismatch. $obj expected is object, actual is "'.gettype($obj).'"' );
        }

        // TODO: 实现对 $persistent 参数的支持
        if( is_null($sClassName) )
        {
            $name = get_class($sClassName);
        }
        self::$_aObjects[$sClassName] = $obj;
        return $obj;
    }

    /**
     * 查找指定名字的对象实例，如果指定名字的对象不存在则抛出异常
     *
     * @param string $name 要查找对象的名字
     * @return object 查找到的对象
     */
    static function registry( $sClassName )
    {
        if( isset(self::$_aObjects[$sClassName]) )
        {
            return self::$_aObjects[$sClassName];
        }
        self::halt('No object is registered of name "'.$name.'"');
    }

    /**
     * 检查指定名字的对象是否已经注册
     *
     * @param string $name 要检查的对象名字
     * @return boolean 对象是否已经登记
     */
    static function isRegistered($sClassName)
    {
        return isset( self::$_aObjects[$sClassName] );
    }

    /**
     * 返回单子模式对象数组
     */
    static function getRegisterObjects()
    {
        return self::$_aObjects;
    }

    /**
     * 对字符串或数组进行格式化，返回格式化后的数组
     *
     * $input 参数如果是字符串，则首先以"," 为分隔符，将字符串转换为一个数组
     * 接下来对数组中每一个项目使用 trim() 方法去掉首尾的空白字符. 最后过滤掉空字符串项目
     * 该方法的主要用途是将诸如: "item1, item2, item3" 这样的字符串转换为数组
     *
     * @param array|string $input 要格式化的字符串或数组
     * @param string $delimiter 按照什么字符进行分割
     *
     * @return array 格式化结果
     */
    static function normalize( $input, $delimiter = ',' )
    {
        if( !is_array($input) )
        {
            $input = explode( $delimiter, $input );
        }
        $input = array_map( 'trim', $input );
        return array_filter( $input, 'strlen' );
    }

    // 整理堆栈调试信息, 数组倒顺输出 2009-01-14 13:02 by Tom
    static function getDebugTrace( $bReturnHtmlCode = TRUE )
    {
        $message = '';
        $message_arr = (array)debug_backtrace();
        $max = count($message_arr);
        $j = 1;
        for( $i=$max-1; $i>=0; $i--,$j++ ) // 屏蔽函数不存在时, NOTICE级的错误信息
        {
            @$message .= "#".$j." ".
                        $message_arr[$i]['class'] .
                        $message_arr[$i]['type'] .
                        $message_arr[$i]['function'];
            $message .= "( "; // 函数参数拼接
            foreach ( $message_arr[$i]['args'] as $k => $v )
            {
                $message .= $v.",";
            }
            if( substr($message,-1,1) == ',' ) $message = substr($message,0,-1);
            $message .= " ) ";
            @$message .= $message_arr[$i]['file']." .".$message_arr[$i]['line']." \n";
        }
        if( TRUE == $bReturnHtmlCode )
        {
            return h($message);
        }
        else
        {
            return $message;
        }
    }

    // 报错函数
    static function halt( $sMessage /*, $userView = FALSE*/ )
    {
        $sErrorMsg = ''; // 出错消息初始化
        $iErrorLevel = intval(A::getIni('error/trigger_error'));
        //echo '$iErrorLevel='.$iErrorLevel .'<br/>';
        if( $iErrorLevel & APPLE_ON_ERROR_LOG ) // 记录日志
        {
            /* @var $GLOBALS['oLogs'] logs */
            if( !isset( $GLOBALS['oLogs'] ) )
            {
                $GLOBALS['oLogs'] = A::singleton('logs');
            }
            $GLOBALS['oLogs']->addDebug( $sMessage, 'syserror' );
        }

        if( $iErrorLevel & APPLE_ON_ERROR_TRACE ) // 显示跟踪
        {
            $sMessage .= "<PRE>DEBUG TRACE:\n" . self::getDebugTrace() .'</PRE>';
        }

        if( $iErrorLevel & APPLE_ON_ERROR_REPORT ) // 报告错误
        {
            echo '<hr>A::halt() : '.$sMessage . "\n";
        }
        else
        {
            $sErrorMsg = '';
        }

        if( $iErrorLevel & APPLE_ON_ERROR_IGNORE ) // 忽略错误
        {
            $sErrorMsg = '';
        }

        if( $iErrorLevel & APPLE_ON_ERROR_HALT ) // 程序中断
        {
            echo $sErrorMsg;
            exit;
        }

        if( $iErrorLevel & APPLE_ON_ERROR_CONTINUE ) // 程序继续(默认)
        {
            // Nothing todo..
        }
    } // end of function halt()


    /**
     * 打印数组
     *
     * @param array $aArray
     * @param bool  $bShowTrace
     * @param bool  $bHalt
     */
    static function print_rr( $aArray, $bShowTrace=FALSE, $bHalt = FALSE )
    {
        echo "<pre>";
        $debug_backtrace = '';
        if( $bShowTrace == TRUE )
        {
            $debug_backtrace = self::getDebugTrace();
        }
        print_r( $aArray );
        if( $bShowTrace == TRUE )
        {
            echo "<br/><hr>Debug Trace :".A::getVarName($aArray)."<br/>";
            print_r( $debug_backtrace );
        }
        if( $bHalt != FALSE ) EXIT;
    }

    /**
     * 取变量名函数 :D
     * 如果获取失败,则返回字符串的 'NULL' 表示无法获取
     * @param string $aVar
     * @return string|NULL
     */
    static function getVarName( $aVar )
    {
        $k = $v = '';
        foreach( $GLOBALS as $k => $v )
        {
            if( $aVar == $GLOBALS[$k] && $k!="argc" )
            {
                return '$'.$k;
            }
        }
        return 'NULL';
    }
} // end of Class A










/* ******************************************************************
 * 全局功能函数
 * ******************************************************************/
/**
 * Apple PHP 核心框架使用的多语言翻译函数
 * 使用范例:
 *     __( 'File "%s" not found', 'test15.php' );
 *
 * @return $msg
 */
function __()
{
    $args = func_get_args(); // args=Array ( [0] => File "%s" not found. [1] => test15.php );
    $msg  = array_shift($args); // msg=File "%s" not found.
    $language = strtolower(A::getIni('error.language'));
    $_messages = A::loadFile('lc_messages.inc.php', A_DIR . DS. 'lang' . DS . $language);
    if (isset($_messages[$msg]))
    {
        $msg = $_messages[$msg];
    }
    array_unshift($args, $msg);
    return call_user_func_array('sprintf', $args);
}


// 转换 HTML 特殊字符，等同于 htmlspecialchars()
function h( $text )
{
    return htmlspecialchars($text);
}


function print_rr( $aArray, $bShowTrace=TRUE, $bHalt = FALSE )
{
    A::print_rr( $aArray, $bShowTrace, $bHalt );
}

/*
 * 因为手机的入口非一致，为了防止SQL注入，额外的添加这个方法
 * 
 * $string  @param 传入的字符串或者数组参数
 * 
 * Author Eden  20150722
 * 
 * */
function addslashForPhone( $sString, $force = 0 )
{
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if( !MAGIC_QUOTES_GPC || $force )
	{
		if( is_array($sString) )
		{
			foreach( $sString as $key => $val )
			{
				$sString[$key] = daddslashes( $val, $force );
			}
		}
		else
		{
			$sString = addslashes( $sString );
		}
	}
	return $sString;
}

// 转义
function daddslashes( $sString, $force = 0 )
{
	return $sString;
	//在总入口进行过滤，所以废弃旧的单独过滤
	/*
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if( !MAGIC_QUOTES_GPC || $force )
	{
		if( is_array($sString) )
		{
			foreach( $sString as $key => $val )
			{
				$sString[$key] = daddslashes( $val, $force );
			}
		}
		else
		{
			$sString = addslashes( $sString );
		}
	}
	return $sString;
	*/
}

// 单独用作ea转义

function eadaddslashes( $sString, $force = 0 )
{
    !defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
    if( !MAGIC_QUOTES_GPC || $force )
    {
        if( is_array($sString) )
        {
            foreach( $sString as $key => $val )
            {
                $sString[$key] = eadaddslashes( $val, $force );
            }
        }
        else
        {
            $sString = addslashes( $sString );
        }
    }
    return $sString;
}
// 去斜线
function stripslashes_deep($value)
{
    return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
}


// 时间函数(毫秒)
function getMicroTime()
{
    list($usec, $sec) = explode( " ", microtime() );
    return ((float)$usec + (float)$sec);
}


// 格式化返回时间差
function getTimeDiff( $time, $dec = 6 )
{
    return number_format( $time, $dec, '.', '' );
}

function normalize( $input, $delimiter = ',' )
{
    return A::normalize( $input, $delimiter );
}





/**
 * 构造 url
 *
 * 构造 url 需要提供两个参数：控制器名称和控制器动作名。如果省略这两个参数或者其中一个。
 * 则 url() 函数会使用应用程序设置中的确定的默认控制名称和默认控制器动作名。
 *
 * 用法：
 * <code>
 * $url = url('Login', 'checkUser');
 * // $url 现在为 ?controller=Login&action=checkUser
 *
 * $url = url('Login', 'checkUser', array('username' => 'dualface'));
 * // $url 现在为 ?controller=Login&action=checkUser&username=dualface
 *
 * $url = url('Article', 'View', array('id' => 1'), '#details');
 * // $url 现在为 ?controller=Article&action=View&id=1#details
 * </code>
 *
 *
 * @param string $controllerName
 * @param string $actionName
 * @param array $params
 * @param string $anchor
 * @return string
 */
function url($controllerName = NULL, $actionName = NULL, $params = NULL, $anchor = NULL )
{
    static $baseurl = NULL, $currentBootstrap = NULL;

    // 确定当前的 URL 基础地址和入口文件名
    if (is_null($baseurl))
    {
        $baseurl = detect_uri_base();
        $p = strrpos($baseurl, '/');
        $currentBootstrap = substr($baseurl, $p + 1); // 脚本名
        $baseurl = substr($baseurl, 0, $p); //
    }
    $bootstrap = $currentBootstrap;

    // 确定控制器和动作的名字
    $defaultController = strtolower(A::getIni('apple.default.controller'));
    $defaultAction = strtolower(A::getIni('apple.default.action'));

    if( $controllerName == NULL )
    {
        $controllerName = !empty($_REQUEST[$defaultController]) ? $_REQUEST[$defaultController] : 'default';
    }
    if( $actionName == NULL )
    {
        $actionName = !empty($_REQUEST[$defaultAction]) ? $_REQUEST[$defaultAction] : 'index';
    }

    // 标准模式
    $url = $baseurl . '/';
    $url .= '?' . $defaultController . '=' . $controllerName;
    $url .= '&' . $defaultAction . '=' . $actionName;
    $parajoin = '&';

    if (is_array($params) && !empty($params)) {
        $url .= '&' . encode_url_args($params);
    }
    if ($anchor) { $url .= '#' . $anchor; }
    return $url;
}

/**
 * 获取当前http访问的脚本名及完整参数
 * @param bool $bTripSymbol 是否去掉结尾多余的 & 符号
 */
function getCurrentURI( $bTripSymbol = FALSE )
{
    $sCurrentUrlBase = detect_uri_base();
    $sQueryString    = !empty($_SERVER['QUERY_STRING']) ? '?'.trim($_SERVER['QUERY_STRING']) : '';
    if( $bTripSymbol == FALSE )
    {
        return $sCurrentUrlBase . $sQueryString;
    }
    else
    {
        if( substr($sQueryString,-1,1) == '&' )
        {
            $sQueryString = substr($sQueryString,0,-1);
        }
        return $sCurrentUrlBase . $sQueryString;
    }
}


/**
 * detect_uri_base()
 * @return string
 */
// 获得当前请求的 URL 地址, like => /frame_apple/public_01/
function detect_uri_base()
{
    static $baseuri = NULL;
    if ($baseuri) { return $baseuri; }
    $filename = basename($_SERVER['SCRIPT_FILENAME']);
    if (basename($_SERVER['SCRIPT_NAME']) === $filename)
    {
        $url = $_SERVER['SCRIPT_NAME'];
    }
    elseif (basename($_SERVER['PHP_SELF']) === $filename)
    {
        $url = $_SERVER['PHP_SELF'];
    }
    elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $filename)
    {
        $url = $_SERVER['ORIG_SCRIPT_NAME']; // 1and1 shared hosting compatibility
    }
    else
    {
        // Backtrack up the script_filename to find the portion matching
        // php_self
        $path    = $_SERVER['PHP_SELF'];
        $segs    = explode('/', trim($_SERVER['SCRIPT_FILENAME'], '/'));
        $segs    = array_reverse($segs);
        $index   = 0;
        $last    = count($segs);
        $url = '';
        do {
            $seg     = $segs[$index];
            $url = '/' . $seg . $url;
            ++$index;
        } while (($last > $index) && (FALSE !== ($pos = strpos($path, $url))) && (0 != $pos));
    }

    // Does the baseUrl have anything in common with the request_uri?
    if (isset($_SERVER['HTTP_X_REWRITE_URL']))
    { // check this first so IIS will catch
        $request_uri = $_SERVER['HTTP_X_REWRITE_URL'];
    }
    elseif (isset($_SERVER['REQUEST_URI']))
    {
        $request_uri = $_SERVER['REQUEST_URI'];
    }
    elseif (isset($_SERVER['ORIG_PATH_INFO']))
    { // IIS 5.0, PHP as CGI
        $request_uri = $_SERVER['ORIG_PATH_INFO'];
        if (!empty($_SERVER['QUERY_STRING']))
        {
            $request_uri .= '?' . $_SERVER['QUERY_STRING'];
        }
    }
    else
    {
        $request_uri = '';
    }

    if (0 === strpos($request_uri, $url))
    { // full $url matches
        $baseuri = $url;
        return $baseuri;
    }

    if (0 === strpos($request_uri, dirname($url)))
    { // directory portion of $url matches
        $baseuri = rtrim(dirname($url), '/') . '/';
        return $baseuri;
    }

    if (!strpos($request_uri, basename($url)))
    { // no match whatsoever; set it blank
        return '';
    }

    // If using mod_rewrite or ISAPI_Rewrite strip the script filename
    // out of baseUrl. $pos !== 0 makes sure it is not matching a value
    // from PATH_INFO or QUERY_STRING
    if ((strlen($request_uri) >= strlen($url))
        && ((FALSE !== ($pos = strpos($request_uri, $url))) && ($pos !== 0)))
    {
        $url = substr($request_uri, 0, $pos + strlen($url));
    }

    $baseuri = rtrim($url, '/') . '/';
    return $baseuri;
}


/**
 * 将数组转换为可通过 url 传递的字符串连接
 *
 * 用法：
 * <code>
 * $string = encode_url_args(array('username' => 'Tom', 'mode' => 'md5'));
 * // $string 现在为 username=dualface&mode=md5
 * </code>
 *
 * @param array $args
 * @param enum $urlMode
 * @param string $parameterPairStyle
 *
 * @return string
 */
function encode_url_args($args, $urlMode = 'URL_STANDARD', $parameterPairStyle = NULL)
{
    $str = '';
    switch ($urlMode)
    {
        case 'URL_STANDARD':
            if( is_null($parameterPairStyle) )
            {
                $parameterPairStyle = '=';
            }
            $sc = '&';
            break;
        case 'URL_PATHINFO':
        case 'URL_REWRITE':
            if (is_null($parameterPairStyle))
            {
                $parameterPairStyle = '/';
            }
            $sc = '/';
            break;
    }

    foreach( $args as $key => $value )
    {
        if( is_null($value) || $value === '' )
        {
            continue;
        }
        if( is_array($value) )
        {
            $append = encode_url_args($value, $urlMode);
        }
        else
        {
            $append = rawurlencode($key) . $parameterPairStyle . rawurlencode($value);
        }
        if( substr($str, -1) != $sc )
        {
            $str .= $sc;
        }
        $str .= $append;
    }
    return substr($str, 1);
}

/**
 * 读结果缓存文件 TODO: 根据 INI 设置来初始目录, 判断缓存目录是否存在
 *
 * /---code php
 *   setStaticCache( 'test0001', $res = 'abcde' );
 *   $data = getStaticCache( 'test0001' );
 *   print_rr($data,TRUE);
 * \---
 *
 * @params  string  $sCacheName  缓存文件名
 * @return  array   $data
 */
function getStaticCache( $sCacheName )
{
    // 缓存文件名只允许 a-Za-z0-9-_
    if( preg_match('/[^a-z0-9\-_]/i', $sCacheName) )
    {
        halt( "Error in getStaticCache(), Illegal CacheName: $sCacheName" );
    }
    if( FALSE != ($tempPath = A::getIni('class.logs.sStaticCacheBasePath')) )
    {
        $sStaticCacheBasePath = $tempPath;
        unset($tempPath);
    }
    else
    {
        $sStaticCacheBasePath = A_DIR.DS.'tmp'.DS.'static_caches';
    }
    static $aResult = array();
    if (!empty($aResult[$sCacheName]))
    {
        return $aResult[$sCacheName];
    }
    $cache_file_path = $sStaticCacheBasePath .DS. $sCacheName . '.php';
    if( file_exists($cache_file_path) )
    {
        @include_once($cache_file_path);
        $aResult[$sCacheName] = $data;
        return $aResult[$sCacheName];
    }
    else
    {
        return FALSE;
    }
}

/**
 * 写结果缓存文件
 *
 * /---code php
 *   setStaticCache( 'test0001', $res = 'abcde' );
 *   $data = getStaticCache( 'test0001' );
 *   print_rr($data,TRUE);
 * \---
 *
 * @params  string  $sCacheName  缓存文件名
 * @params  mix     $caches
 *
 * @return
 */
function setStaticCache( $sCacheName, $mDatas )
{
    // 缓存文件名只允许 a-Za-z0-9-_
    if( preg_match('/[^a-z0-9\-_]/i', $sCacheName) )
    {
        halt( "Error in setStaticCache(), Illegal CacheName: $sCacheName" );
    }
    if( FALSE != ($tempPath = A::getIni('class.logs.sStaticCacheBasePath')) )
    {
        $sStaticCacheBasePath = $tempPath;
        unset($tempPath);
    }
    else
    {
        $sStaticCacheBasePath = A_DIR.DS.'tmp'.DS.'static_caches';
    }
    if( !is_dir($sStaticCacheBasePath) )
    {
        @makeDir( $sStaticCacheBasePath );
    }
    $cache_file_path = $sStaticCacheBasePath .DS. $sCacheName . '.php';
    $content = "<?php\r\n";
    $content .= "\$data = " . var_export($mDatas, TRUE) . ";\r\n";
    $content .= "?>";
    file_put_contents( $cache_file_path, $content, LOCK_EX);
}

/**
 * 检查目标文件夹是否存在，如果不存在则自动创建该目录
 * @param       string      $sfolderName     目录路径。不能使用相对于网站根目录的URL
 * @return      bool
 */
function makeDir( $sfolderName )
{
    $reval = FALSE;
    if( !file_exists($sfolderName) )
    {
        $atmp = array();
        @umask(0); // 如果目录不存在则尝试创建该目录
        preg_match_all('/([^\/]*)\/?/i', $sfolderName, $atmp); // 将目录路径拆分成数组
        $base = ($atmp[0][0] == '/') ? '/' : ''; // 如果第一个字符为/则当作物理路径处理
        foreach ($atmp[1] AS $val) // 遍历包含路径信息的数组
        {
            if( '' != $val )
            {
                $base .= $val;
                if( '..' == $val || '.' == $val )
                {
                    /* 如果目录为.或者..则直接补/继续下一个循环 */
                    $base .= '/';
                    continue;
                }
            }
            else
            {
                continue;
            }
            $base .= '/';
            if( !file_exists($base) )
            {
                /* 尝试创建目录，如果创建失败则继续循环 */
                if( @mkdir(rtrim($base, '/'), 0777) )
                {
                    @chmod($base, 0777);
                    $reval = TRUE;
                }
            }
        }
    }
    else
    {
        /* 路径已经存在。返回该路径是不是一个目录 */
        $reval = is_dir($sfolderName);
    }
    clearstatcache();
    return $reval;
}


/**
 * 获取客户端IP
 * @return String IP 返回客户端真实IP地址
 */
function getRealIP()
{
    static $realip = NULL;
    /*
    */
      if(getConfigValue("disabletoproxyIP")  == 1){
         if(isset($_SESSION['lvproxyid']) && $_SESSION['lvproxyid'] == 0){
                     $realip = '8.8.8.8';
                    return $realip;
         }
    }
    if ($realip !== NULL)
    {
            return $realip;
    }

    if( isset($_SERVER) )
    {
		if( isset($_SERVER['HTTP_X_REAL_IP']) )
        {
            $arr = explode(',', $_SERVER['HTTP_X_REAL_IP']);
            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach( $arr AS $ip )
            {
                $ip = trim($ip);
                if( $ip != 'unknown' )
                {
                    $realip = $ip;
                    break;
                }
            }
        }
        elseif( isset($_SERVER['HTTP_X_FORWARDED_FOR']) )
        {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach( $arr AS $ip )
            {
                $ip = trim($ip);
                if( $ip != 'unknown' )
                {
                    $realip = $ip;
                    break;
                }
            }
        }
        elseif( isset($_SERVER['HTTP_CLIENT_IP']) )
        {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            if( isset($_SERVER['REMOTE_ADDR']) )
            {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
            else
            {
                $realip = '0.0.0.0';
            }
        }
    }
    else
    {
        if( getenv('HTTP_X_FORWARDED_FOR') )
        {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif( getenv('HTTP_CLIENT_IP') )
        {
            $realip = getenv('HTTP_CLIENT_IP');
        }
        else
        {
            $realip = getenv('REMOTE_ADDR');
        }
    }
    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
    return $realip;
}

function array_sort($arr,$keys,$type='desc') {
    $keysvalue = $new_array = array();
    foreach ($arr as $k=>$v){
        $keysvalue[$k] = $v[$keys];
    }
    if($type == 'asc'){
        asort($keysvalue);
    }else{
        arsort($keysvalue);
    }
    reset($keysvalue);
    foreach ($keysvalue as $k=>$v){
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}

/**
 * 获取当前URL的完整地址
 * @return string	完整URL地址
 */
function getUrl( $bShowFullUri = TRUE, $bAllowPort=TRUE )
{
	$temp_url = '';
	if( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS'])!= 'off' )
	{
		$temp_url = 'https://';
	}
	else
	{
		$temp_url = 'http://';
	}
	$temp_url .= $_SERVER['SERVER_NAME'];
	if( TRUE==$bAllowPort && intval($_SERVER['SERVER_PORT'])!=80 )
	{
		$temp_url .= ':'.$_SERVER["SERVER_PORT"];
	}
	if( $bShowFullUri == FALSE )
	{
	    return $temp_url;
	}
	$temp_url .= $_SERVER["REQUEST_URI"];
	return $temp_url;
}

//字符串解密加密
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
{
    $ckey_length = 4;	// 随机密钥长度 取值 0-32;
    			// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
    			// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
    			// 当此值为 0 时，则不产生随机密钥
    $key = md5($key ? $key : 'AFRAME');
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    for($i = 0; $i <= 255; $i++)
    {
    	$rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    for($j = $i = 0; $i < 256; $i++)
    {
    	$j = ($j + $box[$i] + $rndkey[$i]) % 256;
    	$tmp = $box[$i];
    	$box[$i] = $box[$j];
    	$box[$j] = $tmp;
    }
    for($a = $j = $i = 0; $i < $string_length; $i++)
    {
    	$a = ($a + 1) % 256;
    	$j = ($j + $box[$a]) % 256;
    	$tmp = $box[$a];
    	$box[$a] = $box[$j];
    	$box[$j] = $tmp;
    	$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE')
    {
    	if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16))
    	{
    		return substr($result, 26);
    	}
    	else
    	{
    		return '';
    	}
    }
    else
    {
    	return $keyc.str_replace('=', '', base64_encode($result));
    }
}

//session key加密函数
function genSessionKey( $sSessionId='' )
{
    if(isset($GLOBALS['memsession'])){
       $sSessionId = empty($sSessionId) ? $GLOBALS['memsession']->getSessionHander() : $sSessionId;
    }else{
        $sSessionId = empty($sSessionId) ? session_id() : $sSessionId;
    }
    $tmpkeys 	= isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    //return md5( $sSessionId. $tmpkeys . getRealIP() );
    
    // 由于高仿域名取不到user_agent 故此去掉
    // return md5($sSessionId . $tmpkeys);
    return md5($sSessionId);
}


//显示进程处理时间
function debuginfo()
{
    $iNowtime   = getMicrotime();
    $iTotalTime = getTimeDiff( $iNowtime - $GLOBALS['G_APPLE_LOADED_TIME'] );
    $info  = '<hr>PHP Processed in ' . $iTotalTime . ' second(s) <br/>';
    unset( $iNowtime,$iTotalTime );
	return $info;
}


// 对时间变量进行过滤
function getFilterDate( $sDateTime, $sFormat='Y-m-d H:i:s' )
{
    $temp_time = 0;
    if( trim($sDateTime)=='' || ($temp_time=strtotime($sDateTime))==0 )
    {
        return '';
    }
    return date($sFormat, $temp_time );
}

/**
 * [msubstr 截取字符串]
 *
 * @param  [type]  $str     [原始字符串]
 * @param  integer $start   [从何处开始截取]
 * @param  [type]  $length  [截取字符串的长度]
 * @param  string  $charset [字符编码]
 * @param  boolean $suffix  [截取后是否需要有...代替被截掉的字符]
 *
 * @return [type]           [截取后的字符串]
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
    if (function_exists("mb_substr")) {
        $slice = mb_substr($str, $start, $length, $charset);
    } elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    $chaochu = true;
    if (function_exists("mb_strlen")) {
        $len = mb_strlen($str, $charset);
        if ($len <= $length)
            $chaochu = false;
    }
    return $suffix ? ($chaochu ? $slice . '...' : $slice) : $slice;
}

/**
 * 获取配置设置
 * @param 	string	$sKey	//配置关键字
 * @param 	string	$mDefault//如果没有设置时的默认值
 */
function getConfigValue( $sKey,$mDefault='' )
{
	if( !empty($GLOBALS['sys_config']) )
	{
		if( isset($GLOBALS['sys_config'][$sKey]) )
		{
			if( $GLOBALS['sys_config'][$sKey]['configvalue'] != '' )
			{
				return $GLOBALS['sys_config'][$sKey]['configvalue'];
			}
			else
			{
				return $GLOBALS['sys_config'][$sKey]['defaultvalue'];
			}
		}
		else
		{
			return $mDefault;
		}
	}
	else
	{
		return $mDefault;
	}
}
if (!function_exists("isMobile")) {
    # code...

    function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 判断手机发送的客户端标志,兼容性有待提高
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ios',
                'ipod',
                'blackberry',
                'meizu',
                'huawei',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        
        return false;
    }
}
/**
 * CURL模拟POST操作来进行服务器推送信息发送
 * @param type $msg
 * @param type $pipe
 * @return type
 */
function curlmsg($msg, $domain, $pipe, $uri)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://{$domain}/{$uri}?id={$pipe}");

    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_setopt($ch, CURLOPT_POST, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $out = curl_exec($ch);

    curl_close($ch);

    return $out;
}

/*
 * 一个比较方便的打印函数，方便调试
 * */
function v($data) {
	echo "<pre>";
	var_dump($data);
	exit();
}

/**
 * 是否是派生的银行,如标准银行码是cmb
 * 那么派生银行码为 cmb1 cmb2 检测到前几位为cmb就判断是派生银行
 * @param string 银行英文短码
 * @param array 自定义银行列表
 * @return string
 */
function isMultipleBank($bankName, $banks = array('abc', 'cmb', 'alipaywh')){
    foreach($banks as $bank){
        if(substr($bankName, 0, strlen($bank)) == $bank){
            return $bank;
        }
    }
    return '';
}

/**
 * 判断是否使用hgame_lock封锁库的彩种
 * 20170414 Nazz
 */
function isHgameLock($lotteryId=0) {
    if (!$lotteryId) return false;

    if (in_array($lotteryId, array('19', '24', '27', '28'))) {
        return true;
    }
    
    return false;
}

// 更多的全局函数定义..写入此处

// 对象自动载入
spl_autoload_extensions('.php');
spl_autoload_register( array('A', 'autoLoad') );




// 全局配置初始枚举, 所有核心类依赖的全局设置项应在此列出
A::replaceIni(
    array(
            /* 全局 */
            'class.bDevelopMode' => TRUE,       // (全局开发模式), 记录显示SQL错误
//          'class.logs.sStaticCacheBasePath' => '', // 静态Cache 的存放路径  A_DIR.DS.'tmp'.DS.'static_caches'.DS
//
//            /* 调度器 & 控制器 */
//			'apple.default.controller' => 'controller',
//			'apple.default.action' => 'action',
//
//            /* 数据库类 */
//    		'class.db.bRecordProcessTime' => TRUE, // 是否记录执行 SQL 的总计时间
//
//    		/* 日志类 */
//    		'class.logs.sBasePath' => '',  // 默认日志路径 A_DIR.DS.'tmp'.DS.'logs'.DS
//            'class.logs.iMaxLogFileSize' => 5242880, // 日志最大尺寸. 1024*1024*5
//
//    		/*	memcache类  */
    		'class.memcachedb.config' => array( 'MDBHOST'=>'127.0.0.1','MDBPORT'=>6379 ),//服务器设置
//
//    		/* session基本配置信息 */
    		'class.memsession.sessionConfig' => array(
    										'sessionExpireTime' => 1800,	//session过期时间,0永不过期(秒)
    										'sessionCookieName' => '_sessionHandler', //seesion在cookie里的名字
   										//session所用memcache服务器信息，如果和memcahce类使用的一样则不用设置
    										'memcacheConfig'	=> array(
    															'host'=>'127.0.0.1',
    															'port'=>6379,
    														),
    											),

    		/* cookie设置  */
    		'cookieConfig' => array(
    							'expire'	=> 0,	//cookie过期时间(cookie过期时间大于session时间)0永不过期
    							'domain'	=> '',	//cookie域名
    							'path'		=> '/',	//cookie文件夹路径
   							'secure'	=> FALSE,	//是否可以通过HTTPS安全传递cookie
    							'httponly'	=> FALSE,
    						),

    		/* 验证码设置 */
    		'class.validatecode.fontPath' => '', //默认字体路径 ：A_DIR . DS . 'lang' . DS . 'fonts' . DS


            /* 错误处理 */
			'error' => array(
				'trigger_error' => 117  /* 日志全开 =117 */
                 //   APPLE_ON_ERROR_CONTINUE
                 //   | APPLE_ON_ERROR_REPORT
                 //   | APPLE_ON_ERROR_TRACE
                 //   | APPLE_ON_ERROR_LOG
                 //   | APPLE_LOGS_SQL_TO_FILE,
            ),
    )
);
//A::import( A_DIR . DS . '/includes/class/'); // 系统类(db,smarty) 搜索路径

/********************************* 框架初始 时间调试 *************/
# 0.000496 s
//$t2 = getMicrotime();echo 'FrameTime123 = ' . getTimeDiff( $t2 - $GLOBALS['G_APPLE_LOADED_TIME'] );exit;
?>
