<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2016 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Addons\Pay\Model;
use Think\Model;


class UserPayHistoryModel extends Model{
    protected $_auto = array(
        array('type','text_filter',1,'function'),
        array('body','text_filter',1,'function'),
        array('uid','getUid',1,'callback'), 
        array('user_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),      
        array('create_time', NOW_TIME, self::MODEL_BOTH),
    );

    protected function getUid(){
    	return is_login();
    }
    
}
