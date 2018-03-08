<?php
namespace Admin\Controller;

/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */
class FileController extends AdminController {
	/**
     * 上传歌曲
     * 
    */
    public function uploadMusic(){

        /* 返回标准数据 */
        $return		= array('status' => 1, 'info' => '上传成功', 'data' => '');

        /* 调用文件上传组件上传文件 */
        $file		= D('File');
		$adminCon	= C('MUSIC_UPLOAD');
		
		/*驱动配置项*/	
        $up_driver 	= C('MUSIC_UPLOAD_DRIVER');
		$driverConf = C("UPLOAD_{$up_driver}_CONFIG");

		$adminCon['maxSize'] 	= trim(C('ADMIN_UPMUSIC_MAX'));
		$adminCon['exts'] 		= trim(C('ADMIN_UPMUSIC_EXTS'));
			
		$info = $file->upload(
            $_FILES,
            $adminCon,
            $up_driver,
            $driverConf
        );
		//TODO:上传到远程服务器
				
        /* 记录信息 */
        if($info){
        	$return = array_merge($info['file'], $return);
        } else {
            $return['status'] = 0;
            $return['info']   = $file->getError();
        }		
        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }

    /* 文件上传 */
    public function upload(){
		$return  = array('status' => 1, 'info' => '上传成功!', 'data' => '');
		/* 调用文件上传组件上传文件 */
		$File			= D('File');
		$file_driver	= C('DOWNLOAD_UPLOAD_DRIVER');
		$info			= $File->upload(
			$_FILES,
			C('DOWNLOAD_UPLOAD'),
			C('DOWNLOAD_UPLOAD_DRIVER'),
			C("UPLOAD_{$file_driver}_CONFIG")
		);

        /* 记录附件信息 */
        if($info){
            $return['data'] = think_encrypt(json_encode($info['download']));
            $return['info'] = $info['download']['name'];
        } else {
            $return['status'] = 0;
            $return['info']   = $File->getError();
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
    public function uploadPicture(){





        //TODO: 用户登录检测
		$type		= intval(I('get.type'));
        /* 返回标准数据 */
        $return		= array('status' => 1, 'info' => '上传成功', 'data' => '');
        /* 调用文件上传组件上传文件 */
        $Picture 		= D('Picture');
        $pic_driver 	= C('PICTURE_UPLOAD_DRIVER');
       	$adminCon 		= C('PICTURE_UPLOAD');
		$adminCon['rootPath'] 	=  './'. ltrim(C('ADMIN_UPPIC_PATH'), './');
		$adminCon['maxSize'] 	= trim(C('ADMIN_UPPIC_MAX'));
		$adminCon['exts']		= trim(C('ADMIN_UPPIC_EXTS'));
        $info = $Picture->upload(
            $_FILES,
            $adminCon,
            C('PICTURE_UPLOAD_DRIVER'),
            C("UPLOAD_{$pic_driver}_CONFIG"),
			$type 
        ); //TODO:上传到远程服务器
        /* 记录图片信息 */ 
           
        if($info){
        	$return['status'] = 1;
        	$return = array_merge($info['file'], $return);           
        } else {
            $return['status'] = 0;
            $return['info']   = $Picture->getError();
        }
        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }
	   

}
