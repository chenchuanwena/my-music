<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2015 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace User\Controller;
class MsgcenterController extends UserController {
	
	//首页系统通知
    public function index(){ 

		$where['to_uid']	= UID;
		$where['post_uid'] = UID;
		$where['_logic'] = 'OR';
		
		$map['_complex'] = $where;
		$map['status']	= 1;


		$list = $this->lists('Message',$map,'create_time desc');
		
		foreach ($list as &$v){
			if ($v['post_uid'] == UID){
				$v['title']	= '你对 <a class="text-info" href="'.U('/user/'.$v['to_uid']).'">'.$v['to_uname'].' </a>说：';
				$v['is_read'] = 1;
			}else{
				$v['title']	= '<a class="text-info" href="'.U('/user/'.$v['post_uid']).'">'.$v['post_uname'].' </a>对你说：';
			}
		}
		
		$this->meat_title = '消息中心 - '.C('WEB_SITE_TITLE');
		$this->assign('list', $list);
		$this->display();
    }
	


    //系统提醒首页
    public function notice(){ 
    	$map['to_uid']		= UID;
	
		$list = $this->lists('Notice',$map,'create_time desc');  	
		$this->meat_title = '消息中心 - '.C('WEB_SITE_TITLE');
    	$this->assign('list', $list);

		$this->display();
    }
    //提示消息
    public function info(){
    	$this->assign('meat_title', '消息');	
		$this->display();
    }
    
    //回复信息
    public function send(){
		if (!offSpite ('send_msg')){
			$this->error('操作过于频繁，休息会再来操作！');
		}
		if(IS_POST && IS_AJAX && UID){
			$model	= D('Message');	                      	            
			if($data = $model->create()){  
				if($model->add()){
					$dat['info']   =  '操作成功';
					$dat['status'] =   1;
					$dat['content']   =  $data['content'];
					$this->ajaxReturn($dat);
				} else {
					$this->error('操作失败');
				}
			} else {
				$this->error($Message->getError());
			}

	    }else{
	    	$this->error('你访问的页面不存在！');
	    }
    }
    
	public function detail($type='msg',$id=0) {
		$id 	= intval($id);	
		if ($type =='msg' && $id ){
			$model	= M('Message');
			$msg = $model->find($id);
			if ($msg['post_uid'] !=UID && $msg['to_uid'] !=UID){
				$this->error('你无权查看这条信息！');
			}
			if ($msg['post_uid'] == UID){
				$msg['title']	= '你对 <a class="text-info" href="'.U('/user/'.$msg['to_uid']).'">'.$msg['to_uname'].' </a>说：';
			}else{
				$msg['title']	= '<a class="text-info" href="'.U('/user/'.$msg['post_uid']).'">'.$msg['post_uname'].' </a>对你说：';
				$model->where(array('id'=>$id))->setField('is_read',1);
			}
			
			$this->assign('data',$msg);
			$this->display('Msg:detail');			
		}elseif ($type =='notice'&& $id){
			$model	= M('Notice');
			$msg = $model->find($id);
			if ($msg['to_uid'] !=UID){
				$this->error('你无权查看这条信息！');
			}
			$model->where(array('id'=>$id))->setField('is_read',1);
			$this->assign('data',$msg);
			$this->display('Notice:detail');
		}else{
			$this->error('你访问的页面不存在！');
		}
    
    }
	
	public function delmsg($id=0) {
		$model = D('Message');		 
		if ($model->del($id)){
			$this->success('删除成功！');
		}else{
			$this->error($model->getError());
		}
		
	}
    
}