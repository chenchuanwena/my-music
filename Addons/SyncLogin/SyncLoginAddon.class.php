<?php
/**
 * 同步登陆插件
 * @author jry
 */
 
namespace Addons\SyncLogin;
use Common\Controller\Addon;


class SyncLoginAddon extends Addon{

    public $info = array(
        'name' => 'SyncLogin',
        'title' => '第三方登录',
        'description' => '第三方账号同步登录',
        'status' => 1,
        'author' => 'JYmusic',
        'version' => '0.1'
    );

    public function install(){ 
        $prefix = C("DB_PREFIX");
        $model = D();
        $model->execute("DROP TABLE IF EXISTS {$prefix}sync_login;");
        $model->execute("CREATE TABLE {$prefix}sync_login (`login_id` int(11) NOT NULL AUTO_INCREMENT,`uid` int(11) NOT NULL COMMENT '用户id',`openid` varchar(150) NOT NULL,`type` char(40) NOT NULL,`access_token` varchar(150) NOT NULL,`refresh_token` varchar(150) NOT NULL,`is_sync` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否同步',`status` tinyint(2) NOT NULL DEFAULT '0',PRIMARY KEY (`login_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        /* 先判断插件需要的钩子是否存在 */
        $this->getisHook($this->info['name'], $this->info['name'], $this->info['description']);
        return true;
    }

    public function uninstall(){
        //删除钩子
		$model = D();
        $this->deleteHook($this->info['name']);
        $prefix = C("DB_PREFIX");
        $model->execute("DROP TABLE IF EXISTS {$prefix}sync_login;");
        return true;
    }

    //登录按钮钩子
    public function SyncLogin($param){
        $this->assign($param);
        $config = $this->getConfig();
        $this->assign('config',$config);
        $this->display('login');
    }

    /**
     * meta代码钩子
     */
    public function syncMeta($param){
        $platform_options = $this->getConfig();
        echo $platform_options['meta'];
    }
    
}