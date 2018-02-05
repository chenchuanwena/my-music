<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2015 http://www.my-music.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 战神巴蒂 <31435391.qq.com>
// +----------------------------------------------------------------------

namespace Admin\Widget;

use Think\Controller;

/**
 * 分类widget
 */
class JustupWidget extends Controller{

    /* 显示用户自定义导航*/
    public function index($type=1){	
		$driver  =  $type==1 ?  strtolower(C('MUSIC_UPLOAD_DRIVER')): strtolower(C('PICTURE_UPLOAD_DRIVER'));
		$_SESSION['jy_home_']['justup_file'] = null;	
		$this->assign('dtype', $type);
		$this->assign('driver', $driver);
        $this->display('Widget/Justup_index');
    }
}
