<?php

namespace Addons\SyncLogin\Controller;
use Think\Hook;
use Home\Controller\AddonsController;
use User\Api\UserApi as UserApi;
require_once(dirname(dirname(__FILE__))."/ThinkSDK/ThinkOauth.class.php");


class BaseController extends AddonsController{

    //登录地址
    public function login(){
        $type= I('get.type');
        empty($type) && $this->error('参数错误');
        //加载ThinkOauth类并实例化一个对象
        $sns  = \ThinkOauth::getInstance($type);
        //跳转到授权页面
        redirect($sns->getRequestCodeURL());
    }

    //登陆后回调地址
    public function callback(){
        $code =  I('get.code');
        $type= I('get.type');
        $sns  = \ThinkOauth::getInstance($type);

        //腾讯微博需传递的额外参数
        $extend = null;
        if($type == 'tencent'){
            $extend = array('openid' => I('get.openid'), 'openkey' =>  I('get.openkey'));
        }

        $token = $sns->getAccessToken($code , $extend);
        $userInfo = D('Addons://SyncLogin/SyncLogin')->$type($token); //获取传递回来的用户信息
        $condition = array(
            'openid' => $token['openid'],
            'type' => $type,
            'status' => 1,
        );
        $userSyncInfo = D('sync_login')->where($condition)->find(); // 根据openid等参数查找同步登录表中的用户信息
        if($userSyncInfo) {//曾经绑定过
            $ucMember = D('UcenterMember')->find($userSyncInfo ['uid']); //根据UID查找Ucenter中是否有此用户
            if($ucMember){
                $syncData ['access_token'] = $token['access_token'];
                $syncData ['refresh_token'] = $token['refresh_token'];
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
        } else { //没绑定过，注册账号并绑定
        	//取最后一个注册用户 
        	$latUid = M('UcenterMember')->max('id');
        	$username = $password= 'gugu'.($latUid+1);
        	$User = new UserApi;
	        $uid   =   $User->syncRegister($username, $password);
	        if(0 < $uid){
	        	$nickname = $userSyncInfo['name'];	
	        	$nickname =   !empty($nickname)? $nickname: $username ;    	            
	            $user = array('uid' => $uid, 'nickname' => $nickname, 'status' => 1);
	            if(!M('Member')->add($user)){
	                $this->error('用户添加失败，请联系管理员');
	            } else {
	                //添加到用户组
	                // 注册来源-第三方帐号绑定
	            	$other['uid'] = $uid;
	            	$other['type'] = $type;
	            	$other['openid'] = $token['openid'];    
	            	$other['access_token'] = $token['access_token'];
	            	$other['refresh_token'] = $token['refresh_token'];
	            	$other['status'] = 1;
	            	D('sync_login')->add($other); 
	                /* 登录用户 */
	                $Member = D('Member');
	                if($Member->login($uid)){
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