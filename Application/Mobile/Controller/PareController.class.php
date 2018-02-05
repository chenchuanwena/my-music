<?php
// +----------------------------------------------------------------------
// | JYmusic [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015 http://www.my-music.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: JYmusic <31435391.qq.com> <http://www.my-music.cn>
// +----------------------------------------------------------------------
namespace Mobile\Controller;

/**
 * 电台页面
 */
class PareController extends MobileController{
	
	//电台首页
	public function play($k=""){
		$urls = $this->getUrls($k);
		
		
		if (empty($urls)){
			$keys = explode('-',$k);
			$urls = $this->getUrls($keys[0]);
		}
		foreach ($urls  as &$v){
			if ($v['bitRate'] == 128){
				$b128 = $v['url'];
			}			
			if ($v['bitRate'] == 64){
				$b64  = $v['url'];
			}
		}	
		$url = !empty($b64)? $b64 :  (!empty($b128)? $b128 :$b32);
		header("location:".$url);//转向目标地址		
		die();
	}	
	
	public function lrc($k=null){
		$arr = explode('-',$k);
		$name = $arr[0];
		$artist_name = $arr[0];
		
		$lrcurl = "http://lp.music.ttpod.com/lrc/down?lrcid=&artist={$artist_name}&title={$name}";		
		$lrc = file_get_contents($lrcurl );
		$lrc = json_decode($lrc,true);
		return  $lrc['data']['lrc'];
	}
	
	public function album (){
		$key = '依赖-张靓颖';			
		$url = "http://www.xiami.com/search/album?key={$key}";
		import('JYmusic.Snoopy');
		$snoopy	= new \Snoopy();
		$snoopy->fetch($url);		
		$result = $snoopy->results;
		
		$find	= array('~>\s+<~','~>(\s+\n|\r)~');
		$preg	= '/albumBlock_list">(.*?)'.$endstr.'/';
		preg_match($preg, $results, $str);	
		
		dump($str );
	}
	
	public function lrcs($k=null){
		$arr = explode('-',$k);
		$name = $arr[0];
		$artist_name = $arr[0];		
		$lrcurl = "http://lp.music.ttpod.com/lrc/down?lrcid=&artist={$artist_name}&title={$name}";		
		
		
		$keys = urlencode($k);
		$url = "http://so.ard.iyyin.com/s/song_with_out?q={$keys}&page=1&size=2";
		
		$code 			= file_get_contents($url);			
		$code 			= json_decode($code,true);

		return  $lrc['data']['lrc'];
	}
	
	public function down($k=null){		
		header('Content-type:text/html;charset:utf-8');			
		$urls = $this->getUrls($k);
		if (empty($urls)){
			$keys = explode('-',$k);
			$urls = $this->getUrls($keys[0]);
		}
		
		foreach ($urls  as &$v){
			if ($v['bitRate'] == 320){
				$b320 = $v['url'];
			}			
			if ($v['bitRate'] == 128){
				$b128  = $v['url'];
			}

		}	
		$url = !empty($b320)? $b320 : $b128;		
		
		return $url;
		
		/*header('Content-Description: File Transfer'); 
		header('Content-Type: application/octet-stream');
		header('Content-Transfer-Encoding: binary'); 
		header('Expires: 0'); 
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); 
		header('Pragma: public'); 
		header('Content-Length: '.$length); 
		header('Content-Type:application/force-download');//强制下载
		readfile($url); */	
		//header("location:".$url);//转向目标地址
		//die();
	}
	
	protected function getUrls($k){
		$keys = urlencode($k);
		$url = "http://so.ard.iyyin.com/s/song_with_out?q={$keys}&page=1&size=2";
		/*import('JYmusic.Snoopy');
		$snoopy= new \Snoopy();
		$snoopy->fetch($url);		
		$code = $snoopy->results;*/				
		$code 			= file_get_contents($url);			
		$code 			= json_decode($code,true);
		$songInfo 		= $code['data'][0];
		$audition 		= $songInfo['audition_list'];
				
		if (empty($audition)){//第一页没有 采集第二页
			$audition2 = $code['data'][1]['audition_list'];
			if (!empty($audition2)){
				$audition  = $audition2;
			}			
		}
		return $audition;
	}
		
}
