<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2015 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace User\Controller;
use Think\Controller;
use User\Api\UserApi;
/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class UserController extends Controller {

	/**
     *用户控制器初始化
    */
    protected function _initialize(){
		
		/* 读取数据库中的配置 */
        $config = api('Config/lists');
        C($config); //添加配置       	
			
		define('UID',is_login());
		//记录页面 url
		Cookie('__forward__',$_SERVER['REQUEST_URI']);
		//个人空间访问无需验证登录
       	if ('Home' != CONTROLLER_NAME && !is_numeric(CONTROLLER_NAME) && !is_numeric(ACTION_NAME)){
	       if(!UID){// 还没登录 跳转到登录页面
	            $this->error('您还没有登录，请先登录！', U('/Member/login'));
	        }
	    }
		if (UID){
			$user 	= M('Member')->find(UID);//获取当前用户
			$cuser	= M('UcenterMember')->field('username,email')->find(UID);
			$user   = array_merge($user,$cuser);
			$user['url']	= U('/User/'.$user['uid']);
			if(!$user['space'] && C('USER_SPACE_OPEN')){
				$space = $this->openspace(UID);
			}
			$this->login_user = $this->user = $user;//获取当前用户		
		}			
		
        // 是否是超级管理员      
		if (!defined('IS_ROOT')) {
			define('IS_ROOT', is_administrator());
		} 
		if(!IS_ROOT && C('ADMIN_ALLOW_IP')){
            // 检查IP地址访问
            if(C('WEB_SITE_CLOSE')){
	            if(!in_array(get_client_ip(),explode(',',C('HOME_ALLOW_IP')))){
	                $this->error('403:禁止访问');
	            }
	        }       
	        if(!IS_ROOT && !C('WEB_SITE_CLOSE')){
	            $this->error(C('WEB_OFF_MSG'));
	        }
	    }
      
    	$this->meta_title 		= C('WEB_SITE_TITLE');
       	$this->meta_keywords 	= C('WEB_SITE_KEYWORD');
       	$this->meta_description = C('WEB_SITE_DESCRIPTION');
    }
	
	/**
     * 开通个人空间
     * @return array|false
     * 返回数据集
     */
 	protected function openspace ($uid){
 		$data['uid'] = $uid;
 		$user = M('UserSpace')->where($data)->find();
 		if (empty($user)){
			$data['title'] 			= '';
			$data['indexunit'] 		='newShare,hotShare,newMessage';
			$data['sidebarunit'] 	='signature,hotUser';
			$path = $path = __ROOT__.trim(C('USER_SKINS_PATH'),'.');	
			$data['bg']= $path .'/bg/default.jpg';
			$data['banner']=  $path .'/bn/default.jpg';			
			if ($id =  M('UserSpace')->add($data)){
				M('Member')-> where(array('uid'=>$uid))->setField('space',1);
				return $data;
			}
		}
		return $user;
 	}


	/**
     * 通用分页列表数据集获取方法
     * @return array|false
     * 返回数据集
     */
    protected function lists ($model,$where=array(), $order='',$field=true, $status = 1,$listRows=20){
    	
        if(is_string($model)){       	
            $model  =   M(ucfirst($model));
        }
        $order = !is_null($order)?$order:'id DESC';//设置排序
        $total        =   $model->where($where)->count();//获取总数
        $page = new \Think\Page($total, $listRows);
        $page->rollPage = 3;
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
            $page->setConfig('prev', '<');
        	$page->setConfig('next', '>');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $this->assign('_total',$total);
        $limit = $page->firstRow.','.$page->listRows;
        return $model->where($where)->field($field)->limit($limit)->order($order)->select();
    	
    }

}
