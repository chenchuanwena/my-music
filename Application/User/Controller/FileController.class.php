<?php
// +----------------------------------------------------------------------
// | JYmusic
// +----------------------------------------------------------------------
namespace User\Controller;
use Think\Image;
/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */
class FileController extends UserController {

    /* 文件上传 */
    public function upload(){
    	header("Content-Type:text/html;charset=UTF-8");	
    	$sessionId 	= I('get.session_id');
    	$return  	= array('status' => 1, 'info' => '上传成功', 'data' => '');
    	//检测用户上传锁  	
    	$upnum 		= session('user_{UID}_upnum');
		if( !empty($upnum) && $upnum >= 3){
			$return['status'] = 0;
            $return['info']   = '频繁上传，系统已锁，请24小时后再次上传！';        
        }elseif ($sessionId && $sessionId === session_id()){//防止恶意上传
        	//创建锁文件
	    	$map['uid'] = UID;
			/* 调用文件上传组件上传文件 */
			$File 		= D('Admin/File');
			$driver 	= C('USER_MUSICUP_DRIVER');
			$userCon 	= C('USER_UPLOAD');
			$userCon['rootPath']  	=  './'. ltrim(C('USER_UPMUSIC_PATH'),'./');	
			$userCon['maxSize'] 	= trim(C('USER_UPMUSIC_MAX'));
			$userCon['exts'] 		= trim(C('USER_UPMUSIC_EXTS'));

			$info = $File->upload(
				$_FILES,
				$userCon,
				$driver,
				C("UPLOAD_{$driver}_CONFIG")
			);
	        /*记录信息*/
	        if($info){
	        	$file = array_merge($info['file'], $return);
				if($file['ishave']){	        		
	        		$map['listen_file_id'] 	= $return['id'];
	        		$map['down_file_id'] 	= $return['id'];
	        		$map['_logic'] = 'OR';
	        		$data = M('SongsExtend')->where($map)->field('mid')->find();
	        		if (!empty($data)){ //表示重复上传已歌曲储存
						$return['status'] = 0;
	           			$return['info']   = '文件已存在,<a style="color:#545ca6" target="_blank" href="'.U('/music/'.$data['mid']).'">查看音乐详细信息！</a>';	 
	           		}         		
	           	}else{
					$return['status'] = 1;
					//记录单个分享上传数
					$time= intval(C('USER_UP_ERROR_TIME'));			
					session('user_{UID}_upnum',$upnum+1,$time);
					//记录上传的文件ID
					session('user_{UID}_upfile_id',$file['id']);					
					//记录用户上传
					/*$data['uid'] 		= UID;
					$data['uname'] 		= get_nickName(UID);
					$data['user_ip'] 	= ip2long(get_client_ip());
					$data['file_id'] 	= $return['id'];
					$data['create_time']= NOW_TIME;										
					M('UserUpload')->add($data);*/
	           	}				
				$return['file_id'] = $file['id'];
	            
	        } else {
	            $return['status'] = 0;
	            $return['info']   = $File->getError();
	        }        
		}else{
			$return['status'] = 0;
	        $return['info']   = '非法请求';
		}

		/* 返回JSON数据 */	
		$this->ajaxReturn($return);
    }

    /* 下载文件 */
    public function download($id = null){
        if(empty($id) || !is_numeric($id)){
            $this->error('参数错误！');
        }

        $logic = D('Download', 'Logic');
        if(!$logic->download($id)){
            $this->error($logic->getError());
        }

    }

    /**
     * 上传图片
    */
    /* 文件上传 */
    public function uploadPic($type=null){
    	$sessionId = I('get.session_id');
    	$type = (int)I('get.type');   	
    	$return  = array('status' => 1, 'info' => '上传成功', 'data' => '');    	
	    if ($sessionId && $sessionId === session_id()){//防止恶意上传
	    	//$map['uid'] = UID;
	    	//$time = M('UserUp')->where($map)->field('add_time')->find(); 
			/* 调用文件上传组件上传文件 */
			$Picture 		= D('Picture');
			$file_driver 	= C('USER_PICUP_DRIVER');
			$userCon = C('USER_UPLOAD');
			if ($type == 5){ //用户头像
				//先清空当前用户的头像缓存				
				$file_driver  = "local";
	        	$userCon['rootPath'] = './Uploads/Avatars/';
	        	$userCon['subName']  = 'uid_'.UID;
	        	$userCon['saveName'] = '128';
	        	$userCon['replace'] = true;
	        }else{
				$userCon['rootPath'] = trim(C('USER_UPPIC_PATH'));
			}
			$userCon['maxSize'] = trim(C('USER_UPPIC_MAX'));
			$userCon['exts'] 	= trim(C('USER_UPPIC_EXTS'));
			$info = $Picture->upload(
				$_FILES,
				$userCon,
				C('USER_PICUP_DRIVER'),
				C("UPLOAD_{$file_driver}_CONFIG"),
				$type
			);
	        /* 记录信息 */
	        if($info){
	            $return['status'] 	= 1;
	            //$return['path'] 	= $info['file']['path'];
	            $return['file_id']	= $info['file']['id'];
				$return['id']		= $info['file']['id'];
	        } else {
	            $return['status'] = 0;
	            $return['info']   = $Picture->getError();
	        }        
		}else{
			$return['status'] = 0;
	        $return['info']   = '非法请求';
		}
		/* 返回JSON数据 */	
		$this->ajaxReturn($return);
    }
}