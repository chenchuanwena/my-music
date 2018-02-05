<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;
use Think\Upload;

/**
 * 图片模型
 * 负责图片的上传
 */

class PictureModel extends Model{
    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );

    /**
     * 文件上传
     * @param  array  $files   要上传的文件列表（通常是$_FILES数组）
     * @param  array  $setting 文件上传配置
     * @param  string $driver  上传驱动名称
     * @param  array  $config  上传驱动配置
     * @return array           文件上传成功后的信息
     */
    public function upload($files, $setting, $driver = 'Local', $config = null,$type=0){
        /* 上传文件 */
        $setting['callback'] 	= array($this, 'isFile');
		$setting['removeTrash'] = array($this, 'removeTrash');
		$setting['imgCall'] 	= array($this, 'handelImg');
		$setting['imgCallType'] = $type;
		$Upload = new Upload($setting, $driver, $config);
        $info   = $Upload->upload($files);
		/* 设置文件保存位置 */
		$this->_auto[] = array('location', 'local' === strtolower($driver) ? 0 : $config['server_id'], self::MODEL_INSERT);

        if($info){ //文件上传成功，记录文件信息			
			foreach ($info as $key => &$value) {
				/* 已经存在文件记录 */
                if(isset($value['id']) && is_numeric($value['id'])){
					$value['server_id'] = $value['location'];					
                    $value['path']		= ltrim($value['path'], '.'); //在模板里的url路径
                    continue;
                }
				/* 记录文件信息 */
				$value['savepath'] 	= $setting['rootPath'].$value['savepath'];  				
				$value['path'] 		= $value['savepath'].$value['savename']; 
				$value['path'] 		= ltrim($value['path'], '.');//在模板里的url路径
				$value['server_id'] = $config['server_id'];
				$value['type'] 		= $type;
				oss_upload($value['path']);
				if($this->create($value) && ($id = $this->add())){
					$value['id'] = $id;
				} else {
					//TODO: 文件上传成功，但是记录文件信息失败，需记录日志
					unset($info[$key]);
			   }
				
			}
            return $info; //文件上传成功
        } else {
            $this->error = $Upload->getError();
            return false;
        }
    }
	 /**
     * 处理图片
     * @param  array   $file 文件上传数组
	 * @param  array   $type 要处理的图片类型
     * @return       	返回处理后的文件
     */
    public function handelImg($file,$type){
		//dump($file); die;
		$img	=  $file['tmp_name'];
		switch ($type){
			case 1:
			  $size = trim(C('SONG_COVER_SIZE'));
			  break;
			case 2:
			  $size = trim(C('ARTIST_COVER_SIZE'));
			  break;
			case 3:
			  $size = trim(C('ALBUM_COVER_SIZE'));
			  break;
			case 4:
			  $size = trim(C('ESSAY_COVER_SIZE'));
			  break;
			default:
			  return $file;
		}
		$size = explode(",",$size);
		$image = new \Think\Image(); 
		$image->open($img);// 生成一个固定大小为150*150的缩略图并保存为thumb.jpg
		$image->thumb($size['0'],$size['1'],\Think\Image::IMAGE_THUMB_FIXED)->save($img);        
		return $file;
    }


    /**
     * 检测当前上传的文件是否已经存在
     * @param  array   $file 文件上传数组
     * @return boolean       文件信息， false - 不存在该文件
     */
    public function isFile($file){
        if(empty($file['md5'])){
            throw new \Exception('缺少参数:md5');
        }
        /* 查找文件 */
		$map = array('md5' => $file['md5'],'sha1'=>$file['sha1'],);
        return $this->field(true)->where($map)->find();
    }


	/**
	 * 清除数据库存在但本地不存在的数据
	 * @param $data
	 */
	public function removeTrash($data){
		$this->where(array('id'=>$data['id'],))->delete();
	}

}
