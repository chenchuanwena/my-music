<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2016 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Admin\Controller;

/**
 * 后台认证控制器
 */
class AuthController extends AdminController {
	public function index($status=''){
		$map =array();
		if (!empty($status)){
			$map['status'] = $status;
		}
		
        $list = $this->lists('MemberAuthMusician',$map);   	
    	$this->assign('list',$list);
        $this->meta_title = '认证列表';
		Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();
    }

	//驳回
    public  function disable ($id) {
    	if(IS_AJAX || !intval($id)){
			if($this->remove($id)){
				//发送通知
				$title = '音乐认证通知';
				$content = '你申请的音乐人未通过审核未通过审核！';					
				D('Notice')->send($uid,$title,$content);
				$this->success('操作成功',Cookie('__forward__'));
			}else{
				$this->error('删除失败');
			}
    	}else{ 	
    		$this->error('请求失败,参数错误');
    	}
    
	}
    
    //通过审核
    public function pass () {    	    	
    	if(IS_AJAX){
    	 	$uid   		=  I('uid');
			if(!uid) $this->error('你操作的用户不存在');
			
			$linkModel	= M('MemberGroupLink');
			$data['uid']		= $uid;
			$data['group_id']	= 3;
			$time  				= strtotime("2036-01-01");
			if ($linkModel->where($data)->find()){
				if (!$linkModel->where($data)->setField('end_time',$time)){
					$this->error('音乐人通过失败1');
				}
			}else{
				$data['end_time']= $time;
				if (!$linkModel->add($data)){
					$this->error('音乐人通过失败');
				}
			}
			S('user_glink_list',null);		
			M('MemberAuthMusician')->where(array('uid'=>$uid))->setField('status',1);
	        //发送通知
			$title = '音乐认证通知';
			$content = '你申请的音乐人成功通过审核，感谢你的支持！';						
			D('Notice')->send($uid,$title,$content);
			$this->success('音乐人通过操作成功',Cookie('__forward__')); 
    	
    	}else{
    	
    		$this->error('非法请求');
    	}
    }
	
	public function del($id=0){	
		if (intval($id)){
			if($this->remove($id)){
				$this->success('删除成功',Cookie('__forward__'));
			}else{
				$this->error('删除失败');
			}		
		}else{    	
    		$this->error('请选择你要操作的用户');
    	}
	}
	
	protected function remove($id){
		$model		= M('MemberAuthMusician');
		$linkModel	= M('MemberGroupLink');
		$user 		= $model->find($id);
		if(empty($user)){$this->error('你操作的用户不存在');}
		
		$map = array('uid'=>$user['uid'],'group_id'=>3);			
		if ($linkModel->where($map)->find()){
			$linkModel->where($map)->delete();
		}
		if (!$model->delete($id)){
			return false;
		}
		return true;
	}

}
