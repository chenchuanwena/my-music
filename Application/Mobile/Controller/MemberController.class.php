<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2015 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Mobile\Controller;
use User\Api\UserApi;

/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 */
class MemberController extends MobileController {

	/* 用户中心首页 */
	public function index(){
		$this->error('你访问的页面不存在！');
	}


	/* 登录页面 */
	public function login($username = '', $password = '', $verify = '',$autologin = false){
		
		if(IS_POST){ //登录验证
			if(is_login()){
				$this->success('已经登录，请不要重复登录！');
			}	
			if(empty($username )){
				$this->error('请填写邮箱地址！');
			}
			$username = filter_var($username,FILTER_VALIDATE_EMAIL);			
			if(!$username){
				$this->error('邮箱格式不正确！');
			}							
			/* 调用UC登录接口登录 */
			$user = new UserApi;
			$uid = $user->login($username, $password,2);
			if(0 < $uid){ //UC登录成功				
				//验证是否激活				
				/* 登录用户 */ 
				if($status= D('Member')->login($uid)){ //登录用户
					//判断是否自动登录  记录
					if($autologin){
						$auth = think_encrypt($uid,C('DATA_AUTH_KEY'));
						cookie('auth',$auth,30*24*3600); 
					}
					$url = Cookie('__forward__');
					$url = !empty($url)? $url : U('Index/index');
					$this->success('登录成功',$url);
				} else {
					$this->error($Member->getError());
				}

			} else { //登录失败
				switch($uid) {
					case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
					case -2: $error = '密码错误！'; break;
					default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
				}
				$this->error($error);
			}

		} else { //显示登录表单
			cookie('user',null);	
			$this->display();
		}
	}
	
		/*	
	* ajax返回是否登录
	*/	
	public function getUser () {
		// 获取当前用户ID
        $id = is_login();
        $data['status']  = 0;	
       	if(!$id){// 还没登录
       		$userkey = cookie('auth');
			if (!empty($userkey)){
		    	$uid = think_decrypt($userkey,C('DATA_AUTH_KEY')); 
				if (is_numeric($uid)){
					$id = D('Member')->login($uid);
					if (!$id){cookie('auth',null);}
				}							
			}else{
				$data = $this->positioning();
				cookie('location',$data);
			}                   
        }else {
			$data= M('Member')->where(array('uid'=>$id ))->field('uid,nickname,location,specialty')->find();
			$data['status']  = 1;
			$data['avatar']= get_user_avatar($id ,64);
			cookie('user',$data);			
        }
		//记录登录用户	 
        if (IS_POST && IS_AJAX) {
        	$this->ajaxReturn($data);	        		
        }elseif (IS_GET && IS_AJAX){
        	$this->assign('data',$data);	
        	$this->show(':Ajaxget/getTopUser');
        }
                       
	}
	
	/* 退出登录 */
	public function logout(){
		cookie('auth',null);
		cookie('user',null);
		if(UID){
			D('Member')->logout();			
			$this->success('退出成功！', U('Index/index'));
		} else {
			$this->redirect('Member/login');
		}
	}

	/* 验证码，用于登录和注册 */
	public function verify(){
		$verify = new \Think\Verify();
		$verify->fontSize = 16;
		$verify->length   = 4;
		$verify->useNoise = false; 
		$verify->entry(1);
	}

	/**
	 * 获取用户注册错误信息
	 * @param  integer $code 错误编码
	 * @return string        错误信息
	 */
	private function showRegError($code = 0){
		switch ($code) {
			case -1:  $error = '用户名长度必须在16个字符以内！'; break;
			case -2:  $error = '用户名被禁止注册！'; break;
			case -3:  $error = '邮箱被占用！'; break;
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

	public function checkReg ($type='',$value='') {
		if (IS_AJAX){			
			if ($type == 'email' && !empty($value)){
				if(!filter_var($value, FILTER_VALIDATE_EMAIL)) $this->error('邮箱格式不正确！');
				$userId = M('UcenterMember')->getFieldByEmail($value,'id');
				if ($userId){
					$this->error('邮箱不可用！');	
				}else{
					$this->success();
				}
			}elseif ($type == 'nickname'){
				//验证v
				$val = text_filter($value);				
				if ($val != $value)$this->error('昵称不合法！');
				$val = $this->isLegal($value);
				if (!$val)$this->error('昵称禁止使用！');				
				$userId = M('Member')->getFieldByNickname($value,'uid');
				if ($userId){
					$this->error('昵称已经被使用！');	
				}else{
					$this->success();
				}
								
			}else{
				$this->error('不能为空！');					
			}
			
		}else{
			$this->error();			
		}
	
	}

		
	private function isLegal ($str) { 	
		$str1  = @explode(',',trim(C('REG_BAN_NAME')));
		$count = count($str1);
		if($count > 0){
			for( $i=0;$i<$count;$i++){
 				if( stristr($str,$str1[$i])){
 					return false;
 				}
			}			
			return true;
		}
	}
	
	public function addressList() {
		$list = D('Area')->getNetworkList(0);
		///$list = json_encode($list);
		$this->ajaxReturn($list);
	}
			
}
