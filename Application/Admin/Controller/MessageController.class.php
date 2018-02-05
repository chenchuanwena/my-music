<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2016 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+


namespace Admin\Controller;

/**
 * 后台信息留言控制器
 * @author JYmusic
 */
class MessageController extends AdminController {
	public function index ($type='letter') {
		$map = array();
		if(isset($content)){
            $map['content']   =   array('like', '%'.$title.'%');
        }
        if( $type == 'letter') {      
			$list   = $this->lists('Message', $map);
			foreach ($list as &$v){
				$v['title']	= '<a href="'.U('/user/'.$v['post_uid']).'">'.$v['post_uname'].' </a>对<a href="'.U('/user/'.$v['to_uid']).'">'.$v['to_uname'].' </a>说：';		
				$v['type']	= '系统';
			}
		}else{
			$list   = $this->lists('Notice', $map);
		}
		
		$this->assign('type',$type);
        //int_to_string($list);
        $this->assign('list', $list);
        $this->meta_title = '信息管理';
        $this->display();			
	}
	
	public function add(){
		if(IS_POST){
            $model 	= D('Notice');
			$uids	= I('post.to_uid');
			$group 	= I('post.tip_group');

			if ($group > 0){
				$uids = M('MemberGroupLink')->where(array('group_id'=>$group))->getField('uid',true);
			}elseif (!empty($uids)){
				$uids 	= @explode(",",$uids);
			}else{
				$this->error('选择你要发送的用户');
			}
			 
            if($data = $model->create()){
				$count = count($uids);
				if( $count> 0){
					for( $i=0;$i<$count;$i++){
	 					$data['to_uid'] = $uids[$i];
						$model->add($data);
					}
					$this->success('操作成功！',U('index?type=notice'));           		
            	}else{
            		$this->error('用户ID格式错误');           		
            	}
            } else {
                $this->error($model->getError());
            }
        } else {
			$this->meta_title = '添加信息';
			$this->display();
        }			
	}
	
	public function del(){
        $id = array_unique((array)I('ids',0));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
		//dump($id);
        $map = array('id' => array('in', $id) );
        if(M('Message')->where($map)->delete()){
            //记录行为
            //action_log('update_channel', 'channel', $id, UID);
            $data['status']  = 1;
            $data['info'] = '删除成功';
        } else {
        	$data['status']  = 0;
            $data['info'] = '删除失败！';
        }
        $this->ajaxReturn($data);
    }
	
	//更改信息状态
    public function setStatus () {
    	    	
    	return parent::setStatus('Message');
    }

}
