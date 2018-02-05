<?php

namespace Addons\baidushare;
use Common\Controller\Addon;

/**
 * 百度分享插件
 */

    class BaidushareAddon extends Addon{

        public $custom_config = 'config.html';

        public $info = array(
            'name'=>'Baidushare',
            'title'=>'百度分享',
            'description'=>'网站内容分享到第三方网站,带来社会化流量。',
            'status'=>1,
            'author'=>'JYmusic',
            'version'=>'0.1'
        );

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

        //实现的documentDetailAfter钩子方法
        public function pageBody($param){
			if($param['widget'] == 'Baidushare'){
				$this->assign('addons_config', $this->getConfig());
				$this->display('share');
			}
        }

    }