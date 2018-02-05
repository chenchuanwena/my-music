<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2015 http://www.my-music.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 战神巴蒂 <31435391.qq.com>
// +----------------------------------------------------------------------

namespace User\Widget;

use Think\Action;

/**
 * 分类widget
 */
class SpaceWidget extends Action{

    /* 显示用户自定义导航*/
    public function channel($uid){
    	//dump($this->user);
        $this->display('Widget/Space:channel');


    }

    /* 显示用户自定义导航*/
    public function indexunit(){
        $this->display('Widget/Space:indexunit');

    }
    
    /* 显示用户自定义bg*/
    public function bg(){
   		$path = __ROOT__.trim(C('USER_SKINS_PATH'),'.');
   		/*$bnxml = @simplexml_load_file($path.'/space_skins/default_banner.xml');
    	if(is_object($bnxml)){
			$bnxml = json_encode($bnxml);
			$bnxml = json_decode($bnxml, true);
			$this->assign('default_banner',$bnxml['theme']);
		}*/
    	$bgxml = @simplexml_load_file('.'.$path.'/default_bg.xml');
    	if(is_object($bgxml)){
			$bgxml = json_encode($bgxml);		
			$bgxml = json_decode($bgxml, true);
			$skins_bg = $bgxml['theme'];
			foreach ($skins_bg as &$v){
				if (strpos($v['imgurl'],'/') == 0){
					$v['imgurl']	= $path.$v['imgurl'];
				}
				if (strpos($v['thumburl'],'/') == 0){
					$v['thumburl']	= $path.$v['thumburl'];
				}				
			}			
			$this->assign('skins_bg',$skins_bg);
		}
		
        $this->display('Widget/Space:bg');

    }

}
