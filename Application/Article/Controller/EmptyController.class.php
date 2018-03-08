<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Article\Controller;
use Think\Controller;

class EmptyController extends Controller {
   
	
	public function  _empty (){	
		$cname = CONTROLLER_NAME;
		if(is_numeric($cname)){
			R('Index/detail/',array($cname));				
		}else{
			$this->error('你访问的页面不存在');
		}
		
	}
	
	
}