<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Home\Controller;
use Think\Page;
/**
 * 前台排行数据处理,排行功能待完善
 */
class RanksController extends HomeController {
    	
	//默认页面 试听排行
    public function index(){
    	$this->title = '人气排行';	
		$this->getSeoMeta();
    	$this->assign('order','listens');
		$this->view();  	
    }
	
	public function _empty($method){		
		$this->getSeoMeta();
		$this->view(); 	
	}
    
    public function hot () {
		$this->title = '最新推荐';
		$this->getSeoMeta();
    	$this->assign('order','likes');
		$this->view(); 
    }
  
  	//评分
    public function digg(){
    	$this->title = '网友推荐排行';	
		$this->getSeoMeta();
    	$this->assign('order','digg');
		$this->view(); 
    }
        
    public function down(){
    	$this->title = '下载排行';
		$this->getSeoMeta();
    	$this->assign('order','download');
		$this->view(); 
    }
    public function fav(){
    	$this->title = '收藏排行';		
		$this->getSeoMeta();
    	$this->assign('order','favtimes');
		$this->view(); 
    }
    
    public function latest () {
        $this->title = '最新上传';	
		$this->getSeoMeta();
    	$this->assign('order','add_time');
		$this->view(); 
    
    }
	
	public function view() {
		if(file_exists_case($this->view->parseTemplate())){
			$this->display();			
		}else{		
			$this->display('index');
		}
	}	
       
}