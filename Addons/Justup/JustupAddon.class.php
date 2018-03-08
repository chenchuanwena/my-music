<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2016 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Addons\Justup;
use Common\Controller\Addon;

/**
 * 通用社交化评论插件
 * @author JYmusic 修改与Thinkphp
 */

    class JustupAddon extends Addon{
		
        public $info = array(
            'name'			=>'Justup',
            'title'			=>'第三方上传驱动',
            'description'	=>'FTP 阿里OSS、七牛等上传驱动。',
            'status'		=>1,
            'author'		=>'JYmusic',
            'version'		=>'0.1'
        );

        public function install(){
			$this->getisHook('upload', $this->info['name'], $this->info['description']);
            return true;
        }

        public function uninstall(){
            return true;
        }
		
		//获取插件所需的钩子是否存在
        public function getisHook($str, $addons, $msg=''){
            $hook_mod = M('Hooks');
            $where['name'] = $str;
            $gethook = $hook_mod->where($where)->find();
            if(!$gethook || empty($gethook) || !is_array($gethook)){
                $data['name'] = $str;
                $data['description'] = $msg;
                $data['type'] = 1;
                $data['update_time'] = NOW_TIME;
                $data['addons'] = $addons;
                if( false !== $hook_mod->create($data) ){
                    $hook_mod->add();
                }
            }
        }

        //实现的pageFooter钩子方法
        public function upload($param){
			$this->assign('param',$param);
            $this->assign('config', $this->getConfig());
            $this->display('weget');
        }
    }