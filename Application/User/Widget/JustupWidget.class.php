<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2015 http://www.my-music.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 战神巴蒂 <31435391.qq.com>
// +----------------------------------------------------------------------

namespace User\Widget;

use Think\Controller;

/**
 * 分类widget
 */
class JustupWidget extends Controller{

    /* 显示用户自定义导航*/
    public function index($type='song'){	
		$driver  = strtolower(C('USER_MUSICUP_DRIVER'));
		$file = session('justup_file',null);		
		if ($file && !empty($file['name'])){
			//$this->assign('file', $file);
		}else{
			session('justup_file',null);
		}		
		$this->assign('type',$type);
		$this->assign('driver', $driver);
        $this->display('Widget/Justup_index');
    }
}
