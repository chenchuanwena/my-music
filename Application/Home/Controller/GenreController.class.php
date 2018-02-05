<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Home\Controller;

/**
 * 前台曲风数据处理
 */
class GenreController extends HomeController {
    //获取音乐数据
    public function index(){
		$this->getSeoMeta();
		$this->display();
    }
    
    public function detail($id=null){	
 		if ((int)($id)){//判断id
			$info = M('Genre')->where(array('id'=>$id))->field('update_time,status',true)->find();
			$this->getSeoMeta('detail',$info['name']);
    		$this->assign('data',$info);		
    		$this->display('detail');
    	}else{   		
    		$this->error('页面不存在','index');
    	}   
    }
        
	public function _empty($method) {				
		$res =(int)$method;
		if ($res){			
			$this->detail($res);
		}else{
    		$this->error('页面出错');
    	}
	}
  
}