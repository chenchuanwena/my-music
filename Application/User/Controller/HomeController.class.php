<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace User\Controller;
class HomeController extends UserController {
	
    public function index($uid=0){	
		
		$this->setUser($uid);		
		if ( defined('UID') && $uid != UID){
			//记录访问  缓存
			$visitor = array(
				'nickname'=> $this->login_user['nickname'],
				'visit_time'=> NOW_TIME
			);					
			$visitors = F('visitor_'.$uid);		
			if ($visitors){	
				if($visitors[UID]){
					unset($visitors[UID]);											
	 			}elseif(count($visitors) >= 100)	{//删除最后一个							
					array_pop($visitors);
				}										
			}					
			$visitors[UID] = $visitor;
			//缓存7天 7天无任何访问者 清除
			$visitors = F('visitor_'.$uid,$visitors);    			  	    		    	  
		}
		//增加访问了
		$uid	= $this->user['uid'];

		M('Member')->where(array('uid'=>$uid))->setInc('hits',1);
		
		
		$title = empty($this->user['title'])? $this->user['nickname'].'个人空间' : $this->user['title'];
		$this->meta_title = $title.' - '.C('WEB_SITE_TITLE');
    	$this->display('Home:index');   		

    }
    
    //用户歌曲
    public function share($uid=0){	
		$this->setUser($uid);
    	$this->meta_title = $this->user['nickname'].'的分享 - '.C('WEB_SITE_TITLE');
		$this->display('Home:share');
    }
	//用户收藏
    public function fav($uid=0){	
		$this->setUser($uid);
    	$this->meta_title = $this->user['nickname'].'的分享  - '.C('WEB_SITE_TITLE');
		$this->display('Home:fav');
    }
	//用户专辑
    public function album($uid=0){	
		$this->setUser($uid);
    	$this->meta_title = $this->user['nickname'].'的专辑  - '.C('WEB_SITE_TITLE');
		$this->display('Home:album');
    }

           
    //用户档案
    public function profile($uid=0){
		$this->setUser($uid);
		$this->assign('type','profile');
		$this->meta_title = $this->user['nickname'].'个人档案 -' .$this->user['title'].' - '.C('WEB_SITE_TITLE');
		$this->display('User:profile');   		

    }
    
    
   //关注
    public function follow($uid=0){
		$this->setUser($uid);
	    $this->meta_title = $this->user['nickname'].'的关注 - '.$this->user['title'].' - '.C('WEB_SITE_TITLE');;
	    $this->display('Home:follow');   		
    }
    
   //粉丝
    public function fans($uid=0){
		$this->setUser($uid);
	    $this->meta_title = $this->user['nickname'].'的粉丝  - '.C('WEB_SITE_TITLE');
	    $this->display('Home:fans'); 
    }
	
	//访客 
	public function guests($uid=0){
		$this->setUser($uid);		
		$visitors = F('visitor_'.$uid);  //访客
		$list= array();
		foreach ($visitors as $key=>$v){			
			$list[]= array_merge(array('uid'=>$key),$v);
		}
		krsort($list);

		$this->assign('list', $list);
		$this->meta_title = $this->user['nickname'].'的最近访客- '.$this->user['title'].' - '.C('WEB_SITE_TITLE');
		$this->display('gx'); 
	}
    
	
	public function _empty($method) {
		$method=(int)$method;
		if ($method){
			$this->index($method);
		}else{
    		$this->error('页面出错');
    	}
	}
	public function setUser($uid){
		if (!(int)$uid){ $this->error('你访问的页面不存在！');}
		if  (!defined('UID') || UID != $uid){
			$user = M('Member')->where(array('uid'=>$uid,'status'=>1))->find();
			$user['url']= U('/User/'.$user['uid']);	
			$this->user	= $user;
		}

		if (empty($this->user)){			
			$this->error('用户不存在或被禁用！');
		}
			
		if (empty($user['space'])){		
			if($this->openspace($uid)){
				M('Member')->where(array('uid'=>$uid))->setField('space',1);
			}else{
				$this->error('个人空间开通失败~~ 请联系管理员');
			}			
		}
		$space 	=	M('UserSpace')->where(array('uid'=>$uid))->field('id,uid,status,uname',true)->find();
		if(!empty($space)){		
			unset($space['hits']);
			$this->user = array_merge ($this->user,$space);
		}
				
	}
}


