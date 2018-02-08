<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2015 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace User\Controller;
class AlbumController extends UserController {
    /**
	* 用户专辑
	*/    
    public function index(){
    	$this->meat_title = '我的专辑- '.C('WEB_SITE_TITLE');
    	$this->assign('type', 'index');
		$this->display();
    }
    
	/**
	* 用户添加专辑
	*/
    public function create(){
		$model 	= D('Album');
		$time 	= strtotime(date("Y-m-d"));//获取0点的时间戳
		$map['add_uid'] 	= UID;

		$count = $model->where($map)->count();
		if(intval($count) >= 80){
			 $this->error('最多允许添加80张专辑'); 
		}
		
		$map['create_time'] = array('gt',$time);
		$count = $model->where($map)->count();
		if(intval($count) >= intval(C('MAKE_ALBUM_NUM'))){
			 $this->error('每天只允许添加'.$upnum.'张专辑'); 
		}
		
		if(IS_POST){
			if($data = $model->create()){
				if($model->add()){        	
					$this->success('专辑创建成功',U('index'));             
				} else {
					$this->error('专辑创建失败');
				}
			} else {
				$this->error($model->getError());
			}
		}else{
			//获取默认封面
			$files 	= array();        
			$path	= './Public/AlbumCovers/';     
			if($files = scandir($path)) {        
				$files = array_slice($files,2);        
			} 
			if ($files){
				foreach	($files as &$v){
					$v = ltrim($path,'.').$v;
				}
				
				$this->assign('covers',$files);
			}
	
			$this->meat_title = '创建专辑 - '.C('WEB_SITE_TITLE');
			$this->display();			
		}
		
    }
}