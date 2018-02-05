<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace User\Controller;
use User\Api\UserApi;
class ProfileController extends UserController {
	/*
	*个人设置
	*/	
	function index() {
		if(IS_POST){
			$this->setConfig();
		}else{
			$this->meta_title = '个人设置 - '.C('WEB_SITE_TITLE');
			$this->display(); 
		}
	}
	
	/**
    * 修改密码提交
    */
    public function submitPassword(){
        //获取参数
        $password   =   I('post.old');
        $uid  =   UID;
        empty($password) && $this->error('请输入原密码');
        $data['password'] = I('post.password');
        empty($data['password']) && $this->error('请输入新密码');
        $repassword = I('post.repassword');
        empty($repassword) && $this->error('请输入确认密码');

        if($data['password'] !== $repassword){
            $this->error('您输入的新密码与确认密码不一致');
        }
        $Api    =   new UserApi();
        $res    =   $Api->updateInfo($uid, $password, $data);
        if($res['status']){
            $this->success('密码修改成功！',U('index'));
        }else{
            $this->error($res['info']);
        }
    }
    
    /**
     * 修改资料提交
     */
    public function setConfig(){
		if(IS_POST){
	        $model	=   D('Member');	        
			$this->checknickname(I('post.nickname'));
			$this->checkqq(I('post.qq'));
	        if($data = $model->create()){
				$uid	= UID;

	        	$res = $model->where(array('uid'=>$uid))->save();
		        if($res){
		            $user               =   session('user_auth');
		            $user['username']   =   $data['nickname'];
		            session('user_auth', $user);
		            session('user_auth_sign', data_auth_sign($user));
					//更新缓存
					$list = S('sys_user_nickname_list');
					$key = "u{$uid}";
					if(isset($list[$key])){ //已缓存，更新						
						$list[$key] = $data['nickname'];
						S('sys_user_nickname_list', $list);
					}
		            $this->success('修改成功！',U('index'));
		        }else{
		            $this->error('修改失败！');
		        }
	            
	        }else{
	        	$this->error($Member->getError());
	        }        

    	}else{
    		$this->error('非法参数！');
    	}
    }
    
    /*
    *隐私设置
    */
    function setprivacy () {
    
    
    }
    
    /*
    *消息设置
    */
    function setmsg () {
    
    
    }
	
	/*
    *密码设置
    */
    function setpwd () {
		if(IS_POST){
			$this->submitPassword();
		}else{
			$this->meta_title = '密码设置 - '.C('WEB_SITE_TITLE');
			$this->display();			
		}

    }
      
    /*
    *同步设置
    */
    function sync () {
    
    
    }    
        
    /*
    *设置头像
    */
    
   	public function avatar () {
		$this->meta_title = '设置头像 - '.C('WEB_SITE_TITLE');
		$this->assign('type','portrait');
		$this->display();
    }
    
    
	private function checknickname ($nickname) { 	
		$str  = @explode(',',trim(C('REG_BAN_NAME')));
		if(count($str)>0){
			for( $i=0;$i<count($str);$i++){
				if( stristr($nickname,$str[$i])){
					$this->error('昵称中含有非法字符');
				}
			}
		}
		$name = D('Member')->getFieldByUid(UID,'nickname');
		if( $name  != $nickname){
			$count = D('Member')->where(array('nickname'=> $nickname))->count();
			if($count){
				$this->error('昵称已存在');
			}
		}	
	}
	
	private function checkqq ($qq) { 	
		$qq1= D('Member')->getFieldByUid(UID,'qq');
		if( $qq1 != $qq){
			$count = D('Member')->where(array('qq'=> $qq))->count();
			if($count){
				$this->error('qq号码已存在');
			}
		}
	
	}
	
}