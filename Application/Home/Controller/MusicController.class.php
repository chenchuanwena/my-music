<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Home\Controller;
use Think\Controller;
/**
 * 前台音乐数据处理
 */
class MusicController extends HomeController {

	//初始操作
	public function detail($id=0) {

		//单个歌曲显示页
		$id				= (int)$id;		
		$model 			= M('Songs'); 
		$map['status']	= 1;
		$map['id']		= $id;
		if ($id && $music = $model->where($map)->find()){
			$user	=	M('Member')->where(array('status'=>1,'uid'=>$music['up_uid']))->field('uid,nickname,pic_id,songs,albums,follows,fans')->find();
			$song 	= 	M('SongsExtend')->find($id);
			$song 	= 	!empty($song)? array_merge($music, $song ) : $music;
			$song['artist_url']	= U('artist/'.$song['artist_id']);
			$song['user_url']	= $user['url']	= U('user/'.$song['up_uid']);
			$song['album_url']	= U('album/'.$song['album_id']);
			$song['genre_url']	= intval($song['genre_id']) > 0? U('/genre/'.$song['genre_id']) : U('/genre');
			$song['down_url']	= U('down/'.$song['id']);
			$server = get_server($song['server_id']);
			$song['listen_url'] 	= html_entity_decode($song['listen_url']);

			$song['listen_server']	= $server['listen_url'];
			$this->setRecord($id);
			M('Songs')->where(array('id'=>$id))->setInc('listens');
			$this->assign('data',$song);
			$this->assign('user',$user);
			$this->getSeoMeta('detail',$song['name']);
			$this->display('detail');
    	}else{
    		$this->error('你访问的歌曲不存在！');
    	}
		
	}
    //获取音乐数据
    public function getlist($id="",$limit="",$page=""){
		$id= I("id");	//用户提交的i
    	if (IS_AJAX && $id) {    		
			if (strpos($id,',')) {
				$map['id']=array('exp','IN('.$id.')');
			}else {
				$map['id']=(int)$id;						
			}
			$limit = !(int)$limit? 10 : $limit ;
			$page = !(int)$page? 1 : $page;
			$return['status'] = 0;
			$list 	= M('Songs')
					->where($map)
					->field('id,name,album_id,album_name,up_uid,up_uname')
					->page($page.','.$limit)
					->select();	
			if ($list){
				$return['status'] 	= 1;
				$return['count'] 	= count(explode(",",$id));
				$return['list'] 	= $list;
			}
			$this->ajaxReturn($return);
		}else{
			$this->show('页面出错');
		}
    }
    public function getData(){	
    	$id= intval(I("id"));	//用户提交的id
    	$type=I("type");	//用户提交的id			
    	if (IS_AJAX) {
    		$Songs = M('Songs');
    		if ($type=='setInc' && $id){
    			$map['id']=$id;
    			$Songs->where($map)->setInc('listens'); // 试听数加1;
    		}elseif ($id) {
				$map['id']=$id;
				$list = $Songs->alias('a')->join('LEFT JOIN __SONGS_EXTEND__ b ON a.id= b.mid')->field('a.id,a.server_id,a.name,a.artist_id,a.artist_name,a.album_id,a.album_name,a.cover_url,a.up_uid,a.up_uname,b.lrc,b.listen_url')->find($id);
				if ($list['server_id'] > 0){
					$server = get_server($list['server_id']);				
					$list['listen_url'] = $server['listen_url'].$list['listen_url'];				
				}
				$list['listen_url']	= parseuri($list['listen_url']);
				if ('http://' === substr($list['lrc'], 0, 7)){
					$list['lrc'] = file_get_contents($list['lrc']);
				}

				$list['cover_url'] = !empty($list['cover_url'])? $list['cover_url'] :'/Public/static/images/cover.png';

				$Songs->where($map)->setInc('listens'); // 试听数加1 
			}
			$this->ajaxReturn($list);
		}else{
			$this->show('页面出错');
		}
    }
    
    //获取专辑音乐数据
    public function albumSongs(){
		$id= intval(I("id"));	//用户提交的id
    	if (IS_AJAX && $id) {					
			$this->ajaxReturn(get_Album_songs($id));
		}else{
			$this->show('页面出错');
		}
    }
    
    //获取音乐数据
    public function getTopMusic($limit=0,$pos=16){				
    	if (IS_AJAX) {
			$limit 	= !(int)$limit ? 10 : $limit;
			$pos	= !(int)$pos? 1 : $pos;
			$map[] 	= "position & $pos= $pos";
			$map['status'] 	= 1;
			
			$return ['status']	= 0;

			$list 	= M('Songs')->alias('a')
					->join('__SONGS_EXTEND__ b ON a.id= b.mid')
					->where($map)
					->field('a.id,a.server_id,a.name,a.artist_id,a.artist_name,a.album_id,a.album_name,a.cover_url,a.up_uid,a.up_uname,b.lrc,b.listen_url')
					->order('update_time desc')
					->limit($limit)
					->select();			
			if ($list){
				foreach ($list as &$v ){
					if($v['server_id'] > 0) {
						$server = get_server($v['server_id']);
						$v['listen_url'] = $server['listen_url'].$v['listen_url'];
					}				
					$v['artist_url']= U('/artist/'.$song['artist_id']);
					$v['user_url']	= U('/user/'.$song['up_uid']);
					$v['album_url']	= U('/album/'.$song['album_id']);
					$v['genre_url']	= intval($song['genre_id']) > 0? U('/genre/'.$song['genre_id']) : U('/genre');
					$v['down_url']	= U('/down/'.$song['id']);
				}
				$return ['status']	= 1;
				$return ['list']	= $list;
			}	
			
			$this->ajaxReturn($return);	
		}else{
			$this->show('页面出错');
		}
    }
	
	//设置试听记录
	private function setRecord($id){
		//记录临时试听记录
		$ListenRecord 	= cookie('ListenRecord');
		if((count($ListenRecord)) >= 50){
			$ListenRecord = array_splice($ListenRecord,1);
		}
		$ListenRecord[] = $id;		
		cookie('ListenRecord',array_unique($ListenRecord),30*24*3600);	
		
	}
       
    //获取音乐数据
    public function openplay($pos=2){
		$map[] 	= "position & 2= 2";
		$map['status'] 	= 1;
		$list 	= M('Songs')->alias('a')
				->join('__SONGS_EXTEND__ b ON a.id= b.mid')
				->where($map)
				->field('a.id,a.server_id,a.name,a.artist_id,a.artist_name,a.album_id,a.album_name,a.cover_url,a.up_uid,a.up_uname,b.lrc,b.listen_url')
				->order('update_time desc')
				->limit(50)
				->select();			
		if ($list){
			foreach ($list as &$v ){
				$server = get_server($v['server_id']);
				$v['music_url'] = substr($v['listen_url'], 0, -4);
				$v['uid'] = $v['homeid'] = $v['up_uid'];
				$v['did'] = $v['id'];
				$v['namepic'] = "/Public/static/images/avatar.png";
				unset($v['listen_url']);
			}
			$this->ajaxReturn($list);				
		}			
    }	
		
	public function _empty($method) {
		$method=(int)$method;
		if ($method){
			$this->detail($method);
		}else{
    		$this->error('页面出错');
    	}
	}
    
}