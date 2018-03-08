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
class MessageModel extends Model{
	
	protected $msg;
	protected $to_uid;
	
		
	protected $_validate = array(
        array('content', 'require', '消息内容不能为空', 1 ,'regex', self::MODEL_BOTH),
		array('content', '2,400', '音乐标题长度2-400个字符', 0 ,'length', 3)
    );
	
	protected $_auto = array(
		array('content','text_filter',3,'function'),
		array('to_uid','getToUid',3,'callback'),
		array('to_uname','getToUname',3,'callback'),
        array('reply_id','getReplyId',3,'callback'),
		array('post_uid', UID, 3),
		array('post_uname','getPostUname',3,'callback'),
        array('is_tip', 0, 3),
		array('is_read', 0, 3),
		array('status', 1, 3),
		array('create_time', NOW_TIME, 3)
    );
	
	/**
     * 获取提交对象的用户ID
    */
	protected  function getToUid () { 
		$id				= intval(I('reply_id'));
		$touid			= intval(I('to_uid'));
		if ($touid){
			$this->to_uid = $touid;
			return $touid;
		}
		
		if($id){
			$msg	= $this->where(array('id'=>$id))->field('post_uid,reply_id,to_uid')->find();
			if (!$msg){
				$this->error = "你回复的信息不存在";
				return false;
			}
			$this->msg	 = $msg;
			$this->to_uid = $msg['to_uid'];
			return $this->to_uid;
		}
		$this->error = "你回复的信息不存在";
     	return false;
    }
	
	protected  function getToUname () { 
     	return get_nickname($this->to_uid);
    }
	
	protected  function getPostUname () { 
     	return get_nickname(UID);
    }
	
	/**获取全部没有读取过的消息
     * @param $uid 用户ID
    */
	protected  function getReplyId () {    
     	$id		= intval(I('reply_id'));
		if ($this->$msg['post_uid'] == UID){
			return $msg['reply_id'];
		}
		return $id? $id : 0;
    }
	
	
	 /**获取全部没有读取过的消息
     * @param $uid 用户ID
     */
    public function getUnreadMsg($uid){
    	$map['to_uid'] = $uid;
    	$map['is_read'] = 0;
        $msg = D('message')->where($map)->order('id desc')->field('is_read,to_uid,is_tip',true)->limit(9999)->select();
        return $msg;
    }    
    
    	
	 /**获取全部没有提示过的消息
	 * @param $uid 用户ID
	 */
    public function getNoTip($uid){
    	$map['to_uid'] = $uid;
    	$map['is_tip'] = 0;
        $msg = D('message')->where($map)->order('id desc')->field('is_read,to_uid,is_tip',true)->limit(9999)->select();
        return $msg;
    }   
    
    /**设置全部未提醒过的消息为已读
     * @param $uid
    */
    public function setAllRead($uid){
        D('message')->where('to_uid=' . $uid . ' and  is_read=0')->setField('is_read', 1);
    } 
    
    /**发送信息
    * @param    $type 消息类型 system系统，letter私信，app应用
     * @param $uid
    */
    public function sendMsg($to_uid,$title = '您有新的消息',$content,$type='system',$post_uid=0,$reply_id=0){
		$data['post_uid'] = $post_uid;
		if ($post_uid){
			$data['post_uname'] = get_nickname($post_uid);
		}else{
			$data['post_uname'] = '系统提醒';
		}
		$data['to_uid'] = $to_uid;
		$data['title'] = $title;
		$data['content'] = $content;
		$data['reply_id'] = $reply_id;  		
		$data['post_time'] = NOW_TIME; 
		$data['type'] = $type;
    	$id = $this->add($data);   
    	return $id;
    }
	
	/*
	* 删除消息
	*/
	
	public function delMsg($id){
		$id 	= intval($id);
		if (!$id || !$msg = $this->find($id)){
			$this->error="你操作的信息不存在！";
			return false;
		}
		
		if ($msg['post_uid'] !=  UID ){
			$this->error="你无法删除这条信息！";
			return false;	
		}
				
		return  $this->delete($id);
		
	}
	
}
