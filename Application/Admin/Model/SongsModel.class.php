<?php
// +----------------------------------------------------------------------
// | Author: 战神巴蒂<31435391@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;

class SongsModel extends Model {

	protected $artist_name;
	protected $artist_cover;
	protected $album_name;
	protected $album_cover;
	protected $genre_id;
	protected $up_uid;

    protected $_validate = array(
        array('name', 'require', '名称不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
		//array('name','','名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        array('listen_url', 'require', '试听地址不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        //array('artist_name', 'require', '所属艺术家不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        //array('genre_name', 'require', '所属曲风不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
        array('up_uid','getUid', self::MODEL_BOTH,'callback'),
        array('up_uname','getUname',3,'callback'),
		array('artist_name','getArtistName',3,'callback'),
		array('artist_id','getArtistId',3,'callback'),	
		array('album_name','getAlbumName',3,'callback'),
		array('album_id','getAlbumId',3,'callback'),
		array('genre_id', 'getGnreId', self::MODEL_BOTH,'callback'),
		array('genre_name', 'getGnreName', self::MODEL_BOTH,'callback'),
		//array('tags', 'geTags', self::MODEL_BOTH,'callback'),
        //array('down_url','getDownUrl',3,'callback'),
		array('position', 'getPosition', self::MODEL_BOTH, 'callback'),   
        array('listens', 'getListens', self::MODEL_BOTH,'callback'),
		//array('cover_url', 'getcover', self::MODEL_BOTH,'callback'),
		array('status', '1', self::MODEL_BOTH),
		array('add_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
    );

	//获取名称
	protected function getArtistName($name){
		if (!empty($name)){
			$this->artist_name = $name;
			return $name;
		}else{
			return '';
		}
	}
	
	//获取/新增艺术家
	protected function getArtistId($id){				
		$name = $this->artist_name;
		if (!empty($name)){
			$aid  = M('Artist')->getFieldByName($name ,'id');
			if ($aid){				
				return $aid;
			}else{
				$model = D('Artist');
				$data['sort'] = get_str_sort($name);			
				$data['name'] = $name;
				$data['cover_url'] = $this->artist_cover;
				$data = $model->create($data);				
				if ($aid = $model->add($data)){
					return $aid;					
				}else{
					return '';	
				}
			}			
		}else{
			return '';			
		}		
	}
	
	//获取/新增艺术家
	protected function getAlbumName($name){
		if (!empty($name)){
			$this->album_name = $name;
			return $name;
		}else{
			return '';
		}
	}
	
	//获取/新增专辑
	protected function getAlbumId($id){
		$name = $this->album_name;		
		if (!empty($name)){
			$aid = M('Album')->getFieldByName($name ,'id');		
			//存在直接返回 不存在创建
			if ($aid){
				return $aid;			
			}				
			$artist_name = $this->artist_name;								
			if (!empty($artist_name) && $artist_id = M('Artist')->getFieldByName($artist_name,'id')) {					
				$model = D('Album');
				$cover = $this->album_cover;				
				$data = array(
					'name' 			=> $name,
					'artist_id' 	=> $artist_id,
					'artist_name'	=> $artist_name,
					'cover_url' 	=> $cover,
				);	
				$data = $model->create($data);					
				if ($id = $model->add($data)){
					return $id;					
				}
			}
		}
		return '';
	}
	
        	
   /**
    * 获取上传用户id
    */  
    function getUid($id ) { 
    	if (intval($id)) {
			$this->up_uid = $id;
    		return $id;
    	}else{
    		return UID;
    	}
    }
   
   /**
    * 获取上传用户昵称
    */  
    function getUname($name) {  
    	if ($id = $this->up_uid) {
    		return get_nickname($id);
    	}else{
    		return get_nickname(UID);
    	}
    }    

	/**
    * 获取曲风id
    */  
    function getGnreId($id) {
		if ($id){			
			$this->genre_id = $id;
			return $id;
		}else{
			return 0;
		}
    }	
    
   	/**
    * 获取曲风名称
    */  
    function getGnreName() {
		if ($this->genre_id){
			return get_genre_name($this->genre_id);
		}else{
			return  '其它';
		}  	
    }
    
    /*
    * 获取标签
    */  
    function geTags ($tag) { 	
    	if(!empty($tag) && trim($tag)){
    		$t  = M('tag'); 
    		$tag = explode(',',$tag);    		
    		foreach ($tag as $k => $v) {
    			$id = $t->getFieldByName($v,'id');    		 	
    		 	$t->where(array('id'=>$id))->setInc('count');
    		 	$tags .= ','.$id;
    		}    		
    	}
    	return !empty($tags)? trim($tags,',') : "";
    }
    	
	/**
    * 获取试听数
    */ 
    function getListens($listens) { 
		if (strpos($listens,',')){
			return setrand($listens);			
		}else{
			return  empty($listens)? 0 : $listens;
		}  	
    }

	function getcover($url) {
   		if(!empty($url)) {
			return $url;
		}else{
			return __ROOT__.'/Uploads/Picture/song_cover.jpg';
		}
    }
	
	protected function getPosition($position){
        if(!is_array($position)){
            return 0;
        }else{
            $pos = 0;
            foreach ($position as $key=>$value){
                $pos += $value;		//将各个推荐位的值相加
            }
            return $pos;
        }
    }
	
	/**
     * 更新音乐信息
     * @return boolean 更新状态
     * @author 战神~~巴蒂
     */
    public function update($post=null){
		$post = empty($post)? I('post.'): $post;
		if (!empty($post['artist_cover'])){
			$this->artist_cover = $post['artist_cover'];
		}
		if (!empty($post['album_cover'])){
			$this->album_cover = $post['album_cover'];
		}
		//判断并添加标签
		$data = $this->create($post);
		
        if(!$data){ //数据对象创建错误
            return false;
        }
		$post['down_rule']['coin']	= $data['coin'];
		$extend= array(
			'listen_url'	=> 	$post['listen_url'],
			'down_url'		=> 	!empty($post['down_url'])?$post['down_url']:$post['listen_url'],
			'down_type' 	=> 	$post['down_type'],
			'listen_file_id'=> 	$post['listen_file_id'],
			'down_file_id' 	=> 	!empty($post['down_file_id'])? $post['down_file_id'] : $post['listen_file_id'],
			'file_size' 	=> 	$post['file_size'],
			'play_time' 	=> 	$post['play_time'],
			'bitrate' 		=> 	$post['bitrate'],
			'down_rule' 	=> 	json_encode($post['down_rule']),
			'disk_url' 		=> 	$post['disk_url'],
			'disk_pass' 	=> 	$post['disk_pass'],
			'lrc' 			=> 	$post['lrc'],
			'introduce' 	=> 	$post['introduce']
		);
        /* 添加或更新数据 */
        if(empty($data['id'])){					
            //检测是否存在歌曲
			$map['artist_name'] = $post['artist_name'];
			$map['name'] = $post['name'];
			if ($this->where($map)->getField('status') == 1){
				$this->error="歌曲已存在";
				return false;
			}
			$res = $this->add();
			if ($res){			
				$extend['mid'] = $res;
				M('SongsExtend')->add($extend);
				$model = D('Tag');			
				$model->addMusicTags($post['tags'],$res);	
			}
			
        }else{
            $res = $this->save();
			if ($res){
				$extend['mid'] = $data['id'];
				M('SongsExtend')->save($extend);
				$model = D('Tag');			
				$model->updateMusicTags($post['tags'],$data['id']);
			}
        }
		unset($post);
        return $res;
    }

		
	/**
     * 删除状态为-1的数据
     * @return true 删除成功， false 删除失败
    */
    public function remove(){
        //查询假删除的基础数据
		$where['status'] 	= -1;
		$list 	= $this->alias('a')
				->join('__SONGS_EXTEND__ b ON a.id= b.mid')
				->where($where)
				->field('a.id,a.up_uid,a.server_id,b.listen_file_id,b.down_file_id')
				->select();
				
        //删除本地音乐文件
        $file = M('File');    
        foreach ($list as $key=>$v){
			if ($v['server_id']  == 0){
				$lid 	= $v['listen_file_id'];
				$did	= $v['down_file_id'];
				if ($lid){ 
					$map['id'] 	= $lid;
					$path		= $file->where($map)->field('savepath,savename')->find();
					$path 		= $path['savepath'].$path['savename'];
					if($path && file_exists($path)){
						unlink($path);
					}           	
					$file->where($map)->delete();            
				}
				if($did){
					$map['id'] = $did;
					$path = $file->where($map)->field('savepath,savename')->find();
					if($path &&  file_exists($path)){
						unlink($path);
					} 
					$file->where($map)->delete();
				}
			}
			$songId	= $v['id'];
			
			$res = $this->where($where)->delete($songId);
			if ($res){
				M('SongsExtend')->where('mid='.$songId)->delete();
				if (intval($v['up_uid'])){
					M('Member')->where('uid='.$v['up_uid'])->setDec('songs');
				}				
			}
        }
        return $res;
    }
	
}
