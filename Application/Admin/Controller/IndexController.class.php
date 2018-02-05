<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2016 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Admin\Controller;
use User\Api\UserApi as UserApi;
class IndexController extends AdminController {
	
	public function index(){		
        $count=array();	
        $S = M('Songs');
    	$count['songs']		=  $S->count();//获取歌曲总数
    	$count['album']		=  M('Album')->count();//获取专辑总数
    	$count['genre']		=  M('Genre')->count();//获取曲风总数
    	$count['user']		=  M('Member')->count();//获取用户总数
		$count['artist']	=  M('Artist')->count();//获取艺术家总数
    	$version			= JYMUSIC_VERSION;
    	$newSong = $S->where(array('status'=>1))->field('name,add_time')->order('id desc')->limit(6)->select();
    	$msglist = M('message')->where(array('to_uid'=>0))->field('create_time,content')->order('id desc')->limit(6)->select();    	    	    	
    	$this->assign('newSong',$newSong);
		$this->assign('count',$count);		
		$this->assign('msglist',$msglist);
        $this->meta_title = '管理首页';
        $this->display();
    }  
    public function checkUpdate() {
    	header("Content-Type:text/html;charset=UTF-8");	 
		if(extension_loaded('curl')){
			$return = check_update();
           $this->ajaxReturn($return);
	   	}else{	   	
			$this->error('程序无法自动升级,PHP没有开启"curl"扩展。请下载安装包，手动升级！');   			
	   	}   
    }    
    
    public function clearCache() {
    	$dirname = './Runtime/';
		//清文件缓存
		$dirs	=	array($dirname);		
		//清理缓存
		foreach($dirs as $value) {
			rmdirr($value);			
		}
		$this->success("已清理!");
		@mkdir($dirname,0777,true);
    
    } 	
}
