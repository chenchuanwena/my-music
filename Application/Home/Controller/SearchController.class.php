<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Home\Controller;
use Think\Controller;
class SearchController extends HomeController {
    public function index($keys="",$type=1){	   	    		   	
    	$keys      =   text_filter(trim(I('get.keys')));  
    	$type      =   (int)$type ? $type : 1;
    	if (strstr($keys,'/') )$this->error('请填写关键词！'); 
    	if ($keys){  		
    		$typename="";
	    	switch ($type) { 	    		
	    		case 1	:
	    			$map['name']= array('like',"%{$keys}%");
	    	 		$list	 	= $this->lists('Songs',$map,null);
					if (!empty($list)){
						foreach($list as &$v){
							$v['url']		= U('/music/'.$v['id']);						
							$v['artist_url']= U('/artist/'.$v['artist_id']);
							$v['user_url']	= U('/user/'.$v['up_uid']);
							$v['album_url']	= U('/album/'.$v['album_id']);
							$v['genre_url']	= intval($v['genre_id']) > 0? U('/genre/'.$v['genre_id']) : U('/genre');
							$v['down_url']	= U('/down/'.$v['id']);
						}
					}					
					$tpl		= 'songs';
	    	 		$typename 	= "音乐";
	   			break;
				
				case 2	:
	   				$map['name']= array('like',"%{$keys}%");
	    	 		$list 		= $this->lists('Artist',$map);
					$tpl		= 'artist';
					if (!empty($list)){
						foreach($list as &$v){
							$v['url']			= U('/artist/'.$v['id']);
							$v['cover_url'] 	= !empty($v['cover_url'])?$v['cover_url']:"/Public/static/images/album_cover.png";			
						}
					}
	    	 		$typename 	= "艺人";
	   			break;
	    		
	    		case 3	:
	    			$map['name']= array('like',"%{$keys}%");
	    	 		$list 		= $this->lists('Album',$map);
					if (!empty($list)){
						foreach($list as &$v){
							$v['url']			= U('/album/'.$v['id']);
							$v['artist_url'] 	= U('artist/'.$info['artist_id']);
							$v['genre_url'] 	= !empty($song['genre_id']) ? U('/genre/'.$song['genre_id']) : U('/genre');
							$v['type_url'] 		= U('album/type-'.$info['type_id']);
							$v['cover_url'] 	= !empty($v['cover_url'])?$v['cover_url']:"/Public/static/images/album_cover.png";			
						}
					}
					$tpl		= 'album';
	    	 		$typename 	= "专辑";
	   			break;
	   			
	   			case 4	:
	   				$map['nickname']= array('like',"%{$keys}%");
	    	 		$list 			= $this->lists('Member',$map,'uid DESC');
					$tpl			= 'user';
	    	 		$typename 		= "用户";
	   			break;
				
				case 5	:
	   				$map['title'] = array('like',"%{$keys}%");
	    	 		$list = $this->lists('Member',$map,'uid DESC');
	    	 		$typename = "用户";
	   			break;
	   			
	   			case 6	:
	   				$map['lrc'] = array('like',"%{$keys}%");
	    	 		$list = $this->lists('Songs',$map,null,'lrc');
	    	 		$typename = "歌词";
	   			break;
	   				    		
	    		default:	    	 	
	    	 	$list = '';
    		}
			$this->getSeoMeta();			
    		$this->assign('list', $list);
    		$this->assign('typename', $typename);    		
	    	$this->assign('type',$type);
	    	$this->assign('keys',$keys);
	    	$this->display($tpl);
    	}else{
    		$this->error('请填写关键词！');
    	}

    }
}