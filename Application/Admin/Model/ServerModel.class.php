<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Admin\Model;
use Think\Model;


class ServerModel extends Model {
	
	protected $listenDir;

    protected $_validate = array(
        array('name', 'require', '名称不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
        array('listen_url', 'require', '试听地址不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
        
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', 1, self::MODEL_INSERT),
        array('listen_url','checklisten', self::MODEL_BOTH,'callback'),
		array('down_url', 'checkdown', self::MODEL_BOTH,'callback'),
    );
    
    
    protected function checklisten ($dir) {
		$this->listenDir = $dir;
    	return $dir;
    }
    
    protected function checkdown ($dir) { 	
    	if(empty($dir)) {
			$dir= $this->listenDir; 	
		}	
    	return $dir;
    }

}
