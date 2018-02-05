<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace User\Controller;
class GuestController extends UserController {
    /**
	* 用户动态
	*/    
    public function index(){
    	$map['uid'] = UID;  
		$list = $this->lists('Guestbook',$map,'id desc');			
		$this->meat_title = '消息中心 - 我的留言 -'.C('WEB_SITE_TITLE');
    	$this->assign('list', $list);
    	$this->display();
    }    
	
	//删除留言
	public function removeGuestbook(){ 
    	$id = (int)I('id');   
		if ($id){
			$model = M('Guestbook');
			$uid = $model->getFieldById($id,'uid');
			if($uid != UID) $this->error('参数错误！');			
			$return = $model->where(array('id'=>$id))->delete();
			if ($return){
				$this->success('留言删除成功');
			}else{
				$this->error('留言删除失败，请重试！');
			}
		}else{			
			$this->error('参数错误！');
		}
    }
}