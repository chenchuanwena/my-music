<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2016 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Home\Controller;
use Think\Controller;
/**
 * 前台音乐数据处理
 */
class DownController extends HomeController {	
		
	public function index($id=0) {
    	$map['id']	= (int)$id;	//用户提交的id    
		$map['status']	= 1;	//用户提交的id  			
    	$song 	= M('songs')->where(array('id'=>$id))->find();   			
		if(!empty($song)){			
			$music 	= M('SongsExtend')->find($id);
			$song 	= !empty($music)? array_merge($music, $song ) : $song; 
			$song['artist_url']	= U('/artist/'.$song['artist_id']);
			$song['album_url']	= U('/album/'.$song['album_id']);
			$song['genre_url']	= U('/genre/'.$song['genre_id']);
			$song['user_url']	= U('/user/'.$song['up_uid']);
			$song['url']		= U('/music/'.$song['id']);
			$song['key']		= jy_encrypt($song['id'].'/'.$song['server_id'].'/'.$song['name'].'/'.$song['artist_name']);					
			
			$this->title 		= $song['name'];
			$this->getSeoMeta('index',$song['name']);
    		$this->assign('data',$song);
  			$this->display('index');
    	}else{
    		$this->error('你查看的页面不存在！');
    	}    	
    }
    
	public function song($t="mp3",$auth=null){	
		//简单的防盗链
		$host	= $_SERVER['HTTP_HOST'];
		$refe	= $_SERVER['HTTP_REFERER'];
		/*if(!isset($refe) && strpos($refe,$host) === false){
			$this->error('非法访问');
			exit();
		}*/
		//if (empty($auth)) $this->error('文件不存在');
		
		$data 		= jy_decrypt($auth);
		
		$data 		= explode('/',$data);
		$id 		= intval($data[0]);	
		$server_id 	= intval($data[1]);		
		//获取文件
		$extend = M('SongsExtend')->where(array('mid'=>$id))->field('listen_url,down_rule,down_url,disk_url,disk_pass')->find();
		
		$downRule			= json_decode($extend['down_rule'],1);
		if (intval($downRule['coin'])){
			$this->charge($id,'coin',$downRule['coin']);
		}		
		import('JYmusic.HttpDown');
		$object = new \HttpDown();	
		if (!empty($extend['disk_url'])){
			$object->set_byurl($extend['disk_url']);
		}else{
			if(!empty($extend['down_url'])){
				$file 	= $extend['down_url'];
			}else{
				$file 	= $extend['listen_url'];
			}
			if ($server_id > 0 || 'http://' === substr($file, 0, 7)){
				//远程地址
				$server = get_server($server_id);
				$url 	= $server['down_url'].$file;		
				$object->set_byurl($url);
			}else{
				$name	= !empty($data[3])? $data[2].'-'.$data[3] : $data[2];
				//本地文件				
				$file 	= '.'.$file;	
				$ext	=  '.'.pathinfo($file, PATHINFO_EXTENSION);
				$object->mime	= "audio/mpeg"; 
				$object->set_byfile($file);
				$object->filename = $name .$ext;				
			}
					
		}

		//记录下载信息 
		if ($uid = is_login()){
			$model		= M("UserDown");
			$down['uid'] 		= $uid ;
			$down['music_id']	= $id; 
			$downCount	= $model->where($down)->getField('count');
			
			if ( $downCount > 0){
				$data['user_ip'] 	= get_client_ip(1);
				$data['create_time']= NOW_TIME;
				$data['count']		= ++$downCount; 
				$model->where($down)->save($data);
			}else{
				$down['user_ip'] 	= get_client_ip(1);
				$down['create_time']= NOW_TIME;
				$down['count']		= 1; 
				M("UserDown")->add($down);
			}
		}
		//增加下载数
		M("Songs")->where(array('id'=>$id))->setInc('download'); 
		$object->download();
				
	} 
	
