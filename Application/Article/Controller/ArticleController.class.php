<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2029 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Article\Controller;
use Think\Controller;
/**
 * 文档模型控制器
 */
class ArticleController extends  Controller {

	//初始操作
    protected function _initialize(){
        $config =   S('DB_CONFIG_DATA');
        if(!$config){
        	$config =   api('Config/lists');
            S('DB_CONFIG_DATA',$config);
        }
        C($config); //添加配置*/
        define('IS_ROOT', is_administrator());
        if(!IS_ROOT && !C('WEB_SITE_CLOSE')){
            $this->error(C('WEB_OFF_MSG'));
        }		
        if (!is_login()){
	        //检测自动登录
	        $userkey = cookie('autologin');
	        if (!empty($userkey)){
	        	if ($uid = think_decrypt($userkey,C('DATA_AUTH_KEY'))){
					D('Home/Member')->login($uid);
				}
	        	
	        }
    	}
		
		$this->meta_title 		= C('WEB_SITE_TITLE');
		$this->meta_keywords	= C('WEB_SITE_KEYWORD');
		$this->meta_description = C('WEB_SITE_DESCRIPTION');	
		

    }

	/* 文档分类检测 */
	protected function category($id = 0){
		/* 标识正确性检测 */
		$id = $id ? $id : I('get.type', 0);
		if(empty($id)){
			$this->error('没有指定文档分类！');
		}

		/* 获取分类信息 */
		$category = D('Category')->info($id);
		if($category && 1 == $category['status']){
			switch ($category['display']) {
				case 0:
					$this->error('该分类禁止显示！');
					break;
				//TODO: 更多分类显示状态判断
				default:
					return $category;
			}
		} else {
			$this->error('分类不存在或被禁用！');
		}
	}
	
	

}
