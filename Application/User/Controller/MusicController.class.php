<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace User\Controller;
class MusicController extends UserController {
    
   /**
     * 用户的音乐
   */
    public function index(){
    	$this->meat_title = '我的分享 - '.C('WEB_SITE_TITLE');
		$this->display();
    }

   	/**
     * 待审核的音乐
   	*/
    public function audit(){
    	$this->meat_title = '待审核 - '.C('WEB_SITE_TITLE');
		$this->display();
    }
           
    /**
     * 下载过的音乐
     */
    public function down(){
    	$this->meat_title = '我的下载 - '.C('WEB_SITE_TITLE');	
		$this->display();
    }
	       
    //试听记录
    public function listen() {
	    $ListenRecord = cookie('ListenRecord');
		//dump($ListenRecord);
	    if(!empty($ListenRecord)){//判断是否有试听记录
		    $map['id']  = array('in',$ListenRecord);
    		$list = $this->lists('Songs',$map,'id desc','id,name,up_uid,up_uname,artist_id,artist_name,album_id,album_name,genre_name,genre_id,listens,rater,add_time');//获取歌曲数据集
    		$this->assign('list', $list);
    	}    	
    	$this->meat_title = '试听记录 - '.C('WEB_SITE_TITLE');
    	$this->assign('type', 'listen');
    	$this->display();
    }
    
	//试听记录
    public function like() {  	
    	$this->meat_title = '我的喜欢 - '.C('WEB_SITE_TITLE');
    	$this->display();
    }
    /**
     * 用户上传音乐
    */
    public function share($step=1){    	   	
		$model = D('Songs');
		$map['up_uid']		= UID;
		$map['add_time'] 	= array('gt',strtotime(date("Y-m-d")));		
		$share_num			= intval(C('ADD_SONG_NUM'));
		if(!$share_num){
			 $this->error('音乐分享功能暂时关闭！'); 
		}
		$upnum = session('user_{UID}_upnum');
		/*if($upnum >= 3){
            $this->error('频繁上传，系统已锁，请24小时后再次上传！');
        }*/
		$count = $model->where($map)->count();
		if(intval($count) >= $share_num){
			 $this->error('每天只允许分享'.$share_num.'首歌曲'); 
		}		
		if(IS_POST){   
			//清空上传缓存
			session('justup_file',null);
			if(false !== $model->update()){
				//删除锁
				session('user_{UID}_upnum',null);
				//发送审核提醒
	
				$this->success('分享成功，请等待审核...',U('Music/audit'));								
			} else {
				$error = $model->getError();
				$this->error(empty($error) ? '音乐分享失败！' : $error);
			}
		}else{
			$album  = M('Album')->where(array('add_uid'=>UID))->field('id,name')->select();			
			$this->assign('album',$album);
			$this->meat_title = '分享音乐 - '.C('WEB_SITE_TITLE');
			$this->display();	
		}
		
    }   

	/*
	*编辑音乐
	*/
	public function edit(){
		$id			= (int)I('id');
		$map['id']	= $id;
		$map['up_uid']	= UID;
		$model	= D('Songs');
		
		if (!$id || !$data= $model->where($map)->find()){
			$this->error('你编辑的音乐不存在！');		
		}else{
			$extend	 = M('SongsExtend')->where(array('mid'=>$id))->field('lrc,introduce,listen_file_id')->find();
			$data	= array_merge($data,$extend);
		}
		 
		if(IS_POST){
			if(false !== $model->update()){
				$this->success('编辑成功，请等待审核...',U('Music/audit'));								
			} else {
				$error = $model->getError();
				$this->error(empty($error) ? '音乐编辑失败！' : $error);				
			}
		
		}else{
			$this->assign('data',$data);
			$this->display('share');
		}
	}
	
  
    /**
     * 删除
     */     
    public function del(){
     	$id		= I("id");	//用户提交的
     	$type	= I("type");
    	if($id && IS_AJAX && UID)	{ 
    		if('listen' !=$type ){   	
	        	$fav= M("UserMusic"); // 实例化User对象
	        	//$map['model_id'] = 4;  //用户音乐 1-上传 ，2-下载，3-收藏，6-创建专辑
	        	$map['uid']  = UID;
	        	$map['music_id']  = $id;
	        	$map['model_id']  = array('in','1,2,3');	
	        	$data = $fav->where($map)->delete();
	        }else{
	        	$Listen = cookie('ListenRecord');
				foreach ($Listen as $key=>$value){
				    if ($value != $id) $arr[] = $value;
				}
	        	cookie('ListenRecord',$arr);        	 
	        	$data = true;
	        }
        	
        	if ($data){
        		$ajax['status']  = 1;
        		$ajax['info'] = "成功移除！";
        	}else{    		    		
    			$ajax['status']  = 0;
        		$ajax['info'] = "移除失败！";    			
    		}
    		$this->ajaxReturn($ajax);
		}else{ 
			$this->error('非法参数');
		}
    }
}