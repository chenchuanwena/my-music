<?php
/** ***************************************
 * 	   (C) 2014-2016 QQ:31435391	  	  *
 * ****************************************
 * $E-mail: 战神~~巴蒂 (31435391@qq.com) *
 * ***************************************/
namespace JYmusic\TagLib;
use Think\Template\TagLib;

/**
 +-------------------------------
 * JY标签库驱动(获取数据)所有必须至少带有属性，否则不解析
 +-------------------------------
*/
class JY extends TagLib {
	/*
	+----------------------------------------------------------
	*标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
	*标签属性：music -音乐数据，
	*$mod:输出记录的行数如$mod='2',输出偶数行记录
	+----------------------------------------------------------
	*/
	protected $tags   =  array( 
		// 标签定义： 定义标签中对应的属性
		'nav'       => array('attr'=>'cache_time,field,name','close' => 1), //获取导航
		'songs'		=> array('attr'=>'result,id,artist_id,album_id,genre_id,tag_id,up_uid,url,cache_time,pos,limit,order','level'=>3),
		'album'		=> array('attr'=>'result,id,artist_id,type_id,genre_id,add_uid,tag_id,url,cache_time,pos,limit,order','level'=>3),
		'altype'	=> array('attr'=>'result,id,cache_time,limit,order','level'=>3),
		'artist'	=> array('attr'=>'result,id,type_id,region_id,tag_id,pos,sort,cache_time,url,limit,order','level'=>3),
		'tag'		=> array('attr'=>'result,id,ids,cache_time,url,limit,order','level'=>3),
		'genre'		=> array('attr'=>'result,id,ids,pid,cache_time,limit,url,order,tree','level'=>3),
		'video'		=> array('attr'=>'result,id,cache_time,type_id,uid,limit,order','level'=>3),
		'member'	=> array('attr'=>'result,uid,cache_time,limit,order','level'=>3),
		'count'		=> array('attr'=>'name,artist_id,genre_id,album_id,uid','close'=>0),
		'prev'		=> array('attr'=>'result,name,data', 'close' => 1),
        'next'		=> array('attr'=>'result,name,data', 'close' => 1),
		'cate'		=> array('attr'=>'id,name,limit,pid,result','level'=>3),
		'data'      => array('attr'=>'name,field,limit,order,where,join,group,having,table,result,gc','level'=>2),
		'info'   	=> array('attr'=>'id,name,category,pid,pos,type,limit,where,order,field,result','level'=>3),	 			
	);        
    /**
     * music 歌曲标签解析 循环输出数据集
     */       
	public function _songs($tag,$content) {			
		$result		=   isset($tag['result'])?$tag['result']:'songs';		
		$where = '$where["status"]=1;';
		if(!empty($tag['uid'])) {
            $where .= '$where["up_uid"] ='.$tag['uid'].';';
        }
        if(!empty($tag['artist_id'])) {
            $where .= '$where["artist_id"]='.$tag['artist_id'].';';
        }		
		if(!empty($tag['album_id'])){ //
            $where .= '$where["album_id"]= '.$tag['album_id'].';';
        }

		if(!empty($tag['id'])) {
			$id = $tag['id'];
			if (strpos($id, ',')){		
				$where .= '$where["id"]=array("in",array('.$id.');';
			}else{
				$where .= '$where["id"]='.$id.';';
			}
		}	
		if(!empty($tag['tag_id'])) {
			$where .= '$sids = M("MusicTag")->where(array("type_id" => 1,"tag_id"=>'.$tag['tag_id'].'))->getField("music_id",true);';
			$where .= '$where["id"] = !empty($sids)? array("in",$sids) : 0;';
		}
		if(!empty($tag['pos'])){		
			if ($pos = intval($tag['pos'])){
				$where .= '$where[]="position & '.$pos.' = '.$pos.'";';
			}elseif ($tag['pos'] == "*"){
				$where .= '$where["position"]=array("neq",0);';
			}			
		}
		
		if(!empty($tag['genre_id'])) {
			$where .= '$where[\'genre_id\']=array("in",get_genre_ids('.$tag['genre_id'].'));';
		}	
		
		$tag['url']	=  '$'.$result.'[\'url\']=U(\'/music/\'.$'.$result.'[\'id\']);
						$'.$result.'[\'down_url\']=U(\'/down/\'.$'.$result.'[\'id\']);
						$'.$result.'[\'user_url\']=U(\'/user/\'.$'.$result.'[\'up_uid\']);
						$'.$result.'[\'artist_url\']=U(\'/artist/\'.$'.$result.'[\'artist_id\']);
						$'.$result.'[\'album_url\']=U(\'/album/\'.$'.$result.'[\'album_id\']);
						$'.$result.'[\'genre_url\']=!empty($'.$result.'[\'genre_id\']) ? U(\'/genre/\'.$'.$result.'[\'genre_id\']) : U(\'/Genre\');
						$'.$result.'[\'cover_url\']= !empty($'.$result.'[\'cover_url\'])? $'.$result.'[\'cover_url\'] : "/Public/static/images/cover.png";';
        $field = '';
        $arr= array('name'=>'Songs','where'=>$where,'field'=>$field);
		return $this->_musiclist(array_merge($arr,$tag),$content);
    }
    /**
     * album 专辑标签解析 循环输出数据集
     */   
	public function _album($tag,$content) {
		$result		=   isset($tag['result'])?$tag['result']:'album';
		$where = '$where["status"]=1;';
		if(!empty($tag['uid']) ) {
            $where .= '$where["add_uid"]='.$tag['uid'].';';
        }
        if(!empty($tag['artist_id'])) {
            $where .= '$where["artist_id"]='.$tag['artist_id'].';';
        }		
		if(!empty($tag['type_id'])){ //
            $where .= '$where["type_id"]='.$tag['type_id'].';';
        }

		if(!empty($tag['id'])) {
			$id = $tag['id'];
			if (strpos($id, ',')){		
				$where .= '$where["id"]=array("in",array('.$id.');';
			}else{
				$where .= '$where["id"]='.$id.';';
			}
		}
		if(!empty($tag['pos'])){		
			if ($pos = intval($tag['pos'])){
				$where .= '$where[]="position & '.$pos.' = '.$pos.'";';
			}elseif ($tag['pos'] == "*"){
				$where .= '$where["position"]=array("neq",0);';
			}			
		}
		
		if(!empty($tag['genre_id'])) {
			$parseStr .= '$where[\'genre_id\']=array("in",get_genre_ids('.$tag['genre_id'].'));';
		}	

		$tag['url']	= 	'$'.$result.'[\'description\']=text_filter($'.$result.'[\'introduce\']);
						 $'.$result.'[\'url\']=U(\'album/\'.$'.$result.'[\'id\']);
						 $'.$result.'[\'artist_url\']=U(\'/artist/\'.$'.$result.'[\'artist_id\']);
						 $'.$result.'[\'user_url\']=U(\'/user/\'.$'.$result.'[\'add_uid\']);
						 $'.$result.'[\'genre_url\']=U(\'/genre/\'.$'.$result.'[\'genre_id\']);
						 $'.$result.'[\'type_url\']=U(\'/album/type-/\'.$'.$result.'[\'type_id\']);
						 $'.$result.'[\'cover_url\']= !empty($'.$result.'[\'cover_url\'])? $'.$result.'[\'cover_url\'] : "/Public/static/images/album_cover.png";';
        $field = !empty($tag['field'])?$tag['field']:'*';
        $arr= array('name'=>'Album','where'=>$where,'field'=>$field);
		return $this->_musiclist(array_merge($arr,$tag),$content);
	}
	 /**
     * 专辑类型标签解析 循环输出数据集
    */   
	public function _altype($tag,$content) {
		$result		=   isset($tag['result'])?$tag['result']:'albumType';
		$where = '';
		if(!empty($tag['id'])) {
			$id = $tag['id'];
			if (strpos($id, ',')){		
				$where .= '$where["id"]=array("in",array('.$id.');';
			}else{
				$where .= '$where["id"]='.$id.';';
			}
		}		

		$tag['url']	= 	'$'.$result.'[\'url\']=U(\'/album/type-\'.$'.$result.'[\'id\']);';
        $field		= 'id,name,description';
        $arr		= array('name'=>'AlbumType','where'=>$where,'field'=>$field);
		return $this->_musiclist(array_merge($arr,$tag),$content);
	}
    
	/**
	* artist 艺术家标签解析 循环输出数据集
	*/   
	public function _artist($tag,$content) {
		$result		=   isset($tag['result'])?$tag['result']:'artist';
		$where = '$where["status"]=1;';
		if(!empty($tag['type_id'])){ //
            $where .= '$where["type_id"]='.$tag['type_id'].';';
        }
        if(!empty($tag['region_id'])) {
            $where .= '$where["region_id"]='.$tag['region_id'].';';
        }
        if(!empty($tag['sort'])) {
            $where .= '$where["sort"]='.$tag['sort'].';';
        }
		if(!empty($tag['id'])) {
			$id = $tag['id'];
			if (strpos($id, ',')){		
				$where .= '$where["id"]=array("in",array('.$tag['ids'].');';
			}else{
				$where .= '$where["id"]='.$id.';';
			}
		}		
		if(!empty($tag['pos'])){		
			if ($pos = intval($tag['pos'])){
				$where .= '$where[]="position & '.$pos.' = '.$pos.'";';
			}elseif ($tag['pos'] == "*"){
				$where .= '$where["position"]=array("neq",0);';
			}			
		}
		$tag['url']	= 	'$'.$result.'[\'description\']=text_filter($'.$result.'[\'introduce\']);
						 $'.$result.'[\'url\']=U(\'/artist/\'.$'.$result.'[\'id\']);
						 $'.$result.'[\'cover_url\']= !empty($'.$result.'[\'cover_url\'])? $'.$result.'[\'cover_url\'] : "/Public/static/images/artist_cover.png";';
		$field = '';
        $arr= array('name'=>'Artist','where'=>$where,'field'=>$field);
		return $this->_musiclist(array_merge($arr,$tag),$content);
	}
	/**
	* genre 曲风标签解析 循环输出数据集
	*/   
	public function _genre($tag,$content) {
		$result		=   isset($tag['result'])?$tag['result']:'genre';
        $key        =   !empty($tag['key'])?$tag['key']:'i';
        $mod        =   isset($tag['mod'])?$tag['mod']:'2';
        $limit 		=   !empty($tag['limit'])?$tag['limit']:'100';
		$where = '$where["status"]=1;';
		if( isset($tag['pid'])){ //
            $where .= '$where["pid"]='.$tag['pid'].';';
        }
		if(!empty($tag['id'])) {
			$id = $tag['id'];
			if (strpos($id, ',')){		
				$where .= '$where["id"]= array("in","'.$tag['id'].'");';
			}else{
				$parseStr  = '<?php $'.$result.' = M("Genre")->find('.$id.');';
				$parseStr .= '$'.$result.'[\'url\']=U(\'/genre/\'.$'.$result.'[\'id\']); ?>';	
				$parseStr .= $content;
				return $parseStr;
			}
		}
		$parseStr 	= 	'<?php $where=array();';
        $parseStr 	.=  $where ;		
		$parseStr   .= '$_result = M("Genre")->where($where)->limit('.$limit.')';
		if(!empty($tag['order'])){
			if (stristr($tag['order'],',')){
				$order = strtr($tag['order'],array(','=>' desc,')).' desc';
			}else{
				$order = $tag['order'].' desc';
			}			
            $parseStr .= '->order("'.$order.'")';
        }
		if(isset($tag['cache_time'])) {
			$cacheTime  = intval($tag['cache_time']);
			if($cacheTime){
				$parseStr .= '->cache(true,'.$cacheTime.')';  
			}
		}
        
        if(!empty($tag['field'])){
            $parseStr .= '->field("'.$tag['field'].'")';
        }
				
        $parseStr .= '->select();';
		if(!empty($tag['tree'])){
            $parseStr .= '$_result = D("Common/Tree")->toTree($_result);';
        }	
		$parseStr .= 'if($_result):$'.$key.'=0;foreach($_result as $key=>$'.$result.'):';
		$parseStr .= '$'.$result.'[\'url\']=U(\'/genre/\'.$'.$result.'[\'id\']);';
        $parseStr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>'.$content;
        $parseStr .= '<?php endforeach; endif;?>';
		return $parseStr;
    }
	  
    /**
	* tag 标签解析 循环输出数据集
	*/   
	public function _tag($tag,$content) {
		$result		= isset($tag['result'])?$tag['result']:'tag';
		$key        = !empty($tag['key'])?$tag['key']:'i';
        $mod        = isset($tag['mod'])?$tag['mod']:'2';
        $order 		= isset($tag['order'])? trim($tag['order']) : 'id';
        $limit 		= !empty($tag['limit'])?$tag['limit']:'10';
		
		if(!empty($tag['tree'])){
            $parseStr = '<?php $_result = get_tag_tree();';
        }elseif(!empty($tag['song_id'])) {
			$parseStr = '<?php $stags = M("MusicTag")->where(array("type"=>1,"music_id"=>'.$tag['song_id'].'))->getField("tag_id",true);';
			$parseStr .= '$_result = !empty($stags)? M("Tag")->where(array("id"=>array("in",$stags)))->limit('.$limit.')->select() :null;';
		}else{	
			$where = 'array("status"=>1';
			if(!empty($tag['group_id'])){ //
				$where .= ',"group_id"=>'.$tag['group_id'];
			}
			if(!empty($tag['id'])) {
				$id = $tag['id'];
				if (strpos($id, ',')){		
					$where .= ',"id"=>array("in",array('.$tag['ids'].')';
				}else{
					$where .= ',"id"=>'.$id;
				}
			}
			$where .= ')';
			$parseStr   = '<?php $_result = M("Tag")->alias("__TAG")->where('.$where.')->limit('.$limit.')';
			if(!empty($tag['order'])){
				if (stristr($tag['order'],',')){
					$order = strtr($tag['order'],array(','=>' desc,')).' desc';
				}else{
					$order = $tag['order'].' desc';
				}			
				$parseStr .= '->order("'.$order.'")';
			}
			if(isset($tag['cache_time'])) {
				$cacheTime  = intval($tag['cache_time']);
				if($cacheTime){
					$parseStr .= '->cache(true,'.$cacheTime.')';  
				}
			}
			
			if(!empty($tag['field'])){
				$parseStr .= '->field("'.$tag['field'].'")';
			}
					
			$parseStr .= '->select();';	
		}
		$parseStr .='if($_result):$'.$key.'=0;foreach($_result as $key=>$'.$result.'): ';			
		$parseStr .= '$'.$result.'[\'url\']= !empty($'.$result.'["alias"])? U(\'/tag/\'.$'.$result.'[\'alias\']): U(\'/Tag/\'.$'.$result.'[\'id\']);';		
        $parseStr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>'.$content;
        $parseStr .= '<?php endforeach; endif;?>';
		return $parseStr;
    }
    
    /**
	* member 会员标签解析 循环输出数据集
	*/   
	public function _member($tag,$content) {
		$result		=   !empty($tag['result'])?$tag['result']:'member';
		$tag['url'] = '$'.$result.'[\'url\']=U(\'/user/\'.$'.$result.'[\'uid\']);
					   $'.$result.'[\'avatar\']=get_user_avatar($'.$result.'[\'uid\'],128);';		
		if (!empty($tag['uid'])){
			 return $this->_data(array('name'=>"Member", 'where'=>'$where["status"]=1; $where["uid"]='.$tag['uid'].';', 'field'=>$tag['field']),$content);
		}else{
			$tag['order'] 		= 	isset($tag['order'])? trim($tag['order']) : 'uid';		
			$field = isset($tag['field'])?$tag['field']:'';
			$where = '$where["status"]=1;$where["uid"]=array("neq",1);';
			$field = '';		
			$arr= array('name'=>'Member','where'=>$where,'field'=>$field);
			return $this->_musiclist(array_merge($arr,$tag),$content);
		}
    }

	/**
     * video 视频标签解析 循环输出数据集
     */       
	public function _video($tag,$content) {			
		$result		=   isset($tag['result'])?$tag['result']:'video';
		$where = '$where["status"]=1;';
		if(!empty($tag['uid'])) {
            $where .= '$where["uid"] ='.$tag['uid'].';';
        }
		if(!empty($tag['type_id'])) {
            $where .= '$where["type_id"]='.$tag['type_id'].';';
        }
        $field = '';
		$tag['url']	= 	'$'.$result.'[\'url\']=U(\'/video/\'.$'.$result.'[\'id\']);
						 $'.$result.'[\'cover_url\']= !empty($'.$result.'[\'cover_url\'])? $'.$result.'[\'cover_url\'] : "/Uploads/Picture/video_cover.png";';
        $arr= array('name'=>'Video','where'=>$where,'field'=>$field);
		return $this->_musiclist(array_merge($arr,$tag),$content);
    }
	
	/* 共用列表 */
    public function _musiclist($tag, $content){
		$result		=  isset($tag['result'])?$tag['result']:'jy';
        $name       =   !empty($tag['name'])?$tag['name']:'songs';
        $key        =   !empty($tag['key'])?$tag['key']:'i';
        $mod        =   isset($tag['mod'])?$tag['mod']:'2';
        $order 		= 	isset($tag['order'])? text_filter(trim($tag['order'])) : 'id';
        $limit 		=   !empty($tag['limit'])?$tag['limit']:'10';
        $parseStr   =   '<?php '; 
		$parseStr 	.= 	'$where=array();';
		if(!empty($tag['where'])){				
         	$parseStr 	.= $this->parseCondition($tag['where']);	
			if(!empty($tag['time'])) {
				switch ( strtolower($tag['time'])){
					case 'd':
						$time	= strtotime("-1 day");
					  break;  
					case 'w':
						$time	= strtotime("-1 week");
					  break;
					case 'm':
						$time	= strtotime("-1 month");
					break;
						$time	= strtotime("-1 year");
					case 'y':
					break;
					default:
						$time	=  strtotime($tag['time']);
				}
				if ($time){
					$parseStr .= '$where["add_time"]=array("gt",'.$time.');';
				}
			}
        }	
		$parseStr .= '$_result = M("'.$name.'")->alias("__MUSIC")->where($where)';
		if(!empty($tag['page'])){
			$listrow = !empty($tag["limit"]) ? $tag["limit"] : 20;
			$parseStr .= '->page(!empty($_GET["p"])?$_GET["p"]:1,'.$listrow.')';
		}else{
			if(!empty($tag['cache_time'])) {
				$parseStr .= '->cache(true,'.intval($tag['cache_time']).')';
			}

			if(!empty($tag['cache'])) {
				$parseStr .= '->cache(true,'.intval($tag['cache']).')';
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
			$parseStr	.='$__PAGE__->setConfig("prev", "上页");';
			$parseStr 	.='$__PAGE__->setConfig("next", "下页");';
			$parseStr	.= '$'.$result.'_page= $__PAGE__->show();';
			$parseStr	.= ' ?>';				
		}		
        return $parseStr;
    }
    	
	
	/* 列表数据分页 */
	public function _page($tag){
		$table   = $tag['table'];
		$map    = $tag['map'];
		$listrow = $tag['listrow'];
		$parse   = '<?php ';
		$parse  .= '$__PAGE__ = new \Think\Page(music_list_count("' . $table.'",'.$map  . '), ' . $listrow . ');';
		$parse  .= '$__PAGE__->setConfig("theme","%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%");';            
		$parse  .='$__PAGE__->setConfig("prev", "上页");';
        $parse  .='$__PAGE__->setConfig("next", "下页");';
		$parse  .= 'echo $__PAGE__->show();';
		$parse  .= ' ?>';
		return $parse;
	}
    
    /* 导航列表 */
    public function _nav($tag, $content){
        $field  = empty($tag['field']) ? 'true' : $tag['field'];
		$cacheTime  = empty($tag['cache_time']) ? 86400 : intval($tag['cache_time']);
        $tree   =   empty($tag['tree'])? false : true;
        $parse  = $parse   = '<?php ';
        $parse .= '$__NAV__ = M(\'Channel\')->field('.$field.')->where("status=1")->cache("channel","'.$cacheTime.'")->order("sort")->select();';
        if($tree){
            $parse .= '$__NAV__ = list_to_tree($__NAV__, "id", "pid", "_child");';
        }
        $parse .= '?><volist name="__NAV__" id="'. $tag['name'] .'">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    } 
    
	public function _data($tag,$content){
        $name       =   !empty($tag['name'])?$tag['name']:'music';
        $result     =   !empty($tag['result'])?$tag['result']:'music';
      	$parseStr   =   '<?php '; 
		if(!empty($tag['where'])){	
			$parseStr 	.= 	'$where=array();';
         	$parseStr 	.= $this->parseCondition($tag['where']);
        }
		$parseStr .= '$'.$result.' = M("'.$name.'")->alias("__MUSIC")->where($where)';
        if(!empty($tag['order'])){
            $parseStr .= '->order("'.$tag['order'].'")';
        }
        if(!empty($tag['join'])){
            $parseStr .= '->join("'.$tag['join'].'")';
        }
        if(!empty($tag['group'])){
            $parseStr .= '->group("'.$tag['group'].'")';
        }
        if(!empty($tag['having'])){
            $parseStr .= '->having("'.$tag['having'].'")';
        }
        if(!empty($tag['field'])){
            $parseStr .= '->field("'.$tag['field'].'")';
        }
        $parseStr .= '->find();?>';
		if(!empty($tag['url'])){     
	       $parseStr .= $tag['url'];
    	}		
		$parseStr .= $content;
        return $parseStr;
    }
	
	//文章获取分类信息
    public function _cate($tag,$content){
        $result		=  !empty($tag['result'])?$tag['result']:'cate';
		$key		=   !empty($tag['key'])?$tag['key']:'i';
        $mod		=   isset($tag['mod'])?$tag['mod']:'2';
        if(!empty($tag['id'])) {
            // 获取单个分类
            $parseStr  =  '<?php $'.$result.' = M("Category")->find('.$tag['id'].');';
        }elseif(!empty($tag['name'])) {
            // 获取单个分类
            $parseStr  =  '<?php $'.$result.' = M("Category")->getByName('.$tag['name'].');';
        }else{
			$parseStr   =  '<?php $where=array(); $where[\'display\']=1;$where[\'status\']=1;';
			if(!empty($tag['pid'])) {
				$parseStr  .=  '$where[\'pid\']='.$tag['pid'].';';   
			}else{
				$parseStr .= '$where[\'pid\']=0;'; 
			}
			$parseStr   .=  ' $_result = M("Category")->order("sort")->where($where)';
            if(!empty($tag['limit'])){
                $parseStr .= '->limit('.$tag['limit'].')';
            }
            $parseStr .= '->select();';

        }
		$parseStr  .=  'if($_result):$'.$key.'=0;foreach($_result as $key=>$'.$result.'): ';
        $parseStr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );';
		$parseStr .=  '$'.$result.'[\'url\']=!empty($'.$result.'["name"])? U("/article/type/".$'.$result.'["name"]) : U("/article/Type/".$'.$result.'["id"]);';
        $parseStr .=  'if($'.$result.'):?>'.$content.'<?php endif; endforeach;?>';
        $parseStr .= "<?php endif;?>";
        return $parseStr;
    }
    
	public function _info($tag,$content){
        $result  	= !empty($tag['result'])?$tag['result']:'info';
		$name		= !empty($tag['name'])?$tag['name']:'Document';
        $order   	= empty($tag['order'])?'level,create_time':$tag['order'];
        $field  	= empty($tag['field'])?'*':$tag['field'];           
	   if(!empty($tag['id'])) { // 获取单个数据
			$join   	= 'INNER JOIN __DOCUMENT_'.strtoupper($name).'__ ON __DOCUMENT.id = __DOCUMENT_'.strtoupper($name).'__.id';
            return $this->_data(array('name'=>"Document", 'where'=>'$where["status"]=1; $where["id"]='.$tag['id'].';', 'field'=>$field,'result'=>$result,'order'=>$order,'join'=>$join),$content);
        }else{ // 获取数据集
            $where = '$where["status"]=1;';          
            if(!empty($tag['model'])) {
                $where .= '$where["model_id"]='.$tag['model'].';';
            }
			if(!empty($tag['cover'])) {
                $where .= '$where["cover_id"]=array("gt","0");';
            }
            if(!empty($tag['cate'])) { 
                if(strpos($tag['cate'],',')) {
                    $where .= ' $where["category_id"]=array("IN","'.$tag['cate'].'");';
                }else{
					$where .=  '$cids = get_stemma('.$tag['cate'].',M("Category"));';			
					$where .=  'if (is_array($cids)){';						
					$where .=  '$cids = array_column($cids,"id");array_unshift($cids,'.$tag['cate'].');';
					$where .=  ' $where["category_id"]=array("IN",$cids);';
					$where .=  '}else{';
					$where .=  ' $where["category_id"]='.$tag['cate'].';};';
                }
            }

            if(!empty($tag['pid'])){ //
                $where .= '$where["pid" => '.$tag['pid'].';';
            }
			if(!empty($tag['pos'])) {
				$pos = intval($tag['pos']);
				$where .= '$where[]="position & '.$pos.' = '.$pos.'";';
			}

			if(	strtolower($name) == 'site') {
				if(empty($tag['type'])) {
					$tag['type'] = 'about';
				}
				$order = 'create_time ASC';
				$where .= '$where["appname"]="'. $tag['type'].'";';
				$tag['url']	=  '$'.$result.'[\'url\'] = U(\'/article/site/\'.$'.$result.'[\'name\']);';
			}else{
				$tag['url']	=  '$'.$result.'[\'url\'] = U(\'/article/\'.$'.$result.'[\'id\']);							
				$'.$result.'[\'cate_url\'] = get_category_url($'.$result.');
				$'.$result.'[\'cover_url\'] = get_cover($'.$result.');
				$'.$result.'[\'cate\'] = get_category_title($'.$result.'["category_id"]);';
				
			}
			
			$arr = array('name'=>$name,'where'=>$where,'field'=>$field,'result'=>$result,'order'=>$order,'limit'=>!empty($tag['limit'])?$tag['limit']:'');
			
            return $this->_musiclist(array_merge($arr,$tag),$content);
        }
    }
	
	/**
     * count 获取总数
    */       
	public function _count($tag,$content) {			
		$result	=   isset($tag['result'])?$tag['result']:'count';
		$name	=   isset($tag['name'])? ucfirst($tag['name']):'Songs';			
		$where  = 'array("status"=>1';
		if(!empty($tag['uid'])) {	
            $where .= ',"add_uid" =>'.$tag['uid'];
        }
		if(!empty($tag['artist_id'])) {
            $where .= ',"artist_id"=>'.$tag['artist_id'];
        }
		if(!empty($tag['album_id'])) {
            $where .= ',"album_id"=>'.$tag['album_id'];
        }
		if(!empty($tag['genre_id'])) {
            $where .= ',"genre_id"=>array("in",get_genre_ids('.$tag['genre_id'].'))';
        }
        $where .= ')';
		$parse   = '<?php ';
        $parse  .= '$__COUNT__ = M("'. $name.'")->where('.$where.')';
		if(isset($tag['cache_time'])) {
			$cacheTime  = intval($tag['cache_time']);
			if($cacheTime){
				$parse .= '->cache(true,'.$cacheTime.')';  
			}
		}
		$parse  .= '->count();';
        $parse  .= 'echo $__COUNT__;';
        $parse  .= ' ?>';
		return $parse;
    }
	
	/* 获取下一个数据 */
    public function _next($tag, $content){
		$result	=   isset($tag['result'])?$tag['result']:'next';
        $name	=   isset($tag['name'])? ucfirst($tag['name']):'Songs';	
		
		if($name == "Info"){
			$info   = !empty($tag['data'])? $tag['data'] : 'data';
			$parse  = '<?php ';
			$parse .= '$' . $result . ' = D(\'Document\')->next($' . $info . ');';
			$parse .= 'if(!empty($'.$result.')): $'.$result.'[\'url\'] = U(\'/article/\'.$'.$result.'[\'id\']);?>';
			$parse .= $content;
			$parse .= '<?php endif; ?>';
		}else{
			$url 	=  $name != 'Songs'? $name : 'Music';
			$info   =  !empty($tag['data'])? $tag['data'] : 'data';
			$parse  = '<?php ';
			$parse .= '$' . $result. ' = M("'.$name.'")->field("id,name")->order("id")->where(array("status"=>1,"id"=> array("gt", $'.$info.'["id"])))->find();';
			$parse .= 'if(empty($'.$result.')): $' . $result. ' = M("'.$name.'")->field("id,name")->order("id ASC")->find();?> <?php endif; ?> <?php $'.$result.'[\'url\']=U(\'/'.$url.'/\'.$'.$result.'[\'id\']); ?>  ';
			$parse .= $content;
		}
		return $parse;
    }

    /* 获取上一个数据 */
    public function _prev($tag, $content){
		$result	=   isset($tag['result'])?$tag['result']:'prev';
        $name	=   isset($tag['name'])? ucfirst($tag['name']):'Songs';
		if($name == "Info"){			
			$info   = !empty($tag['data'])? $tag['data'] : 'data';
			$parse  = '<?php ';
			$parse .= '$' . $result . ' = D(\'Document\')->prev($' . $info . ');';
			$parse .= 'if(!empty($'.$result.')):  $'.$result.'[\'url\'] = U(\'/article/\'.$'.$result.'[\'id\']);?>';
			$parse .= $content;
			$parse .= '<?php endif; ?>';
		}else{		
			$url 	=  $name != 'Songs'? $name : 'Music';
			$info   = !empty($tag['data'])? $tag['data'] : 'data';
			$parse  = '<?php ';
			$parse .= '$' . $result . ' = M("'.$name.'")->field("id,name")->order("id DESC")->where(array("status"=>1,"id"=> array("lt", $'.$info.'["id"])))->find();';
			$parse .= 'if(empty($'.$result.')): $' . $result. ' = M("'.$name.'")->field("id,name")->order("id DESC")->find();?>  <?php endif; ?><?php $'.$result.'[\'url\']=U(\'/'.$url.'/\'.$'.$result.'[\'id\']); ?>  ';
			$parse .= $content;
		}
        return $parse;
    }
    
    

}