    /*下载检测*/   
    public function check ($id=0) {
    	$id 		= (int)$id;			
		if ($id && IS_POST){   
			$smodel 	= M('Songs');		
			$song = $smodel->field('id,name,server_id,artist_name,coin,score')->find($id);
			if (empty($song)){
				$this->error('你下载的歌曲不存在');
			}	
			$down 	= M('SongsExtend')->where(array('mid'=>$id))->field('down_rule,disk_url,disk_pass')->find(); 			
			
			$downRule	= json_decode($down['down_rule'],1);

			$uid 		= is_login();		
			$umodel 			= M('Member');		
			$downCoin			= intval($downRule['coin']);
			$return['status']	= 1;			
			
			//检测是否需要登录下载
			if ($downRule && !empty($downCoin) && ($downCoin > 0 || !in_array('0',$downRule['group']))){
				if(!$uid){
					$this->error('你还没有登录请登录');
				}
				
				//检测用户所在用户组
				$groups	  	= M('MemberGroupLink')->where(array('uid'=>$uid))->getField('group_id',true);
				$groups[] 	= '1';
				
				$in 		= array_intersect($downRule['group'],$groups);
				if(empty($in)){
					//定义用户组
					$gtext = array(
						0 => '游客',
						1 => '普通会员',
						2 => 'VIP会员',
						3 => '认证音乐人'
					);
					$str = "";
					foreach ($downRule['group'] as $v){
						$str .= $gtext[$v].'、';
					}
					$str = rtrim($str,'、');				
					$this->error('抱歉，你所在用户组无权限下载，下载需要【'.$str.'】权限');
				}
				
				if ($downCoin > 0){
					$userCoin 	= $umodel ->getFieldByUid($uid ,'coin');
					$info		= '下载音乐【'.$song['name'].'】,需要金币'.$downCoin;
								
					if ($userCoin >= $downCoin){
						//提示用户需要金币下载
						$return['status']	= 2;
						$return['info']		.= $info.',是否确认购买【24小时不重复扣费】';
					}else{
						//提示用户金币不足
						$this->error($info.'</br>你的金币不足无法购买.<a href="'.U('/user/account/charge').'">[充值金币]</a>');
					}				
				}			
				
			}
			if (!empty($down['disk_url'])){
				$return['disk_pass']	= $down['disk_pass'];
			}
			$key 				= jy_encrypt($song['id'].'/'.$song['server_id'].'/'.$song['name'].'/'.$song['artist_name']);				
			$return['down_url'] = U('/down/song?auth='.$key);
			$this->ajaxReturn($return);
		
   		}else{
   			$this->error('你访问的页面不存在！');		
   		}
    }
	
	//执行扣费
	protected function charge($sid=0,$type='coin',$num){
		if ($num < 1 || !intval($sid) || !$uid	= is_login()){
			$thsi->error('参数错误');
		}
		//检测歌曲是否所需积分
		$umodel 	= M('Member');
		//24小时内下载不扣积分
		$map['uid'] 		= $uid ;
		$map['music_id'] 	= $sid;
		$udata 	= M("UserDown")->where($map)->field('create_time')->order('create_time asc')->find();					
		$time	= intval($udata['create_time'])+ (24*60*60);	
		if ($time > time()){		
			return true;
		}
		//检测歌曲是否所需积分或金币下载
		if ($type == 'coin'){
			$userCoin = $umodel ->getFieldByUid($uid ,'coin');//获取该用户积分
			if ( intval($userCoin) >= $num ){//检测积分
				$umodel->where(array('uid'=>$uid))->setDec('coin',$num); 
   				return true;
			}else{
				$this->error('金币不足无法下载,你当前的金币为:'.$userCoin);	    				
			}
			
		}else{
			if ($score > 0 ){//积分下载
				$userScore = $umodel ->getFieldByUid($uid ,'score');//获取该用户积分
				if ( intval($userScore) >= $num ){//检测积分;	
					$umodel->where(array('uid'=>$uid))->setDec('score',$num);
					return true;    				
				}else{
					$this->error('积分不足无法下载,你当前的积分为:'.$userScore);	    				
				}
			}		
		}
	}

	public function _empty($method) {
		$method=(int)$method;
		if ($method){
			$this->index($method);
		}else{
    		$this->error('页面出错');
    	}
	}	
    
}