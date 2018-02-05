<?php

namespace Home\Controller;
use User\Api\UserApi ;

class OauthController extends HomeController{

    //登录地址
    public function login($type = null){
    	import("Vendor.ThinkSDK.ThinkOauth");
        empty($type) && $this->error('参数错误');
        //加载ThinkOauth类并实例化一个对象
        $sns  = \ThinkOauth::getInstance($type);
        //跳转到授权页面
        redirect($sns->getRequestCodeURL());
    }

    //登陆后回调地址
    public function callback($type = null, $code = null){
    	import("Vendor.ThinkSDK.ThinkOauth");
        $sns  	= \ThinkOauth::getInstance($type);
		$type	= strtolower($type);
        //腾讯微博需传递的额外参数
        $extend = null;
        if($type  == 'tencent'){
            $extend = array('openid' => I('get.openid'), 'openkey' =>  I('get.openkey'));
        }

        $token 		= $sns->getAccessToken($code , $extend);
        $userInfo 	= D('SyncLogin')->$type($token); //获取传递回来的用户信息      
		$uid 		= is_login();
		
		$syncModel  = D('SyncLogin');
		$map = array(
			'openid' 	=> $token['openid'],
			'type' 		=> $type
		);
		$syncUser 	= $syncModel->where($map)->find(); // 根据openid等参数查找同步登录表中的用户信息 		
		if ($uid){ //已经登录  执行绑定		
			$map['uid'] 	= $uid;
			$map['status'] 	= 1;
			if (empty($syncUser)){ //用户不存在  添加绑定
				$syncModel->add($map);
			}else{			
				if($syncUser['uid']  != $uid  || $syncUser['status']  != 1){//更新绑定					
					$syncModel->save($map);
				}	
			}
			//跳转到绑定页面

			$this->redirect('/User/profile#sns');
			
		}else{//没有登录 
			if (!empty($syncUser) && $syncUser['status'] == 1){//直接登录 						
				$uid = $syncUser['uid'];
				if( D('Member')->login($uid)){    
					$this->redirect('/');              
				}else{
					$this->error('登录失败');
				}
			
			}else{	//用户不存在 跳转直接注册				
				$oauthUser = array(
					'nickname'		=> $userInfo['nick'],
					'sex' 			=> !empty($userInfo['sex'])? $userInfo['sex']+1 : 1,
					'avatar'		=> $userInfo['head'],
					'type' 			=> $type,
					'openid'		=> $token['openid'],
					'access_token'	=> $token['access_token'],
					'refresh_token' => $token['refresh_token']
				);
				$this->register($oauthUser);
				//$this->display('Oauth:register');
			}
		}

    }
    //第三方登录注册新账号
    protected function register ($oauthUser){				
			/* 调用注册接口直接注册用户 */
			//获取 最后一个用户id
			$model		= M('UcenterMember');
			
			$maxid 	  	= $model->max('id');
			$maxid		= ++$maxid;
			$username	= 'jyuser_'.++$maxid;
			$password   = 'jyuser_'.$oauthUser['type'].rand();
			$email		=  $maxid.'@jy.cn';
			
            $User = new UserApi;
			$uid = $User->register($username, $password, $email);
			if(0 < $uid){ //注册成功
				$user = array(
					'uid'		=> $uid,
					'nickname'	=> $oauthUser['nickname'],
					'sex'		=> (int)$oauthUser['sex'],
					'reg_ip'	=> get_client_ip(1),
					'reg_time'	=> NOW_TIME,
					'status' 	=> 1
				);
	            if(!M('Member')->add($user)){
	                $this->error('用户注册失败，请重试！');
	            }
				//添加第三方同步数据库
				$oauth = array (
					'uid'			=> $uid,
					'type'			=> text_filter($oauthUser['type']),
					'openid'		=> text_filter($oauthUser['openid']),
					'access_token' 	=> text_filter($oauthUser['access_token']),
					'refresh_token' => text_filter($oauthUser['refresh_token']),
					'status' => 1
				);
				D('sync_login')->add($oauth); 
				//添加用户头像
				if(!empty($avatar)){
					$avatar = array( 
						'uid' 			=> $uid,
						'path' 			=> "",
						'type'			=> 5,
						'url' 			=> $avatar,
						'status'		=> 1,
						'create_time'	=> NOW_TIME
					);
					M('picture')->add($avatar);
				}
				//登录用户
				D('Member')->login($uid); //登录用户*/	
			} else { //注册失败，显示错误信息
				$this->error($this->showRegError($uid));
			}
 	
    }
	/**
	 * 获取用户注册错误信息
	 * @param  integer $code 错误编码
	 * @return string        错误信息
	 */
	private function showRegError($code = 0){
		switch ($code) {
			case -1:  $error = '用户名长度必须在1-32个字符以内！'; break;
			case -2:  $error = '用户名被禁止注册！'; break;
			case -3:  $error = '用户名被占用！'; break;
			case -4:  $error = '密码长度必须在6-30个字符之间！'; break;
			case -5:  $error = '邮箱格式不正确！'; break;
			case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
			case -7:  $error = '邮箱被禁止注册！'; break;
			case -8:  $error = '邮箱被占用！'; break;
			case -9:  $error = '手机格式不正确！'; break;
			case -10: $error = '手机被禁止注册！'; break;
			case -11: $error = '手机号被占用！'; break;
			default:  $error = '未知错误';
		}
		return $error;
	}
           
    /**
     * 取消绑定本地帐号
    */
    public function unbind($type){
		$type 	= text_filter(strtolower($type));
		$uid	= is_login();

		if (M('SyncLogin')->where(array('uid'=>$uid,'type'=>$type))->setField('status',0)) {
			$this->redirect('/User/profile#sns');
		}else{
			 $this->error('取消绑定失败！');
		}  	


    }

}