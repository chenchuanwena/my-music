<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2015 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Home\Controller;
use User\Api\UserApi;

/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 */
class MemberController extends HomeController {

	/* 用户中心首页 */
	public function index(){
		$this->getSeoMeta();
		$this->display();
	}

	/* 注册页面 */
	public function register($username = '', $password = '', $repassword = '', $email = '', $verify = ''){
        $this->getSeoMeta();
		
		if(!C('USER_ALLOW_REGISTER')){
            $this->error('注册已关闭');
        }
		if(IS_POST){ //注册用户
			$str  = @explode(',',trim(C('REG_BAN_NAME')));
			if(count($str)>0){
				for( $i=0;$i<count($str);$i++){
	 				if( stristr($username,$str[$i])){
	 					$this->error('用户名['.$username.']禁止注册！');
	 				}
				}
			} 
			/* 检测验证码 */
			if(!check_verify($verify,1)){
				$this->error('验证码输入错误！');
			}					
			/* 检测密码 */
			if($password != $repassword){
				$this->error('密码和重复密码不一致！');
			}
			/* 调用注册接口注册用户 */
            $User = new UserApi;
			$uid = $User->register($username, $password, $email);
			if(0 < $uid){ //注册成功		
				//TODO: 发送通知				
				$title 		= '感谢你注册'.C('WEB_SITE_NAME').'会员';
				$content 	= str_replace(array('{$webname}','{$webmail}'), array(C('WEB_SITE_NAME'),C('WEB_EMAIL')),C('REG_GREET_CONTENT'));						
				D('Notice')->send($uid,$title,$content);
				
				$uid 	= $User->login($username, $password);				
				$memberModel	= D('Member');
				
				//是否开启用户激活
				if(C('SEND_ACTIVATE_MAIL')){
					//发送激活邮件
					$mailModel	= D('Mail');
					$key		= think_encrypt($email,'',3600);

					if ($mailModel->send_activate_email($email,$key)){
						$info = array(
							'uid'				=> $uid,
							'nickname' 			=> $username,
							'reg_time' 			=> NOW_TIME,
							'reg_ip' 			=> get_client_ip(1),
							'last_login_time' 	=> NOW_TIME,
							'birthday'			=> '1000-01-01',
							'status' 			=> 2,
							'cdkey'				=> $key
						);
								
						$user = $memberModel->create($info);
						$memberModel->add($user);						
						$data['url']	= U('activate?email='.$email);
						$data['status'] = 1;
						$data['info'] 	= '注册成功，请登录邮箱激活！';
					}else{
						$data['status'] = 0;
						$data['info']   = '激活邮件发送失败，请联系网站客服！';
					}		
				}else{
					$memberModel->login($uid); //登录用户*/
					$data['uid']  		= $uid;
					$data['nickname']	= $username;
					$data['status']  	= 1;
					$data['url']		= U('/');
					cookie('user',$data);
					$data['info'] 		= '注册成功！';
				}
				$this->ajaxReturn($data);
				
			} else { //注册失败，显示错误信息
				$this->error($this->showRegError($uid));
			}

		} else { //显示注册表单
			$this->display();
		}
	}

