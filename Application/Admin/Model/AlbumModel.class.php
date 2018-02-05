<?php
// +----------------------------------------------------------------------
// | Author: 战神巴蒂<31435391@qq.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;


class AlbumModel extends Model {
    
	protected $artist_name;
	protected $genre_id;
	protected $up_uid;
	
	protected $_validate = array(
        array('name', 'require', '名称不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
        array('artist_name', 'require', '所属艺术家不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
	    array('add_uid','getUid', self::MODEL_BOTH,'callback'),
        array('add_uname','getUname',3,'callback'),
		array('artist_name', 'getArtistName', self::MODEL_BOTH,'callback'),
        array('artist_id', 'getArtistId', self::MODEL_BOTH,'callback'),
		array('genre_id', 'getGnreId', self::MODEL_BOTH,'callback'),
		array('genre_name', 'getGnreName', self::MODEL_BOTH,'callback'),
		array('position', 'getPosition', self::MODEL_BOTH, 'callback'),
		array('sort', 'getSort', self::MODEL_BOTH, 'callback'),
		//array('cover_url', 'getcover', self::MODEL_BOTH,'callback'),
		array('add_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', '1', self::MODEL_BOTH),
    );
	
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
			//存在直接返回 不存在创建
			if ($aid){				
				return $aid;
			}else{
				$model = D('Artist');
				$data['sort'] = get_str_sort ($artist_name);			
				$data['name'] = $name;
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
    
    protected function getcover($url) {
   		if(!empty($url)) {
			return $url;
		}else{
			return '';
		}
    }
	
	protected function getSort($sort) { 
		if (!empty($sort) && ctype_alnum($sort)){
			return $sort;
		}else{
			$name = I('post.name');
			return get_str_sort ($name);
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
     * 更新专辑信息
     * @return boolean 更新状态
     * @author 战神~~巴蒂
     */
    public function update($post=null){
		$post = empty($post)? I('post.'): $post;	
		$data = $this->create($post);
        if(!$data){ //数据对象创建错误
            return false;
        }
		/* 添加或更新数据 */
        if(empty($data['id'])){
            $res = $this->add();
        }else{
            $res['name'] = $data['name'];		
            $res['id'] = $data['id'];
			$res['status'] = $this->save();
        }
        return $res;
	}
}


