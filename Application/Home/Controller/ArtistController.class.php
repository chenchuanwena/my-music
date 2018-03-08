<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Home\Controller;
use Think\Controller;
/**
 * 前台艺术家数据处理
 */
class ArtistController extends HomeController {
				
    //获取艺术家聚合数据
    public function index(){ 
    
    	$type	=	M('ArtistType')->field('id,name')->select();	
		$this->assign('type', $type);		
    	$this->getSeoMeta();
		$this->display();
    }
    
    //按分类获取艺术家数据
    public function type($id = null){
		$id 	= (int)$id;
    	$type	= 	M('ArtistType')->field('id,name')->select();  	
    	$data	= 	M('ArtistType')->find($id);  
		
		$this->assign('type', $type);
		$this->assign('data', $data);
    	$this->getSeoMeta();
		$this->display('type');
    }
	
		
	//歌手专辑
	public function album($id=0){
    	$id = (int)($id);
		$model=  M('Artist');		
		if ($id  &&  $data = $model->where(array('id' => $id))->find()){
			$data['url'] 		= U('/artist/'.$id);
			$data['cover_url'] 	= !empty($data['cover_url'])?$data['cover_url']:"/Public/static/images/album_cover.png";
			$data['album_url'] 	= U('/artist/album_'.$id);
			$this->getSeoMeta('detail',$data['name'].'的专辑');
			$this->assign('data', $data);
			$this->display('album');			
		}else{			
			$this->error('你访问的页面不存在');
		}
    }
	
	//歌手详细
    public function detail($id=null){
		$id = (int)($id);		
		$model=  M('Artist');
		if ($id  &&  $data = $model->where(array('id' => $id))->find()){
			$data['url'] 		= U('/artist/'.$id);
			$data['cover_url'] 	= !empty($data['cover_url'])?$data['cover_url']:"/Public/static/images/artist_cover.png";
			$data['album_url'] 	= U('/artist/album-'.$id);			
			//增加点击量
			$model->where('id='.$id)->setInc('hits'); // 点击数加1
			$this->getSeoMeta('detail',$data['name']);
			$this->assign('data', $data);
			$this->display('detail');			
		}else{			
			$this->error('你访问的页面不存在');
		}
    }
	
	public function _empty($method) {
		$id = (int)$method;		
		if ($id){
			$this->detail($id);
		}else{
			$str = explode("-",strtolower($method));	
			if ( count($str) > 1){
				if ($str[0] === "album"){
					$this->album($str[1]);
				}
			
				if ($str[0] === "song" ){
					$this->index($str[1]);
				}
						
				if ($str[0] === "type") {
					$this->type($str[1]);
				}
			}else{	
				$this->error('访问的页面不存在');
			}

    	}
	}
	
}