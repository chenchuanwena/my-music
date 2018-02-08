<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Home\Controller;
use Think\Controller;

class AjaxController extends HomeController {
    protected function _initialize(){
		if (!IS_AJAX) $this->error('请求类型错误，访问失败！');
	}
		
	//ajax获取歌曲
	public function getSong($artist_id=0,$album_id=0,$genre_id=0,$tag_id=0,$pos=0,$order="add_time",$limit=10,$page=1,$field=""){
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
		$field = empty($field)? "id,name,genre_id,genre_name,album_id,album_name,artist_id,artist_name,up_uname,up_uid,score,coin,listens,favtimes,digg,bury,download,server_id,add_time,update_time": text_filter($field);
		
		
		$limit = !(int)$limit? 10 : $limit ;
		$page = !(int)$page? 1 : $page;
		$song	= M('Songs')->where($map)->order($order)->cache(true,3600)->page($page.','.$limit)->field($field)->select(); 
		$this->goback($song);
    }
	
	//30天试听记录
    public function listenRecord($limit=10,$page=1,$type="h") {
		if($type=="t"){
			$ListenRecord = cookie('TmpRecord');
		}else{			
			$ListenRecord = cookie('ListenRecord');
		}			
		$limit = !(int)$limit? 10 : $limit ;
		$page  = !(int)$page? 1 : $page;	    	
		if(!empty($ListenRecord)){
			$map['id']=array('in',$ListenRecord);
			$list=M('songs')->where($map)->field('tags,gold,score,lyrics,composer,midi,sing,mix,server_id',true)->page($page.','.$limit)->limit($limit)->select();	    		
			$this->goback($list); 
		}else{
			if (IS_GET) {
				$this->show('<p style="text-align:center;">暂时还没有记录！</p>');
			}else{
				$this->error('未查询到数据');
			}
		}
    }
	
	
	//ajax获取收藏
   	public function getFav($limit=20,$type=1,$uid=0,$page=1){
		$this->getlist($limit,$type,M('UserFav'),$uid,$p); 
    }
	
	//ajax获取喜欢
   	public function getLike($limit=20,$type=1,$uid=0,$page=1){
		$this->getlist($limit,$type,M('UserLike'),$uid); 
    }
	
	//ajax获取推荐
   	public function getRecmmend($limit=20,$type=1,$uid=0,$page=1){
		$this->getlist($limit,$type,M('UserRecommend'),$uid,$p); 
    }
	
		
	//公用获取列表
	protected function getlist ($limit,$type,$model,$uid,$page){
		$uid	= !(int)$uid? is_login() : $uid;
		if (!$uid){		
			if (IS_GET) {
				$this->show('<p style="text-align:center;">你还没有登录！</p>');
			}else{
				$this->error('你还没有登录！',U('Member/login'));
			}
		}
		switch ($type = (int)$type) {
			case 1:
				$table = M('Songs');
				$field ='b.id,b.name,b.up_uid,up_uname,b.genre_name,b.genre_id,b.artist_name,b.artist_id,b.album_name,b.album_id,b.listens,b.favtimes,b.likes,b.add_time';
				break;						
			case 2:
				$table = M('Artist');
				break;
			case 3:
				$table = M('Album');
				break;				
			default:
				if (IS_GET) {
					$this->show('<p style="text-align:center;">你请求的类型不存在！</p>');
				}else{
					$this->error('你请求的类型不存在！');
				}
				
		} 
		$limit = !(int)$limit? 10 : $limit ;
		$page = !(int)$page? 1 : $page;
		$map['a.uid']		= $uid;
		$map['a.type']	= $type;
		$list = $model->alias(a)
				->join('__SONGS__ b ON a.music_id = b.id')
				->where($map)->order('create_time DESC')
				->page($page.','.$limit)
				->field($field)
				->select();				
		$this->goback($list); 
	}
	
	//公用返回处理	
	protected function goback ($list){
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
				$this->show('<p style="text-align:center;">暂时还没有记录！</p>');
			}
		}
		
	}
	
}










