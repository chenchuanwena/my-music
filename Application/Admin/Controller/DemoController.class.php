<?php

namespace Admin\Controller;
/**
 * 前台标签控制器
 */
class DemoController extends AdminController {

    public function index (){
		header("Content-type: text/html; charset=utf-8");
		/*$map['mid'] = array('lt',211);
		M('SongsExtend')->where($map)->setField('down_url','');
		dump('haol '); exit;*/
		//baidu api
		
		/*$keys = "光明";
		$url = "http://sug.music.baidu.com/info/suggestion?format=json&word={$key}&version=2&from=0";
				
		$songId = 242263441;
		
		$albumId = 10518186;*/
		
		
		//试听 api 需要上面获取歌曲id   128

		/*$rate = array(32,64,128,192,320,835);
		$ra = $rate[4];
		$url = "http://music.baidu.com/data/music/fmlink?songIds={$songId}";
		
		//下载api接口
		$url = "http://yinyueyun.baidu.com/data/cloud/downloadsongfile?songIds={$songId}&type=mp3&rate=128&format=128";
		$keys = "孙露";
		$keys = urlencode($keys);
		//$url = "http://so.ard.iyyin.com/s/song_with_out?q={$keys}&page=1&size=2";
		/*import('JYmusic.Snoopy');
		$snoopy= new \Snoopy();
		$snoopy->fetch($url); 
		$code = $snoopy->results;*/
		
		/*$keys = "光明";
		$keys = urlencode($keys);
		$coverurl = "http://lp.music.ttpod.com/pic/down?album={$keys}";
		
		$url = $coverurl;
		
		$code = file_get_contents($url);	
		$code = json_decode($code,true);

		dump($code);*/
		$this->display();
	}
	
	
	
	public function add($keys = null,$genre_id=null){
		header("Content-type: text/html; charset=utf-8");
		$keys = trim($keys);
		if (empty($keys)){			
			$this->error('没有填写查找的歌曲名称');
		}
		$keys = urlencode($keys);
		
		//天天动听api
		$url = "http://so.ard.iyyin.com/s/song_with_out?q={$keys}&page=1&size=2";
		
		/*import('JYmusic.Snoopy');
		$snoopy= new \Snoopy();
		$snoopy->fetch($url);		
		$code = $snoopy->results;*/
				
			$code = file_get_contents($url);
			$code = json_decode($code,true);
			$songInfo 		= $code['data'][0];
			$songId		= $songInfo['song_id'];
			$name			= $songInfo['song_name'];
			$artist_name 	= $songInfo['singer_name'];
			$audition 		= $songInfo['audition_list'];
						
			if (empty($audition)){//第一页没有 采集第二页
				$audition2 = $code['data'][1]['audition_list'];
				if (!empty($audition2)){
					$audition  = $audition2;
				}else{
					$this->error("没有搜索歌曲地址信息 ");	
				}				
			}

			$music = array (
				'server_id'		=> 28,
				'genre_id'		=> $genre_id,
				'name' 			=> $name,
				'artist_name'	=> $artist_name,
				'album_name'	=> $songInfo['album_name'],		
				'file_size'		=> $audition[0]['size'],
				'play_time'		=> $audition[0]['duration'],
				'bitrate'		=> 320,
				'listen_url'	=> $songId	
			);
						
			/*foreach ($audition  as &$v){
				if ($v['bitrate'] == 128){
					$music['down_url'] = $v['url'];
				}
				if ($v['bitrate'] == 320 ){
					$music['down_url'] = $v['url'];
				}
				if ($v['bitrate'] == 128){
					$music['listen_url'] = $v['url'];
				}			
				if ($v['bitrate'] == 64){
					$music['listen_url'] = $v['url'];
				}
				if ($v['bitrate'] == 32){
					$music['listen_url'] = $v['url'];
				}
			}*/
			
			//获取图片地址
			$artist_name = urlencode($artist_name);
			$coverurl = "http://lp.music.ttpod.com/pic/down?artist={$artist_name}";
			$cover = file_get_contents($coverurl);
			$cover = json_decode($cover,true);	

			$music['cover_url'] = $music['album_cover'] = $music['artist_cover'] = $cover['data']['singerPic'];			
			
			
			$name = urlencode($name);

			$res = D('Admin/Songs')->update($music);
			if ($res){
				$this->success('入库成功');
			}else{
				$this->error(D('Admin/Songs')->getError());
			}

    }	
       
}