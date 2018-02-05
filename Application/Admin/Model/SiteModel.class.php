<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2029 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Admin\Model;
use Think\Model;


class SiteModel extends Model {
	
	protected $listenDir;

    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
		array('name', 'require', '标识不能为空', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', 'checkName', '标识已经存在', self::VALUE_VALIDATE, 'callback', self::MODEL_BOTH),
        array('title', '1,80', '标题长度不能超过80个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
		array('content', 'require', '内容不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH)
        
    );

    protected $_auto = array(
		array('title', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', 1, self::MODEL_INSERT)
    );
    
    
        /**
     * 检查标识是否已存在(只需在同一根节点下不重复)
     * @param string $name
     * @return true无重复，false已存在
     * @author huajie <banhuajie@163.com>
     */
    protected function checkName(){
        $name        = I('post.name');
        $id          = I('post.id', 0);

        $map = array('name' => $name, 'id' => array('neq', $id), 'status' => array('neq', -1));
        $res = $this->where($map)->getField('id');
        if ($res) {
            return false;
        }
        return true;
    }


}
