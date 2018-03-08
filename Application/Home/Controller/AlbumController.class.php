<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2029 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Home\Controller;
/**
 * 前台专辑数据处理
 */
class AlbumController extends HomeController {
	    	
	//获取专辑数据
    public function index($type=0){ 

		$title= '全部专辑';
		//数据中查询记录
		$type=M('AlbumType')->field('id,name')->select();
		$this->assign('type', $type);  	
    	$this->title = $title;
    	$this->getSeoMeta();
		$this->display();		
		
    }
    //获取指定类型专辑数据
    public function type($id=0){	
		if ((int)$id && $data = M('AlbumType')->field('id,name,description')->find($id)){
			$this->getSeoMeta();
	    	$this->assign('data', $data);
			$this->display('type');
		}else {
		 	$this->error('你访问的专辑类型不存在！');
		}
    }
	
	//获取指定类型专辑数据
    public function hot($id=0){
    	$id = intval(I('id'));
    	if ($id){
	    	$this->type($id,'hits');
		}else {
		 	$this->error('你访问的页面不存在');
		}
    }

    
    public function detail($id =0){
    	$id = (int)$id;
		if ($id){
			$model=  M('Album');
			$info=$model->find($id);
			if(empty($info))
				$this->error('你访问的页面不存在');	
			
			$model->where(array('id'=>$id))->setInc('hits'); // 点击数加1
			$info['cover_url'] 	= !empty($info['cover_url'])?$info['cover_url']:"/Public/static/images/album_cover.png";
			$info['artist_url'] = U('artist/'.$info['artist_id']);
			$info['genre_url'] 	= !empty($song['genre_id']) ? U('/genre/'.$song['genre_id']) : U('/genre');
			$info['type_url'] 	= U('album/type-'.$info['type_id']);
			
			$this->getSeoMeta('detail',$info['name']);
			
			$this->assign('data', $info);
			$this->display('detail');
		}else {
		 	$this->error('你访问的页面不存在！');
		}
    }
	
	public function _empty($method) {
		if (strpos($method,'-')){
			$str	=  explode("-",$method);
			if ($str[0] == 'type' && is_numeric($str[1])){
				$this->type($str[1]);
			}else{			
				$this->error('你访问的页面不存在');
			}
		}else{
			$method=(int)$method;		
			if ($method){
				$this->detail($method);
			}else{			
				$this->error('你访问的页面不存在');
			}
		}
	}
	
}