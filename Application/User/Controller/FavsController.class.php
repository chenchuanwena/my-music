<?php
// +----------------------------------------------+
// | JYmusic音乐管理系统						  |
// +----------------------------------------------+
// | Copyright (c) 2014-2016[http://www.my-music.cn]  |
// +----------------------------------------------+
// | Author: 战神~~巴蒂 [31435391@qq.com] 		  |
// +----------------------------------------------+

namespace User\Controller;
class FavsController extends UserController {
    /**
	* 收藏歌曲
	*/    
    public function index(){
    	$this->meta_title = '我的收藏 - '.C('WEB_SITE_TITLE');
		$this->display();
    }
       
    /**
	* 收藏专辑
	*/    
    public function album(){
    	$this->meta_title = '我的专辑收藏 - '.C('WEB_SITE_TITLE');
		$this->display();
    }
    
    
    /**
	* 收藏歌手
	*/    
    public function artist(){
    	$this->meta_title = '我的艺术家收藏 - '.C('WEB_SITE_TITLE');
		$this->display();
    }
}