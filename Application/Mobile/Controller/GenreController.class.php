<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Mobile\Controller;

/**
 * 前台曲风数据处理
 */
class GenreController extends MobileController {
    //获取音乐数据
    public function index(){
		$this->meta_title = '曲风分类 - '.C('WEB_SITE_TITLE');
		$this->display();
    }
    
    public function detail($id=null){	
 		if ((int)($id)){//判断id
			$info = M('Genre')->where(array('id'=>$id))->field('update_time,status',true)->find();
			$this->meta_title = $info['name'].' - '.C('WEB_SITE_TITLE');
    		$this->assign('data',$info);		
    		$this->display('detail');
    	}else{   		
    		$this->error('页面不存在','index');
    	}   
    }

	public function getlist($id=null){	
		$limit	=  I('request.limit',10,'intval');			
		$page	=  I('request.page',1,'intval');
		$genre	=  I('request.genre',0,'intval');
		$order	=  I('request.order');	
		$order	= !empty($order)? text_filter($order) : "add_time";			
		$map['status']	= 1;
		if ($genre){$map['genre_id']= $genre;}
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
        
	public function _empty($method) {				
		$res =(int)$method;
		if ($res){			
			$this->detail($res);
		}else{
    		$this->error('页面出错');
    	}
	}
  
}