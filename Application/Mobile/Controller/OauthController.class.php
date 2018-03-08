<?php

namespace Mobile\Controller;
use User\Api\UserApi ;
//require_once(dirname(dirname(__FILE__))."/ThinkSDK/ThinkOauth.class.php");
class OauthController extends MobileController{

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
        $sns  = \ThinkOauth::getInstance($type);

        //腾讯微博需传递的额外参数
        $extend = null;
        if($type == 'tencent'){
            $extend = array('openid' => I('get.openid'), 'openkey' =>  I('get.openkey'));
        }

        $token = $sns->getAccessToken($code , $extend);
        $userInfo = D('SyncLogin')->$type($token); //获取传递回来的用户信息      
        $condition = array(
            'openid' => $token['openid'],
            'type' => $type,
            'status' => 1,
        );
        $userSyncInfo = D('sync_login')->where($condition)->find(); // 根据openid等参数查找同步登录表中的用户信息
        if($userSyncInfo) {//曾经绑定过
            $syncData ['access_token'] = $token['access_token'];
			$syncData ['refresh_token'] = $token['refresh_token'];
			$ucMember = D('UcenterMember')->find($userSyncInfo ['uid']); //根据UID查找Ucenter中是否有此用户
			if (UID && UID != $ucMember ['id'] ){//更改绑定 用户uid
				$syncData['uid']= UID;
				$status = D('sync_login')->where( array('uid' =>$ucMember ['id']) )->save($syncData); //更新Token
				$status = $status? 1 : 0;
				//跳转到绑定页面
				$this->redirect('/User/Account/bind',array('type' => $type,'status'=>$status));
			}else{				
				if($ucMember){					
					D('sync_login')->where( array('uid' =>$ucMember ['id'] ) )->save($syncData); //更新Token                  
					if( D('Member')->login($ucMember['id']) ){    
						$this->redirect('/');              
					}else{
						$this->error($Member->getError());
					}
				}else{//
					$map= array(
						'openid' => $token['openid'],
						'type' => $type
					);
					D('sync_login')->where($map)->delete();
				}
			}
        } else { //没绑定过，注册账号并绑定
        	/*$syncs = array( 
	    		'QQ' 		=> '腾讯互联',
	    		'SINA' 		=> '新浪微博',
	    		'TENCENT' 	=> '腾讯微博',
	    		'RENREN'	=> '人人网',
	    		'DOUBAN'	=> '豆瓣网'
	     	);
	    	$this->assign('syncs',$syncs);       	
        	$this->display('register');*/
        	if(UID){
				//添加到用户组// 绑定来源-第三方帐号绑定当前登录用户
				$other['uid'] = UID;
				$other['type'] = $type;
				$other['openid'] = $token['openid'];    
				$other['access_token'] = $token['access_token'];
				$other['refresh_token'] = $token['refresh_token'];
				$other['status'] = 1;
				$status = D('sync_login')->add($other);
				//跳转到绑定页面
				$this->redirect('/User/Account/bind',array('type' => $type));			
			}else{//为第三方账号注册用户															
				//取最后一个注册用户 
				$latUid = M('UcenterMember')->max('id');     	
				$username = $password= 'gugu_'.($latUid+1);
				$User = new UserApi;
				$uid   =   $User->syncRegister($username, $password);
				if(0 < $uid){   	            
					$user = array(
						'uid' => $uid,
						'reg_time' => NOW_TIME,
						'reg_ip' => get_client_ip(), 
						'uid' => $uid, 
						'nickname' => !empty($userInfo['nick'])? $userInfo['nick'] : $username, 
						'sex' =>  !empty($userInfo['sex'])? $userInfo['sex']+1 : 1,
						'status' => 1
					);
					if(!M('Member')->add($user)){
						$this->error('用户添加失败，请联系管理员');
					} else {
						//添加到用户组// 注册来源-第三方帐号绑定
						$other['uid'] = $uid;
						$other['type'] = $type;
						$other['openid'] = $token['openid'];    
						$other['access_token'] = $token['access_token'];
						$other['refresh_token'] = $token['refresh_token'];
						$other['status'] = 1;
						D('sync_login')->add($other); 
						//添加用户头像
						if(!empty($userInfo['head'])){
							$avatar = array( 
								'uid' => $uid,
								'path' => "",
								'url' => $userInfo['head'],
								'status'=> 1,
								'create_time'=>NOW_TIME
							);
							M('UserAvatar')->add($avatar);
						}	            	
						/* 登录用户 */
						if(D('Member')->login($uid)){
							$message['uid'] =$uid;
							$message['appname'] = 'Member/register';
							$message['title'] = '第三方账号'.C('WEB_SITE_NAME').'登录成功';
							$message['content'] = str_replace(array('{$webname}','{$webmail}'), array(C('WEB_SITE_NAME'),C('WEB_SITE_NAME')),C('REG_GREET_CONTENT'));
							$message['from_uid'] =0;
							$msg = D('NotifyMessage')->sendMessage($message);
							$this->redirect('/'); 
						}else{
							$this->error($Member->getError());
						}
					}
				}else{
					$this->error('用户信息注册失败，请联系管理员'); //注册失败
				}
			}
        }
    }
    //第三方登录注册新账号
    public function register ($nickname = '', $password = '', $email = '',$sex = 0){
		if(IS_POST){
			$str  = @explode(',',trim(C('REG_BAN_NAME')));
			if(count($str)>0){
				for( $i=0;$i<count($str);$i++){
	 				if( stristr($nickname,$str[$i])){
	 					$this->error('昵称禁止使用！');
	 				}
				}
			} 			
			$userId = M('Member')->getFieldByNickname($nickname ,'uid');
			if ($userId){$this->error('昵称已经被使用！');}		
			/* 检测密码 */
			if($password != $repassword){
				$this->error('密码和重复密码不一致！');
			}
			/* 调用注册接口注册用户 */
            $User = new UserApi;
			$uid = $User->register($email, $password, $email);
			if(0 < $uid){ //注册成功
				$M = M('Member');					
				$data['nickname'] = $nickname;
				$data['sex'] =$sex;
				$data['reg_time']= NOW_TIME;
				$data['status'] = 1;
				$user = $M->create($data);
	            if(!$M->add($user)){
	                $this->error('用户注册失败，请重试！');
	            }else{			
					//TODO: 发送通知
					if (C('REG_GREET_MSG')){						
						$message['uid'] =$uid;
						$message['appname'] = 'Member/register';
						$message['title'] = '感谢你注册'.C('WEB_SITE_NAME').'会员';
						$message['content'] = str_replace(array('{$webname}','{$webmail}'), array(C('WEB_SITE_NAME'),C('WEB_SITE_NAME')),C('REG_GREET_CONTENT'));
						$message['from_uid'] =0;
						$msg = D('NotifyMessage')->sendMessage($message);
					}		
					//登录用户
					$uid = $User->login($email, $password,2);
					D('Member')->login($uid); //登录用户*/					
					$this->success('注册成功！',U('/User/Profile/index'));
				}
			} else { //注册失败，显示错误信息
				$this->error($this->showRegError($uid));
			}
		}else{
			$this->error('参数错误');
		}    	
    }
           
    /**
     * 第三方帐号集成 - 绑定本地帐号
    */
    public function dobind($type){
    	if(IS_POST){
    		$bind = M('SyncLogin')->where(array('uid'=>UID))->field('type')->select();
	    	if (count($bind) > 1) {
	    		$binds = array_column($bind,'type'); 
	    	}else{
	    		$binds[] = $bind['type'];
	    	}  	
	    	if ( in_array($type,$binds)) $this->error('已经绑定过了！');
    		$type = strtolower($type);
    	}    
    }

}