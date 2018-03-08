<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2015 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Common\Model;

use Think\Model;
/**
 * 邮件模型 - 数据对象模型
 * @author 战神~~巴蒂 <37802023@qq.com> 
 */
class MailModel extends Model {

	// 允许发送邮件的类型
	public static $allowed = array('Register','unAudit','resetPass','resetPassOk','invateOpen','invate','atme','comment','reply');
	public $message;

	/**
	 * 初始化方法，加载phpmailer，初始化默认参数
	 * @return void
	 */
	public function __construct() {
		vendor('phpmailer.class#phpmailer','',".php");
		vendor('phpmailer.class#pop3','',".php");
		vendor('phpmailer.class#smtp','',".php");
		$emailset  = unserialize(C('MAIL_CONF'));
		$this->option = array(
			'email_sendtype'		=> $emailset['email_sendtype'],
			'email_host'			=> $emailset['email_host'],
			'email_port'			=> $emailset['email_port'],
			'email_ssl'				=> $emailset['email_ssl'],
			'email_account'			=> $emailset['email_account'],
			'email_password'		=> $emailset['email_password'],
			'email_sender_name'		=> $emailset['email_sender_name'],
			'email_sender_email'	=> $emailset['email_sender_email'],
			'email_reply_account'	=> $emailset['email_sender_email']
		);
	}
	
	/**
	 * 发送注册成功邮件
	 * @param array $data 邮件相关内容数据
	 * @return boolean 是否发送成功
	 */
	public function send_register_email($uid) {
		$user =M('ucenterMember')->where(array('id'=>$uid))->field('username,email')->find();
		$title = '欢迎加入'.C('WEB_SITE_NAME');
		$body ='<b>尊敬的'.$user['username'].':</b><br>这是我们写给您的第一封邮件，感谢您注册成为'.C('WEB_SITE_NAME').'会员<br><br>如果您没有申请注册'.C('WEB_SITE_NAME').'，请忽略此邮件';
		return $this->send_email($email,$title,$body);
	}
	
	/**
	 * 发送注册激活邮件
	 * @param array $data 邮件相关内容数据
	 * @return boolean 是否发送成功
	 */
	public function send_activate_email($email,$key) {
		
		$title 	= C('WEB_SITE_NAME').'会员邮箱激活';
		$href	= U('/member/activate?token='.$key,true,true,true);
		
		$body 	='<b>尊敬的'.$email.':</b><br>';		
		$body  .='为了保障您帐号的安全性，请点击下面的链接激活邮箱：<br><br>';
		$body  .='<a href="'.$href.'"><b>'.$href.'</b></a><br><br>';
		$body  .='如果您点击上述链接无效，请把下面的代码拷贝到浏览器的地址栏中：<br><br>';
		$body  .='<a href="'.$href.'">'.$href.'</a><br><br>';
		$body  .='本链接1小时候后将自动失效。<br><br>';
		$body  .='如果您没有申请注册'.C('WEB_SITE_NAME').'，请忽略此邮件<br><br>';
		$body  .='感谢使用'.C('WEB_SITE_NAME').'<br>';
		$body  .=C('WEB_SITE_NAME').'会员中心<br>';
		$body  .=date('Y-m-d');
				
		return $this->send_email($email,$title,$body);
	}

	public function send_find_email($email,$key) {
		$title 	= '找回登录密码'.C('WEB_SITE_NAME');
		$href	= U('/member/resetpwd/?token='.$key,true,true,true);
		
		$body  = '您在'.C('WEB_SITE_NAME').'申请找回密码，请点击以下链接重置密码（24小时内有效）<br><br>';
		$body  = '<a href="'.$href.'">'.$href.'</a><br><br>';
		$body  .='如果您点击上述链接无效，请把下面的代码拷贝到浏览器的地址栏中：<br><br>';
		$body  .='<a href="'.$href.'">'.$href.'</a><br><br>';	
		$body  .='如果您没有申请找回密码功能，请忽略此邮件<br><br>';
		$body  .='感谢使用'.C('WEB_SITE_NAME').'<br>';
		$body  .=C('WEB_SITE_NAME').'会员中心<br>';
		return $this->send_email($email,$title,$body);
	}
	/**
	 * 测试发送邮件
	 * @param array $data 邮件相关内容数据
	 * @return boolean 是否发送成功
	 */
	public function test_email($data) {
		$this->option = $data;
		$this->option['email_reply_account'] = $this->option['email_sender_email'];
		return $this->send_email($data['sendto_email'],'测试邮件','这是一封测试邮件');
	}

