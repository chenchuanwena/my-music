<?php
/** ***************************************
 * 版权所有 (C) 2014-2016 QQ:378020023	  *
 * ****************************************
 * $E-mail: 战神~~巴蒂 (378020023@qq.com) *
 * ***************************************/
namespace mymusic\TagLib;
use Think\Template\TagLib;

/**
 +-------------------------------
 * music标签库驱动(获取数据)所有必须至少带有属性，否则不解析
 +-------------------------------
 */
class ME extends TagLib {
	/*
	+----------------------------------------------------------
	*标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
	*标签属性：music -音乐数据，
	*$mod:输出记录的行数如$mod='2',输出偶数行记录
	+----------------------------------------------------------
	*/
	protected $tags   =  array( 
		// 标签定义： 定义标签中对应的属性
		'songs'     => array('attr' => 'result,cache_time,limit,order','level'=>3),
		'album'     => array('attr' => 'result,cache_time,limit,order','level'=>3),
		'audit'     => array('attr' => 'result,cache_time,limit,order','level'=>3),
		'fav'		=> array('attr'	=> 'result,name,cache_time,limit,order','level'=>3),
		'listen'	=> array('attr'	=> 'result,cache_time,limit,order','level'=>3),
		'down'      => array('attr'	=> 'result,cache_time,limit,order','level'=>3),
		'like'		=> array('attr'	=> 'result,cache_time,limit,order','level'=>3),
		'recommend' => array('attr'	=> 'result,cache_time,limit,order','level'=>3),
		'fans'		=> array('attr'	=> 'result,cache_time,limit,order','level'=>3),
		'follow'	=> array('attr'	=> 'result,cache_time,limit,order','level'=>3),
		'visitor'	=> array('attr'	=> 'result,limit,','level'=>3),
		'count'		=> array('attr'	=> 'name,type,uid','close'=>0),
	); 
	
	
	/**
     * 收藏标签解析 循环输出数据集
    */       
	public function _fav($tag,$content) {			
		$tag['result']	= isset($tag['result'])?$tag['result']:'fav';
		$name			= !empty($tag['name'])? ucfirst($tag['name']):'Songs';	
		$tag['table']	= 'UserFav';
		if ($name == 'Songs'){
			$tag['type'] = 1;
			return $this->_jionsong($tag,$content);
		}
		if ($name == 'Artist'){
			$tag['type'] = 2;
			return $this->_jionartist($tag,$content);
		}
		if ($name == 'Album'){
			$tag['type'] = 3;
			return $this->_jionalbum($tag,$content);
		}
    }
	/**
     * 试听标签解析 循环输出数据集
    */       
	public function _songs($tag,$content) {			
		$result = $tag['result']	= isset($tag['result'])?$tag['result']:'share';
        
		if(!empty($tag['uid'])) {
            $tag['where'] = '$where["status"] =1;$where["up_uid"] ='.$tag['uid'].';';
        }else{
			$tag['where'] = '$where["status"] =1; $where["up_uid"]= array(\'neq\',1);';
		}
		$tag['url']	=  '$'.$result.'[\'url\']=U(\'/music/\'.$'.$result.'[\'id\']);
				$'.$result.'[\'down_url\']=U(\'/down/\'.$'.$result.'[\'id\']);
				$'.$result.'[\'user_url\']=U(\'/user/\'.$'.$result.'[\'up_uid\']);
				$'.$result.'[\'artist_url\']=U(\'/artist/\'.$'.$result.'[\'artist_id\']);
				$'.$result.'[\'album_url\']=U(\'/album/\'.$'.$result.'[\'album_id\']);
				$'.$result.'[\'genre_url\']=!empty($'.$result.'[\'genre_id\']) ? U(\'/genre/\'.$'.$result.'[\'genre_id\']) : U(\'/Genre\');
				$'.$result.'[\'cover_url\']= !empty($'.$result.'[\'cover_url\'])? $'.$result.'[\'cover_url\'] : "/Public/static/images/cover.png";';

		return $this->_musiclist($tag,$content);;
    }
	
	
	public function _album($tag,$content) {			
		$result = $tag['result']	= isset($tag['result'])?$tag['result']:'share';
		$tag['name'] = 'Album';
        
		if(!empty($tag['uid'])) {
            $tag['where'] = '$where["status"] =1;$where["add_uid"] ='.$tag['uid'].';';
        }else{
			$tag['where'] = '$where["status"] =1; $where["add_uid"]= array(\'neq\',1);';
		}
		$tag['url']	=  '$'.$result.'[\'url\']=U(\'/album/\'.$'.$result.'[\'id\']);
				$'.$result.'[\'down_url\']=U(\'/down/\'.$'.$result.'[\'id\']);
				$'.$result.'[\'user_url\']=U(\'/user/\'.$'.$result.'[\'add_uid\']);
				$'.$result.'[\'artist_url\']=U(\'/artist/\'.$'.$result.'[\'artist_id\']);
				$'.$result.'[\'genre_url\']=!empty($'.$result.'[\'genre_id\']) ? U(\'/genre/\'.$'.$result.'[\'genre_id\']) : U(\'/genre\');
				$'.$result.'[\'cover_url\']= !empty($'.$result.'[\'cover_url\'])? $'.$result.'[\'cover_url\'] : "/Public/static/images/cover.png";';

		return $this->_musiclist($tag,$content);;
    }

	/**
     * 试听标签解析 循环输出数据集
    */       
	public function _listen($tag,$content) {			
		$tag['result']	= isset($tag['result'])?$tag['result']:'listen';
		$tag['table']	= 'UserListen';
		return $this->_jionsong($tag,$content);
    }
	/**
     * 待审标签解析 循环输出数据集
    */       
	public function _audit($tag,$content) {			
		$tag['result']	= isset($tag['result'])?$tag['result']:'audit';
		$tag['table']	= 'UserUpload';
		$tag['status']	= 2;
		return $this->_jionsong($tag,$content);
    }
		
	/**
     * 下载标签解析 循环输出数据集
    */       
	public function _down($tag,$content) {			
		$tag['result']	= isset($tag['result'])?$tag['result']:'down';
		$tag['table']	= 'UserDown';
		return $this->_jionsong($tag,$content);
    }
		
	/**
     * 喜欢标签解析 循环输出数据集
    */       
	public function _like($tag,$content) {			
		$tag['result']	= isset($tag['result'])?$tag['result']:'like';
		$name			= !empty($tag['name'])? ucfirst($tag['name']):'Songs';	
		$tag['table']	= 'UserLike';
		if ($name == 'Songs'){
			return $this->_jionsong($tag,$content);
		}
		if ($name == 'Artist'){
			return $this->_jionartist($tag,$content);
		}
		if ($name == 'Album'){
			return $this->_jionalbum($tag,$content);
		}
		return $this->_jionsong($tag,$content);
    }
	
	/**
     * 喜欢标签解析 循环输出数据集
    */       
	public function _recommend($tag,$content) {			
		$tag['result']	= isset($tag['result'])?$tag['result']:'like';
		$tag['table']	= 'UserRecommend';
		return $this->_jionsong($tag,$content);
    }
	
	/**
     * 艺术家标签解析 循环输出数据集
    */       
	protected function _jionartist($tag,$content){	
		$result		=  	isset($tag['result'])?$tag['result']:'artist';
		$where = "";
		if(!empty($tag['uid'])) {
            $where .= '$where["uid"] ='.$tag['uid'].';';
        }
		if(!empty($tag['type'])) {
            $where .= '$where["type"]='.$tag['type'].';';
        }
		$tag['join'] 	= '__ARTIST__ b ON a.music_id= b.id';
		$tag['field']	= 'b.id,b.name,b.cover_url,b.type_id,b.type_name,b.sort,b.region,b.hits,b.favtimes,b.likes,b.introduce,b.add_time';
		$tag['url']		= '$'.$result.'[\'url\']=U(\'/artist/\'.$'.$result.'[\'id\']);
						   $'.$result.'[\'cover_url\']= !empty($'.$result.'[\'cover_url\'])? $'.$result.'[\'cover_url\'] : "/Public/static/images/artist_cover.png";';
	
        $where .= ')';
		$arr= array('name'=>$tag['table'],'where'=>$where,'field'=>$field,'id'=>$result);
		return $this->_musiclist(array_merge($tag,$arr),$content);
    }
	/**
     * 专辑标签解析 循环输出数据集
    */  
	protected function _jionalbum($tag,$content) {
		$result		=  	isset($tag['result'])?$tag['result']:'album';
		$where = "";
		if(!empty($tag['uid'])) {
            $where .= '$where["uid"]='.$tag['uid'].';';
        }
		if(!empty($tag['type'])) {
            $where .= '$where["type"]='.$tag['type'].';';
        }

		$tag['join'] 	= '__ALBUM__ b ON a.music_id= b.id';
		$tag['field']	= 'b.id,b.name,b.cover_url,b.type_id,b.type_name,b.artist_id,b.artist_name,b.genre_name,b.genre_id,b.sort,b.company,b.hits,b.favtimes,b.likes,b.introduce,b.add_time';
		$tag['url']		= '$'.$result.'[\'url\']=U(\'/album/\'.$'.$result.'[\'id\']);
					 $'.$result.'[\'artist_url\']=U(\'/artist/\'.$'.$result.'[\'artist_id\']);
					 $'.$result.'[\'genre_url\']=U(\'/genre/\'.$'.$result.'[\'genre_id\']);
					 $'.$result.'[\'cover_url\']= !empty($'.$result.'[\'cover_url\'])? $'.$result.'[\'cover_url\'] : "/Public/static/images/album_cover.png";';
			
		$arr= array('name'=>$tag['table'],'where'=>$where,'id'=>$result);
		return $this->_musiclist(array_merge($tag,$arr),$content);
    }
	
	/**
     *  循环输出音乐数据集
    */       
	protected  function _jionsong($tag,$content) {
		$result		=  isset($tag['result'])?$tag['result']:'song';
		$where = "";
		if(!empty($tag['status'])) {
            $where .= '$where["status"] ='.$tag['status'].';';
        }
		if(!empty($tag['uid'])) {
            $where .= '$where["uid"]='.$tag['uid'].';';
        }
		if(!empty($tag['type'])) {
            $where .= '$where["type"]='.$tag['type'].';';
        }
		$tag['join'] 	= '__SONGS__ b ON a.music_id= b.id';
		$tag['field']	= 'b.id,b.name,b.cover_url,b.up_uid,b.up_uname,b.artist_id,b.artist_name,b.album_id,b.album_name,b.genre_name,b.genre_id,b.listens,b.likes,b.favtimes,b.add_time';

		$tag['url']	=  '$'.$result.'[\'url\']=U(\'/music/\'.$'.$result.'[\'id\']);
				$'.$result.'[\'down_url\']=U(\'/down/\'.$'.$result.'[\'id\']);
				$'.$result.'[\'user_url\']=U(\'/User/\'.$'.$result.'[\'up_uid\']);
				$'.$result.'[\'artist_url\']=U(\'/artist/\'.$'.$result.'[\'artist_id\']);
				$'.$result.'[\'album_url\']=U(\'/album/\'.$'.$result.'[\'album_id\']);
				$'.$result.'[\'genre_url\']=!empty($'.$result.'[\'genre_id\']) ? U(\'/genre/\'.$'.$result.'[\'genre_id\']) : U(\'/genre\');
				$'.$result.'[\'cover_url\']= !empty($'.$result.'[\'cover_url\'])? $'.$result.'[\'cover_url\'] : "/Public/static/images/cover.png";';
		$arr= array('name'=>$tag['table'],'where'=>$where,'id'=>$result);
		return $this->_musiclist(array_merge($tag,$arr),$content);
    }
	/**
     *  粉丝标签
    */  
	public function _fans($tag, $content){
		$result			= isset($tag['result'])?$tag['result']:'fans';		
		$tag['name']	= 'UserFollow';
		if(!empty($tag['uid'])) {
           $tag['where'] = '$where["follow_uid"] ='.$tag['uid'].';';
        }
		$tag['url']		= 	'$'.$result.'[\'url\']=U(\'/user/\'.$'.$result.'[\'uid\']);
					 $'.$result.'[\'nickname\']=get_nickname($'.$result.'[\'uid\']);
					 $'.$result.'[\'avatar\']=get_user_avatar($'.$result.'[\'uid\']);';
			
		return $this->_musiclist($tag,$content);
	}
	/**
     *  关注标签
    */  
	public function _follow($tag, $content){
		$result		= isset($tag['result'])?$tag['result']:'follow';
		$tag['name']	= 'UserFollow';
		if(!empty($tag['uid'])) {
            $tag['where'] = '$where["uid"] ='.$tag['uid'].';';
        }
		$tag['url']		= 	'$'.$result.'[\'url\']=U(\'/user/\'.$'.$result.'[\'follow_uid\']);
					$'.$result.'[\'uid\']=$'.$result.'[\'follow_uid\'];
					 $'.$result.'[\'nickname\']=get_nickname($'.$result.'[\'follow_uid\']);
					 $'.$result.'[\'avatar\']=get_user_avatar($'.$result.'[\'follow_uid\']);';
			
		return $this->_musiclist($tag,$content);
	}
	
	/*访客*/	
	 public function _visitor($tag, $content){
		$result		= isset($tag['result'])?$tag['result']:'visitor';
		$limit 		= !empty($tag['limit'])?$tag['limit']:'20';
		$tag['uid']	= isset($tag['uid'])?$tag['uid']:'$user[\'uid\']';
		$key        =   !empty($tag['key'])?$tag['key']:'i';
		$mod        =   isset($tag['mod'])?$tag['mod']:'2';		
		
		$parseStr   =   '<?php $_result =  get_visitors('.$tag['uid'].','.$limit.');';
		$parseStr .= ' if($_result):$'.$key.'=0;foreach($_result as $key=>$'.$result.'): ';
            
	    $parseStr .= '$'.$result.'[\'url\'] = U("/user/".$'.$result.'[\'uid\']);';
        $parseStr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>'.$content;
        $parseStr .= '<?php endforeach; endif;?>';
		return $parseStr;
	 }
	
       
	/* 共用列表 */
    public function _musiclist($tag, $content){				
        $name       =   !empty($tag['name'])?$tag['name']:'songs';
		$result     =   $tag['result'];
        $key        =   !empty($tag['key'])?$tag['key']:'i';
        $mod        =   isset($tag['mod'])?$tag['mod']:'2';
        $limit 		=   !empty($tag['limit'])?$tag['limit']:'10';
        $parseStr   =   '<?php ';
		if(!empty($tag['where'])){
			$parseStr .= '$where=array();';
            $parseStr .= $this->parseCondition($tag['where']);
			
        }
		$parseStr   .=   '$_result = M("'.$name.'")->alias("a")->where($where)';
		if(!empty($tag['join'])){
            $parseStr .= '->join("'.$tag['join'].'")';
        }
				
		if(!empty($tag['page'])){
			$listrow = !empty($tag["limit"]) ? $tag["limit"] : 20;
			$parseStr .= '->page(!empty($_GET["p"])?$_GET["p"]:1,'.$listrow.')';
		}else{		
			if(isset($tag['cache_time'])) {
				$cacheTime  = intval($tag['cache_time']);
				if($cacheTime){
					$parseStr .= '->cache(true,'.$cacheTime.')';  
				}
			}
			if(!empty($tag['limit'])){
				$parseStr .= '->limit("'.$tag['limit'].'")';
			}
		}			
		
		if(!empty($tag['order'])){						
			$order	= $this->parseCondition($tag['order']);							
			if (stristr($order,',')){
				$order = strtr($order,array(','=>' desc,')).' desc';
				$parseStr .= '->order("'.$order.'")';
			}elseif(stristr($order,"$")){
				$parseStr .= '->order('. $order .'." desc")';
			}elseif(strpos($order," ")){
				$parseStr .= '->order("'.$order.'")';
			}else{					
				$parseStr .= '->order("'.$order.' desc")';
			}
        }
		
        if(!empty($tag['field'])){
            $parseStr .= '->field("'.$tag['field'].'")';
        }

				
        $parseStr .= '->select();if($_result):$'.$key.'=0;foreach($_result as $key=>$'.$result.'): ';
        if(!empty($tag['url'])){     
	       $parseStr .= $tag['url'];
    	}
        $parseStr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>'.$content;
        $parseStr .= '<?php endforeach; endif;?>';
		if (!empty($tag['page'])){
			$roll 		=   !empty($tag['roll'])? intval($tag['roll']):5;
			$parseStr  .= '<?php ';
			if (!empty($tag['total'])){
				$parseStr .= '$'.$result.'_total= '.intval($tag['total']).';';
			}else{		
				$parseStr .= '$'.$result.'_total= M("'. $name.'")->where($where)->count();';
			}
			$parseStr	.= '$__PAGE__ = new \Think\Page($'.$result.'_total,' . $listrow . ');';
			$parseStr	.= '$__PAGE__ ->rollPage = '.$roll.';';
			$parseStr	.= '$__PAGE__->setConfig("theme","%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%");';            
			$parseStr	.= '$__PAGE__->setConfig("prev", "上页");';
			$parseStr 	.= '$__PAGE__->setConfig("next", "下页");';
			$parseStr	.= '$'.$result.'_page= $__PAGE__->show();';
			$parseStr	.= ' ?>';				
		}		
        return $parseStr;
    }
	
	/**
     * count 获取总数
    */       
	public function _count($tag,$content) {			
		$result		=   isset($tag['result'])?$tag['result']:'count';
		$name		=   isset($tag['name'])? ucfirst($tag['name']):'Upload';		
		$parse 	= 	'<?php $where=array();';		
		if(!empty($tag['uid'])) {	
            $parse .= '$where["uid"] ='.$tag['uid'].';';
        }
		if($name != "Upload" || $name != "Down") {	
			$type	=  isset($tag['type'])? (int)$tag['name']: 1;			
            $parse .= '$where["type"] ='.$type.';';
        }
        $parse  .= '$__COUNT__ = M("User'. $name.'")->where($where)->count();';
        $parse  .= 'echo $__COUNT__;';
        $parse  .= ' ?>';
		return $parse;
    }
}