	/* 登录页面 */
	public function login($username = '', $password = '', $verify = '',$autologin = false){
		//dump($username);
		if(IS_POST){ //登录验证
			if(is_login()){
				$this->success('已经登录，请不要重复登录！');
			}			
			if(C('VERIFY_OFF')){// 检测验证码 
				if(!check_verify($verify,1)){
					$this->error('验证码输入错误！');
				}
			}			
			/* 调用UC登录接口登录 */
			$user = new UserApi;
			$uid = $user->login($username, $password);
			if(0 < $uid){ //UC登录成功
				/* 登录用户 */
				$member = D('Member');
				$status = $member->login($uid);
				if($status){ //登录用户
					//判断是否自动登录
					if($autologin){
						$key = think_encrypt($uid,C('DATA_AUTH_KEY'));
						cookie('autologin',$key,30*24*3600); 
					}
					$data['status']  	= 1;
					$data['uid']  		= $uid;
        			$data['nickname']	= get_nickname($uid); 
					cookie('user',$data);
					$this->success('登录成功！',U('/'));
				} else {
					$this->error($member->getError());
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
			$this->getSeoMeta();
			$this->display();
		}
	}

	/* 退出登录 */
	public function logout(){
		if(is_login()){
			D('Member')->logout();
			cookie('autologin',null);
			cookie('user',null);
			$this->success('退出成功！', U('/'));
		} else {
			$this->redirect('Member/login');
		}
	}

	/* 验证码，用于登录和注册 */
	public function verify(){
		$verify = new \Think\Verify();
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
     
    /*
    * 找回密码 需要邮箱插件
    */
    
    public function findpwd () {
    	if (is_login()) {
			$this->error( '您已登陆',U('/'));
		}
    	if (IS_POST) {
    		$post 	= I('post.');
			$email  = $post['email'];
			/* 检测验证码 */
			if(!check_verify($post['verify'],1)){
				$this->error('验证码输入错误！');
			}
			/* 检测邮箱*/
			if (!filter_var($post['email'],FILTER_VALIDATE_EMAIL)){
				$this->error('邮箱格式不正确！');
			}
			
			/*检测用户*/
			if (!$uid  =  M('ucenterMember')->getFieldByEmail($email,'id')){
				$this->error('用户不存在！');				
			}
			/*检测用户是否有效*/
											
			//生成连接
			$key	= jy_encrypt($uid,60*60*24);
			
			if ( D('Mail')->send_find_email($email,$key)){
				
				$this->success('重置密码链接已发送邮箱！',$url );				
			}else{
				$this->error('邮件发送失败！');
			}			
    		
    	}else{
    		$this->getSeoMeta();
      		$this->display();  			
    	}
    }
	
	/*重置密码*/	
	public function resetpwd($token = ""){
		if (IS_POST){
		
            $token	= I('post.token');
			if (empty($token) ||  !$uid = jy_decrypt($token)){
				$this->error('链接已失效！');
			}

			$password   	= I('post.password');
            $repassword 	= I('post.repassword');
			
			if (strlen($password) < 6 || strlen($password) > 30) $this->error('密码长度6-30个字符！');
			
            empty($password) 	&& $this->error('请输入新密码');                      
			empty($repassword) 	&& $this->error('请输入确认密码');

            if($password  !== $repassword){
                $this->error('新密码与确认密码不一致');
            }
			$Api    =   new UserApi();
			$res    =   $Api->resetpwd($uid, $password);
			if($res['status']){
				$this->success('密码修改成功！',U('login'));
			}else{
				$this->error($res['info']);
			}
		
		}else{
			$this->assign('token',I('get.token'));
			$this->display('findpwd');			
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
       		$userkey = cookie('autologin');
			if (!empty($userkey)){
		    	if ($uid = think_decrypt($userkey,C('DATA_AUTH_KEY')));
		    	$member = D('Member');    	
		    	$id = D('Member')->login($uid);
		    	$data['uid']  = $uid;
		    	$data['status']  = 1;
        		$data['nickname']= get_nickname($id);       
			}                    
        }else {
        	$data['status']  = 1;
        	$data['uid']  = $id; 
        	$data['nickname']= get_nickname($id); 
			cookie('user',$data);			
        }		
        if (IS_POST && IS_AJAX) {
        	$this->ajaxReturn($data);	        		
        }elseif (IS_GET && IS_AJAX){
        	$this->assign('data',$data);	
        	$this->show(':Ajaxget/getTopUser');
        }                     
	}
		
	/*
	* 用户激活
	*/	
	public function activate ($email=null,$token=null) {
		// 获取当前用户ID		
		if(IS_POST)	{
			$post = I('post.');
			/* 检测验证码 */
			if(!check_verify($post['verify'])){
				$this->error('验证码输入错误！');
			}
			/* 检测邮箱*/
			$email = filter_var($post['email'],FILTER_VALIDATE_EMAIL);
			if ($email){			
				$uid  =  M('ucenterMember')->getFieldByEmail($email,'id');
				if (!$uid){
					$this->error('激活的账号不存在！');
				}

				$user = M('Member')->where(array('uid'=>$uid))->field('last_login_time,status')->find();
				$ltime = time()-$user['last_login_time'];
				if($user['status'] == 1) {
					$this->error('该用户已经激活！',U('Member/login'));
				}elseif($ltime < 3600){//小于1小时禁止
					$this->error('激活邮件已发送到你的邮箱，请不要重复发送！');
				}else{
					$mailModel	= D('Mail');
					$key		= think_encrypt($email,'',3600);
					if ($mailModel->send_activate_email($email,$key)){
						M('Member')->where(array('uid'=>$uid))->setField('last_login_time',NOW_TIME);
						$this->success('激活邮件已发送到你的邮箱');
					}else{
						$this->error('激活邮件发送失败，请联系网站客服！');
					}
				}
	
			}else{
				$this->error('邮箱格式不正确！');
			}
		
		}else{
			$token 	= text_filter($token);
			$email 	= text_filter($email);
			
			if (!empty($email)){
				$mailModel	= D('Mail');			
				$mailUrl	= $mailModel->gotomail($email);

				$this->assign('title','感谢注册'.C('WEB_SITE_NAME').',激活邮件已发送到'.$email.',请查看邮件并激活完成注册');
				$this->assign('mail_url',$mailUrl);
				$this->assign('toactivate','true');
			}elseif (!empty($token)){
				$active	= think_decrypt($token);
				if (empty($active)){
					$this->assign('title','激活链接已失效，请输入你注册的邮箱重新激活！');
					$this->assign('show','reactivate');
				}else{
					$map['cdkey'] 	= $token;
					$status 		= M('Member')->where($map)->getField('status');
					if(empty($status)){
						$this->error('你激活的用户不存在或被禁用！',U('/'));
					}else{
						if($status != 2) {
							$this->error('该用户已经激活！',U('Member/login'));
						}else{
							M('Member')->where($map)->SetField('status',1);
							$this->success('邮箱激活成功', U('Member/login'));
						}
					}

				}			
			}else{
				$this->assign('title','输入你注册的邮箱重新激活！');
			}			
			$this->display();
		}
	}
	
	//单个验证用户名 邮箱等
	public function check($action="",$val=""){
		$val	= text_filter($val);
		switch ($action) {
			case 'username':  
				if (M('ucenterMember')->getFieldByEmail($val,'id')){
					$this->error('用户名已存在！');				
				};
				$str  = @explode(',',trim(C('REG_BAN_NAME')));
				if(count($str)>0){
					for( $i=0;$i<count($str);$i++){
		 				if( stristr($val,$str[$i])){
		 					$this->error('用户名禁止注册！');
		 				}
					}
				} 
			break;
			
			case 'email':  
				if (!filter_var($val,FILTER_VALIDATE_EMAIL)){
					$this->error('邮箱被格式不正确！');
				} 			
				if (M('ucenterMember')->getFieldByEmail($val,'id')){
					$this->error('邮箱被占用！');
				}			
			break;
	
			case 'verify':  
				/* 检测验证码 */
				if(!check_verify($val,1)){
					$this->error('验证码输入错误！');
				}			
			 break;		
			default:  $this->error('你访问的页面不存在');
		}
		return $this->success('输入正确！');;
		
	}
	

	
	public function getmsg(){
		if ( $uid = is_login()) {
			$msg = D('Common/Message');
			$list = $msg->getUnreadMsg($uid);
	        if ( IS_GET && IS_AJAX) {	//ajax	 get 渲染模板 返回html	
				$this->assign('list',$list);	
				$this->show(':Ajaxget/getTopMsg');
			}elseif(IS_POST && IS_AJAX){//ajax	 post  返回json
				$data['unread']  = count($list);
				$data['info'] = $list;				
				$this->ajaxReturn($data);			
			}
		}
	}
}
