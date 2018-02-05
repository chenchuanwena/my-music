<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Admin\Controller;

/**
 * 后台配置控制器
 */
class ConfigController extends AdminController {

    /**
     * 配置管理
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        /* 查询条件初始化 */
        $map = array();
        $map  = array('status' => 1);
        if(isset($_GET['group'])){
            $map['group']   =   I('group',0);
        }
        if(isset($_GET['name'])){
            $map['name']    =   array('like', '%'.(string)I('name').'%');
        }

        $list = $this->lists('Config', $map,'sort,id');
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('group',C('CONFIG_GROUP_LIST'));
        $this->assign('group_id',I('get.group',0));
        $this->assign('list', $list);
        $this->meta_title = '配置管理';       
        $this->display();
    }

    /**
     * 新增配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if(IS_POST){
            $Config = D('Config');
            $data = $Config->create();
            if($data){
                if($Config->add()){
                    S('DB_CONFIG_DATA',null);
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $this->meta_title = '新增配置';
            $this->assign('info',null);
            $this->display('edit');
        }
    }

    /**
     * 编辑配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function edit($id = 0){
        if(IS_POST){
            $Config = D('Config');
            $data = $Config->create();
            if($data){
                if($Config->save()){
                    S('DB_CONFIG_DATA',null);
                    //记录行为
                    action_log('update_config','config',$data['id'],UID);
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Config')->field(true)->find($id);

            if(false === $info){
                $this->error('获取配置信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑配置';
            $this->display();
        }
    }

    /**
     * 批量保存配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function save($config){
        if($config && is_array($config)){
            $Config = M('Config');
            foreach ($config as $name => $value) {
                $map = array('name' => $name);
                $Config->where($map)->setField('value', $value);
            }
        }
        S('DB_CONFIG_DATA',null);
        $this->success('保存成功！');
    }

    /**
     * 删除配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Config')->where($map)->delete()){
            S('DB_CONFIG_DATA',null);
            //记录行为
            action_log('update_config','config',$id,UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    // 获取某个标签的配置参数
    public function group() {
        $id     =   I('get.id',1);
        $type   =   C('CONFIG_GROUP_LIST');
        $list   =   M("Config")->where(array('status'=>1,'group'=>$id))->field('id,name,title,extra,value,remark,type')->order('sort')->select();
        if($list) {
            $this->assign('list',$list);
        }
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('id',$id);
        $this->meta_title = $type[$id].'设置';
        $this->display();
    }

    /**
     * 配置排序
     */
    public function sort(){
        if(IS_GET){
            $ids = I('get.ids');
            //获取排序的数据
            $map = array('status'=>array('gt',-1));
            if(!empty($ids)){
                $map['id'] = array('in',$ids);
            }elseif(I('group')){
                $map['group']	=	I('group');
            }
            $list = M('Config')->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->meta_title = '配置排序';
            $this->display();
        }elseif (IS_POST){
            $ids = I('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = M('Config')->where(array('id'=>$value))->setField('sort', $key+1);
            }
            if($res !== false){
                $this->success('排序成功！',Cookie('__forward__'));
            }else{
                $this->error('排序失败！');
            }
        }else{
            $this->error('非法请求！');
        }
    }
    
    /*
    * 驱动配置
    * @author 战神巴蒂 <31435391.qq.com>
    */
    
   	public function updrive(){
   		if (IS_POST){
   			$config  = I('post.');	
			$cfile 	= include './Data/Conf/up_drive.php';			
			foreach( $config as $k => $v ){
				$v = $this->setDriveServer($k,$v);				
				foreach( $v as $b=>$c ){
					$config[$k][$b]  = trim($c);
				}				
			}
			$config	= array_merge($cfile,$config);
			$text ="<?php\treturn " . var_export($config, true).';';		
			$result = D('File')->writeConfig('up_drive',$text);
   			if ($result){
				 S('DB_CONFIG_DATA',null);
   				$this->success('更新成功', U('Config/group'));   				
   			}else{
   				$this->error('更新失败,请检查[Data/Conf]文件是否可写');
   			}
   		}else{
	        $this->meta_title = '上传驱动设置';
	        $this->display();
   	
		}
   	}  	
   	
   /*
    * 视图配置
    * @author 战神巴蒂 <31435391.qq.com>
    */
    public function view($theme=null){
    	$cfile 	= './Data/Conf/home_view.php';
		$ufile 	= './Data/Conf/user_view.php';
		$afile 	= './Data/Conf/article_view.php';
		$mfile = './Data/Conf/mobile_view.php';
		
    	$home_conf 		= include 	$cfile;
    	$user_conf 		= include  	$ufile;
		$article_conf 	= include  	$afile;
		$mobile_conf 	= include  	$mfile;
    	if (IS_POST){
			if(!empty($theme)){
				//读取模板配置文件
				$theme_conf = include  './Template/'.$theme.'/conf.php';
		
				$tpl['TMPL_PARSE_STRING']  =  array(
					'__STATIC__' => __ROOT__ . '/Public/static',
				    '__TMPL__'   => __ROOT__ . '/Template/'.$theme. '/' . $theme_conf['static'] ,
				);			  
							
   				$home_conf		= array_merge($home_conf,array('DEFAULT_THEME'=>$theme. '/' . $theme_conf['home_path']) ,$tpl);
   				$user_conf 		= array_merge($user_conf,array('DEFAULT_THEME'=>$theme. '/' . $theme_conf['user_path']) ,$tpl);
				$article_conf 	= array_merge($article_conf,array('DEFAULT_THEME'=>$theme. '/' . $theme_conf['article_path']) ,$tpl);
				$mobile_conf 	= array_merge($mobile_conf,array('DEFAULT_THEME'=>$theme. '/' . $theme_conf['mobile_path']) ,$tpl);				
   			}else{
   				$result = false;
   			}
   			$result 	= file_put_contents($cfile, "<?php\treturn " . var_export($home_conf, true).';');
   			$result1 	= file_put_contents($ufile, "<?php\treturn " . var_export($user_conf, true).';');  
			$result2 	= file_put_contents($afile, "<?php\treturn " . var_export($article_conf, true).';');
			$result3 	= file_put_contents($mfile, "<?php\treturn " . var_export($mobile_conf, true).';');				
   			if ($result1 && $result && $result2 && $result3){
				S('DB_CONFIG_DATA',null);
   				$this->success('更新成功',U('view'));  				
   			}else{
   				$this->error('更新失败,请检查[Application/Common/]文件夹下配置文件是否可写！');
   			}
   		}else{
   			//screenshot.png
   			$dir = './Template/';  			
			$file = new \OT\File;
   			$dirs = $file->get_dirs($dir);
   			$view = array();
			foreach ($dirs['dir'] as $k=>$v) {//遍历目录
				if ($v != '.' && $v != '..'){
					$conf = $dir . $v. '/conf.php';	
					if(file_exists($conf)){
						$conf	= include $conf;
						$view[$k]  				= $conf;
						$view[$k]['dir']		= $v;	
						$view[$k]['theme_name'] 	= $v.'/'.$conf['home_path'];						
						$view[$k]['cover']		= $dir . $v. '/screenshot.png';
					}
				} 
			}
			
			$this->assign('current_theme',$home_conf['DEFAULT_THEME']);
			//dump($home_conf['DEFAULT_THEME']); die;
			$this->assign('list',$view);
			$this->assign('home_conf',$home_conf);
			$this->assign('user_conf',$user_conf);
			$this->assign('article_conf',$article_conf);
	        $this->meta_title = '视图设置';
	       	$this->display();   	
		}   	
    }
	
   /*
    *  Home 模块配置
    * @author 战神巴蒂 <31435391.qq.com>
    */
    
   	public function homemodule(){
   		$conf = get_custom_config('home_view');
   		if (IS_POST){
   			$config  = I('post.');
   			$config = array_merge($conf,$config);
			$text ="<?php\treturn " . var_export($config, true).';';		
			$result = D('File')->writeConfig('home_view',$text);			
			if ($result){
				S('DB_CONFIG_DATA',null);
   				$this->success('更新成功',U('view'));   				
   			}else{
   				$this->error('更新失败,请检查[Data/Conf/home_view.php]文件是否可写');
   			}
   		}
   	}
   	
   	/*
    *  User 模块配置
    * @author 战神巴蒂 <31435391.qq.com>
    */
    
   	public function usermodule (){
   		if (IS_POST){
			$conf = get_custom_config('user_view');
   			$config  = I('post.');
   			$config = array_merge($conf,$config);
   			$text ="<?php\treturn " . var_export($config, true).';';		
			$result = D('File')->writeConfig('user_view',$text);				
			if ($result){
				S('DB_CONFIG_DATA',null);
   				$this->success('更新成功',U('view'));   				
   			}else{
   				$this->error('更新失败,请检查[/Data/Conf/user_view.php]文件是否可写');
   			}
   		}
   	}
	
	/*
    *  User 模块配置
    * @author 战神巴蒂 <31435391.qq.com>
    */
    
   	public function articlemodule (){
   		if (IS_POST){
			$conf = get_custom_config('article_view');
   			$config  = I('post.');
   			$config = array_merge($conf,$config);
   			$text ="<?php\treturn " . var_export($config, true).';';		
			$result = D('File')->writeConfig('article_view',$text);				
			if ($result){
				S('DB_CONFIG_DATA',null);
   				$this->success('更新成功',U('view'));   				
   			}else{
   				$this->error('更新失败,请检查[Data/Conf/article_view.php]文件是否可写');
   			}
   		}
   	}
    
    public function getxml($file) {
   		$xml = @simplexml_load_file($file);			
		if(is_object($xml)){
			$xml = json_encode($xml);
			$xml = json_decode($xml, true);
		}
		return is_array($xml)?  $xml : null;
    }
	
	public function setDriveServer ($driver,$config){
		if ( !empty($config['server_id'])) return $config;							
		$driver		= 	explode("_",$driver);
		$driver 	= 	strtolower($driver[1]);
		switch ($driver){	
			case 'ftp': 
				if (empty($config['host']) ||  empty($config['username']) || empty(	$config['password']) ){					
					return $config;
				}
							
				$host 		= $config['host'];
				$bucket		= null;
				$serverNanme= 'FTP【'.$config['username'].'】';	
			break; 
			
			case 'qiniu':
				if (empty($config['accessKey']) ||  empty($config['secrectKey']) || empty(	$config['bucket']) ){					
					return $config;
				}
				
				if (!empty($config['domain'])){
					$host 		= $config['domain'];
					$serverNanme= '七牛云【'.$config['bucket'].'】';	
					$bucket		= null;
				}else{
					$host 		= 'qiniudn.com'; 
					$bucket		= $config['bucket'].'.';
					$serverNanme= '七牛云【'.$config['bucket'].'】';	
				}					
								
			break;
										
			case 'upyun':
				if (empty($config['host']) ||  empty($config['username']) || empty(	$config['password']) || empty(	$config['bucket'])){					
					return $config;
				}			
				$host 		= $config['host'];
				$bucket		= $config['bucket'].'.'; 
				$serverNanme= '又拍云【'.$bucket.'】';					
			break; 
			
			case 'aliyun': 
				if (empty($config['AccessKeyId']) ||  empty($config['AccessKeySecret']) || empty($config['Bucket']) ){					
					return $config;
				}			
				$host = $config['Endpoint'];
				$bucket	= $config['Bucket'].'.'; 
				$serverNanme= '阿里云【'.$config['Bucket'].'】';					
			break;
			
			case 'julong': 
				if (empty($config['AK'])){					
					return $config;
				}			
				$host 	= "";
				$bucket	= 'pan.julongdj.com/'; 
				$serverNanme= '聚龙网盘';	
				$this->updateConf($driver,$serverNanme);				
			break;
										
		}
		
		
		$data = array(
			'name'		=> $serverNanme,
			'listen_url'=> 'http://' .$bucket.ltrim($host,'http://')
		);
		
		$model 	= D('Server');
		//查询是否创建
		$res = $model->where($data)->find();				
		if ($res){
			$config['server_id'] = $res['id'];	
		}else{			
			$data	= $model->create($data);			
			if ($id = $model->add($data)){
				S('serverList',null);
				$config['server_id'] = 	$id;
			}
		}		
		return $config;
	}
	
	public function updateConf($driver,$name){
		$model	= M('Config');
		$mconf	= $model->getFieldByName('MUSIC_UPLOAD_DRIVER','extra');
		$mconf 	= parse_config_attr($mconf);
		$ishave = false;
		if(!isset($mconf[$driver])){ //不存在该配置
			$mconf[$driver] = $name;
			$str = '';
			foreach($mconf as $k => $v){
				$str .= $k.":".$v."\r\n";
			}
			$model->where(array('name'=>'MUSIC_UPLOAD_DRIVER'))->setField('extra',$str);
			$model->where(array('name'=>'PICTURE_UPLOAD_DRIVER'))->setField('extra',$str);
			$model->where(array('name'=>'USER_MUSICUP_DRIVER'))->setField('extra',$str);
			$model->where(array('name'=>'USER_PICUP_DRIVER'))->setField('extra',$str);
		}

	}
           
}









