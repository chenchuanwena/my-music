<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2015 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Common\Model;

use Think\Model;

/**
 * 消息通知模型
 */
class NoticeModel extends Model{
	
	protected $_validate = array(
		array('content', 'require', '消息内容不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
		array('title','getTitle',3,'callback'),
		array('tip_group', 0, self::MODEL_INSERT),
        array('is_read', 0, self::MODEL_INSERT),
        array('create_time', NOW_TIME, self::MODEL_BOTH)
    );
	
	protected  function getTitle ($title) { 
		return !empty($title)? $title : '您有新的系统提醒';
	}
	/**获取全部没有读取过的消息
     * @param $uid 用户ID
    */
    public function getUnread($uid){
    	$map['uid'] 	= $uid;
    	$map['is_read'] = 0;
        $msg = $this->where($map)->order('id desc')->field('is_read,uid,',true)->limit(9999)->select();
        return $msg;
    }    
    
    	
	/**获取全部没有提示过的消息
	 * @param $uid 用户ID
	*/
    public function getAll($uid){
    	$map['uid'] = $uid;
        $msg 		= D('message')->where($map)->order('id desc')->limit(9999)->select();
        return $msg;
    }   
    
    /**设置全部未提醒过的消息为已读
     * @param $uid
    */
    public function setAllRead($uid){
        $this->where('uid=' . $uid . ' and  is_read=0')->setField('is_read', 1);
    }
    
    
    /**发送信息
    * @param    
     * @param $uid
    */
    public function send($uid,$title = '您有新的系统提醒',$content){

		$data['to_uid'] 		= $uid;
		$data['title'] 			= $title;
		$data['content'] 		= $content;	
		$data = $this->create($data);	
		if (!$this->add($data)) {
            $this->error = '系统通知发送失败！';
            return false;
        }
    	return true;
    }
}
