<?php

namespace Home\Controller;
/**
 * 前台标签控制器
 */
class TagController extends HomeController {

    public function index(){
		$this->getSeoMeta();
		$this->display();         
    }
            
    public function detail($id = 0){ 
		
		if($tag = D('Tag')->info($id)){
			$this->getSeoMeta('detail',$tag['name']);
    		$this->assign('data',$tag);			
    		$this->display('detail');
		} else {
			$this->error('你访问的页面不存在');
		}       
    }
	
	
	
	public function _empty ($method) {
		if (ctype_alpha($method) || (int)$method) {
			$this->detail($method);
		}else{
			$this->error('你访问的页面不存在');
		}	
	}
	
       
}