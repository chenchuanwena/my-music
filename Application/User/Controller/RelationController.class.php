<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace User\Controller;
class RelationController extends UserController {
    /**
	* 关注用户
	*/    
    public function index(){
    	$this->meat_title = '我的关注 - '.C('WEB_SITE_TITLE');
		$this->display();
    }
       
    /**
	* 粉丝
	*/    
    public function fans(){
    	$this->meat_title = '我的粉丝 - '.C('WEB_SITE_TITLE');
		$this->display();
    }
    
}