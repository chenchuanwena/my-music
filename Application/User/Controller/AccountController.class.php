<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2015 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace User\Controller;
class AccountController extends UserController {
    /**
	* 账户首页
	*/    
    public function index(){
    	$this->meta_title = '我的账户 - '.C('WEB_SITE_TITLE');
		$this->display();
    }
	
	public function score(){
    	$this->meta_title = '我的积分 - '.C('WEB_SITE_TITLE');
		$this->display();
    }
	
	public function charge(){
    	$this->meta_title = '我的金币 - '.C('WEB_SITE_TITLE');
		$this->display();
    }
	
	
	public function vip(){
		//获取vip购买的金币数

		$rule	= M('MemberGroup')->where('id=2')->getField('rules');
		$rule 	= json_decode($rule,true);
		
		if (IS_POST){
			$type	= I('post.pay_type');
			if (!empty($rule[$type])){
				$coin 	= intval($this->user['coin']);
				$want	= intval($rule[$type]);
				if ($coin >= $want){
					$map['uid']	= UID;
					
					//设置用户vip
					$model			= M('MemberGroupLink');
					$map['group_id']= 2;
					$user = $model->where($map)->find();
					if ($type == 'pay_ycoin'){
						$time	= strtotime('+12 month');
					}elseif ($type == 'pay_hycoin'){
						$time	= strtotime('+6 month');
					}else{
						$time	= strtotime('+1 month');
					}
					
					$info	= '升级';
									
					if ($user){
						if ($user['end_time'] > NOW_TIME){
							$time = $user['end_time'] + ($time-NOW_TIME);
							$info = '续费';
						}
						$res = $model->where($map)->setField('end_time',$time);					
					}else{
						$map['end_time'] = $time;						
						$res = $model->add($map);
					}
					
					if ($res){
						//扣除用户金币
						M('Member')->where($map)->setDec('coin',$want);
						S('user_group_links',null);
						$this->error('恭喜VIP'.$info.'成功到期时间'.time_format($time),U('index'));
					}else{
						$this->error('VIP'.$info.'失败！');
					}
			
				}else{
					$this->error('你的金币余额不足无法购买，请充值金币后购买');
				}
			}else{
				$this->error('你选择的会员类型不存在');
			}
			
		}else{		
			$this->assign('price',$rule);
			$this->meta_title = '我的VIP - '.C('WEB_SITE_TITLE');
			$this->display();
		}
    }

}