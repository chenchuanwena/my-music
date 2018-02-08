<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2016 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Admin\Controller;
use Think\Controller;
class UserGroupController extends AdminController {
    public function index(){	
		$mode	=   D('MemberGroup');     
		$list = $this->lists($mode,null,'id desc',null);    
        $this->assign('list', $list);       
		$this->meta_title = '用户组管理';
        $this->display();
	}
	public function add(){
		if(IS_POST){
            $mode	=  D('MemberGroup');  
            if($data = $mode->create()){
                if($mode->add()){
					S('user_groups_list',null);
                    $this->success('新增成功',U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($mode->getError());
            }
        } else {
			$rule  = $this->get_rule();
			$this->assign('rules',$rule);
			$this->meta_title = '添加用户组';
			$this->display();
        }
	}
	
	public function mod($id = 0){
		$mode	= D('MemberGroup');
        if(IS_POST){        
            if($data = $mode->create()){
				$data['rules']= json_encode(I('post.rule'));
                if($mode->save($data)!== false){
					S('user_groups_list',null);
                    $this->success('更新成功',Cookie('__forward__'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($mode->getError());
            }
        } else {
            $data = array();
            /* 获取数据 */
            $data = $mode->field(true)->find($id);
            if(false === $data){
                $this->error('获取后台数据信息错误');
            }
			$rule  = $this->get_rule();
			$this->assign('rules',$rule);
			//用户组设置的规则
			$data['rules'] = json_decode($data['rules'],true);
			
            $this->assign('data', $data);
			$this->meta_title = '修改用户组';
			$this->display('edit');
        }
	}
	
    public function del(){
		$this->error('暂时不支持，新增或删除用户组!');
        $id = array_unique((array)I('id',0));

        if (empty($id)) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('MemberGroup')->where($map)->delete()){
            //记录行为
            //action_log('update_channel', 'channel', $id, UID);
			S('user_group_links',null);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
	
	public function addUser(){
		$uid = I('post.uid');
        $gid = I('post.gid');
		$time = I('post.end_time');
        if( empty($uid) ){
            $this->error('请选着你授权的用户');
        }
        $model = D('MemberGroup');
        if(empty($gid) || !$model->find($gid)){
            $this->error('用户组不存在');
        }
		if( empty($time)){
            $this->error('请选择到期时间');
        }	
		$linkModel	= M('MemberGroupLink');
		
		$data['uid'] 		= $uid;	
		$data['group_id'] 	= $gid;	
		if ($time == 'max'){
			$time  = strtotime("2036-01-01");		
		}else{
			$time = strtotime('+'.$time.' month');			
		}

		if ($linkModel->where($data)->find()){
			if (!$linkModel->where($data)->setField('end_time',$time)){
				$this->error('授权操作失败');
			}
		}else{
			$data['end_time']= $time;
			if (!$linkModel->add($data)){
				$this->error('授权操作失败');
			}
		}
		S('user_group_links',null);
		$this->success('授权操作成功',Cookie('__forward__'));	
    }
	
	public function removeUser(){
		$uid 	= I('uid');
        $gid 	= I('gid');
		$model = D('MemberGroup');
		if( empty($uid) ){
            $this->error('请选着你要移除授权的用户');
        }
        $model = D('MemberGroup');
        if(empty($gid) || !$model->find($gid)){
            $this->error('用户组不存在');
        }
		$linkModel	= M('MemberGroupLink');
		$map['uid']			= $uid;
		$map['group_id']	= $gid;
		if (!$linkModel->where($map)->find()){
			$this->error('你要移除授权的用户不在该用户组');
		}
		if ($linkModel->where($map)->delete()){
			S('user_group_links',null);
			$this->success('已成功移除授权',Cookie('__forward__'));
		}else{
			$this->error('用户授权移除失败');
		}
	}
	
	protected function get_rule(){
		return  array(
			'is_pay'	=> array(
				'title'		=> '是否需要购买',
				'type'		=> 'radio',
				'options'=>array(
					'0'=>'否',	
					'1'=>'是',
				),
				'value'		=> '0',
				'tip'		=> '用户组是否需要购买',
			),
			'pay_mcoin'	=> array(
				'title'		=> '月支付金币',
				'type'		=> 'num',
				'value'		=>'10',
			),
			'pay_hycoin'=> array(
				'title'		=> '半年支付金币',
				'type'		=> 'num',
				'value'		=>'55',
			),
			'pay_ycoin'	=> array(
				'title'		=> '年支付金币',
				'type'		=> 'num',
				'value'		=>'100',
			)
		);
	}
}



