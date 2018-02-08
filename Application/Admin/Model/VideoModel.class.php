<?php
// +----------------------------------------------------------------------
// | Author: 战神巴蒂<31435391@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;


class VideoModel extends Model {
	
	protected $uid;
	protected $title;
	protected $cover;

    protected $_validate = array(
		array('url', 'require', '请填写解析地址', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('type_id', 'require', 'URL不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
		array('url', 'getUrl', self::MODEL_BOTH,'callback'),
		array('name', 'getName', self::MODEL_BOTH,'callback'),
		array('cover_url', 'getCover', self::MODEL_BOTH,'callback'),
		array('up_uid','getUid',3,'callback'), 
        array('up_uname','getUname',3,'callback'),       
        array('type_name', 'getTypeName', self::MODEL_BOTH,'callback'),
        array('position', 'getPosition', self::MODEL_BOTH, 'callback'),
		array('add_time', NOW_TIME, self::MODEL_INSERT),
		array('update_time', NOW_TIME, self::MODEL_INSERT),
		array('status', '1', self::MODEL_BOTH)
    );
    
	
	//解析视频地址
	function getUrl ($url){
		import('JYmusic.VideoUrlParser');
		$object = new \VideoUrlParser();
		$info = $object::parse($url);
		if($info){
			$this->title 	= $info['title'];
			$this->cover	= $info['img'];
			return $info['swf'];		
		}else{
			$this->error="未获取到视频信息!";
			return false;
		}
	}
    
	function getName($name) {    	
		return  !empty($name)?  $name : $this->title;
    }
	
	function getCover($cover) {    	
		return  !empty($cover)? $cover : $this->cover;
    }
	
	/**
    * 获取下载地址,防止没有填写
    */          
	function getTypeName() {    	
    	if(!empty($_POST['type_id'])){
    		return M('VideoType')->getFieldById($_POST['type_id'],'name');   		
    	}else{   		
    		return  '其他';
    	}
    	
    }
	
	
	function getUid($uid) {
		$uid = empty($uid)? UID : $uid;
		$this->uid	=	$uid;
		return $uid;
	}		 
	  
	  
   /**
    * 获取上传用户昵称
    */  
    function getUname() { 
		return get_nickname($this->uid);
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
   
}
