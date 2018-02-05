<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2016 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Home\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller {

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
					D('Member')->login($uid);
				}
	        	
	        }
    	}

    }

	/* 空操作 */
	public function _empty($method){
		 // 检查是否存在默认模版 如果有直接输出模版
		if(file_exists_case($this->view->parseTemplate())){ 
			$this->getSeoMeta();		
            $this->display();
		}else{
			$this->redirect('/');
		}
	}

	/* 用户登录检测 */
	protected function login(){
		/* 用户登录检测 */
		is_login() || $this->error('您还没有登录，请先登录！', U('User/login'));
	}
	
	
	/* 解析SEO规则 */
	protected function getSeoMeta($action='',$name=''){
		$rule = D('SeoRule')->getCurrentMeta($action);
		$this->meta_title 		= $this->replace_meta($rule['title_rule'],$name);
	    $this->meta_keywords  	= $this->replace_meta($rule['keywords_rule'],$name);
	    $this->meta_description = $this->replace_meta($rule['description_rule'],$name);
	}
	
	
	/* 规则替换字符 */
	protected function replace_meta($str,$name){		
		return str_replace(
			array('{$webname}','{$webtitle}','{$webkeywords}','{$webdescription}','{$name}','{$username}'),
			array(C('WEB_SITE_NAME'),C('WEB_SITE_TITLE'),C('WEB_SITE_KEYWORD'),C('WEB_SITE_DESCRIPTION'),$name),
			$str
		);
	}
	/**
     * 前台音乐数据通用分页列表数据集获取方法
     * @return array|false
     * 返回数据集
     */
    protected function lists ($model,$where=array(), $order="",$field="",$status='1',$listRows=20,$total=null){
    	//dump();
    	if (isset($where['status'])) $where['status']= $status;  
        if(is_string($model)){
        	$model = ucfirst($model);     	
        	if('Songs' == $model ){
            	$field = is_null($field)? $field : 'description';            	 
        	}
			if('Artist' == $model){
        		$field = is_null($field)? $field:'description,sort';
        	}      	
            $model  =   M($model);
        }
        $order = !empty($order)? $order:'id DESC';//设置排序
        $total = !empty($total)? $total:$model->where($where)->count();//获取总数
        $page = new \Think\Page($total, $listRows);
        $page->rollPage = 5;
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
            $page->setConfig('first','首页');
            //$page->setConfig('last','尾页');
            $page->setConfig('prev', '上页');
        	$page->setConfig('next', '下页');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $this->assign('_total',$total);
        $limit = $page->firstRow.','.$page->listRows;
        return $model->where($where)->field($field,true)->limit($limit)->order($order)->select();
    	
    }
}
