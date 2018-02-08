<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2015 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace User\Controller;
class AuthController extends UserController {
    //认证首页
    public function index($uid=0){ 	
		$this->error('你访问的页面不存在');
	}
    
    //认证音乐人
    public function musician(){
		$model	= D('MemberAuthMusician');
		
		if (is_musician(UID)){
			$this->error('你已经成功成为认证音乐人');
		}
		$status	= $model->where(array('uid'=>UID))->getField('status');
		if ($status == 2){
			$this->error('你的认证我们将尽快审核!');
		}
		
		//验证音乐数量 C('AUTH_MUSICIAN_SONGS');
	
		if(IS_POST){
			if ($this->user['songs'] < C('AUTH_MUSICIAN_SONGS')){
				$this->error('你分享的音乐数量不足'.C('AUTH_MUSICIAN_SONGS').'首，无法申请!');
			}
			if ($model->create()){
				if ($model->add()){
					$this->success('认证申请成功,我们将尽快审核你的请求',U('/user/account'));
				}else{
					$this->error('认证申请失败请重试');
				}	
				
			}else{
				$this->error($model->getError());
			}
						
		}else{
			$this->meta_title = '认证音乐人 - '.C('WEB_SITE_TITLE');
			$this->display();
		}
    }
       
}