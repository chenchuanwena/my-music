<?php

namespace Mobile\Controller;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class AlbumController extends MobileController {
	 //系统首页
    public function index(){
		$title= '最新专辑';
		$this->title = $title;
		$this->meta_title = $title.' - '.C('WEB_SITE_TITLE');
		$this->display();	
    }
	
	
	public function getlist(){
		$limit	=  I('request.limit',10,'intval');			
		$page	=  I('request.page',1,'intval');
		$order	=  I('request.order','add_time','htmlspecialchars');			
		$map['status']	= 1;
		$list 	= D('Album')->page($page,$limit)->where($map)->field('status',true)->order($order.' DESC')->select();
		if (IS_GET){
			$this->assign('list',$list);
			$this->display();
		}else{
			$return['status'] 	= 1;
			$return['list'] 	= $list;
			$this->ajaxReturn($return);
		}			
	
    }
	//系统首页  
   public function detail($id=0){
	    $id = (int)$id;
		if ($id){
			$model=  M('Album');
			$info=$model->find($id);
			if(empty($info))
				$this->error('你访问的页面不存在');	
			
			$model->where(array('id'=>$id))->setInc('hits'); // 点击数加1
			$info['cover_url'] 	= !empty($info['cover_url'])?$info['cover_url']:"/Public/static/images/album_cover.png";
			$info['artist_url'] = U('Artist/'.$info['artist_id']);
			$info['genre_url'] 	= !empty($song['genre_id']) ? U('/Genre/'.$song['genre_id']) : U('/Genre');
			$this->meta_title = $info['name'].' - '.C('WEB_SITE_TITLE');
			$this->assign('data', $info);
			$this->display('detail');
		}else {
		 	$this->error('你访问的页面不存在！');
		}	       
    }		
	
	public function _empty($method) {
		$method=(int)$method;		
		if ($method){
			$this->detail($method);
		}else{			
			$this->error('你访问的页面不存在');
		}
	}
	
}