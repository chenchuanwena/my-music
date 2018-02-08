<?php
namespace Admin\Model;
use Think\Model;
use Think\Upload;
use OT\File;

/**
 * 文件模型
 * 负责文件的下载和上传
 */

class FileModel extends Model{
    /**
     * 文件模型自动完成
     * @var array
     */
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );

    /**
     * 文件模型字段映射
     * @var array
     */
    protected $_map = array(
        'type' => 'mime',
    );

    /**
     * 文件上传
     * @param  array  $files   要上传的文件列表（通常是$_FILES数组）
     * @param  array  $setting 文件上传配置
     * @param  string $driver  上传驱动名称
     * @param  array  $config  上传驱动配置
     * @return array           文件上传成功后的信息
     */
    public function upload($files, $setting, $driver = 'Local' , $config = null){
		 /* 上传文件 */
        $setting['callback']	= array($this, 'isFile');
		$setting['removeTrash'] = array($this, 'removeTrash');
		
		if( $driver == 'ftp') {	
			$setting['rootPath'] 	= './';
		}
		if( $driver == 'qiniu') {	
			$setting['rootPath'] 	= './';
			$setting['subName'] = null ;
		}

        $Upload = new Upload($setting, $driver, $config);
        $info   = $Upload->upload($files);
		/* 设置文件保存位置 */
		$this->_auto[] = array('location', 'local' === strtolower($driver) ? 0 : $config['server_id'], self::MODEL_INSERT);
		
        if($info){ //文件上传成功，记录文件信息
			foreach ($info as $key => &$value) {
                /* 已经存在文件记录 */
                if(isset($value['id']) && is_numeric($value['id'])){
					$value['server_id'] = $value['location'];
					
                    $value['path'] = ltrim($value['savepath'], '.').$value['savename']; //在模板里的url路径
                    continue;
                }
				/* 记录文件信息 */
				$value['savepath'] 	= $setting['rootPath'].$value['savepath'];
				$value['path'] 		= $value['savepath'].$value['savename']; 
				$value['path'] 		= ltrim($value['path'], '.');//在模板里的url路径
				$value['server_id'] = $config['server_id'];
				oss_upload($value['path']);
				$value['path']=C('OSS_DOMAIN').ltrim($value['path'], '.');
				$value['savepath']=C('OSS_DOMAIN').ltrim($value['savepath'], '.');
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
        }
    }

    /**
     * 下载指定文件
     * @param  number  $root 文件存储根目录
     * @param  integer $id   文件ID
     * @param  string   $args     回调函数参数
     * @return boolean       false-下载失败，否则输出下载文件
     */
    public function download($root, $id, $callback = null, $args = null){
        /* 获取下载文件信息 */
        $file = $this->find($id);
        if(!$file){
            $this->error = '不存在该文件！';
            return false;
        }

        /* 下载文件 */
        switch ($file['location']) {
            case 0: //下载本地文件
                $file['rootpath'] = $root;
                return $this->downLocalFile($file, $callback, $args);
			case 1: //下载FTP文件
				$file['rootpath'] = $root;
				return $this->downFtpFile($file, $callback, $args);
                break;
            default:
                $this->error = '不支持的文件存储类型！';
                return false;

        }
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
		$map 	= array('md5' => $file['md5'],'sha1'=>$file['sha1']);
		$file 	= $this->field(true)->where($map)->find();
		if (!empty($file)){
			$file['ishave'] = 1;
		}		
		return $file;
    }
		

    /**
     * 下载本地文件
     * @param  array    $file     文件信息数组
     * @param  callable $callback 下载回调函数，一般用于增加下载次数
     * @param  string   $args     回调函数参数
     * @return boolean            下载失败返回false
     */
    private function downLocalFile($file, $callback = null, $args = null){
        if(is_file($file['rootpath'].$file['savepath'].$file['savename'])){
            /* 调用回调函数新增下载数 */
            is_callable($callback) && call_user_func($callback, $args);

            /* 执行下载 */ //TODO: 大文件断点续传
            header("Content-Description: File Transfer");
            header('Content-type: ' . $file['type']);
            header('Content-Length:' . $file['size']);
            if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) { //for IE
                header('Content-Disposition: attachment; filename="' . rawurlencode($file['name']) . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
            }
            readfile($file['rootpath'].$file['savepath'].$file['savename']);
            exit;
        } else {
            $this->error = '文件已被删除！';
            return false;
        }
    }

	/**
	 * 下载ftp文件
	 * @param  array    $file     文件信息数组
	 * @param  callable $callback 下载回调函数，一般用于增加下载次数
	 * @param  string   $args     回调函数参数
	 * @return boolean            下载失败返回false
	 */
	private function downFtpFile($file, $callback = null, $args = null){
		/* 调用回调函数新增下载数 */
		is_callable($callback) && call_user_func($callback, $args);

		$host = C('DOWNLOAD_HOST.host');
		$root = explode('/', $file['rootpath']);
		$file['savepath'] = $root[3].'/'.$file['savepath'];

		$data = array($file['savepath'], $file['savename'], $file['name'], $file['mime']);
		$data = json_encode($data);
		$key = think_encrypt($data, C('DATA_AUTH_KEY'), 600);

		header("Location:http://{$host}/onethink.php?key={$key}");
	}

	/**
	 * 清除数据库存在但本地不存在的数据
	 * @param $data
	 */
	public function removeTrash($data){
		$this->where(array('id'=>$data['id'],))->delete();
	}
	
	/**
	 * 删除文件
	 * @param $id
	 */
	
	public function delFile($fileId) {
		$map['id'] = $fileId;
    	$file = $this->where($map)->find();
    	$path = $file['savepath'].$file['savename'];
    	if(is_file($path)){
    		if(unlink($path)){
    			$this->where($map)->delete();
    		}
    	}
    }
    
    /**
    * 读取配置文件
    **/
    
    public function readConfig($path) {    
    	return File::read_file($path);
    }
    
    /**
    * 写入配置文件
    **/    
    public function writeConfig($name,$text) {  
    	$path ='./Data/Conf/'.$name.'.php'; 
    	return File::write_file($path,$text);
    }

}
