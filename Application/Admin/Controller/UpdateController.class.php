<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2016 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+


namespace Admin\Controller;
use OT\File;

/**
 * 在线更新
 */
class UpdateController extends AdminController{

	/**
	 * 初始化页面
	 */
	public function index(){
		$this->meta_title = '在线更新';
		$data = check_update();
		if(IS_POST){
			$this->display();
			$this->update($data['update_time'],$data['app_version'],$data['url']);
		}else{
			$this->assign('data',$data);
			$this->display();
		}	
	}


	/**
	 * 在线更新
	 */
	private function update($uptime,$version,$updatedUrl){
		//PclZip类库不支持命名空间
		import('OT/PclZip');

		$date  			= date('YmdHis');
		$backupFile 	= I('post.backupfile');
		$backupDatabase = I('post.backupdatabase');
		sleep(1);

		$this->showMsg('JYmusic更新日志：');
		$this->showMsg('更新开始时间:'.date('Y-m-d H:i:s'));
		sleep(1);

		/* 建立更新文件夹 */
		$folder = $this->getUpdateFolder();
		File::mk_dir($folder);
		$folder = $folder.'/'.$date;
		File::mk_dir($folder);

		//备份重要文件
		if($backupFile){
			$this->showMsg('开始备份重要程序文件...');
			G('start1');
			$backupallPath = $folder.'/backupall.zip';
			$zip = new \PclZip($backupallPath);
			$zip->create('Application,ThinkPHP,.htaccess,admin.php,index.php');
			$this->showMsg('成功完成重要程序备份,备份文件路径:<a href=\''.__ROOT__.$backupallPath.'\'>'.$backupallPath.'</a>, 耗时:'.G('start1','stop1').'s','success');
		}

		//下载并保存
		$this->showMsg('开始获取远程更新包...');
		sleep(1);
		//$this->showMsg($updatedUrl);
		$zipPath = $folder.'/update.zip';
		$downZip = $this->getRemoteUrl($updatedUrl);
		if(empty($downZip)){
			$this->showMsg('下载更新包出错，请重试！', 'error');
			exit;
		}
		File::write_file($zipPath, $downZip);
		$this->showMsg('获取远程更新包成功,更新包路径：<a href=\''.__ROOT__.ltrim($zipPath,'.').'\'>'.$zipPath.'</a>', 'success');
		sleep(1);

		/* 解压缩更新包 */ //TODO: 检查权限
		$this->showMsg('更新包解压缩...');
		sleep(1);
		$zip = new \PclZip($zipPath);
		$res = $zip->extract(PCLZIP_OPT_PATH,'./');
		
		if($res === 0){
			$this->showMsg('解压缩失败：'.$zip->errorInfo(true).'------更新终止', 'error');
			exit;
		}
		$this->showMsg('更新包解压缩成功', 'success');
		sleep(1);

		/* 更新数据库 */
		$updatesql = './update.sql';
		if(is_file($updatesql)){
			$this->showMsg('更新数据库开始...');
			if(file_exists($updatesql)){
				$Model = M();
				$sql = File::read_file($updatesql);
				$sql = str_replace("\r\n", "\n", $sql);
				foreach(explode(";\n", trim($sql)) as $query){
					$prefix = C('DB_PREFIX');
					$query = str_replace('jy_',$prefix,trim($query));
					$Model->execute($query);
				}
			}
			unlink($updatesql);
			$this->showMsg('更新数据库完毕', 'success');
		}

		/* 系统版本号更新 */
		$model	= M('Config');
			
		$uptime	= $model->where(array('name' =>'JYMUSIC_UPDATE_TIME'))->setField('value',$uptime);
		$version= $model->where(array('name' =>'JYMUSIC_VERSION'))->setField('value',$version);

		if($uptime){
			$this->showMsg('系统版本号更新成功', 'success');
		}else{
			$this->showMsg('系统版本更新失败', 'danger');
		}
		sleep(1);
		
		//自动清理缓存
		$dirname = './Runtime/';
		//清文件缓存
		$dirs	=	array($dirname);		
		//清理缓存
		foreach($dirs as $value) {
			rmdirr($value);			
		}
		@mkdir($dirname,0777,true);
		$this->showMsg('缓存清理完毕！', 'success');	
		sleep(1);

		$this->showMsg('----------------------------------------------------------------------------');
		$this->showMsg('在线更新全部完成，如有备份，请及时将备份文件移动至非web目录下！', 'success');
		
	}
	/**
	 * 获取远程数据
	 * @author huajie <banhuajie@163.com>
	 */
	private function getRemoteUrl($url = '', $method = '', $param = ''){
		$opts = array(
			CURLOPT_TIMEOUT        => 20,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL            => $url,
			CURLOPT_USERAGENT      => $_SERVER['HTTP_USER_AGENT'],
		);
		if($method === 'post'){
			$opts[CURLOPT_POST] = 1;
			$opts[CURLOPT_POSTFIELDS] = $param;
		}

		/* 初始化并执行curl请求 */
		$ch = curl_init();
		curl_setopt_array($ch, $opts);
		$data  = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);
		return $data;
	}

	/**
	 * 实时显示提示信息
	 */
	private function showMsg($msg, $class = ''){
		echo "<script type=\"text/javascript\">showmsg(\"{$msg}\",\"{$class}\")</script>";
		flush();
		ob_flush();
	}

	/**
	 * 生成更新文件夹名
	 */
	private function getUpdateFolder(){
		$key = sha1(C('DATA_AUTH_KEY'));
		if (!file_exists('./Data/Update')){ 
			mkdir ('./Data/Update');
		}; 
		return './Data/Update/update_'.$key;
	}


	/**
	 * Ajax检查新版本升级
	 */
	function ajaxCheck(){
		if(extension_loaded('curl')){
			$data = check_update();		
			$this->ajaxReturn($data);
		}else{
			$this->error('程序无法自动升级,请配置支持curl');
		}
	}

}

