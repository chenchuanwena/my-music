<?php

namespace Mobile\Controller;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class MusicController extends MobileController {

	public function detail($id=0) {
		//单个歌曲显示页
		$id=(int)$id;		
		$model =  M('Songs'); 
		if ($id){
			$map['status']	=	1;
			$map['id']		=	$id;
			$music			=	$model->where($map)->find();			
			if(!empty($music)){
				//$user	=	M('Member')->where(array('status'=>1,'uid'=>$music['up_uid']))->field('uid,nickname,pic_id,songs,albums,follows,fans')->find();																
				$song 	= 	M('SongsExtend')->find($id);
				$song 	= 	!empty($song)? array_merge($music, $song ) : $music; 		
				$song['artist_url']	= U('/Artist/'.$song['artist_id']);
				$song['user_url']	= U('/User/'.$song['up_uid']);
				$song['album_url']	= U('/Album/'.$song['album_id']);
				$song['genre_url']	= intval($song['genre_id']) > 0? U('/Genre/'.$song['genre_id']) : U('/Genre');
				//$song['user_url']	= U('/User/Home/'.$song['up_uid']);
				$song['down_url']	= U('/Down/'.$song['id']);
				
				M('Songs')->where(array('id'=>$id))->setInc('listens'); 
				
				$this->assign('data',$song);
				//$this->assign('user',$user);												
				$this->meta_title = $song['name']. '在线试听 - '.C('WEB_SITE_TITLE');
		    	$this->display('detail');
		    }else{
		    	$this->error('你访问的页面不存在！');
		    }
    	}else{
    		$this->error('你访问的页面不存在！');
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
			//记录临时试听记录
			$ListenRecord = cookie('ListenRecord');
			//只记录20条数据
			if((count($ListenRecord)) >= 20){$ListenRecord = array_splice($ListenRecord,1);}
			$ListenRecord[] = $id;	
			cookie('ListenRecord',array_unique($ListenRecord),30*24*3600);
			$this->ajaxReturn($list);
		}else{
			$this->show('页面出错');
		}
	}
	
	//获取列表
	public function getlist($artist_id=0,$album_id=0,$genre_id=0,$tag_id=0,$pos=0,$order="add_time",$limit=10,$page=1,$field=""){
		$map['status']	= 1;
		if ((int)$artist_id){
			$map['artist_id'] = $artist_id;
		}
		if ((int)$album_id){
			$map['album_id'] = $album_id;
		}
		if ((int)$artist_id){
			$map['genre_id'] = $genre_id;
		}
		if ((int)$artist_id){
			$map['tag_id'] = $tag_id;
		}
		if ((int)$pos){
			$map[] 	= "position & {$pos}= {$pos}";
		}
		if (ctype_alpha($order)){
			$order = $order .' DESC';
		}
		$field = empty($field)? "id,name,genre_id,genre_name,album_id,album_name,artist_id,artist_name,up_uname,up_uid,score,coin,listens,favtimes,digg,bury,download,cover_url,add_time,update_time": text_filter($field);		
		$limit = !(int)$limit? 10 : $limit ;
		$page = !(int)$page? 1 : $page;
		$list	= M('Songs')->where($map)->order($order)->cache(true,3600)->page($page.','.$limit)->field($field)->select(); 
		if (IS_POST){
			if (!empty($list)){
				$data['status'] = 1;
				$data['info']	= '数据获取成功';
				$data['list']	= $list;
				unset($list);				
			}else{
				$data['status'] = 0;
				$data['info']	= '未查询到数据';
			}
			$this->ajaxReturn($data);
		}else{
			if (!empty($list)){
				$this->assign('list',$list);
				$this->display();
			}else{
				$this->show('<p style="text-align:center;">还没有记录！</p>');
			}
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