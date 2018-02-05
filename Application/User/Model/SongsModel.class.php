<?php
// +----------------------------------------------------------------------
// | Author: 战神~~巴蒂
// +----------------------------------------------------------------------

namespace User\Model;
use Think\Model;

class SongsModel extends Model{
	protected $genre_id;
	protected $listen_file_id;
	protected $down_file_id;
	protected $listen_url;
		
	protected $_map = array(         
		'song' 		=>'name',
		'artist'	=>'artist_name',
		'genre'		=>'genre_id', 
		'coins'		=>'coin',
		'seid'		=>'server_id',
		'fileid'	=>'listen_file_id',
		'durl'		=>'down_url',
		'lurl'		=>'listen_url',
		'downid'	=>'down_file_id',
		'durl'		=>'down_url',
		'arid'		=>'artist_id',
		'album'		=>'album_id',
		'signature'	=>'introduce'
	);
	
    protected $_validate = array(
        array('name', 'require', '音乐标题不能为空', 1 ,'regex', self::MODEL_BOTH),
		array('name', '2,80', '音乐标题长度2-80个字符', 0 ,'length', 3),
        array('listen_file_id', 'number', '请上传文件', 1 , 'regex', self::MODEL_BOTH),
		array('genre_id', 'require', '请选择所属分类', 1 ,'regex', self::MODEL_BOTH),
        array('genre_id', 'number', '请选择所属分类', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
		array('album_id', 'require', '请选择所属专辑', 1 ,'regex', self::MODEL_BOTH),
        array('album_id', 'number', '请选择所属专辑', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
   
	);

    protected $_auto = array(
		array('name','text_filter',3,'function'),
        array('up_uid', UID, self::MODEL_INSERT),
        array('up_uname','getName',3,'callback'),
		array('genre_id','getGenreId',3,'callback'),
        array('genre_name','getGenreName',3,'callback'),
        array('listen_file_id','getlistenFileid',3,'callback'),
		array('down_file_id','getDownFileid',3,'callback'),
		array('listen_url','getlistenUrl',1,'callback'),
		array('down_url','getDownUrl',1,'callback'),
		array('sing','text_filter',3,'function'),
		array('lyrics','text_filter',3,'function'),
		array('composer','text_filter',3,'function'),
		array('midi','text_filter',3,'function'),
		array('album_name', 'getAlbumName',3,'callback'), 
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('add_time', NOW_TIME, self::MODEL_BOTH),
        array('status', 2, self::MODEL_BOTH),
		array('album_name','getAlbumName',3,'callback'),
        array('artist_name','getArtistName',3,'callback')
    );
         
    protected  function getName () {    
     	return get_nickName(UID);
    }
	
	protected  function getGenreId ($id) {
		$id =  intval(I('post.genre'));;
		$this ->genre_id = $id;
     	return $id ;
    }
	
	protected  function getGenreName () {
     	return M('Genre')->getFieldById($this ->genre_id,'name');
    }
	
	/*protected  function getSing () {
		$lrc = I('post.sing');
     	return  empty($lrc)? "" : text_filter($lrc);
    }
	
	
	protected  function getLyrics () {
		$lrc = I('post.lyrics');
     	return  empty($lrc)? "" : text_filter($lrc);
    }
	
	
	protected  function getComposer () {
		$lrc = I('post.Composer');
     	return  empty($lrc)? "" : text_filter($lrc);
    }
	protected  function midi () {
		$lrc = I('post.lrc');
     	return  empty($lrc)? "" : text_filter($lrc);
    }*/
	
	protected  function getTstr ($str) {
		dump($str);
		//$lrc = I('post.lrc');
     	return  empty($str)? "" : text_filter($str);
    }
	

	protected  function getlistenFileid ($id) {
     	$id =  intval(I('post.fileid'));
		$this->listen_file_id = $id;
     	return $id;
    }
	
	protected  function getDownFileid ($id) {
     	$id =  intval(I('post.downid'));
		$this->down_file_id = $id ;
     	return $id;
    }
	
	protected  function getlistenUrl () {
		
     	$id 	=  $this->listen_file_id;		
		if ($id){
			$list 	=  M('File')->where(array('id'=>$id))->field('savepath,savename,url')->find();
			if (!empty($list['url'])){
				$this->listen_url =  $list['url'];
			}else{
				$path = trim($list['savepath'],'.');
				$this->listen_url =  $path.$list['savename'];
			}	
		}else{
			$url = I('post.lurl');
			$this->listen_url = text_filter($url);
		}
		
		return $this->listen_url;
    }
	
	protected  function getDownUrl () {
     	$id 	=  $this->down_file_id;
		if ($id){
			$list 	=  M('File')->where(array('id'=>$id))->field('savepath,savename,url')->find();
			if (!empty($list['url'])){
				$this->down_url =  $list['url'];
			}else{
				$path = trim($list['savepath'],'.');
				$this->down_url =  $path.$list['savename'];
			}	
		}else{
			$url = I('post.durl');
			$this->down_url = text_filter($url);
		}
		return $this->down_url;  
    }
   	protected  function getAlbumName () {
     	$id =  intval(I('post.album'));
     	if (!empty($id)){
     		return M('Album')->getFieldById($id,'name');
     	}else{     		
     		return '';
     	}
    }
    
    protected  function getArtistName () {
     	$id =  intval(I('post.arid'));
     	if (!empty($id)){
     		return M('Artist')->getFieldById($id,'name');
     	}else{     		
     		return '';
     	}
    }
    
	/**
     * 更新音乐信息
     * @return boolean 更新状态
     * @author 战神~~巴蒂
     */
    public function update($post=""){
		$post = empty($post)? I('post.'): $post;		
		$data = $this->create($post);

		if(!$data){ //数据对象创建错误
            return false;
        }	 
		//判断并添加标签
		$extend= array(
			'listen_url'	=> $data['listen_url'],
			'down_url'		=> !empty($data['down_url'])?$data['down_url']:$data['listen_url'],
			'listen_file_id'=> $data['listen_file_id'],
			'down_file_id' 	=> !empty($data['down_file_id'])? $data['down_file_id'] : $data['listen_file_id'],
			'lrc' 			=> text_filter($post['lrc']),
			'introduce'		=> text_filter($post['signature'])
		);
	
        /* 添加或更新数据 */
        if(empty($data['id'])){	
			$res = $this->add($data);
			if ($res){			
				$extend['mid'] = $res;
				M('SongsExtend')->add($extend);
				//添加到用户上传
				$up	= array(
					'uid'		=> UID,
					'music_id'	=> $res,
					'file_id'	=> $data['listen_file_id'],
					'user_ip'	=> get_client_ip(1),
					'create_time'=> NOW_TIME
				);
				M('UserUpload')->add($up);
			}
			
        }else{
            $res = $this->save($data);
			if ($res){
				$extend['mid'] = $data['id'];
				M('SongsExtend')->save($extend);
			}
        }
        return $res;
    }
	
}
