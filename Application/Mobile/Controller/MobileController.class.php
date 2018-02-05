<?php
namespace Mobile\Controller;
use Think\Controller;

/**
 * 前台手机公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class MobileController extends Controller {

	/* 空操作，用于输出404页面 */
	public function _empty(){
		$this->redirect('Index/index');
	}

    protected function _initialize(){
        $config =   S('DB_CONFIG_DATA');
        if(!$config){
        	$config =   api('Config/lists');
            S('DB_CONFIG_DATA',$config);
        }				
		C($config); //添加配置*/   
        if(!C('WEB_SITE_CLOSE')){
            $this->error(C('WEB_OFF_MSG'));
        }
		define('UID',is_login());
		//$this->assign('pajax', I('get._pjax'));
        $this->meta_title 		= C('WEB_SITE_TITLE');
       	$this->meta_keywords 	= C('WEB_SITE_KEYWORD');
       	$this->meta_description = C('WEB_SITE_DESCRIPTION');
    }

}