	/**
	 * 发送邮件
	 * @param string $sendto_email 收件人的Email
	 * @param string $subject 主题
	 * @param string $body 正文
	 * @param array $senderInfo 发件人信息 array('email_sender_name'=>'发件人姓名', 'email_account'=>'发件人Email地址')
	 * @return boolean 是否发送邮件成功
	 */
	public function send_email( $sendto_email, $subject, $body, $senderInfo = '') {
        $mail = new \PHPMailer();
		if(empty($senderInfo)) {
			$sender_name = $this->option['email_sender_name'];
			$sender_email = empty($this->option['email_sender_email']) ? $this->option['email_account'] : $this->option['email_sender_email'];
		} else {
			$sender_name = $senderInfo['email_sender_name'];
			$sender_email = $senderInfo['email_sender_email'];
		}

		if($this->option['email_sendtype'] =='smtp') {
			$mail->Mailer = "smtp";
			$mail->Host	= $this->option['email_host'];	// sets GMAIL as the SMTP server
			$mail->Port	= $this->option['email_port'];	// set the SMTP port
			if($this->option['email_ssl']) {
				$mail->SMTPSecure = "ssl";	// sets the prefix to the servier  tls,ssl
			}
			if(!empty($this->option['email_account']) && !empty($this->option['email_password'])){
				$mail->SMTPAuth = true;						 // turn on SMTP authentication
				$mail->Username = $this->option['email_account'];	 // SMTP username
				$mail->Password = $this->option['email_password']; // SMTP password
			}
		}elseif($this->option['email_sendtype'] =='sendmail'){
			$mail->Mailer = "sendmail";
			$mail->Sendmail	= '/usr/sbin/sendmail';
		}else{
			$mail->Mailer = "mail";
		}
		
		$mail->Sender = $this->option['email_account']; 			// 真正的发件邮箱

		$mail->SetFrom($sender_email, $sender_name, 0);				// 设置发件人信息

		$mail->CharSet = "UTF-8"; 									// 这里指定字符集！
		$mail->Encoding	= "base64";

		if(is_array($sendto_email)) {
			foreach($sendto_email as $v){
				$mail->AddAddress($v);
			}	
		} else {
			$mail->AddAddress($sendto_email);
		}

		//以HTML方式发送
		$mail->IsHTML(true); 				// send as HTML
		$mail->Subject		= $subject;		// 邮件主题
		$mail->Body			= $body;			// 邮件内容
		$mail->AltBody		= "text/html";
		$mail->SMTPDebug	= false;

		$result = $mail->Send();

		$this->setMessage($mail->ErrorInfo);
		return $result;
	}	

	public function setMessage ($message) {
		$this->message = $message;
	}
	
	public function gotomail($mail){ 
		$t=explode('@',$mail); 
		$t=strtolower($t[1]); 
		switch ($t){
			case '163.com':
			  $url = 'http://mail.163.com';
			  break;  
			case 'vip.163.com':
			  $url = 'http://vip.163.com';
			  break;
			case '126.com':
			  $url = 'http://mail.126.com';
			  break;
			case 'qq.com':
			case 'vip.qq.com':
			case 'foxmail.com':
			  $url = 'http://mail.qq.com';
			  break;
			case 'gmail.com':
			  $url = 'http://mail.google.com';
			  break;
			case 'sohu.com':
			  $url = 'http://mail.sohu.com';
			  break;
			case 'tom.com':
			  $url = 'http://mail.tom.com';
			  break;
			case 'vip.sina.com':
			  $url = 'http://vip.sina.com';
			  break;
			case 'yahoo.com.cn':
			case 'yahoo.cn':
			  $url = 'mail.cn.yahoo.com';
			  break;
			case 'yeah.net':
			  $url = 'http://www.yeah.net';
			  break;
			case '21cn.com':
			  $url = 'mail.21cn.com';
			  break;
			case 'hotmail.com':
			  $url = 'http://www.hotmail.com';
			  break;
			case 'sogou.com':
			  $url = 'http://mail.sogou.com';
			  break;
			case '188.com':
			  $url = 'http://www.188.com';
			  break;
			case '139.com':
			  $url = 'http://mail.10086.cn';
			  break;
			case '189.cn':
			  $url = 'http://webmail15.189.cn/webmail';
			  break;
			case 'wo.com.cn':
			  $url = 'http://mail.wo.com.cn/smsmail';
			  break;
	
			default:
			  $url	= '';
		}
		return $url; 
	}
}