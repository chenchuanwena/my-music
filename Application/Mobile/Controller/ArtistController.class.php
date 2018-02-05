<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Mobile\Controller;
use Think\Controller;
/**
 * 前台艺术家数据处理
 */
class ArtistController extends MobileController {
				
    //获取艺术家聚合数据
    public function index(){ 
		$type	=	M('ArtistType')->field('id,name')->select();	
		$this->assign('type', $type);		
		$this->meta_title = '艺术家 - '.C('WEB_SITE_TITLE');
		$this->display();
    }
	
	//歌手专辑
	public function album($id=0){
    	$id = (int)($id);
		$model=  M('Artist');		
		if ($id  &&  $data = $model->where(array('id' => $id))->find()){	
			$data['url'] = U('/Artist/'.$id);
			$data['album_url'] = U('/Artist/album_'.$id);
			$this->getSeoMeta('detail',$data['name'].'的专辑');
			$this->meta_title = $data['name'].'的专辑 - '.C('WEB_SITE_TITLE');
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
			$data['url'] 		= U('/Artist/'.$id);
			$data['cover_url'] 	= !empty($data['cover_url'])?$data['cover_url']:"/Public/static/images/artist_cover.png";
			$data['album_url'] 	= U('/Artist/album_'.$id);			
			//增加点击量
			$model->where('id='.$id)->setInc('hits'); // 点击数加1
			$this->meta_title = $data['name'].'的歌曲 - '.C('WEB_SITE_TITLE');
			$this->assign('data', $data);
			$this->display('detail');			
		}else{			
			$this->error('你访问的页面不存在');
		}
    }
	
	//获取详细列表
	public function getlist(){
		$limit	=  I('request.limit',10,'intval');			
		$page	=  I('request.page',1,'intval');
		$order	=  I('request.order','add_time','htmlspecialchars');			
		$map['status']	= 1;
		$list 	= D('Artist')->page($page,$limit)->where($map)->field('status',true)->order($order.' DESC')->select();
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
		$id = (int)$method;		
		if ($id){
			$this->detail($id);
		}else{
			$str = explode("_",strtolower($method));	
			if ( count($str) > 1 && ($str[0] === "album" || $str[0] === "song" || $str[0] === "type")) {
				$this->$str[0]($str[1]);
			}else{	
				$this->error('访问的页面不存在');
			}

    	}
	}
	
}