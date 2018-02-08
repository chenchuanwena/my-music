<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Mobile\Controller;
use Think\Page;
/**
 * 前台排行数据处理,排行功能待完善
 */
class RanksController extends MobileController {
    	
	//默认页面 试听排行
    public function index(){
		$this->meta_title = '人气排行 - '.C('WEB_SITE_TITLE');
		$this->assign('order','listens');
		$this->view(); 
	
    }
	
    public function getlist(){
		$limit	=  I('request.limit',10,'intval');			
		$page	=  I('request.page',1,'intval');
		$order	=  I('request.order','add_time','htmlspecialchars');	
	
		$map['status']	= 1;
		$list 	= D('Songs')->page($page,$limit)->where($map)->field('status',true)->order($order.' DESC')->select();
		if (IS_GET){				
			$this->assign('list',$list);
			$this->display();
		}else{
			$return['status'] 	= 1;
			$return['list'] 	= $list;
			$this->ajaxReturn($return);
		}			
	
    }

    public function hot () {
		$this->meta_title = '最新推荐  - '.C('WEB_SITE_TITLE');
    	$this->assign('order','likes');
		$this->view(); 
    }
  
  	//评分
    public function digg(){
    	$this->meta_title = '网友推荐排行  - '.C('WEB_SITE_TITLE');	
    	$this->assign('order','digg');
		$this->view(); 
    }
    
    
    public function down(){
    	$this->meta_title = '下载排行  - '.C('WEB_SITE_TITLE');
    	$this->assign('order','download');
		$this->view(); 
    }
    public function fav(){
    	$this->meta_title = '收藏排行  - '.C('WEB_SITE_TITLE');		
    	$this->assign('order','favtimes');
		$this->view(); 
    }
    
    public function latest () {
        $this->title = '最新上传  - '.C('WEB_SITE_TITLE');
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