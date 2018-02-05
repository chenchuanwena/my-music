<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Admin\Controller;
use Think\Controller;
class SongsController extends AdminController {
    public function index($status = null,$title = null,$pos=null){
		$Songs =   D('Songs');
        /* 查询条件初始化 */
        //$map['uid'] = UID;
        if(isset($title)){
            $map['name']   =   array('like', '%'.$title.'%');
        }
        if(isset($status)){
            $map['status']  =   $status;
        }else{
            $map['status']  =   array('in', '0,1');
        }
		if(!empty($pos)){
            $map[] = "position & {$pos} = {$pos}";
        }
        $list = $this->lists($Songs	,$map,'add_time desc','id,name,album_name,artist_name,genre_name,listens,position,download,add_time,status');
        //int_to_string($list);				
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
		$this->assign('positions', C('MUSIC_POSITION'));  
        $this->assign('list', $list);
        $this->meta_title = '歌曲管理';

        $this->display();
	}
		
	public function add(){

		if(IS_POST){
            $model = D('Songs');
			$data = $model->update();
            if(false !== $data){
				$map['uid'] = $data['up_uid'];
				M("Member")->where($map)->setInc('songs',1);//增加上传歌曲数量
                $this->success('新增成功！', U('index'));
            } else {
                $error = $model->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
			$this->assign('positions', C('MUSIC_POSITION'));
			$this->meta_title = '添加歌曲';

			$this->display();
        }
	}
	
	public function mod($id = 0){
        if(IS_POST){
            $model = D('Songs');
            if(false !== $model->update()){
                $this->success('编辑成功！',Cookie('__forward__'));
            } else {
                $error = $model->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $data = array();
            /* 获取数据 */
			$data = M('Songs')->alias('a')->join('LEFT JOIN  __SONGS_EXTEND__ b ON a.id= b.mid')->find($id);
			if(false === $data){
                $this->error('获取后台数据信息错误');
            }
			//解析下载规则
			$data['down_rule'] 	= json_decode($data['down_rule'],1);
			$this->assign('positions', C('MUSIC_POSITION'));  
            $this->assign('data', $data);
			$this->meta_title = '修改歌曲';
			$this->display('add');
        }
	}

	/*批量新增歌曲*/
	public function addall(){
		if(IS_POST){
            $model = D('Songs');			
			$data = $model->update();			
            if(false !== $data){
				$map['uid'] =$data['up_uid'];
				M("Member")->where($map)->setInc('songs',1);//增加上传歌曲数量                
				$return['name'] 	= I('post.name');
				$return['status'] 	= 1;				
				$this->ajaxReturn($return);				
            } else {
                $error = $model->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }			
        } else {
			$this->meta_title = '批量添加歌曲';			
			$this->display();
		}
	}
	
	/**
    * 删除
    */
    public function del(){
        $ids=I('id');
        if ( empty($ids) ) {
            $this->error('请选择要操作的数据!');
        }
        $map = array('id' => array('in', $ids) );
        if(M('Songs')->where($map)->delete()){
            //记录行为
            //action_log('update_channel', 'channel', $id, UID);
            $data['status'] = 1;
            $data['info'] 	= '删除成功';
			$data['url']	= Cookie('__forward__');
        } else {
        	$data['status']  = 0;
            $data['info'] = '删除失败！';
        }
        $this->ajaxReturn($data);
    }
    
    /**
     * 批量推荐位
    */    
   	public function setpos () {
   		if (IS_AJAX){
   			$ids=I('id');
   			$data['position'] =I('position');
   			$data['update_time'] = NOW_TIME;
   			$res = M('Songs')-> where(array('id'=>array('in',$ids)))->save($data);	
			if ($res){
				$this->success('推荐成功');
			}else{
				$this->error('推荐失败');
			}
		}else{
		 	$this->error('非法请求');
		}  		
    }
	public function setall () {		
		$post	= I('post.');
		
		$ids	= $post['ids'];
        if ( empty($ids) ) {
            $this->error('请选择要操作的数据!');
        }
		unset($post['ids']);
		$artist_name 	= $post['artist_name'];
		if (!empty($artist_name)){			
			$artist_id  = M('Artist')->getFieldByName($artist_name ,'id');
			if ($artist_id){				
				$post['artist_id'] = $artist_id;
			}else{
				$model = D('Artist');
				$data['sort'] = get_str_sort($artist_name);			
				$data['name'] = $artist_name;
				$data['cover_url'] = '';
				$data = $model->create($data);				
				if ($artist_id = $model->add($data)){
					$post['artist_id'] =  $artist_id;					
				}else{
					$this->error('歌手自动新增失败，请手动添加');	
				}
			}	
		}else{
			unset($post['artist_name']);
		}
		$album_name = $post['album_name'];		
		if (!empty($album_name)){
			$aid = M('Album')->getFieldByName($album_name ,'id');
			//存在直接返回 不存在创建
			if ($aid){
				$post['album_id'] = $aid;			
			}else{									
				$model = D('Album');			
				$data = array(
					'name' 			=> $album_name,
					'artist_id' 	=> $artist_id,
					'artist_name'	=> $artist_name,
					'cover_url' 	=> '',
				);	
				$data = $model->create($data);					
				if ($id = $model->add($data)){
					return $id;					
				}else{
					$this->error('专辑自动新增失败，请手动添加');		
				}			
			}
		}else{
			unset($post['album_name']);
		}
		
		if (!empty($post['genre_id'])){
			$post['genre_name']  = get_genre_name($post['genre_id']);
		}
	
		foreach($post as $k => $v){
			if (empty($v)) unset($post[$k]); 
		}

		if (!empty($post)){
			$map['id'] = array('in', $ids);
			if (M('Songs')->where($map)->save($post)){
				$this->success('操作成功',Cookie('__forward__'));
			}else{
				$this->error('批量修改失败');
			}
			
		}else{
			$this->error('请设置批量修改的参数');	
		}
		
	}
	
	public function setreplace($replace_field="name",$before_str="",$after_str="") {
		if (!empty($before_str)){
			$Model = M('Songs');
			if ($replace_field == "listen_url" || $replace_field == "down_url"|| $replace_field == "lrc"){
				$res = $Model->execute("UPDATE __SONGS_EXTEND__ SET {$replace_field} = replace({$replace_field},'{$before_str}','{$after_str}')");
			}else{
				$res = $Model->execute("UPDATE __SONGS__ SET {$replace_field} = replace({$replace_field},'{$before_str}','{$after_str}')");
			}
			if ($res){
				$this->success('操作成功',Cookie('__forward__'));				
			}else{
				$this->error('批量替换失败');
			}
		}else{
			$this->error('请输入要替换的字符串');
		}
	}
        
    //批量导入
    public function bulkImport ($type = null) {   
    	header("Content-Type:text/html;charset=UTF-8");	    	
    	$path= C('SONGS_IMPORT_PATH');
		
    	session('fileList', null);
    	if ($type == 'refresh'){
    		session('fileList',null); 
    		$lock = session('upload_path') . 'import.lock';            
            if(is_file($lock)){ unlink($lock);}
    	}

		if(is_dir($path)){       	
			//$fs=array(array(),array(),array());
			if(!($dh=opendir($path))) return false;         
			while(($entry=readdir($dh))!==false){
				if($entry!="." && $entry!=".."){
					//$path2 = iconv("UTF-8","gb2312",$path."/".$entry);                	
					if(is_dir($file= file_realpath($path."/".$entry))){
						//组合二级目录
						$pathName = iconv("gb2312","UTF-8",$entry);
						$fileList[] = array('path'=>$file,'pathName'=>$pathName);
					}elseif(is_file($file)){
						//组合根目录导入文件
						$importList[]=array('path'=>$file,'fileName'=>$entry,'dirName'=>'');
					}
				}
			}    
			closedir($dh);
			if(!empty($fileList)){ //二级目录
				foreach ($fileList as $v) {
					$path2 = $v['path'];
					$dh2= opendir($path2);
					//dump($dh2);
					while(($entry2=readdir($dh2))!==false){
						if($entry2!="." && $entry2!=".."){
							$file2=file_realpath($path2."/".$entry2);
							if(is_file($file2)){
								$importList2[]=array('path'=>$file2,'fileName'=>$entry2,'dirName'=>$v['pathName']);
							}
						}	          			
					}
					closedir($dh2);	
				} 	          			          			          	
			}
			if (!empty($importList) && !empty($importList2)){
				$importList=array_merge($importList,$importList2);//合并数组;	            	
			}elseif(empty($importList) && !empty($importList2)) {
				$importList = $importList2;
			}
			session('fileList',$importList);
		}else{
			$this->assign('info','系统无法获取对应目录内容！');
		}

		
	    $this->assign('import_path',$path);
	    if(!empty($importList)){
	    	$this->assign('list',$importList);
	    }else{
			 $this->assign('info','这是一个无内容的空目录哦');
		}
		$this->meta_title = '批量导入';
		$this->display();
    }
    //批量导入
    public function fileImport ($tables = null, $id = null, $start = null) { 
    	header("Content-Type:text/html;charset=UTF-8");	
    	if(IS_POST && !empty($tables) && is_array($tables)){ //初始化
    		session('post_data',I('post.'));
            $uploadPath =  trim(C('ADMIN_UPMUSIC_PATH'),'.');			
			if(strpos($uploadPath,'/')!= 0 ){
				$uploadPath = './'.$uploadPath;
			}else{
				$uploadPath = '.'.$uploadPath;
			}
			
    		 //检查是否有正在执行的任务   realpath
            $lock = $uploadPath."import.lock";            
            if(is_file($lock)){
                $this->error('检测到有一个导入任务正在执行，请稍后再试！');
            } else {
                //创建锁文件
                file_put_contents($lock, NOW_TIME);
            }
            $dir = $uploadPath.date('Y-m-d'). "/";
            $tab = array('id' => 0, 'start' => 0);         
            //缓存导入路径
            session('upload_path',$uploadPath);
            session('upload_import_path',$dir);
            session('backup_tables', $tables);
            if (file_exists($dir)){
            	$this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
            }else{
            	 if(mkdir($dir)){
            	 	$this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
            	 }else{
            	 	$this->error('初始化失败，导入文件创建失败！');
            	 }          	
            }
    	
    	}elseif (IS_GET && is_numeric($id) && is_numeric($start)) { //导入数据
    		$tables = session('backup_tables');
			if(isset($tables[$id])){
				//$uid      = is_login();
				$fileList 	= session('fileList');
				$data 		= session('post_data');			
				$data['up_uid'] 	= isset($data['up_uid'])? $data['up_uid'] : UID;	
				$data['listens'] 	= setrand($data['listens']);
				$data['download'] 	= setrand($data['download']);

				$unid 		= uniqid();
				//$dir  		= __ROOT__.str_replace('.','',session('upload_import_path'));
				$file		= $fileList[$id]['path'];
				//$filesize	= filesize ($file);
				$ext		= file_extend($file);
				$newFile	= session('upload_import_path').$unid.'.'.$ext;

				if(rename($file,$newFile)){
					//处理入库
					//获取音乐信息
					$mp3	= $this->getSongInfo($newFile);
					
					
					$data['file_size']	= format_bytes($mp3['Filesize']);
					$data['play_time']	= $mp3['Length mm:ss'];
					$data['bitrate']	= $mp3['Bitrate'];					
					$data['name'] 		= substr(file_name_convert($fileList[$id]['fileName']),0,-4);//获取名称
					/*$mnames = @explode("-", $mname);
					$f = count($mnames);
					if($f <= 2){
						$data['name'] = $mname ;//获取名称
					}else{												
						//处理歌曲名称包含" - "
						$names = "";
						for($n=2;$n<$f;$n++){
							if($n==($f-1)){
								$names .= $mnames[$n];
							}else{
								$names .= $mnames[$n]." - ";
							}
						}
						$data['name'] = $names ;//获取名称
						$data["tone"] = trim($mnames[0]);
						$data["bpm"] = trim($mnames[1]);
					}*/
						
					$data['listen_url']  = trim($newFile,'.');	
					D('Songs')->update($data);
					M("Member")->where(array('uid'=>$data['up_uid']))->setInc('songs',1);//增加上传歌曲数量					
					$tab = array('id' => ++$id, 'start' => 0);
					$this->success('导入完成！', '', array('tab' => $tab));
            	}else{
            		$this->error('导入失败！');
            		//dump(session('upload_import_path'));
            	}
            } else { //清空缓存
  				unlink(session('upload_path') . 'import.lock');              
            	session('upload_import_path', null);
            	session('upload_path',null);
            	session('backup_tables', null);
            	session('post_data',null);
            	session('fileList', null);            	
                $this->success('导入完成！',U('index'));
            }    			
    	
    	}else { //出错
            $this->error('参数错误！');
        }

    }
    
    //根据曲风创建目录
    public function createGenreDir () {
    	if(IS_POST){
    		$path= C('SONGS_IMPORT_PATH');
     		$list = M('Genre')->field('name')->select();
     		$info = '';
     		foreach ($list as &$v) {
     			$dir = $path.iconv('utf-8', 'gbk', $v['name']);
     			//$dir = $path.$v['name'];
     			if(!is_dir($dir))  {
     				if(!mkdir($dir)){ 
   						$info .='创建'.$v['name'].'失败!/<br>';  
  					}else{
  						//dump('创建'.$v['name'].'成功!');
  						$info .='创建'.$v['name'].'成功!/<br>'; 					
     				}
     			}
     		} 
     		$this->success($info);
     	}else{
     		$this->error('参数错误！');
     	}
    
    }
    //更改歌曲状态
    public function setStatus () {
    	    	
    	return parent::setStatus('Songs');
    	
    }
	
	/**
     * 更新音乐属性
     * @return array 音乐属性信息
     * @author 战神~~巴蒂
     */
    public function getSongInfo($file){

		import('JYmusic.Mp3File');
		$mp3 = new \Mp3File($file);
		return $mp3->get_metadata();	
    
	}
            
}