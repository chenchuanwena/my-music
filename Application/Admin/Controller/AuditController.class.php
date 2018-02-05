<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2016 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Admin\Controller;

/**
 * 后台音乐审核控制器
 */
class AuditController extends AdminController {
	public function index(){
		$map['status'] = 2;
        $list = $this->lists('Songs',$map,'id desc','id,name,up_uid,up_uname,album_name,genre_name,artist_name,add_time,status');   	
    	$this->assign('list',$list);
        $this->meta_title = '待审列表';
        $this->display();
    }
    

	//驳回
    function disable () {
    	if(IS_POST){
    	 	$id    =   I('post.id');
    	 	$uid   =   I('post.uid');
    	 	$map['id'] = $id;	 
    	 	//删除表中数据 
    	 	$res = M('Songs')->where($map)->delete();
			if ($res){
				$model	= M('SongsExtend');
				$song	= $model()->field('server_id,listen_file_id,down_file_id')->select();
				$lid	= $song['listen_file_id'];				
				if ($lid){ 
					$map['id'] 	= $lid;
					$path		= $file->where($map)->field('savepath,savename')->find();
					$path 		= $path['savepath'].$path['savename'];
					if($path && file_exists($path)){
						unlink($path);
					}           	
					$file->where($map)->delete();            
				}
				$model->where(array('mid'=>$id ))->delete(); 
				//发送通知
				$title = '歌曲审核通知';
				$content = '你上传的音乐['.$list['name'].']未通过审核,请重新上传！';											
				D('Notice')->send($uid,$title,$content);
				$this->success('操作成功');				
			}else{
				$this->error('驳回失败,基础数据删除失败');
			}

    	}else{	
    		$this->error('非法请求');
    	}
    
	}
    
    //通过审核
    public function pass () {    	    	
    	if(IS_POST){
    	 	$id    	=  I('post.id');
    	 	$uid   	=  I('post.uid');

			M('Songs')-> where(array('id'=>$id))->setField('status',1);
	    	//M('UserUpload')->where(array($map))->save($up);
	        M('Member')->where(array('uid'=>$uid))->setInc('songs',1);  //增加会员添加歌曲数量  
	        //发送通知
			$title = '歌曲审核通知';
			$content = '你上传的音乐['.$up['music_name'].']成功通过审核！';
			D('Notice')->send($uid,$title,$content);                   
			$this->success('操作成功');
    	
    	}else{
    	
    		$this->error('非法请求');
    	}
    } 	
	public function listen ($id){
		$Songs = M('Songs');
		$map['id']=$id;
		$list = $Songs->alias('a')->join('LEFT JOIN __SONGS_EXTEND__ b ON a.id= b.mid')->field('a.server_id,a.artist_name,a.name,b.listen_url')->find($id);
		if ($list['server_id'] == 28){
			$server = get_server($list['server_id']);
			$key	= $list['name'].'-'.$list['artist_name'];
			$list['music_url'] 	= html_entity_decode($server['listen_url'].'?k='.$key.'.ogg');

		}else{
			$server = get_server($list['server_id']);				
			$list['music_url'] = html_entity_decode($server['listen_url'].$list['listen_url']);					
		}
		unset($list['listen_url']);
		$this->ajaxReturn($list); 
	
	}
}
